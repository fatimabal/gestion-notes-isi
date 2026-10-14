<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class SujetDepose extends Notification
{
    public function __construct(
        public string $matiere,
        public string $enseignantNom
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => "L'enseignant {$this->enseignantNom} a déposé le sujet de {$this->matiere}",
            'type'    => 'sujet_depose',
            'matiere' => $this->matiere,
        ];
    }
}