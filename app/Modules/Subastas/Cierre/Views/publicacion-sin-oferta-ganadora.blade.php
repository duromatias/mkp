@component('mail::message')
    <div class="row main-color title">
        <p>Oh, no! Tu publicación no alcanzó su objetivo. ¿Tuviste algún inconveniente?</p>
    </div>

    <div class="row">
        <p>Tu publicación de {{ $publicacion->obtenerNombreVehiculo() }} finalizó sin llegar a su objetivo, si queres volver a participar en la próxima subasta, te acercamos unos tips que podrían ayudarte a potenciar tus publicaciones.</p>
        <p>1- Fotos claras: Recordá sacarle al vehículo fotos nítidas, y que muestren todos los detalles. Mientras más fotos, más atractiva va a ser la publicación.</p>
        <p>2- Precio estratégico: Tu publicación va a ser ofrecida entre agencias como la tuya, debería tener un valor competitivo, ya que no va a ser destinado a un consumidor final.</p>
        <p>3- Detalle completo: Si tu vehículo tiene algún detalle que quieras informarle al potencial comprador, es importante informalo en la publicación, mientras más información contenga tu publicación, más visible será para otros compradores. Recordá que un aviso bien completo tiene mas chances de recibir ofertas</p>
    </div>

    <div class="button-row">
        @component('mail::button', ['url' => $urlPublicacion])
            Ver publicación
        @endcomponent
    </div>

@endcomponent
