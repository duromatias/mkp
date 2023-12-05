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
    <h1 class="main-color title">
        Ofertá para sumar vehículos a tu agencia
    </h1>

    <div class="description">
        Hola {{ $name }}, ¿Cómo estás?
    </div>

    <div class="description">
        Esta es tu oportunidad para sumar vehículos en tu agencia. Ofertá eligiendo el auto que vos quieras.
    </div>


        <div class="description">
        <span>
            Las mejores oportunidades las podes encontrar en
        </span>
        <a href="{{ config('app.spa_url') }}">
            <img src="{{url('images/logo-small.png')}}" class="inline-logo" alt="logo deusados"/>
        </a>
    </div>

    <div class="description">
        Ante cualquier consulta contestá este mail.
    </div>

    <div class="button-row">
        @component('mail::button', ['url' => \App\Modules\Subastas\Subasta::getSubastasSpaHomePage()])
            Ver subastas
        @endcomponent
    </div>

@endcomponent
