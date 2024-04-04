<?php

namespace App\Jobs;

use App\Models\Job;
use App\Mail\NewJobMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NewJobMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $job;
    /**
     * Create a new job instance.
     */
    public function __construct(Job $job)
    {
        $this->job = $job;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $email = new NewJobMail($this->job);

        Mail::send($email);
    }

    public function retryUntil()
    {
        // $availableAt = Carbon::now()->timestamp;
        $availableAt = Carbon::now()->toDateTimeString();
        // return Carbon::now()->addMinutes(10); // For example, the job should be retried in 10 minutes
        DB::connection('your_connection')->insert('your_insert_query_here', [
            'queue' => 'default',
            'attempts' => 0,
            'reserved_at' => null,
            'available_at' => $availableAt, // Use the formatted datetime string
            'created_at' => $availableAt, // Use the same value for created_at
            'payload' => 'your_payload_value_here',]);
    }
}
