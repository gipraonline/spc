<?php

namespace App\Jobs;

use App\Imports\SalesImport;
use App\Models\AdminSaleDraft;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProcessSalesUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $filePath,
        public string $batchId,
    ) {}

    public function handle(): void
    {
        $fullPath = Storage::path($this->filePath);

        if (!file_exists($fullPath)) {
            return;
        }

        Excel::import(new SalesImport($this->batchId), $fullPath);

        Storage::delete($this->filePath);
    }
}