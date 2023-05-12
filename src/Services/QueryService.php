<?php

namespace Vcian\LaravelDBAuditor\Services;

use Illuminate\Support\Str;
use Vcian\LaravelDBAuditor\Constants\Constant;

/**
 *
 */
class QueryService
{
    public function getQueryLogs(): array
    {
        $response = [];
        $content = file_get_contents(storage_path(Constant::QUERY_LOG_FILE_PATH . Constant::DEFAULT_QUERY_LOG_FILENAME));
        $matches = array_filter(preg_split("/\r\n|\n|\r/", $content));

        foreach (array_reverse($matches) as $match) {
            $singleRecord = explode(' : ', $match);
            $response[] = [
                'timestamp' => $singleRecord[0],
                'method' => trim($singleRecord[1]),
                'query' => trim($singleRecord[2]),
                'duration' => trim($singleRecord[3])
            ];
        }

        return $response;
    }
}
