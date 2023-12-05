@push('styles')
    <style>
        .title {
            text-align: center;
            font-weight: 500;
            font-size: 1.5rem;
        }

        .consulta-user-container {
            padding: 4vh 2vw;
        }
        .consulta-user-container .table {
            padding: 2vh 0;
            color: #5a5a5a;
        }

        .consulta-user-container .table b {
          display: inline-block;
          width: 60%;
        }

        .consulta-user-container .table span {
          white-space: nowrap;
        }

        .consulta-user-container .table .line {
            margin: 0.5vh 0;
            width: 100%;
            border-bottom: 2px solid #5a5a5a;
        }
    </style>
@endpush
<div class="consulta-user-container">
    <em class="main-color">Te dejamos los datos para que puedas contactarte</em>

    <div class="table">
        <div>
            <b>Nombre y Apellido</b>
            <span>{{ $nombre }}</span>
        </div>
        <div class="line"></div>
        <div>
            <b>Teléfono</b>
            <span>{{ $telefono }}</span>
        </div>
        <div class="line"></div>
        <div>
            <b>Email</b>
            <span>{{ $email }}</span>
        </div>
    </div>
</div>