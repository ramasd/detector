<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectErrorMail extends Mailable
{
    use Queueable, SerializesModels;

    public $project;
    public $request_data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($project, $request_data)
    {
        $this->project = $project;
        $this->request_data = $request_data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.project_error')
            ->from(config('app.email'), config('app.name'))
            ->subject('Project error');
    }
}
