<?php

declare(strict_types=1);

namespace Gedcom;

use Gedcom\Parser\Interfaces\ParserInterface;

class Parser implements ParserInterface
{
    private readonly Gedcom $gedcom;
    private array $lineBuffer = [];
    private int $currentLine = 0;
    private array $errors = [];

    // PHP 8.4 property hooks for lazy initialization
    private \SplFileObject $fileHandle {
        get => $this->fileHandle ??= new \SplFileObject($this->fileName, 'r');
    }

    private string $fileName;
    private bool $useStreaming = false;

    public function __construct()
    {
        $this->gedcom = new Gedcom();
    }

    public function parse(string $fileName): ?Gedcom
    {
        if (!file_exists($fileName)) {
            throw new \InvalidArgumentException("File not found: $fileName");
        }

        $this->fileName = $fileName;
        $fileSize = filesize($fileName);

        // Use streaming for large files (> 100MB) to optimize memory usage
        if ($fileSize > 100 * 1024 * 1024) {
            $this->useStreaming = true;
            return $this->parseStreaming();
        }

        // Optimized file reading for smaller files
        $this->lineBuffer = file($fileName, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $this->currentLine = 0;
        $this->errors = [];

        while (!$this->eof()) {
            $record = $this->getCurrentLineRecord();

            if ($record === false) {
                continue;
            }

            $depth = (int) $record[0];

            // We only process 0 level records here. Sub levels are processed
            // in methods for those data types (individuals, sources, etc)

            if ($depth == 0) {
                // Although not always an identifier (HEAD,TRLR):
                if (isset($record[1])) {
                    $this->normalizeIdentifier($record[1]);
                }

                if (isset($record[1]) && trim((string) $record[1]) == 'HEAD') {
                    \Gedcom\Parser\Head::parse($this);
                } elseif (isset($record[2]) && trim((string) $record[2]) == 'SUBN') {
                    \Gedcom\Parser\Subn::parse($this);
                } elseif (isset($record[2]) && trim((string) $record[2]) == 'SUBM') {
                    \Gedcom\Parser\Subm::parse($this);
                } elseif (isset($record[2]) && $record[2] == 'SOUR') {
                    \Gedcom\Parser\Sour::parse($this);
                } elseif (isset($record[2]) && $record[2] == 'INDI') {
                    \Gedcom\Parser\Indi::parse($this);
                } elseif (isset($record[2]) && $record[2] == 'FAM') {
                    \Gedcom\Parser\Fam::parse($this);
                } elseif (isset($record[2]) && str_starts_with(trim((string) $record[2]), 'NOTE')) {
                    \Gedcom\Parser\Note::parse($this);
                } elseif (isset($record[2]) && $record[2] == 'REPO') {
                    \Gedcom\Parser\Repo::parse($this);
                } elseif (isset($record[2]) && $record[2] == 'OBJE') {
                    \Gedcom\Parser\Obje::parse($this);
                } elseif (isset($record[1]) && trim((string) $record[1]) == 'TRLR') {
                    // EOF
                    break;
                } else {
                    $this->logUnhandledRecord(self::class . ' @ ' . __LINE__);
                }
            } else {
                $this->logUnhandledRecord(self::class . ' @ ' . __LINE__);
            }

            $this->forward();
        }

        return $this->getGedcom();
    }

    /**
     * Streaming parser for large files (PHP 8.4 optimization)
     */
    private function parseStreaming(): ?Gedcom
    {
        $this->fileHandle->setFlags(\SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);

        foreach ($this->fileHandle as $lineNumber => $line) {
            $record = $this->parseLine($line);

            if (empty($record)) {
                continue;
            }

            $depth = (int) $record[0];

            // Process only 0 level records in streaming mode for memory efficiency
            if ($depth === 0) {
                $this->processTopLevelRecord($record);
            }
        }

        return $this->gedcom;
    }

    /**
     * Process top-level records with optimized matching (PHP 8.4 match expression)
     */
    private function processTopLevelRecord(array $record): void
    {
        if (isset($record[1])) {
            $this->normalizeIdentifier($record[1]);
        }

        $tag = $record[2] ?? $record[1] ?? '';

        match (trim($tag)) {
            'HEAD' => \Gedcom\Parser\Head::parse($this),
            'SUBN' => \Gedcom\Parser\Subn::parse($this),
            'SUBM' => \Gedcom\Parser\Subm::parse($this),
            'SOUR' => \Gedcom\Parser\Sour::parse($this),
            'INDI' => \Gedcom\Parser\Indi::parse($this),
            'FAM' => \Gedcom\Parser\Fam::parse($this),
            'REPO' => \Gedcom\Parser\Repo::parse($this),
            'OBJE' => \Gedcom\Parser\Obje::parse($this),
            'TRLR' => null, // EOF
            default => str_starts_with($tag, 'NOTE') ? \Gedcom\Parser\Note::parse($this) : $this->logUnhandledRecord(self::class . ' @ ' . __LINE__)
        };
    }

    public function getGedcom(): Gedcom
    {
        return $this->gedcom;
    }

    public function getCurrentLineRecord(): array
    {
        if ($this->eof()) {
            return [];
        }

        $line = $this->lineBuffer[$this->currentLine];
        return $this->parseLine($line);
    }

    private function parseLine(string $line): array
    {
        // Simple line parser that splits the line into level, tag, and value
        if (preg_match('/^(\d+)\s+(@[^@]+@)?\s*(\w+)(\s+(.*))?$/', $line, $matches)) {
            if (!empty($matches[2])) {
                // Line with ID: level, ID, tag, value
                return [$matches[1], $matches[2], $matches[3] ?? ''];
            } else {
                // Line without ID: level, tag, value
                return [$matches[1], $matches[3], $matches[5] ?? ''];
            }
        }

        return [];
    }

    public function normalizeIdentifier(string $identifier): string
    {
        return trim($identifier, '@');
    }

    public function logUnhandledRecord(string $context): void
    {
        if ($this->eof()) {
            return;
        }

        $line = $this->lineBuffer[$this->currentLine];
        $this->errors[] = "Unhandled record at $context: $line";
    }

    public function skipToNextLevel(int $level): void
    {
        while (!$this->eof()) {
            $record = $this->getCurrentLineRecord();
            if ((int)$record[0] <= $level) {
                break;
            }
            $this->forward();
        }
    }

    public function parseMultiLineRecord(): string
    {
        $result = '';
        $startLine = $this->currentLine;
        $startRecord = $this->getCurrentLineRecord();
        $startLevel = (int)$startRecord[0];

        if (isset($startRecord[2])) {
            $result = $startRecord[2];
        }

        $this->forward();

        while (!$this->eof()) {
            $record = $this->getCurrentLineRecord();
            $currentLevel = (int)$record[0];

            if ($currentLevel <= $startLevel) {
                $this->back();
                break;
            }

            if ($currentLevel > $startLevel && isset($record[1]) && $record[1] === 'CONT') {
                $result .= "\n" . ($record[2] ?? '');
            } elseif ($currentLevel > $startLevel && isset($record[1]) && $record[1] === 'CONC') {
                $result .= ($record[2] ?? '');
            } else {
                $this->back();
                break;
            }

            $this->forward();
        }

        return $result;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function forward(): void
    {
        $this->currentLine++;
    }

    public function back(): void
    {
        $this->currentLine--;
    }

    public function eof(): bool
    {
        return $this->currentLine >= count($this->lineBuffer);
    }
}