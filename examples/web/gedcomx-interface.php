<?php

/**
 * Web interface for Gedcom X import/export functionality
 * 
 * This provides a simple web form for uploading and converting genealogical files
 * between GEDCOM and Gedcom X formats.
 */

require_once __DIR__ . '/../../vendor/autoload.php';

use Gedcom\GedcomResource;

$resource = new GedcomResource();
$message = '';
$error = '';
$statistics = null;

// Handle file upload and conversion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['genealogy_file'])) {
    try {
        $uploadedFile = $_FILES['genealogy_file'];
        $outputFormat = $_POST['output_format'] ?? 'gedcom';

        if ($uploadedFile['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('File upload failed');
        }

        $tempFile = $uploadedFile['tmp_name'];
        $originalName = $uploadedFile['name'];
        $fileExtension = pathinfo($originalName, PATHINFO_EXTENSION);

        // Detect input format
        $inputFormat = $resource->detectFileFormat($tempFile);

        // Import the file
        $gedcom = $resource->import($tempFile);

        if ($gedcom) {
            $statistics = $resource->getStatistics($gedcom);

            // Generate output filename
            $baseName = pathinfo($originalName, PATHINFO_FILENAME);
            $outputExtension = ($outputFormat === 'gedcomx') ? 'json' : 'ged';
            $outputFileName = $baseName . '_converted.' . $outputExtension;

            // Set headers for download
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $outputFileName . '"');

            if ($outputFormat === 'gedcomx') {
                $content = $resource->gedcomxGenerator->generate($gedcom);
            } else {
                $content = \Gedcom\Writer::convert($gedcom);
            }

            echo $content;
            exit;
        } else {
            $error = 'Failed to parse the uploaded file';
        }

    } catch (Exception $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gedcom X Converter</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 2px dashed #ddd;
            border-radius: 4px;
            background: #fafafa;
        }
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
        }
        button {
            background: #007cba;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        button:hover {
            background: #005a87;
        }
        .message {
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .info-box {
            background: #e7f3ff;
            border: 1px solid #b8daff;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
        }
        .format-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 20px 0;
        }
        .format-card {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            background: #fafafa;
        }
        .format-card h3 {
            margin-top: 0;
            color: #333;
        }
        .statistics {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
        }
        .stat-item {
            text-align: center;
            padding: 10px;
            background: white;
            border-radius: 4px;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #007cba;
        }
        .stat-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gedcom X Converter</h1>

        <div class="info-box">
            <strong>About this tool:</strong> Convert genealogical data between traditional GEDCOM 5.5 format and modern Gedcom X (JSON) format. 
            Upload your file and select the desired output format.
        </div>

        <?php if ($error): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($message): ?>
            <div class="message success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="genealogy_file">Select Genealogy File:</label>
                <input type="file" id="genealogy_file" name="genealogy_file" accept=".ged,.gedcom,.json,.gedcomx" required>
                <small>Supported formats: GEDCOM (.ged, .gedcom), Gedcom X (.json, .gedcomx)</small>
            </div>

            <div class="form-group">
                <label for="output_format">Output Format:</label>
                <select id="output_format" name="output_format" required>
                    <option value="gedcom">GEDCOM 5.5 (.ged)</option>
                    <option value="gedcomx">Gedcom X (.json)</option>
                </select>
            </div>

            <button type="submit">Convert and Download</button>
        </form>

        <div class="format-info">
            <div class="format-card">
                <h3>GEDCOM 5.5</h3>
                <p><strong>Traditional format</strong> used by most genealogy software. Text-based with hierarchical structure.</p>
                <ul>
                    <li>Widely supported</li>
                    <li>Established standard</li>
                    <li>Human-readable text format</li>
                </ul>
            </div>
            <div class="format-card">
                <h3>Gedcom X</h3>
                <p><strong>Modern JSON format</strong> designed for web applications and APIs. More flexible and extensible.</p>
                <ul>
                    <li>JSON-based structure</li>
                    <li>RESTful API friendly</li>
                    <li>Extensible data model</li>
                </ul>
            </div>
        </div>

        <?php if ($statistics): ?>
            <div class="statistics">
                <h3>File Statistics</h3>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number"><?= $statistics['individuals'] ?></div>
                        <div class="stat-label">Individuals</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= $statistics['families'] ?></div>
                        <div class="stat-label">Families</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= $statistics['sources'] ?></div>
                        <div class="stat-label">Sources</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= $statistics['repositories'] ?></div>
                        <div class="stat-label">Repositories</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= $statistics['notes'] ?></div>
                        <div class="stat-label">Notes</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= $statistics['media_objects'] ?></div>
                        <div class="stat-label">Media Objects</div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="info-box">
            <h3>Usage Examples:</h3>
            <p><strong>CLI Usage:</strong></p>
            <code>
                # Import Gedcom X file<br>
                php examples/cli/gedcomx-import.php family.json family.ged<br><br>
                # Export to Gedcom X<br>
                php examples/cli/gedcomx-export.php family.ged family.json
            </code>
        </div>
    </div>
</body>
</html>