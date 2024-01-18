<?php

namespace Vcian\LaravelDBAuditor\Traits;

use Illuminate\Support\Facades\File;
use Vcian\LaravelDBAuditor\Constants\Constant;

trait DBMigrationTrack
{
    /**
     * Get Database related information from the migration file.
     * Collect all the details into one place.
     */
    public function collectDataFromFile($type = 'command')
    {
        $data = [];

        $filesInFolder = File::files(database_path('migrations'));

        foreach ($filesInFolder as $path) {
            $file = pathinfo($path);

            $fileName = $file['basename'];

            if ($type === 'command') {
                array_push($data,
                    [
                        $this->getMigrationDate($file['filename']),
                        $this->getMigrationTableName($fileName),
                        $this->replaceStringWithDots($this->getMigrationFieldName($fileName)),
                        $this->getMigrationAction($fileName),
                        $fileName,
                        $this->getMigrationStatus($file['filename']),
                        $this->getMigrationCreatedBy($fileName),
                    ]
                );
            } else {
                array_push($data,
                    [
                        'date' => $this->getMigrationDate($file['filename']),
                        'table' => $this->getMigrationTableName($fileName),
                        'fields' => $this->getMigrationFieldName($fileName),
                        'action' => $this->getMigrationAction($fileName),
                        'file' => $fileName,
                        'status' => $this->getMigrationStatus($file['filename']),
                        'createdby' => $this->getMigrationCreatedBy($fileName),
                    ]
                );
            }

        }

        return $data;
    }

    /**
     * Filter based on the command argument.
     */
    public function filter($filterType, $data, $filter)
    {
        $result = array_filter($data, function ($item) use ($filter, $filterType) {

            switch ($filterType) {
                case 'table':
                    return $item[1] === $filter;
                case 'action':
                    return $item[3] === $filter;
                case 'status':
                    return $item[5] === $filter;
                default:
                    return $item;
            }
        });

        return array_values($result);
    }

    /**
     * Read File and Get File Content
     */
    public function getFileData(string $file): string
    {
        return file_get_contents(database_path('migrations/'.$file));
    }

    /**
     * Get migration action from the define schema in migration file.
     */
    public function getMigrationAction(string $file): string
    {
        $fileContents = $this->getFileData($file);
        if (strpos($fileContents, 'Schema::create') !== false) {
            return Constant::CREATE;
        } elseif (strpos($fileContents, 'Schema::table') !== false) {
            return Constant::UPDATE;
        } else {
            return Constant::NOT_DEFINE;
        }
    }

    /**
     * Get migration date from the migration file name.
     */
    public function getMigrationDate(string $fileName): string
    {
        $parts = explode('_', $fileName);
        $date = implode('_', array_slice($parts, 0, 3));

        return str_replace('_', '-', $date);
    }

    /**
     * Get table name from define schema in migration file.
     */
    public function getMigrationTableName(string $file): string
    {
        $tableNameMatches = [];

        $migrationFile = $this->getFileData($file);

        if (preg_match('/Schema::create\(\'(\w+)\'/', $migrationFile, $tableNameMatches)) {
            return $tableNameMatches[1];
        } elseif (preg_match('/Schema::table\(\'(\w+)\'/', $migrationFile, $tableNameMatches)) {
            return $tableNameMatches[1];
        } else {
            return Constant::NOT_DEFINE;
        }
    }

    /**
     * Get field name from the define schema in file.
     */
    public function getMigrationFieldName(string $file): string
    {
        $fieldMatches = [];
        $fieldNames = [];

        $migrationFile = $this->getFileData($file);

        if (preg_match_all('/\$table->(\w+)\(\'(\w+)\'/', $migrationFile, $fieldMatches)) {
            $fieldNames = $fieldMatches[2];
        }

        return ! empty($fieldNames) ? implode(', ', $fieldNames).PHP_EOL : '-';
    }

    /**
     * Get status from the command.
     */
    public function getMigrationStatus(string $fileName): string
    {
        $output = shell_exec('php artisan migrate:status');

        preg_match_all('/\s(\d{4}_\d{2}_\d{2}_\d{6}_\w+).*?Pending/', $output, $matches);

        if (in_array($fileName, $matches[1])) {
            return Constant::PENDING;
        } else {
            return Constant::MIGRATED;
        }
    }

    /**
     * Replace the long string with dots.
     */
    public function replaceStringWithDots(string $inputString): string
    {
        $elements = explode(', ', $inputString);
        $firstFourElements = array_slice($elements, 0, 4);
        $result = implode(', ', $firstFourElements);

        if (count($elements) > 4) {
            $result .= ', ...';
        }

        return $result;
    }

    /**
     * Get created by from git commit for migration file.
     */
    public function getMigrationCreatedBy(string $file): string
    {
        $currentUser = gethostname();

        $repositoryPath = base_path();
        $migrationFilePath = 'database/migrations/'.$file;
        $authorName = trim(shell_exec("git -C $repositoryPath log --follow --format='%an' -- $migrationFilePath"));

        return $authorName != '' ? $authorName : $currentUser;
    }
}
