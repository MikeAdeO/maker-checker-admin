<?php

namespace App\Mail;

use App\Models\UserDraft;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyAdmin extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $userDraft;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(UserDraft $userDraft)
    {
        $this->userDraft = $userDraft;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('There is a pending $request')
            ->from('no-reply@gloove.co')
            ->markdown('emails.admin.notify');
    }
}
