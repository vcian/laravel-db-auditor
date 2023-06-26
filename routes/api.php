<?php

use Illuminate\Support\Facades\Route;
use Vcian\LaravelDBAuditor\Controllers\DisplayController;

Route::prefix('api')->group(function() {
    Route::get('getAudit', [DisplayController::class, 'getAudit']);
    Route::get('getTableData/{table}', [DisplayController::class, 'getTableData']);
    Route::get('gettableconstraint/{table}', [DisplayController::class, 'getTableConstraint']);


    Route::post('change-constraint', [DisplayController::class, 'changeConstraint']);
});

