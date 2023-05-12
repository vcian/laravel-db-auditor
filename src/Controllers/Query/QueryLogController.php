<?php

namespace Vcian\LaravelDBAuditor\Controllers\Query;

use Illuminate\Contracts\View\View;
use Vcian\LaravelDataBringin\Http\Controllers\Controller;
use Vcian\LaravelDBAuditor\Services\QueryService;

/**
 * QueryLogController
 */
class QueryLogController extends Controller
{
    public function __construct(protected QueryService $queryService)
    {
    }

    /**
     * @return View
     */
    public function index(): View
    {
        $responseData = $this->queryService->getQueryLogs();

        return view('DBAuditor::query-logs.index', compact('responseData'));
    }
}
