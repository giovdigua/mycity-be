<?php

namespace App\Jobs;

use App\Mail\SendCsvEmail;
use App\Models\User;
use DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use function Laravel\Prompts\table;

class ExportUserToCSVJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $data = DB::table('users')
            ->select(
                'name',
                'surname',
                'email',
                'fiscal_code',
                'phone_number',
                'date_of_birth'
            )->get();

        $csvFileName = 'export_user_my_city.csv';
        $csvFilePath = storage_path('app/' . $csvFileName);

        $file = fopen($csvFilePath, 'w');
        fputcsv($file, array_keys((array)$data->first()));
        foreach ($data as $row) {
            fputcsv($file, (array)$row);
        }
        fclose($file);

        Mail::to('info@mycity.it')->send(new SendCsvEmail($csvFilePath));

        Storage::delete($csvFilePath);
    }
}
