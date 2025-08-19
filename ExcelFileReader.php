<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
$folder = explode('/',trim(explode($_SERVER['DOCUMENT_ROOT'],$_SERVER['SCRIPT_FILENAME'])[1],'/'))[0];
include_once($_SERVER['DOCUMENT_ROOT'].("/".$folder).'/app/Helpers/'."db_helper.php");

class ExcelFileReader
{
    public static function previewExcel($path)
    {
        try {
            $server = DocServer($path);
            if (!$server) {
                return self::error("Document server not configured.");
            }

            $postData = [
                "targetPath" => "uploads/" . $path,
                "drive" => $server["drive"]
            ];

            // Step 1: Get MIME type and size
            $mimeInfo = self::curlPost($server["server"] . 'mim-type', $postData);
            $mime = $mimeInfo["mime"] ?? '';
            $size = $mimeInfo["size"] ?? '';

            if (!$mime || !$size ) {
                return self::error("Only Excel or CSV files are allowed.");
            }

            // Step 2: Download file content from server
            $fileContent = self::curlPost($server["server"] . 'read', $postData, false);
            if (!$fileContent) {
                return self::error("Failed to read file content.");
            }

            // Step 3: Store to a temp path
            $extension = pathinfo($path, PATHINFO_EXTENSION);
			$tempPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid("temp_") . ".xlsx";
			file_put_contents($tempPath, $fileContent);

			// Load and read Excel
			$spreadsheet = IOFactory::load($tempPath);
			$data = $spreadsheet->getActiveSheet()->toArray();
			unlink($tempPath); // Clean up

            if (empty($data)) {
                return self::error("The Excel file appears to be empty.");
            }

            // Step 5: Generate table HTML
            $html = "
            <style>
                .excelTable {
                    border-collapse: collapse;
                    width: 100%;
                    font-size: 14px;
                    margin-bottom: 20px;
                }
                .excelTable th, .excelTable td {
                    border: 1px solid #888;
                    padding: 6px 10px;
                    text-align: left;
                }
                .excelTable thead {
                    background-color: #f0f0f0;
                    font-weight: bold;
                }
            </style>
            <table class='excelTable'>
                <thead><tr>";

            foreach ($data[0] as $header) {
                $html .= "<th>" . htmlspecialchars($header, ENT_QUOTES, 'UTF-8') . "</th>";
            }

            $html .= "</tr></thead><tbody>";

            foreach (array_slice($data, 1) as $row) {
                $html .= "<tr>";
                foreach ($row as $cell) {
                    $html .= "<td>" . htmlspecialchars($cell, ENT_QUOTES, 'UTF-8') . "</td>";
                }
                $html .= "</tr>";
            }

            $html .= "</tbody></table>";
            echo json_encode([
                'html' => $html,
                'filename' => basename($path),
                'downloadUrl' => urlencode($path),
            ]);

        } catch (\Throwable $th) {
            echo self::error("Unable to preview file.");
        }
    }

    private static function curlPost($url, $data, $decodeJson = true)
    {
        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        return $decodeJson ? json_decode($response, true) : $response;
    }

    private static function error($message)
    {
        return json_encode([
            'html' => "<div style='color: red; font-weight: bold;'>$message</div>",
            'filename' => 'Error',
            'downloadUrl' => '#'
        ]);
    }
}

ExcelFileReader::previewExcel($_GET["path"]);
