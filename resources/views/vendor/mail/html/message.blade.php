@push('styles')
    <style>
        .body {
            padding: 5vh 5vw;
        }
        .title {
            text-align: center;
        }

        .button-row {
            margin-top: 4vh;
            text-align: center;
        }
    </style>
@endpush

@component('mail::layout')
    @slot('preheader')
        {{ $preheader ?? '' }}
    @endslot

    @slot('header')
        @component('mail::header', ['url' => config('app.spa_url')])
        @endcomponent
    @endslot

    <div class="body">
        {{ $slot }}
    </div>

    @slot('footer')
        @component('mail::footer')
        @endcomponent
    @endslot
@endcomponent
