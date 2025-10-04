<?php

namespace App\Exports;

use App\Models\Evenement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ParticipantsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $event;

    public function __construct(Evenement $event)
    {
        $this->event = $event;
    }

    public function collection()
    {
        return $this->event->participants;
    }

    public function headings(): array
    {
        return ['Nom', 'Prénom', 'Email', 'Téléphone'];
    }

    public function map($participant): array
    {
        return [
            $participant->nom,
            $participant->prenom,
            $participant->email,
            $participant->telephone ?? 'N/A',
        ];
    }
}
