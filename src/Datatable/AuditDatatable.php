<?php

namespace Vcian\LaravelDBAuditor\Datatable;

use Yajra\DataTables\Services\DataTable;

class AuditDatatable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($row) {
                // Add custom action column
            });
    }

    public function query()
    {
        // Define your data source query here
    }

    public function html()
    {
        // Define the HTML structure of your DataTable
    }
}
