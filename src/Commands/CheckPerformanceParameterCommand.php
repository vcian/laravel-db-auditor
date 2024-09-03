<?php

namespace Vcian\LaravelDBAuditor\Commands;

use Illuminate\Console\Command;
use Vcian\LaravelDBAuditor\Traits\Audit;
use Vcian\LaravelDBAuditor\Constants\Constant;

/**
 * Class CheckPerformanceParameterCommand
 *
 * This command checks various database performance parameters.
 *
 * @package Vcian\LaravelDBAuditor\Commands
 */
class CheckPerformanceParameterCommand extends Command
{
    use Audit;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:check-performance-parameter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a database performance report with optimization suggestions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Generating Database Performance Report...');

        $report = $this->generatePerformanceReport();

        if (isset($report['error'])) {
            $this->error($report['error']);
            return 1;
        }

        $this->table(
            ['Parameter', 'Current Value', 'Suggestion'],
            $this->formatReportForTable($report)
        );

        $this->info('Database Performance Report generated successfully.');
        return 0;
    }

    /**
     * Format the report data for display in a table.
     *
     * @param array $report The report data
     * @return array The formatted report data
     */
    private function formatReportForTable(array $report): array
    {
        $tableData = [];
        foreach ($report as $parameter => $data) {
            $tableData[] = [
                $parameter,
                $this->formatValue($data['current']),
                $data['suggestion']
            ];
        }
        return $tableData;
    }

    /**
     * Format a value for display in a table.
     *
     * @param string $value The value to format
     * @return string The formatted value
     */
    private function formatValue(string $value): string
    {
        $numericValue = (int) $value;
        return $numericValue >= Constant::BYTES_IN_GB ? round($numericValue / Constant::BYTES_IN_GB, 2) . ' ' . Constant::GB :
               ($numericValue >= Constant::BYTES_IN_MB ? round($numericValue / Constant::BYTES_IN_MB, 2) . ' ' . Constant::MB :
               ($numericValue >= Constant::BYTES_IN_KB ? round($numericValue / Constant::BYTES_IN_KB, 2) . ' ' . Constant::KB : $value));
    }
}
