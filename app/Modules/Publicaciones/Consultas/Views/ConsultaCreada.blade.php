@push('styles')
    <style>
        .title {
            text-align: center;
            font-weight: 500;
            font-size: 1.5rem;
            margin-bottom: 2rem;
        }

        .consulta-user-container {
            padding: 1rem 2rem;
        }
        .consulta-user-container .table {
            padding: 2vh 0;
            color: #5a5a5a;
        }

        .consulta-user-container .table b {
          display: inline-block;
          min-width: 60%;
          white-space: nowrap;
        }

        .consulta-user-container .table span {
          min-width: 40%;
          white-space: nowrap;
        }

        .consulta-user-container .table .line {
            margin: 0.5vh 0;
            width: 100%;
            border-bottom: 2px solid #5a5a5a;
        }

        .consulta-mensaje-container {
            padding: 1vw 1vw;
            background-color: #b6efe5;
            font-size: 1.2rem;
        }

        .button-row {
            margin-top: 4vh;
            text-align: center;
        }
    </style>
@endpush


@component('mail::message')
    <h1 class="main-color title">
        ¡Hay un interesado en tu
            <b>
                {{ \App\Modules\Shared\Utils\StringFormatter::capitalizeText($publicacion->marca) }} {{ \App\Modules\Shared\Utils\StringFormatter::capitalizeText($publicacion->modelo) }}!
            </b>
    </h1>

    <div class="consulta-user-container">
        <em class="main-color">Te dejamos los datos para que puedas contactarte</em>

        <div class="table">
            <div>
                <b>Nombre y Apellido</b>
                <span>{{ $consulta->nombre }}</span>
            </div>
            <div class="line"></div>
            <div>
                <b>Teléfono</b>
                <span>{{ $consulta->telefono }}</span>
            </div>
            <div class="line"></div>
            <div>
                <b>Email</b>
                <span>{{ $consulta->email }}</span>
            </div>
        </div>
    </div>

    <div class="consulta-mensaje-container">
        <b class="main-color">Consulta:</b>
        <em class="main-color">"{{ $consulta->texto }}"</em>
    </div>

    <div class="button-row">
        @component('mail::button', ['url' => $publicacion->obtenerUrlSpa()])
            Ver Publicación
        @endcomponent
    </div>

@endcomponent
