@component('mail::message')
    <div class="row main-color title">
        <p>{{ $publicacion->ultimaOferta->usuario->obtenerNombreVendedor() }} hizo una oferta superadora de {{ $publicacion->moneda }} {{ $publicacion->ultimaOferta->precio_ofertado }}</p>
        <p>Tu {{ $publicacion->obtenerNombreVehiculo() }}, podría tener su comprador perfecto...</p>
    </div>

    <div class="row">
        <p>¡Felicitaciones! Tu {{ $publicacion->obtenerNombreVehiculo() }} recibió una oferta ganadora. A continuación te acercamos los datos del interesado.</p>
        <p>No olvides que tenes 7 días a partir de este momento para contactarte y concretar la venta!</p>
    </div>

    @component('datos-contacto', [
        'nombre'   => $publicacion->ultimaOferta->usuario->obtenerNombreVendedor(),
        'telefono' => $publicacion->ultimaOferta->usuario->obtenerTelefonoContacto(),
        'email'    => $publicacion->ultimaOferta->usuario->email,
    ])
    @endcomponent

    <div class="button-row">
        @component('mail::button', ['url' => $urlPublicacion])
            Ver publicación
        @endcomponent
    </div>

@endcomponent
