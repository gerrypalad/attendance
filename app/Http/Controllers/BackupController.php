<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PDO;
use Exception;

class BackupController extends Controller
{
    private string $backupPath = 'backups';

    /**
     * Authorization helper
     */
    private function authorizeAdmin(): void
    {
        if (!auth()->user()?->is_admin) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function index()
    {
        $this->authorizeAdmin();
        $backups = $this->getBackups();
        return view('backups.index', compact('backups'));
    }

    /**
     * Store a new backup using pure PHP (PDO)
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        try {
            // 1. Get database configurations
            $connection = config('database.default');
            $dbConfig   = config("database.connections.{$connection}");

            if ($dbConfig['driver'] !== 'mysql') {
                return back()->with('error', 'Only MySQL/MariaDB connections are supported.');
            }

            // 2. Prepare paths and files
            $backupDirectory = storage_path("app/{$this->backupPath}");
            if (!is_dir($backupDirectory)) {
                mkdir($backupDirectory, 0755, true);
            }

            $filename = 'backup_' . date('Y-m-d_His') . '.sql';
            $fullPath = $backupDirectory . DIRECTORY_SEPARATOR . $filename;

            // 3. Connect via PDO and dump schema/data
            $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']};charset=utf8mb4";
            $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);

            $handle = fopen($fullPath, 'w+');

            // Write initial structural SQL headers
            fwrite($handle, "-- Pure PHP Laravel Database Backup\n");
            fwrite($handle, "-- Generated: " . date('Y-m-d H:i:s') . "\n");
            fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n\n");

            // Fetch all tables
            $tables = [];
            $result = $pdo->query("SHOW TABLES");
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }

            // Loop tables to extract structure and records
            foreach ($tables as $table) {
                // Export Structure
                fwrite($handle, "-- Table structure for table `{$table}`\n");
                fwrite($handle, "DROP TABLE IF EXISTS `{$table}`;\n");

                $showCreate = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch();
                fwrite($handle, $showCreate['Create Table'] . ";\n\n");

                // Export Data
                fwrite($handle, "-- Dumping data for table `{$table}`\n");
                $rows = $pdo->query("SELECT * FROM `{$table}`");

                while ($data = $rows->fetch()) {
                    $keys = array_map(fn($k) => "`$k`", array_keys($data));
                    $values = array_map(function($v) use ($pdo) {
                        if ($v === null) return 'NULL';
                        return $pdo->quote($v);
                    }, array_values($data));

                    fwrite($handle, "INSERT INTO `{$table}` (" . implode(', ', $keys) . ") VALUES (" . implode(', ', $values) . ");\n");
                }
                fwrite($handle, "\n\n");
            }

            fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
            fclose($handle);

            return back()->with('success', 'Database backup created successfully via pure PHP.');

        } catch (Exception $e) {
            // Clean up empty/broken files if writing fails mid-execution
            if (isset($fullPath) && file_exists($fullPath)) {
                unlink($fullPath);
            }
            return back()->with('error', 'Failed to create backup: ' . $e->getMessage());
        }
    }

    public function download(string $filename): StreamedResponse
    {
        $this->authorizeAdmin();
        $this->validateFilename($filename);
        $fullPath = storage_path("app/{$this->backupPath}/{$filename}");

        if (!file_exists($fullPath)) {
            abort(404, 'Backup file not found.');
        }

        return response()->streamDownload(function () use ($fullPath) {
            readfile($fullPath);
        }, $filename, [
            'Content-Type' => 'application/sql',
        ]);
    }

    public function destroy(string $filename)
    {
        $this->authorizeAdmin();
        $this->validateFilename($filename);
        $fullPath = storage_path("app/{$this->backupPath}/{$filename}");

        if (file_exists($fullPath)) {
            unlink($fullPath);
            return back()->with('success', 'Backup deleted successfully.');
        }

        return back()->with('error', 'Backup file not found.');
    }

    private function getBackups(): array
    {
        $backupPath = storage_path("app/{$this->backupPath}");
        $backups = [];

        if (!is_dir($backupPath)) {
            return $backups;
        }

        foreach (glob($backupPath . '/*.sql') as $file) {
            $backups[] = [
                'filename' => basename($file),
                'size' => $this->formatBytes(filesize($file)),
                'size_raw' => filesize($file),
                'created' => date('Y-m-d H:i:s', filemtime($file)),
                'modified' => filemtime($file),
            ];
        }

        usort($backups, fn($a, $b) => $b['modified'] <=> $a['modified']);
        return $backups;
    }

    private function validateFilename(string $filename): void
    {
        if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $filename)) {
            abort(400, 'Invalid filename.');
        }

        if (str_contains($filename, '..') || str_contains($filename, '/')) {
            abort(400, 'Invalid filename.');
        }
    }

    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        return round($bytes / (1024 ** $pow), $precision) . ' ' . $units[$pow];
    }
}
