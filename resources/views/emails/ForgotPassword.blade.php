@push('styles')
    <style>
        .contenedor-boton {
            text-align: center;
        }

        .texto-inferior{
            margin-top: 35px;
            font-size: 14px;
            color: #5c5353;
        }
    </style>
@endpush

@component('mail::message')
<p>
    Recibiste este email porque recibimos una petición para restablecer la contraseña de tu cuenta.
</p>

<div class="contenedor-boton">
    @component('mail::button', ['url' => $url])
        Restablecer Contraseña
    @endcomponent
</div>

<p class="texto-inferior">
    Este link expirará en 60 minutos.

    Si no requeriste el cambio de tu contraseña, no necesitas hacer nada.
    De no poder ver correctamente este mensaje, haga click en este enlace:
    <a href="{{$url}}">Link</a>
</p>

@endcomponent
