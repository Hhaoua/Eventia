<?php

namespace App\Mail;

use App\Models\Billet;
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
    public $billet;

    public function __construct(User $user, Evenement $event, Billet $billet)
    {
        $this->user = $user;
        $this->event = $event;
        $this->billet = $billet;
    }

    public function build()
    {
        return $this->subject('🎫 Votre billet pour : ' . $this->event->titre)
            ->markdown('emails.inscription-event')
            ->attach(public_path('storage/' . $this->billet->qr_code_path), [
                'as' => 'billet-qrcode.svg',
                'mime' => 'image/svg+xml',
            ]);
    }
}
