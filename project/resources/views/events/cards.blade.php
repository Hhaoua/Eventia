@forelse($events as $event)
    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 cursor-pointer overflow-hidden group border border-gray-100 hover:border-blue-200">
        <div class="relative overflow-hidden">
            <img src="{{ $event->image_url }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300" alt="{{ $event->titre }}">
            @if($event->featured)
                <div class="absolute top-3 left-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white px-3 py-1 rounded-full text-sm font-medium flex items-center space-x-1">
                    ⭐<span>En vedette</span>
                </div>
            @endif
            <div class="absolute top-3 right-3 px-3 py-1 rounded-full text-sm font-medium {{ $event->tarif > 0 ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                {{ $event->tarif > 0 ? '€'.$event->tarif : 'Gratuit' }}
            </div>
        </div>
        <div class="p-5 space-y-4">
            <div class="flex items-center justify-between">
                <span class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm font-medium">{{ $event->type }}</span>
                <span class="px-2 py-1 rounded-full text-xs font-medium {{ ($event->places_max - $event->inscrits_count) > 5 ? 'text-green-600 bg-green-50' : 'text-orange-600 bg-orange-50' }}">
                    {{ $event->places_max - $event->inscrits_count }} places restantes
                </span>
            </div>
            <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors duration-200 line-clamp-2">
                {{ $event->titre }}
            </h3>
            <p class="text-gray-600 text-sm leading-relaxed line-clamp-2">{{ $event->shortDescription }}</p>
            <div class="space-y-2 text-sm text-gray-600">
                <div class="flex items-center space-x-2">📅 {{ $event->date->format('d M Y H:i') }}</div>
                <div class="flex items-center space-x-2">📍 {{ $event->lieu }}</div>
                <div class="flex items-center space-x-2">👥 {{ $event->inscrits_count }}/{{ $event->places_max }} participants</div>
            </div>
            <div class="pt-3 border-t border-gray-100">
                <p class="text-xs text-gray-500">Organisé par <span class="font-medium text-gray-700">{{ $event->organisateur->name ?? 'Inconnu' }}</span></p>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-1.5">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-1.5 rounded-full" style="width: {{ $event->places_max > 0 ? ($event->inscrits_count / $event->places_max) * 100 : 0 }}%;"></div>
            </div>
        </div>
    </div>
@empty
    <div class="col-span-full text-center text-gray-500">
        Aucun événement ne correspond à vos critères.
    </div>
@endforelse