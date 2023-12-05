<h3>Login</h3>
<?php if (request('mensaje')) { ?>
<b><?= request('mensaje') ?></b>
<?php } ?>
<form method="post" action="/login">
    <table>
        <tr>
            <td>Usuario</td>
            <td><input type="text" name="username" /></td>
        </tr>
        <tr>
            <td>Contraseña</td>
            <td><input type="password" name="password" /></td>
        </tr>
        <tr>
            <td></td>
            <td><button type="submit">Enviar</button></td>
        </tr>
    </table>
</form>