@component('mail::message')
    <div class="row main-color title">
        <p>¡Felicitaciones! Tu oferta por {{ $nombreVehiculo }} ha sido la ganadora de la subasta.</p>
    </div>

    <div class="row">
        <p>Felicitaciones por tu oferta por {{ $nombreVehiculo }}</p>
    </div>

    <div class="row">
        <p>Tu oferta de {{ $moneda }} {{ $precio }} por el vehículo {{ $nombreVehiculo }} fué la ganadora. 
            A continuación te acercamos los datos del vendedor para que te puedas contactar. 
            No te olvides que tenés 7 días a partir de este momento para contactarte y concretar la compra.</p>
    </div>

    @component('datos-contacto', [
        'nombre'   => $vendedorNombre,
        'telefono' => $vendedorTelefono,
        'email'    => $vendedorEmail,
    ])
    @endcomponent

    <div class="button-row">
        @component('mail::button', ['url' => $urlPublicacion])
            Ver publicación
        @endcomponent
    </div>

@endcomponent
