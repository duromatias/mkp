@push('styles')
    <style>
        .primary-button {
            border: none;
            padding: 12px 23px;
            border-radius: 4px;
            background-color: #0a3066;
            color: white;
            font-size: 1rem;
            font-weight: bold;
        }
    </style>
@endpush

<a href="{{ $url }}" target="_blank">
    <button class="primary-button">{{ $slot }}</button>
</a>
