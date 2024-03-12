&lt;?php

namespace Gedcom\Utils;

class DataOptimizer
{
    public static function trimString(string $input): string
    {
        return trim($input);
    }

    public static function normalizeIdentifier(string $identifier): string
    {
        $trimmed = self::trimString($identifier);
        return trim($trimmed, '@');
    }

    public static function concatenateWithSeparator(array $strings, string $separator = ' '): string
    {
        return implode($separator, array_map([self::class, 'trimString'], $strings));
    }
}
