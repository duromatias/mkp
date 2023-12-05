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

    <div class="description">
        No se ha podido procesar la financiación en Deusados debido a la falta de información o la operación no pudo generarse en Web Agencias.
        El problema se presentó para el usuario {{ $email }} en la publicación número {{ $publicacion_id }} de la agencia {{ $agencia }}.
    </div>

    <div class="button-row">
        @component('mail::button', ['url' => $publicacion_url])
            Ver Publicación
        @endcomponent
    </div>

@endcomponent
