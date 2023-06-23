<?php

use Illuminate\Support\Facades\Route;
use Vcian\LaravelDBAuditor\Controllers\DisplayController;

Route::get('laravel-db-auditor', [DisplayController::class, 'index']);