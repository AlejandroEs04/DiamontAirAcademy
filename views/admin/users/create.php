<form method="POST" class="formulario" action="/admin/usuarios/create">
    <label for="name">Nombre</label>
    <input type="text" name="usuario[nombre]" id="name">
    <label for="lastName">Apellido</label>
    <input type="text" name="usuario[apellido]" id="lastName">
    <label for="email">Correo</label>
    <input type="email" name="usuario[email]" id="email">
    <label for="password">Contraseña</label>
    <input type="password" name="usuario[contrasena]" id="password">
    <label for="userType">Tipo</label>
    <select name="usuario[tipo_usuario_id]" id="userType">
        <option value="1">Administrador</option>
        <option value="2" selected>Alumno</option>
    </select>

    <input type="submit" value="Guardar Usuario" class="btn bg-indigo-500 hover:bg-indigo-600">
</form>