<h1>Usuarios</h1>

<a class="btn bg-indigo-500 hover:bg-indigo-600 transition-colors" href="/admin/usuarios/create">Registrar Usuario</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Correo</th>
            <th>Tipo</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach($usuarios as $usuario): ?>
            <tr>
                <td><?php echo $usuario->id ?></td>
                <td><?php echo $usuario->nombre ?></td>
                <td><?php echo $usuario->apellido ?></td>
                <td><?php echo $usuario->email ?></td>
                <td><?php if($usuario->tipo_usuario_id == 1){ echo "Administrador"; } else { echo "Estudiante"; } ?></td>
                <td>
                    <a href="/admin/usuarios/edit?id=<?php echo $usuario->id ?>">Editar</a>
                    <a href="/admin/usuarios/delete?id=<?php echo $usuario->id ?>">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>