<x-mail::message>
# Bonjour {{ $user->prenom }},

Votre inscription à l’événement **{{ $event->titre }}** est confirmée !

---

**Date & heure :** {{ $event->date->format('d/m/Y à H:i') }}
**Lieu :** {{ $event->lieu }}
**Prix :** {{ $event->type === 'payant' ? $event->tarif.' FCFA' : 'Gratuit' }}
**Code billet :** `{{ $billet->code_unique }}`

---

### Votre billet QR code
Présentez ce code à l’entrée :

<div style="text-align: center; margin: 24px 0;">
<img src="{{ asset('storage/' . $billet->qr_code_path) }}" alt="QR Code billet" style="width: 200px; height: 200px;" />
</div>

<x-mail::button :url="asset('storage/' . $billet->qr_code_path)" color="gray">
Télécharger le billet
</x-mail::button>

---

Merci de votre confiance et à bientôt !
</x-mail::message>
