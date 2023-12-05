@component('mail::message')
    <div class="row main-color title">
        <p>Tu oferta fue superada...</p>
    </div>

    <div class="row">
        <p>Tu oferta para {{ $nombreVehiculo }} fue superada por otro comprador. </p>
    </div>

    <div class="row">
        <p>Te invitamos a seguir navegando en nuestra web para encontrar vehículos similares.</p>
    </div>

    <div class="button-row">
        @component('mail::button', ['url' => $urlPublicacion])
            Ver publicación
        @endcomponent
    </div>

@endcomponent
