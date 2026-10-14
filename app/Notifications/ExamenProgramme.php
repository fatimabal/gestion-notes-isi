<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class ExamenProgramme extends Notification
{
    public function __construct(
        public string $matiere,
        public string $type,      // 'examen' ou 'devoir'
        public string $date,
        public string $classe
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message'  => "{$this->type} de {$this->matiere} prévu le {$this->date}",
            'type'     => $this->type,
            'matiere'  => $this->matiere,
            'date'     => $this->date,
            'classe'   => $this->classe,
        ];
    }
}