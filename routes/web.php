<?php

use Illuminate\Support\Facades\Route;
use Vcian\LaravelDBAuditor\Controllers\DisplayController;

Route::get('laraveldbauditor', [DisplayController::class, 'index']);
Route::get('getAudit', [DisplayController::class, 'getAudit']);