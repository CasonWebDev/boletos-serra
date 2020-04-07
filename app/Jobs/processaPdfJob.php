<?php

namespace App\Jobs;

use App\Http\Services\processaPdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class processaPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $file;
    private $mes;
    private $ano;

    public $timeout = 0;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file, $mes, $ano)
    {
        $this->file = storage_path("app/{$file}");
        $this->mes = $mes;
        $this->ano = $ano;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        processaPdf::processarPdf($this->file, $this->mes, $this->ano);
    }
}
