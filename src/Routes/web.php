<?php

use Illuminate\Support\Facades\Route;
use Vcian\LaravelDBAuditor\Controllers\Query\QueryOptimizationController;
use Vcian\LaravelDBAuditor\Controllers\Query\QueryLogController;

Route::group([
    'prefix' => 'db-auditor',
    'as' => 'db-auditor.'
], function () {
    //Query Optimization
    Route::get('/', [QueryOptimizationController::class, 'index'])->name('optimization');
    //Query Logs
    Route::get('/query-logs', [QueryLogController::class, 'index'])->name('logs');
});
