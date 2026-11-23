<?php

namespace App\Notifications;

use App\Models\Reclamation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReclamationTraitee extends Notification
{
    use Queueable;

    public function __construct(public Reclamation $reclamation) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => 'Votre réclamation pour ' .
                ($this->reclamation->note?->matiere?->libelle ?? 'une note') .
                ' a été traitée.',
            'reclamation_id' => $this->reclamation->id,
        ];
    }
}