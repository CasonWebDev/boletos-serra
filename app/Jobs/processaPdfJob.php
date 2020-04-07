<?php

namespace App\Jobs;

use App\Http\Services\sintetizaPdf;
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
    private $page;

    public $timeout = 0;
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file, $mes, $ano, $page)
    {
        $this->file = $file;
        $this->mes = $mes;
        $this->ano = $ano;
        $this->page = $page;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        sintetizaPdf::sintetizarPdf($this->file, $this->mes, $this->ano, $this->page);
    }
}
