@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto py-12 px-6">
        <h2 class="text-2xl font-bold mb-6">Choisissez votre moyen de paiement</h2>

        <form action="{{ route('events.traiterPaiement', $event->id) }}" method="POST">
            @csrf

            <div class="space-y-4">
                <label class="flex items-center">
                    <input type="radio" name="moyen_paiement" value="carte" required class="mr-3">
                    <span>💳 Carte bancaire</span>
                </label>

                <label class="flex items-center">
                    <input type="radio" name="moyen_paiement" value="mobile_money" required class="mr-3">
                    <span>📱 Mobile Money (Moov, Orange)</span>
                </label>

                <label class="flex items-center">
                    <input type="radio" name="moyen_paiement" value="paypal" required class="mr-3">
                    <span>🌍 PayPal</span>
                </label>
            </div>

            <x-primary-button class="mt-6">
                Continuer vers le paiement
            </x-primary-button>
        </form>
    </div>
@endsection
