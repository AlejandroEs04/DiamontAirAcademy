<?php
    use Model\Usuario;
?>

<h1>Gestionar Horario</h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- Sección para editar horario -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4">Detalles del Horario</h2>
        
        <form action="/admin/horarios/actualizar" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($schedule->id ?? ''); ?>">
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="dia_semana">Día de la semana</label>
                <select name="dia_semana" id="dia_semana" class="w-full p-2 border rounded" required>
                    <option value="">Seleccione un día</option>
                    <option value="1" <?= $schedule->dia_semana == 1 ? 'selected' : '' ?>>Lunes</option>
                    <option value="2" <?= $schedule->dia_semana == 2 ? 'selected' : '' ?>>Martes</option>
                    <option value="3" <?= $schedule->dia_semana == 3 ? 'selected' : '' ?>>Miércoles</option>
                    <option value="4" <?= $schedule->dia_semana == 4 ? 'selected' : '' ?>>Jueves</option>
                    <option value="5" <?= $schedule->dia_semana == 5 ? 'selected' : '' ?>>Viernes</option>
                    <option value="6" <?= $schedule->dia_semana == 6 ? 'selected' : '' ?>>Sábado</option>
                    <option value="7" <?= $schedule->dia_semana == 7 ? 'selected' : '' ?>>Domingo</option>
                    <option value="8" <?= $schedule->dia_semana == 8 ? 'selected' : '' ?>>Todos los dias</option>
                </select>
            </div>
            
            <div class="mb-4">
                    <label class="block text-gray-700 mb-2" for="hora_inicio">Hora Inicio</label>
                    <input type="time" name="hora_inicio" id="hora_inicio" 
                        value="<?php echo isset($schedule->hora_inicio) ? htmlspecialchars(date('H:i', strtotime($schedule->hora_inicio))) : '00:00'; ?>" 
                        class="w-full p-2 border rounded" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="hora_fin">Hora Fin</label>
                <input type="time" name="hora_fin" id="hora_fin" 
                    value="<?php echo isset($schedule->hora_fin) ? htmlspecialchars(date('H:i', strtotime($schedule->hora_fin))) : '00:00'; ?>" 
                    class="w-full p-2 border rounded" required>
            </div>
            
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white py-2 px-4 rounded">
                Actualizar Horario
            </button>
        </form>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4">Inscripciones (<?php echo count($inscripciones); ?>)</h2>
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <form action="/admin/inscripciones/crear" method="POST">
                <input type="hidden" name="horario_id" value="<?php echo htmlspecialchars($schedule->id ?? ''); ?>">
                
                <div class="mb-3">
                    <label class="block text-gray-700 mb-1">Alumno</label>
                    <select name="usuario_id" class="w-full p-2 border rounded" required>
                        <option value="">Seleccione un alumno</option>
                        <?php 
                            $alumnos = Usuario::whereMany('tipo_usuario_id', 2); 
                            foreach ($alumnos as $alumno): 
                        ?>
                            <option value="<?php echo $alumno->id; ?>">
                                <?php echo $alumno->nombre . ' ' . $alumno->apellido; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="block text-gray-700 mb-1">Estado</label>
                    <select name="estado" class="w-full p-2 border rounded" required>
                        <option value="activa" selected>Activa</option>
                        <option value="completada">Completada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>
                
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded text-sm">
                    Agregar Inscripción
                </button>
            </form>
        </div>

        <?php if (!empty($inscripciones)) : ?>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="py-2 px-4 border">Alumno</th>
                            <th class="py-2 px-4 border">Estado</th>
                            <th class="py-2 px-4 border">Fecha</th>
                            <th class="py-2 px-4 border">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inscripciones as $inscripcion) : 
                            $alumno = Usuario::find($inscripcion->usuario_id);
                        ?>
                            <tr>
                                <td class="py-2 px-4 border">
                                    <?php echo htmlspecialchars($alumno->nombre . ' ' . $alumno->apellido); ?>
                                </td>
                                <td class="py-2 px-4 border">
                                    <span class="px-2 py-1 rounded-full text-xs 
                                        <?php echo $inscripcion->estado == 'activa' ? 'bg-green-100 text-green-800' : 
                                              ($inscripcion->estado == 'completada' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'); ?>">
                                        <?php echo ucfirst($inscripcion->estado); ?>
                                    </span>
                                </td>
                                <td class="py-2 px-4 border">
                                    <?php echo date('d/m/Y', strtotime($inscripcion->fecha_inscripcion)); ?>
                                </td>
                                <td class="py-2 px-4 border">
                                    <form action="/admin/inscripciones/actualizar" method="POST" class="inline">
                                        <input type="hidden" name="id" value="<?php echo $inscripcion->id; ?>">
                                        <select name="estado" onchange="this.form.submit()" 
                                                class="text-sm p-1 border rounded">
                                            <option value="activa" <?= $inscripcion->estado == 'activa' ? 'selected' : '' ?>>Activa</option>
                                            <option value="completada" <?= $inscripcion->estado == 'completada' ? 'selected' : '' ?>>Completada</option>
                                            <option value="cancelada" <?= $inscripcion->estado == 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                                        </select>
                                    </form>
                                    
                                    <form action="/admin/inscripciones/eliminar" method="POST" class="inline">
                                        <input type="hidden" name="id" value="<?php echo $inscripcion->id; ?>">
                                        <button type="submit" onclick="return confirm('¿Eliminar esta inscripción?')" 
                                                class="text-red-500 hover:text-red-700 p-0 m-0">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <p class="text-gray-500">No hay inscripciones para este horario.</p>
        <?php endif; ?>
    </div>
</div>