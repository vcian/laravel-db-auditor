<?php

namespace Vcian\LaravelDBAuditor\Traits;

use Illuminate\Support\Facades\DB;
use Vcian\LaravelDBAuditor\Constants\Constant;
use Vcian\LaravelDBAuditor\Queries\DatabaseConstraintClass;
use Vcian\LaravelDBAuditor\Queries\DatabaseConstraintListClass;
use function Termwind\{renderUsing};
use function Termwind\{render};

trait DisplayTable
{
    /**
     * @var array
     */
    protected array $result;

    /**
     * @param $query
     * @return array
     */
    public function select($query): array
    {
        return DB::select($query);
    }

    /**
     * Displays the table details.
     *
     * @param string $tableName
     */
    public function display(string $tableName) :void
    {
        $constraint = new DatabaseConstraintClass($tableName);
        $fields = $this->getFieldsDetails($tableName);
        $data = [
            'table' => $tableName,
            'size' => $this->getTableSize($tableName),
            'fields' => $fields,
            'field_count' => count($fields),
            'constraint' => $constraint()
        ];

        render(view('DBAuditor::'.connection_driver().'.constraint', ['data' => $data]));
    }
}
