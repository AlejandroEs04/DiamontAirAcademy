<?php
    use Model\Usuario;
    use Model\Clase;
    use Model\Modalidad;
?>

<h1>Administrar Clases</h1>

<a class="btn bg-indigo-500 hover:bg-indigo-600 text-white py-2 px-4 rounded transition-colors mb-6" 
   href="/admin/usuarios/create">
    Registrar Nueva Clase
</a>

<!-- Tabla de Clases -->
<div class="bg-white rounded-lg shadow overflow-hidden mb-8 mt-1">
    <table class="min-w-full ">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left">ID</th>
                <th class="px-4 py-2 text-left">Nombre</th>
                <th class="px-4 py-2 text-left">Descripción</th>
                <th class="px-4 py-2 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($clases as $clase): ?>
                <tr class="border-t">
                    <td class="px-4 py-2"><?php echo $clase->id ?></td>
                    <td class="px-4 py-2 font-medium"><?php echo $clase->nombre ?></td>
                    <td class="px-4 py-2"><?php echo $clase->descripcion ?></td>
                    <td class="px-4 py-2">
                        <a href="/admin/clases?id=<?php echo $clase->id ?>" 
                           class="text-indigo-500 hover:text-indigo-700 mr-3">Ver</a>
                        <a href="/admin/clases/edit?id=<?php echo $clase->id ?>" 
                           class="text-indigo-500 hover:text-indigo-700 mr-3">Editar</a>
                        <a href="/admin/clases/delete?id=<?php echo $clase->id ?>" 
                           class="text-red-500 hover:text-red-700">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if(!is_null($schedules)): ?>
<!-- Sección de Horarios -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- Tabla de Horarios -->
    <div>
        <h1>Horarios</h1>
        <table class="min-w-full bg-white">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Modalidad</th>
                    <th class="px-4 py-2 text-left">Día</th>
                    <th class="px-4 py-2 text-left">Horario</th>
                    <th class="px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($schedules as $schedule): ?>
                    <tr class="border-t">
                        <td class="px-4 py-2">
                            <?php 
                                $modalidad = Modalidad::find($schedule->modalidad_id);
                                echo $modalidad ? $modalidad->nombre : 'Desconocido';
                            ?>
                        </td>
                        <td class="px-4 py-2">
                            <?php 
                                $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                                echo $dias[$schedule->dia_semana - 1] ?? 'Desconocido';
                            ?>
                        </td>
                        <td class="px-4 py-2">
                            <?php echo date('H:i', strtotime($schedule->hora_inicio)) . ' - ' . date('H:i', strtotime($schedule->hora_fin)); ?>
                        </td>
                        <td class="px-4 py-2">
                            <a href="/admin/schedules/edit?id=<?php echo $schedule->id ?>" 
                               class="text-indigo-500 hover:text-indigo-700 mr-3">Editar</a>
                            <a href="/admin/schedules/delete?id=<?php echo $schedule->id ?>" 
                               class="text-red-500 hover:text-red-700">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Formulario para agregar/editar horario -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4"><?php echo isset($scheduleEdit) ? 'Editar Horario' : 'Agregar Horario'; ?></h2>
        
        <form action="/admin/schedules/<?php echo isset($scheduleEdit) ? 'update' : 'create'; ?>" method="POST">
            <input type="hidden" name="clase_id" value="<?php echo $_GET["id"] ?? ''; ?>">
            
            <?php if(isset($scheduleEdit)): ?>
                <input type="hidden" name="id" value="<?php echo $scheduleEdit->id; ?>">
            <?php endif; ?>
            
            <div class="mb-4">
                <label class="block mb-2">Modalidad</label>
                <select name="modalidad_id" class="w-full p-2 border rounded" required>
                    <option value="">Seleccione modalidad</option>
                    <?php foreach(Modalidad::all() as $modalidad): ?>
                        <option value="<?php echo $modalidad->id; ?>"
                            <?php echo (isset($scheduleEdit) && $scheduleEdit->modalidad_id == $modalidad->id) ? 'selected' : '' ?>>
                            <?php echo $modalidad->nombre; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block mb-2">Día de la semana</label>
                <select name="dia_semana" class="w-full p-2 border rounded" required>
                    <option value="">Seleccione día</option>
                    <option value="1" <?= (isset($scheduleEdit) && $scheduleEdit->dia_semana == 1) ? 'selected' : '' ?>>Lunes</option>
                    <option value="2" <?= (isset($scheduleEdit) && $scheduleEdit->dia_semana == 2) ? 'selected' : '' ?>>Martes</option>
                    <option value="3" <?= (isset($scheduleEdit) && $scheduleEdit->dia_semana == 3) ? 'selected' : '' ?>>Miércoles</option>
                    <option value="4" <?= (isset($scheduleEdit) && $scheduleEdit->dia_semana == 4) ? 'selected' : '' ?>>Jueves</option>
                    <option value="5" <?= (isset($scheduleEdit) && $scheduleEdit->dia_semana == 5) ? 'selected' : '' ?>>Viernes</option>
                    <option value="6" <?= (isset($scheduleEdit) && $scheduleEdit->dia_semana == 6) ? 'selected' : '' ?>>Sábado</option>
                    <option value="7" <?= (isset($scheduleEdit) && $scheduleEdit->dia_semana == 7) ? 'selected' : '' ?>>Domingo</option>
                </select>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block mb-2">Hora Inicio</label>
                    <input type="time" name="hora_inicio" 
                           value="<?php echo isset($scheduleEdit) ? date('H:i', strtotime($scheduleEdit->hora_inicio)) : ''; ?>" 
                           class="w-full p-2 border rounded" required>
                </div>
                <div>
                    <label class="block mb-2">Hora Fin</label>
                    <input type="time" name="hora_fin" 
                           value="<?php echo isset($scheduleEdit) ? date('H:i', strtotime($scheduleEdit->hora_fin)) : ''; ?>" 
                           class="w-full p-2 border rounded" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 mb-2" for="fecha_inicio">Fecha Inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" 
                           value="<?php echo isset($scheduleEdit) ? $scheduleEdit->fecha_inicio : ''; ?>" 
                           class="w-full p-2 border rounded focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 mb-2" for="fecha_fin">Fecha Fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" 
                           value="<?php echo isset($scheduleEdit) ? $scheduleEdit->fecha_fin : ''; ?>" 
                           class="w-full p-2 border rounded focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>
            
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white py-2 px-4 rounded">
                <?php echo isset($scheduleEdit) ? 'Actualizar' : 'Guardar'; ?>
            </button>
        </form>
    </div>
</div>
<?php endif; ?>