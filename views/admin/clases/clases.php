<h1>Administrar clases</h1>

<a class="btn bg-indigo-500 hover:bg-indigo-600 transition-colors" href="/admin/usuarios/create">Registrar Clase</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Description</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach($clases as $clase): ?>
            <tr>
                <td><?php echo $clase->id ?></td>
                <td><?php echo $clase->nombre ?></td>
                <td><?php echo $clase->descripcion ?></td>
                <td>
                    <a href="/admin/clases?id=<?php echo $clase->id ?>">Ver</a>
                    <a href="/admin/clases/edit?id=<?php echo $clase->id ?>">Editar</a>
                    <a href="/admin/clases/delete?id=<?php echo $clase->id ?>">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if(!is_null($schedules)): ?>
    <table>
        <thead>
            <tr>
                <th>Modalidad</th>
                <th>Día Semana</th>
                <th>Hora Inicio</th>
                <th>Hora Fin</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($schedules as $schedule): ?>
                <tr>
                    <td><?php echo $schedule->modalidad_id; ?></td>
                    <td><?php echo $schedule->dia_semana; ?></td>
                    <td><?php echo $schedule->hora_inicio; ?></td>
                    <td><?php echo $schedule->hora_fin; ?></td>
                    <td><?php echo $schedule->fecha_inicio; ?></td>
                    <td><?php echo $schedule->fecha_fin; ?></td>
                    <td>
                        <a href="/admin/schedules/edit?id=<?php echo $clase->id ?>">Editar</a>
                        <a href="/admin/schedules/delete?id=<?php echo $clase->id ?>">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>