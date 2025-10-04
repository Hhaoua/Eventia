<?php

namespace App\Services;

use App\Models\Billet;
use App\Models\Evenement;
use App\Models\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class BilletService
{
    public function genererBillet(User $user, Evenement $evenement)
    {
        $codeUnique = $this->genererCodeUnique();

        $billet = Billet::create([
            'user_id' => $user->id,
            'evenement_id' => $evenement->id,
            'code_unique' => $codeUnique,
        ]);

        // Générer le QR code
        $qrCodePath = $this->genererQRCode($billet);

        $billet->update([
            'qr_code_path' => $qrCodePath
        ]);

        return $billet;
    }

    private function genererCodeUnique()
    {
        return 'BIL-' . strtoupper(Str::random(8)) . '-' . time();
    }

    private function genererQRCode(Billet $billet)
    {
        $qrCodeContent = json_encode([
            'billet_id' => $billet->id,
            'code' => $billet->code_unique,
            'event' => $billet->evenement->titre,
            'user' => $billet->user->email,
            'date' => $billet->created_at->toISOString()
        ]);

        $filename = 'qrcodes/billet_' . $billet->id . '.svg';
        $path = public_path('storage/' . $filename);

        // Créer le dossier s'il n'existe pas
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        QrCode::size(300)
            ->backgroundColor(255, 255, 255)
            ->color(0, 0, 0)
            ->style('square')
            ->eye('square')
            ->generate($qrCodeContent, $path);

        return $filename;
    }

    public function validerBillet($codeUnique)
    {
        $billet = Billet::where('code_unique', $codeUnique)
            ->where('statut', 'valide')
            ->first();

        if (!$billet) {
            return ['success' => false, 'message' => 'Billet invalide ou déjà utilisé'];
        }

        $billet->update([
            'statut' => 'utilise',
            'date_utilisation' => now()
        ]);

        return ['success' => true, 'billet' => $billet];
    }
}
