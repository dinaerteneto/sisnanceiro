<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Sisnanceiro\Models\User as UserModel;

class NewUser extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    private $passwordGenerated;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(UserModel $user, $passwordGenerated)
    {
        $this->user              = $user;
        $this->passwordGenerated = $passwordGenerated;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user              = $this->user;
        $passwordGenerated = $this->passwordGenerated;
        return $this->subject('Novo usuário: ' . $this->user->person->firstname)
            ->from(ENV('MAIL_FROM_EMAIL'), ENV('MAIL_FROM_NAME'))
            ->view('user._new-user-email', compact('user', 'passwordGenerated'));
    }
}
