<h1>Gestionar Clase</h1>

<form action="/admin/schedules/create" method="POST" class="formulario">
    <label for="modalidad">Modalidad</label>
    <select name="modalidad_id" id="modalidad" require>
        <option value="">Seleccione una Modalidad</option>
        <?php foreach($modalidades as $modalidad): ?>
            <option value="<?php echo $modalidad->id ?>"><?php echo $modalidad->nombre ?></option>
        <?php endforeach; ?>
    </select>
    <label for="hora">Hora</label>
    <input type="time" name="hora" id="hora">
    <label for="inicio">Día Inicio</label>
    <input type="date" name="diainicio" id="inicio">
    <label for="fin">Día Fin</label>
    <input type="date" name="diafin" id="fin">

</form>