<div>
    <!-- The whole future lies in uncertainty: live immediately. - Seneca -->
</div>


{{--
    Partial: Map picker
    Requerido: $hotel->latitude, $hotel->longitude (podem ser null)
    Leaflet.js deve ser carregado na página que inclui este partial
--}}
<div class="col-12">
    <label class="form-label small fw-semibold">
        Localização no mapa
        <span class="text-muted fw-normal">— clique no mapa para definir a posição</span>
    </label>

    <div id="hotelMapPicker" class="rounded-3 border mb-2" style="height:280px;"></div>

    <div class="row g-2">
        <div class="col-md-6">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light">Latitude</span>
                <input type="number" name="latitude" id="latInput"
                       class="form-control"
                       value="{{ old('latitude', $hotel->latitude ?? '') }}"
                       step="0.0000001" placeholder="-12.5700000">
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light">Longitude</span>
                <input type="number" name="longitude" id="lngInput"
                       class="form-control"
                       value="{{ old('longitude', $hotel->longitude ?? '') }}"
                       step="0.0000001" placeholder="13.4000000">
            </div>
        </div>
    </div>
    <div class="form-text">
        <i class="bi bi-info-circle me-1"></i>
        Clique no mapa para posicionar o hotel. Centro de Benguela: -12.5700, 13.4000
    </div>
</div>