{{-- resources/views/emails/inscription-event.blade.php --}}
<x-mail::message>
# Bonjour {{ $user->nom }},

Votre inscription à l’événement **{{ $event->titre }}** est confirmée !

**Date & heure :** {{ $event->date->format('d/m/Y H:i') }}  
**Lieu :** {{ $event->lieu }}  
**Prix :** {{ $event->type === 'paid' ? $event->tarif.' €' : 'Gratuit' }}

<x-mail::button :url="route('events.show', $event)">
Voir l’événement
</x-mail::button>

Merci de votre confiance et à bientôt !  
**L’équipe {{ config('app.name') }}**
</x-mail::message>