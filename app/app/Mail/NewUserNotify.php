<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Sisnanceiro\Models\User as UserModel;

class NewUserNotify extends Mailable
{
 use Queueable, SerializesModels;

 public $user;

 /**
  * Create a new message instance.
  *
  * @return void
  */
 public function __construct(UserModel $user)
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
  $user = $this->user;
  return $this->subject('Novo usuário: ' . $this->user->person->firstname)
   ->from(ENV('MAIL_WEBMASTER'), ENV('MAIL_WEBMASTER'))
   ->view('user._new-user-email-notify', compact('user'));
 }
}
