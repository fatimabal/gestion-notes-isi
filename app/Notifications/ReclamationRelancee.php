<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class ReclamationRelancee extends Notification
{
    public function __construct(
        public string $matiere,
        public string $etudiantNom
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => "Réclamation de {$this->etudiantNom} en {$this->matiere} nécessite votre attention",
            'type'    => 'reclamation',
        ];
    }
}