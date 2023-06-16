<?php

namespace Vcian\LaravelDBAuditor\Controllers;

use Vcian\LaravelDBAuditor\Constants\Constant;
use Vcian\LaravelDBAuditor\Traits\Rules;
use Yajra\DataTables\Services\DataTable;

class DisplayController
{
    use Rules;

    /**
     * Return view for audit
     * @return view
     */
    public function index() 
    {
        return view('DBAuditor::auditor.pages.audit');
    }
    
    public function getAudit()
    {
        return $this->tablesRule();
        
    }

}