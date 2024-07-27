<?php

namespace App\Http\Controllers;

use App\Jobs\ExportUserToCSVJob;
use Illuminate\Http\Request;

class ExportCsvController extends Controller
{
    public function export()
    {
        ExportUserToCSVJob::dispatch();
        return response()->json(['message' => 'Export Csv successfully email sending...']);
    }
}
