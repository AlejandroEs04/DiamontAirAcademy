<h1>Gestionar Clase</h1>

<form action="/admin/clases/<?php echo $clase->id ? 'edit' : 'create'; ?>" method="POST">
    <input type="hidden" name="id" value="<?php echo $clase->id ?? ''; ?>">
    
    <div class="form-group">
        <label for="name">Nombre</label>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($clase->nombre ?? ''); ?>" id="name" required>
    </div>
    
    <div class="form-group">
        <label for="description">Descripción</label>
        <textarea name="descripcion" id="description" required><?php echo htmlspecialchars($clase->descripcion ?? ''); ?></textarea>
    </div>
    
    <div class="form-group">
        <label for="level">Nivel</label>
        <select name="nivel" id="level" required>
            <option value="Principiante" <?php echo ($clase->nivel ?? '') === 'Principiante' ? 'selected' : ''; ?>>Principiante</option>
            <option value="Intermedio" <?php echo ($clase->nivel ?? '') === 'Intermedio' ? 'selected' : ''; ?>>Intermedio</option>
            <option value="Avanzado" <?php echo ($clase->nivel ?? '') === 'Avanzado' ? 'selected' : ''; ?>>Avanzado</option>
        </select>
    </div>
    
    <div class="form-group">
        <label for="duration">Duración (minutos)</label>
        <input type="number" name="duracion_minutos" value="<?php echo $clase->duracion_minutos ?? 60; ?>" id="duration" required min="1">
    </div>
    
    <div class="form-group">
        <label for="category">Categoría</label>
        <select name="categoria_id" id="category" required>
            <option value="">Seleccione una categoría</option>
            <?php foreach($categorias as $categoria): ?>
                <option value="<?php echo $categoria->id; ?>" <?php echo ($categoria->id === ($clase->categoria_id ?? '')) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($categoria->nombre); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <input type="submit" value="<?php echo $clase->id ? 'Actualizar Clase' : 'Crear Clase'; ?>" class="btn bg-blue-500 hover:bg-blue-600">
</form>

<h1>Horarios</h1>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Modalidad</th>
                <th>Día</th>
                <th>Hora Inicio</th>
                <th>Hora Fin</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($schedules as $schedule): ?>
                <tr>
                    <td><?php echo obtenerModalidad($schedule->modalidad_id); ?></td>
                    <td><?php echo obtenerNombreDia($schedule->dia_semana); ?></td>
                    <td><?php echo date('H:i', strtotime($schedule->hora_inicio)); ?></td>
                    <td><?php echo date('H:i', strtotime($schedule->hora_fin)); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($schedule->fecha_inicio)); ?></td>
                    <td><?php echo $schedule->fecha_fin ? date('d/m/Y', strtotime($schedule->fecha_fin)) : 'Indefinido'; ?></td>
                    <td>
                        <a href="/admin/schedules/edit?id=<?php echo $schedule->id ?>">Editar</a>
                        <a href="/admin/schedules/delete?id=<?php echo $schedule->id ?>">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<h2>Agregar Nuevo Horario</h2>
<form action="/admin/schedules/create" method="POST" class="formulario">
    <input type="hidden" name="clase_id" value="<?php echo $clase->id; ?>">
    
    <div class="form-group">
        <label for="modalidad">Modalidad</label>
        <select name="modalidad_id" id="modalidad" required>
            <option value="">Seleccione una Modalidad</option>
            <?php foreach($modalidades as $modalidad): ?>
                <option value="<?php echo $modalidad->id; ?>"><?php echo htmlspecialchars($modalidad->nombre); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label for="dia_semana">Día de la semana</label>
        <select name="dia_semana" id="dia_semana" required>
            <option value="">Seleccione un día</option>
            <option value="1">Lunes</option>
            <option value="2">Martes</option>
            <option value="3">Miércoles</option>
            <option value="4">Jueves</option>
            <option value="5">Viernes</option>
            <option value="6">Sábado</option>
            <option value="7">Domingo</option>
            <option value="8">Todos los dias</option>
        </select>
    </div>
    
    <div class="form-group">
        <label for="hora_inicio">Hora Inicio</label>
        <input type="time" name="hora_inicio" id="hora_inicio" required>
    </div>
    
    <div class="form-group">
        <label for="hora_fin">Hora Fin</label>
        <input type="time" name="hora_fin" id="hora_fin" required>
    </div>
    
    <div class="form-group">
        <label for="fecha_inicio">Fecha Inicio</label>
        <input type="date" name="fecha_inicio" id="fecha_inicio" required min="<?php echo date('Y-m-d'); ?>">
    </div>
    
    <div class="form-group">
        <label for="fecha_fin">Fecha Fin (opcional)</label>
        <input type="date" name="fecha_fin" id="fecha_fin">
    </div>
    
    <div class="form-group">
        <label for="capacidad">Capacidad Máxima</label>
        <input type="number" name="capacidad_maxima" id="capacidad" min="1" value="15">
    </div>
    
    <input type="submit" value="Guardar Horario" class="btn bg-indigo-500 hover:bg-indigo-600">
</form>

<?php 
// Función auxiliar para mostrar nombre del día
function obtenerNombreDia($numeroDia) {
    $dias = [
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado',
        7 => 'Domingo',
        7 => 'Todos los dias',
    ];
    return $dias[$numeroDia] ?? 'Desconocido';
}
function obtenerModalidad($numeroDia) {
    $dias = [
        1 => 'Lunes a Viernes',
        2 => 'Sabatinos'
    ];
    return $dias[$numeroDia] ?? 'Desconocido';
}
?>