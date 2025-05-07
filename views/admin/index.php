<?php
use Model\Usuario;
use Model\Clase;
use Model\Inscripcion;
use Model\Horario;
use Model\Modalidad;
use Model\Encuesta;

$totalAlumnos = Usuario::count('tipo_usuario_id', 2);
$totalClases = Clase::count();
$totalHorarios = Horario::count();
$ultimasInscripciones = Inscripcion::getLatest(5);
?>

<div class="min-h-screen">
    <!-- Header ya definido en tu layout -->
    
    <div class="container mx-auto px-4 py-8 pt-0">
        <h1 class="text-3xl font-bold text-indigo-900 mb-8">Panel de Administración</h1>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">Total Alumnos</p>
                        <h3 class="text-2xl font-bold"><?php echo $totalAlumnos; ?></h3>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class='bx bxs-user text-blue-500 text-xl'></i>
                    </div>
                </div>
                <a href="/admin/usuarios" class="text-blue-500 text-sm mt-2 block hover:underline">Ver todos</a>
            </div>
            
            <!-- Tarjeta Clases -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">Total Clases</p>
                        <h3 class="text-2xl font-bold"><?php echo $totalClases; ?></h3>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class='bx bxs-dance text-green-500 text-xl'></i>
                    </div>
                </div>
                <a href="/admin/clases" class="text-green-500 text-sm mt-2 block hover:underline">Gestionar clases</a>
            </div>
            
            <!-- Tarjeta Horarios -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">Horarios Activos</p>
                        <h3 class="text-2xl font-bold"><?php echo $totalHorarios; ?></h3>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class='bx bxs-calendar text-yellow-500 text-xl'></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">Encuestas Activas</p>
                        <h3 class="text-2xl font-bold"><?php echo Encuesta::count(['activa' => 1]); ?></h3>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class='bx bxs-notepad text-purple-500 text-xl'></i>
                    </div>
                </div>
                <a href="/admin/encuestas" class="text-purple-500 text-sm mt-2 block hover:underline">Gestionar encuestas</a>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Últimas Inscripciones</h2>
                    <a href="/admin/inscripciones" class="text-indigo-500 text-sm hover:underline">Ver todas</a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Alumno</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Clase</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Fecha</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($ultimasInscripciones as $inscripcion): 
                                $alumno = Usuario::find($inscripcion->usuario_id);
                                $horario = Horario::find($inscripcion->horario_id);
                                $clase = $horario ? Clase::find($horario->clase_id) : null;
                            ?>
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4 text-sm">
                                    <?php echo htmlspecialchars($alumno->nombre . ' ' . $alumno->apellido); ?>
                                </td>
                                <td class="py-3 px-4 text-sm">
                                    <?php echo $clase ? htmlspecialchars($clase->nombre) : 'N/A'; ?>
                                </td>
                                <td class="py-3 px-4 text-sm">
                                    <?php echo date('d/m/Y', strtotime($inscripcion->fecha_inscripcion)); ?>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 rounded-full text-xs 
                                        <?php echo $inscripcion->estado == 'activa' ? 'bg-green-100 text-green-800' : 
                                              ($inscripcion->estado == 'completada' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'); ?>">
                                        <?php echo ucfirst($inscripcion->estado); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Próximas Clases -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Próximas Clases</h2>
                
                <?php 
                $proximasClases = Horario::getUpcomingClasses(3);
                if (!empty($proximasClases)): 
                ?>
                    <div class="space-y-4">
                        <?php foreach ($proximasClases as $horario): 
                            $clase = Clase::find($horario->clase_id);
                            $modalidad = Modalidad::find($horario->modalidad_id);
                        ?>
                        <div class="border-l-4 border-indigo-500 pl-4 py-2">
                            <h3 class="font-medium text-indigo-700"><?php echo htmlspecialchars($clase->nombre); ?></h3>
                            <p class="text-sm text-gray-600">
                                <i class='bx bxs-calendar'></i> 
                                <?php echo date('l d/m', strtotime($horario->fecha_inicio)); ?> 
                                a las <?php echo date('H:i', strtotime($horario->hora_inicio)); ?>
                            </p>
                            <p class="text-sm text-gray-500">
                                <?php echo htmlspecialchars($modalidad->nombre); ?> • 
                                <?php echo obtenerNombreDia($horario->dia_semana); ?>
                            </p>
                            <a href="/admin/horarios/editar?id=<?php echo $horario->id; ?>" class="text-indigo-500 text-xs hover:underline mt-1 inline-block">
                                Ver detalles
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <a href="/admin/horarios" class="text-indigo-500 text-sm mt-4 inline-block hover:underline">Ver todos los horarios</a>
                <?php else: ?>
                    <p class="text-gray-500">No hay clases programadas</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Gráfico de actividad (ejemplo) -->
        <div class="mt-8 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Actividad Reciente</h2>
            <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                <p class="text-gray-500">Gráfico de actividad se mostraría aquí</p>
            </div>
        </div>
    </div>
</div>

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