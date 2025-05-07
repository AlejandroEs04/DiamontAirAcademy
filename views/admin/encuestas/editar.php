<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Encuesta: <?php echo htmlspecialchars($encuesta->titulo); ?></h1>
        <div class="flex space-x-4">
            <a href="/admin/preguntas/crear?encuesta_id=<?php echo $encuesta->id; ?>" 
               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                Agregar Pregunta
            </a>
            <a href="/admin/encuestas" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                Volver
            </a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Detalles de la Encuesta</h2>
        <p class="text-gray-700 mb-2"><strong>Descripción:</strong> <?php echo htmlspecialchars($encuesta->descripcion); ?></p>
        <p class="text-gray-700">
            <strong>Estado:</strong> 
            <span class="px-2 py-1 rounded-full text-xs <?php echo $encuesta->activa ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                <?php echo $encuesta->activa ? 'Activa' : 'Inactiva'; ?>
            </span>
        </p>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <h2 class="text-xl font-semibold p-6 border-b">Preguntas</h2>
        
        <?php if(empty($preguntas)): ?>
            <p class="p-6 text-gray-500">No hay preguntas en esta encuesta.</p>
        <?php else: ?>
            <ul class="divide-y divide-gray-200">
                <?php foreach($preguntas as $pregunta): ?>
                <li class="p-6 hover:bg-gray-50">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-medium"><?php echo htmlspecialchars($pregunta->texto_pregunta ?? ''); ?></h3>
                            <p class="text-sm text-gray-500 mt-1">
                                Tipo: <?php echo ucfirst(str_replace('_', ' ', $pregunta->tipo_respuesta)); ?> |
                                Requerida: <?php echo $pregunta->requerida ? 'Sí' : 'No'; ?> |
                                Orden: <?php echo $pregunta->orden; ?>
                            </p>
                            
                            <?php if($pregunta->tipo_respuesta === 'opcion_multiple' && !empty($pregunta->opciones)): ?>
                                <div class="mt-2">
                                    <p class="text-sm font-medium text-gray-700">Opciones:</p>
                                    <ul class="list-disc list-inside text-sm text-gray-600">
                                        <?php foreach($pregunta->opciones as $opcion): ?>
                                            <li><?php echo htmlspecialchars($opcion->texto_opcion); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="flex space-x-2">
                            <a href="/admin/preguntas/editar?id=<?php echo $pregunta->id; ?>" 
                               class="text-blue-500 hover:text-blue-700 text-sm">
                                Editar
                            </a>
                            <?php if($pregunta->tipo_respuesta === 'opcion_multiple'): ?>
                                <a href="/admin/preguntas/opciones?pregunta_id=<?php echo $pregunta->id; ?>" 
                                   class="text-purple-500 hover:text-purple-700 text-sm">
                                    Opciones
                                </a>
                            <?php endif; ?>
                            <form action="/admin/preguntas/eliminar" method="POST" class="inline">
                                <input type="hidden" name="id" value="<?php echo $pregunta->id; ?>">
                                <button type="submit" onclick="return confirm('¿Eliminar esta pregunta?')" 
                                        class="text-red-500 hover:text-red-700 text-sm">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>