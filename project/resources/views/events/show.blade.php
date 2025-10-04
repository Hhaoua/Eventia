@extends('layouts.app')
@section('title', $event->titre)

@push('head')
    <!-- Stripe -->
    <script src="https://js.stripe.com/v3/"></script>
    <!-- PayPal -->
    <script src="https://www.paypal.com/sdk/js?client-id={{ config('services.paypal.client_id') }}&currency=EUR&intent=capture"></script>
@endpush

@section('content')
    <div class="max-w-4xl mx-auto py-12 px-6">

        <img src="{{ $event->image_url ?? 'https://via.placeholder.com/800x400' }}" alt="{{ $event->titre }}" class="w-full h-64 object-cover rounded-xl mb-6">

        <h1 class="text-3xl font-bold mb-2">{{ $event->titre }}</h1>
        <p class="text-gray-600 mb-4">{{ $event->shortDescription }}</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6 text-gray-700">
            <div>📅 {{ $event->date->format('d/m/Y à H:i') }}</div>
            <div>📍 {{ $event->lieu }}</div>
            <div>👥 {{ $event->inscrits_count }}/{{ $event->places_max }} participants</div>
            <div>
                💰
                @if($event->type === 'payant')
                    {{ number_format($event->tarif, 2, ',', ' ') }} FCFA
                @else
                    Gratuit
                @endif
            </div>
        </div>

        <div class="prose max-w-none text-gray-800 mb-8">
            {!! nl2br(e($event->description)) !!}
        </div>

        <div>
            @auth
                @php
                    $alreadyRegistered = $event->participants->contains(auth()->user()->id);
                    $isFull = $event->inscrits_count >= $event->places_max;
                @endphp

                @if($alreadyRegistered)
                    <button class="px-6 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed">Déjà inscrit</button>
                @elseif($isFull)
                    <button class="px-6 py-2 bg-red-500 text-white rounded-lg cursor-not-allowed">Complet</button>
                @else
                    @if($event->type === 'payant')
                        <button onclick="openPaymentModal()"
                                class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                            S’inscrire
                        </button>

                        <!-- Modal -->
                        <div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
                            <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
                                <h3 class="text-lg font-semibold mb-4">Choisissez votre moyen de paiement</h3>

                                <!-- Onglets -->
                                <div class="flex border-b mb-4">
                                    <button class="flex-1 py-2 text-sm font-medium border-b-2 border-purple-600 text-purple-600"
                                            onclick="switchTab('stripe')">Stripe</button>
                                    <button class="flex-1 py-2 text-sm font-medium"
                                            onclick="switchTab('paypal')">PayPal</button>
                                    <button class="flex-1 py-2 text-sm font-medium"
                                            onclick="switchTab('mobile')">Mobile Money</button>
                                </div>

                                <!-- Contenu -->
                                <div id="tab-stripe">
                                    <form id="payment-stripe">
                                        <div id="card-element" class="border rounded-lg p-3 mb-4"></div>
                                        <div id="card-errors" class="text-red-600 text-sm mb-4"></div>
                                        <button type="submit"
                                                class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                                            Payer {{ number_format($event->tarif, 2, ',', ' ') }} FCFA
                                        </button>
                                    </form>
                                </div>

                                <div id="tab-paypal" class="hidden">
                                    <div id="paypal-button-container"></div>
                                </div>

                                <div id="tab-mobile" class="hidden">
                                    <form id="payment-mobile">
                                        <input type="tel" name="phone" placeholder="Numéro de téléphone" required
                                               class="w-full px-3 py-2 border rounded-lg mb-4">
                                        <select name="operateur" required class="w-full px-3 py-2 border rounded-lg mb-4">
                                            <option value="">Choisir opérateur</option>
                                            <option value="orange">Orange Money</option>
                                            <option value="mtn">MTN Money</option>
                                            <option value="moov">Moov Money</option>
                                        </select>
                                        <button type="submit"
                                                class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                            Payer {{ number_format($event->tarif, 2, ',', ' ') }} FCFA
                                        </button>
                                    </form>
                                </div>

                                <div class="flex justify-end mt-4">
                                    <button onclick="closePaymentModal()"
                                            class="text-sm text-gray-500 hover:underline">Fermer</button>
                                </div>
                            </div>
                        </div>

                        <script>
                            function switchTab(tab) {
                                document.querySelectorAll('[id^=tab-]').forEach(el => el.classList.add('hidden'));
                                document.getElementById('tab-' + tab).classList.remove('hidden');
                            }

                            function openPaymentModal() {
                                document.getElementById('paymentModal').classList.remove('hidden');
                            }
                            function closePaymentModal() {
                                document.getElementById('paymentModal').classList.add('hidden');
                            }

                            // Stripe
                            const stripe = Stripe("{{ config('services.stripe.key') }}");
                            const elements = stripe.elements();
                            const cardElement = elements.create('card');
                            cardElement.mount('#card-element');

                            document.getElementById('payment-stripe').addEventListener('submit', async (e) => {
                                e.preventDefault();

                                // Étape 1 : créer un PaymentIntent côté serveur
                                const res = await fetch("{{ route('events.create-payment-intent', $event->id) }}", {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                });

                                const { clientSecret, error } = await res.json();
                                if (error) {
                                    document.getElementById('card-errors').textContent = error;
                                    return;
                                }

                                // Étape 2 : confirmer le paiement avec la carte
                                const { error: confirmError } = await stripe.confirmCardPayment(clientSecret, {
                                    payment_method: {
                                        card: cardElement,
                                        billing_details: {
                                            name: "{{ auth()->user()->name ?? '' }}",
                                            email: "{{ auth()->user()->email ?? '' }}"
                                        }
                                    }
                                });

                                if (confirmError) {
                                    document.getElementById('card-errors').textContent = confirmError.message;
                                } else {
                                    // Étape 3 : inscription après paiement réussi
                                    const registerRes = await fetch("{{ route('events.register', $event->id) }}", {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        }
                                    });
                                    const data = await registerRes.json();
                                    if (data.success) {
                                        alert('✅ Paiement Stripe réussi !');
                                        location.reload();
                                    } else {
                                        alert('❌ ' + (data.message || 'Échec de l’inscription.'));
                                    }
                                }
                            });

                            // PayPal
                            paypal.Buttons({
                                createOrder: function(data, actions) {
                                    return actions.order.create({
                                        purchase_units: [{
                                            amount: {
                                                value: "{{ $event->tarif }}"
                                            }
                                        }]
                                    });
                                },
                                onApprove: function(data, actions) {
                                    return actions.order.capture().then(function(details) {
                                        fetch("{{ route('events.payer.paypal', $event->id) }}", {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            },
                                            body: JSON.stringify({ order_id: data.orderID })
                                        }).then(res => res.json()).then(data => {
                                            if (data.success) {
                                                alert('✅ Paiement PayPal réussi !');
                                                location.reload();
                                            } else {
                                                alert('❌ Échec du paiement PayPal');
                                            }
                                        });
                                    });
                                }
                            }).render('#paypal-button-container');

                            // Mobile Money (simulation)
                            document.getElementById('payment-mobile').addEventListener('submit', (e) => {
                                e.preventDefault();
                                alert('📱 Paiement Mobile Money simulé. En attente de confirmation...');
                                setTimeout(() => {
                                    fetch("{{ route('events.payer.mobile', $event->id) }}", {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({ operateur: e.target.operateur.value, phone: e.target.phone.value })
                                    }).then(res => res.json()).then(data => {
                                        if (data.success) {
                                            alert('✅ Paiement Mobile Money réussi !');
                                            location.reload();
                                        } else {
                                            alert('❌ Échec du paiement Mobile Money');
                                        }
                                    });
                                }, 2000);
                            });
                        </script>
                    @else
                        <form action="{{ route('events.register', $event->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                                S’inscrire
                            </button>
                        </form>
                    @endif
                @endif
            @else
                <a href="{{ route('login.form') }}"
                   class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    Connectez-vous pour vous inscrire
                </a>
            @endauth
        </div>
    </div>
@endsection
