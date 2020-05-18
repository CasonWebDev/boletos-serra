<?php

namespace App\Jobs;

use App\Http\Services\deletaBoletos;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeletaBoletoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $boleto;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($boleto)
    {
        $this->boleto = $boleto;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        deletaBoletos::deletaBoleto($this->boleto);
    }
}
