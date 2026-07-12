<div>
    <!-- The only way to do great work is to love what you do. - Steve Jobs -->
</div>

{{--
    Partial: Mapa de localização na página pública
    Requerido: $hotel->latitude, $hotel->longitude
--}}
@if($hotel->latitude && $hotel->longitude)
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3">
            <i class="bi bi-geo-alt me-2"></i>Localização
        </h5>
        <div id="hotelMapShow" class="rounded-3" style="height:280px;"></div>
        <p class="small text-muted mt-2 mb-0">
            <i class="bi bi-pin-map me-1"></i>
            {{ $hotel->address }}{{ $hotel->neighborhood ? ', ' . $hotel->neighborhood : '' }}, {{ $hotel->city }}
        </p>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const hotelLat = @json($hotel->latitude);
    const hotelLng = @json($hotel->longitude);
    const hotelName = @json($hotel->name);

    const map = L.map('hotelMapShow').setView([hotelLat, hotelLng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org">OpenStreetMap</a>'
    }).addTo(map);

    // Ícone personalizado
    const icon = L.divIcon({
        html: `<div style="
            background:#1a5276;
            color:white;
            border-radius:50% 50% 50% 0;
            transform:rotate(-45deg);
            width:36px;height:36px;
            display:flex;align-items:center;justify-content:center;
            box-shadow:0 2px 8px rgba(0,0,0,.3);
            border:2px solid white;
        ">
            <i class="bi bi-building" style="transform:rotate(45deg);font-size:14px;"></i>
        </div>`,
        className: '',
        iconSize: [36, 36],
        iconAnchor: [18, 36],
        popupAnchor: [0, -36],
    });

    L.marker([hotelLat, hotelLng], { icon })
        .addTo(map)
        .bindPopup(`<strong>${hotelName}</strong>`)
        .openPopup();
</script>
@endpush
@endif