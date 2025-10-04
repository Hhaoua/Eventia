<?php

namespace App\Exports;

use App\Models\Evenement;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AnalyticsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return collect([
            ['Label', 'Valeur'],
            ['Utilisateurs inscrits', User::count()],
            ['Événements publiés', Evenement::count()],
            ['Revenu total (FCFA)', Evenement::sum('tarif')],
            ['Taux de remplissage moyen (%)', Evenement::avg('places_max') > 0
                ? round(Evenement::avg('inscrits_count') / Evenement::avg('places_max') * 100, 1)
                : 0],
        ]);
    }

    public function headings(): array
    {
        return [];
    }

    public function map($row): array
    {
        return $row;
    }
}
