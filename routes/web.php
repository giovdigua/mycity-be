<?php

use App\Http\Controllers\ExportCsvController;
use Illuminate\Support\Facades\Route;

Route::get('/export', [ExportCsvController::class, 'export']);
