<?php 
namespace App\Mail;

use App\Models\Evenement;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InscriptionEventMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $event;

    public function __construct(User $user, Evenement $event)
    {
        $this->user = $user;
        $this->event = $event;
    }

    public function build()
    {
        return $this->subject('Votre inscription est confirmée')
                    ->markdown('emails.inscription-event');
    }
}