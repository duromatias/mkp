@push('styles')
    <style>
			.title {
				text-align: center;
				font-weight: 500;
				font-size: 1.5rem;
				margin-bottom: 2rem;
			}

            .preheader {
                display: none;
                visibility: hidden;
                opacity:0;
                color:transparent;
                height:0;
                width:0;
            }

			.description {
				margin-bottom: 1rem;
			}

			.button-row {
				margin-top: 4vh;
				text-align: center;
			}
    </style>
@endpush

@component('mail::message')
    @slot('preheader')
        Tenés tiempo hasta el {{ date('d/m/Y', strtotime($subasta->fecha_fin_inscripcion)) }} para inscribir tus autos a la Subasta.
    @endslot

    <h1 class="main-color title">
        Comienza una nueva Subasta
    </h1>

    <div class="description">
        Hola {{ $name }}, ¿Cómo estás? Te queremos recordar que tenés tiempo hasta el {{ date('d/m/Y', strtotime($subasta->fecha_fin_inscripcion)) }} para inscribir tus autos a la Subasta.
    </div>

    <div class="description">
        Recordá que es una buena oportunidad para potenciar tu agencia y poder vender tus vehículos de una manera más fácil y rápida.
    </div>

    <div class="button-row">
        @component('mail::button', ['url' => \App\Modules\Publicaciones\Publicacion::misPublicacionesSpaPage()])
            Inscribir vehículos
        @endcomponent
    </div>

@endcomponent
