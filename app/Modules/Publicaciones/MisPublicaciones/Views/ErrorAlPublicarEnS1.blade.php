@component('mail::message')
    <div class="main-color title">
        {{ $mensaje }}
    </div>

    <div class="button-row">
        @component('mail::button', ['url' => $publicacion->obtenerUrlSpa()])
            Ver Publicación
        @endcomponent
    </div>

@endcomponent
