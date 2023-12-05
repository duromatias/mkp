@push('styles')
    <style>
        .header {
            background: linear-gradient(180deg, #f8e5e4 0%, #f8e5e4 25%, #b6efe5);
        }

        .header .logo {
            display: block;
            position: relative;
            margin: 0 auto;
            width: 70%;
            max-width: 15rem;
        }
    </style>
@endpush

<div class="header">
    <a href="{{ $url }}">
        <img src="{{url('images/logo-horizontal.png')}}" class="logo" alt="logo"/>
    </a>
</div>
