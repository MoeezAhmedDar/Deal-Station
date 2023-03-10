<?php

namespace App\Jobs;

use App\Mail\CashierWelcomeMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class CashierWelcomeMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $queueData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($queueData)
    {
        $this->queueData = $queueData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->queueData['email'])->cc($this->queueData['m_email'])->send(new CashierWelcomeMail($this->queueData['data']));
    }
}
