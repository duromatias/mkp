@push('styles')
    <style>
        .title {
            text-align: center;
            font-weight: 500;
            font-size: 1.5rem;
            margin-bottom: 2rem;
        }


        .description {
            text-align: center;
        }

        .button-row {
            margin-top: 4vh;
            text-align: center;
        }
    </style>
@endpush

@component('mail::message')
    <h1 class="main-color title">
        Tu publicación está vencida
    </h1>

    <div class="main-color description">
        Tu publicación de
        <b> {{ \App\Modules\Shared\Utils\StringFormatter::capitalizeText($publicacion->marca) }} {{ \App\Modules\Shared\Utils\StringFormatter::capitalizeText($publicacion->modelo) }}! </b>
        está vencida y tenés que renovarla, si aun no lo vendiste!
        Si lo vendiste, recordá darlo de baja desde tu panel de administración.
    </div>

    <div class="button-row">
        @component('mail::button', ['url' => $publicacion->obtenerUrlSpa()])
            Ver Publicación
        @endcomponent
    </div>

@endcomponent
