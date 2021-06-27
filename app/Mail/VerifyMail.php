<?php

namespace App\Mail;

use App\User;
use Crypt;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyMail extends Mailable
{
    use Queueable, SerializesModels;
    private $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('view.name');
         // generate link
         $encryptedEmail = Crypt::encrypt($this->user->email);

         // ex: domain.com/verify?token=xxxx
         $link = route('signup.verify', ['token' => $encryptedEmail]);
 
         return $this->subject('Verify Your Email Address')
             ->with('link', $link)
             ->view('email.signup');
    }
}
