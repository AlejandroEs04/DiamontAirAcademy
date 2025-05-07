<h1>Administrar Horarios</h1>

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
        <?php foreach($schedules as $schedule): ?>
            <tr>
                <td><?php echo $schedule->id ?></td>
                <td><?php echo $schedule->hora ?></td>
                <td><?php echo $schedule->diainicio ?></td>
                <td><?php echo $schedule->diafin ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>