@push('styles')
    <style>
        .title {
            text-align: center;
            font-weight: 500;
            font-size: 1.5rem;
            margin-bottom: 2rem;
        }

        .description {
            margin-bottom: 1rem;
        }

        .description .inline-logo {
            padding-left: 4px;
            height: 1.1rem;
            width: auto;
        }

        .button-row {
            margin-top: 4vh;
            text-align: center;
        }
    </style>
@endpush

@component('mail::message')

    <div class="description" tyle='color: #222;'>
        Deusados no se pudieron obtener los datos de monto máximo a financiar o montos y cantidades de cuotas para realizar la solicitud de un préstamo.
        <br>
        El problema se presenta en la publicación número {{ $publicacion_id }} de la agencia {{ $agencia }}
    </div>

    <div class="button-row">
        @component('mail::button', ['url' => $publicacion_url])
            Ver Publicación
        @endcomponent
    </div>

@endcomponent
