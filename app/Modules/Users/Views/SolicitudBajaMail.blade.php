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
        Solcitud de baja
    </h1>

    <div class="description">
        El usuario {{ $userEmail }} solicitó la baja
    </div>

    <div class="button-row">
        @component('mail::button', ['url' => App\Modules\Users\Models\User::getUsersSpaPage()])
            Deshabilitar usuario
        @endcomponent
    </div>
@endcomponent