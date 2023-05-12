<?php

namespace Vcian\LaravelDBAuditor\Controllers\Query;

use Illuminate\Contracts\View\View;
use Vcian\LaravelDataBringin\Http\Controllers\Controller;

/**
 * QueryOptimizationController
 */
class QueryOptimizationController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        return view('DBAuditor::optimization.index');
    }
}
