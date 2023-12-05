@push('styles')
    <style>
        .social-media-icons-row {
            margin: 0 auto 4vh;
            text-align: center;
        }

        .social-media-icons-row a {
            text-decoration: none;
        }

        .social-media-icons-row .social-media-icon {
            display: inline-block;
            padding: 4px;
            width: 32px;
            height: 32px;
            border-radius: 4px;
        }

        .footer-text {
            color: #5a5a5a;
            font-size: 0.7rem;
            margin-bottom: 2vh;
            text-align: center;
        }
    </style>
@endpush

<div class="social-media-icons-row">
    <a href="{{\App\Modules\Parametros\Parametro::getById(\App\Modules\Parametros\Parametro::ID_LINKEDIN)->valor}}">
        <img src="{{url("images/linkedin-icon.png")}}" class="social-media-icon" alt="linkedin">
    </a>
    <a href="{{\App\Modules\Parametros\Parametro::getById(\App\Modules\Parametros\Parametro::ID_FACEBOOK)->valor}}">
        <img src="{{url("images/facebook-icon.png")}}" class="social-media-icon" alt="facebook">
    </a>
    <a href="{{\App\Modules\Parametros\Parametro::getById(\App\Modules\Parametros\Parametro::ID_INSTAGRAM)->valor}}">
        <img src="{{url("images/instagram-icon.png")}}" class="social-media-icon" alt="instagram">
    </a>
</div>

<div class="footer-text">
    © {{ date('Y') }} {{ config('app.name') }} - Deusados es un producto de Decreditos S.A. CUIT: 30-70787425-9
</div>
<div class="footer-text">
    Av. De Los Incas 5150, CABA, Buenos Aires 1427, Argentina, 0810-888-1111
</div>
<div class="footer-text">
    Antes de imprimir este correo, piense en el medio ambiente
</div>

