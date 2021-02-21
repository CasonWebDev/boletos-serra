<?php

namespace App\Jobs;

use App\Http\Services\sintetizaPdf;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class processaPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var sintetizaPdf $sintetizaPdf */
    private $sintetizaPdf;
    private $file;
    private $page;

    public $timeout = 0;
    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file, $page)
    {
        $this->sintetizaPdf = app(sintetizaPdf::class);
        $this->file = $file;
        $this->page = $page;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->sintetizaPdf->sintetizarPdf($this->file, $this->page);
    }
}
