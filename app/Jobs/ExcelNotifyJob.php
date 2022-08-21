<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Events\ExcelExportCompletedEvent;

class ExcelNotifyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $attach;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $attach)
    {
        $this->attach = $attach;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //发送广播通知
        ExcelExportCompletedEvent::dispatch($this->attach['file_name'], $this->attach['disk']);
    }
}
