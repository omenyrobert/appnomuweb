<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\AuthenticationController as Auth;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $email;
    public $code;
    public $fullname;
    public function __construct($emailx,$codex)
    {
        //
        $this->email = $emailx;
        $this->code = $codex;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = Auth::getUserByEmail($this->email);

        if (sizeof($user)>0) {
            $this->fullname = $user[0]['name'];
            return $this->view('email.resetPass');
        } else {
            return redirect()->back()->withErrors(['Errors'=>'No Such User Found']);
        }
        
    }
}
