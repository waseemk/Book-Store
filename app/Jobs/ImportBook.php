<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Mail\SendImportNotification;
use App\Mail\SendFailedJobNotification;
use Illuminate\Support\Facades\Mail;

use App\Libraries\BookDataImportProcessor;

class ImportBook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->bookDataImportProcessor = new BookDataImportProcessor();

        \Log::debug("ImportBook::handle - Importing book data starts...");

        $data = $this->bookDataImportProcessor->importData();

        // Send email
        if($data == true){
            $this->sendEmail();
        }

        \Log::debug("ImportBook::handle - Importing book data ends...");
    }

    /**
     * Sending a email to user of gp checker import data.
     *
     * @return void
     */
    private function sendEmail()
    {
        $user = $this->user;
        // Send email
        
        Mail::to($user)->send(new SendImportNotification());
    }

    /**
     * The job failed to process.
     *
     * @param  $exception
     * @return void
     */
    public function failed($exception)
    {
        $user = $this->user;
        // Send failed email
        Mail::to($user)->send(new SendFailedJobNotification());
    }
}
