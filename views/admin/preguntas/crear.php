<div class="container mx-auto p-0">
    <div class="flex flex-col mb-1">
        <a href="/admin/encuestas/editar?id=<?php echo $pregunta->encuesta_id; ?>" 
           class="text-indigo-500 hover:text-indigo-700 mr-4">
            <i class='bx bx-arrow-back'></i> Volver
        </a>
        <h1>Agregar Nueva Pregunta</h1>
    </div>

    <form method="POST" class="bg-white shadow-md rounded-lg p-6">
        <input type="hidden" name="encuesta_id" value="<?php echo $pregunta->encuesta_id; ?>">

        <!-- Campo de texto de la pregunta -->
        <div class="mb-6">
            <label for="texto_pregunta" class="block text-gray-700 font-medium mb-2">Texto de la pregunta *</label>
            <textarea id="texto_pregunta" name="texto_pregunta" rows="3" required
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                      placeholder="¿Qué te pareció nuestro servicio?"><?php echo htmlspecialchars($pregunta->texto_pregunta ?? ''); ?></textarea>
        </div>

        <!-- Tipo de respuesta -->
        <div class="mb-6">
            <label class="block text-gray-700 font-medium mb-2">Tipo de respuesta *</label>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <?php foreach($tipos_pregunta as $valor => $texto): ?>
                    <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="tipo_respuesta" value="<?php echo $valor; ?>" 
                            <?php echo ($pregunta->tipo_respuesta ?? 'texto') === $valor ? 'checked' : ''; ?>
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 m-0">
                        <span class="ml-3 block text-sm font-medium text-gray-700">
                            <?php echo $texto; ?>
                        </span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Configuraciones adicionales -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Orden -->
            <div>
                <label for="orden" class="block text-gray-700 font-medium mb-2">Orden</label>
                <input type="number" id="orden" name="orden" min="0" 
                       value="<?php echo $pregunta->orden ?? '0'; ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            
            <!-- Requerida -->
            <div class="flex items-center">
                <input type="checkbox" id="requerida" name="requerida" value="1"
                       <?php echo isset($pregunta->requerida) && $pregunta->requerida ? 'checked' : 'checked'; ?>
                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded m-0">
                <label for="requerida" class="ml-2 block text-sm text-gray-700">
                    Pregunta obligatoria
                </label>
            </div>
        </div>

        <!-- Sección dinámica para opciones (aparece cuando se selecciona opción múltiple) -->
        <div id="opciones-container" class="hidden mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-3">Opciones de respuesta</h3>
            <div id="opciones-list" class="space-y-3">
                <!-- Las opciones se agregarán dinámicamente aquí -->
            </div>
            <button type="button" id="agregar-opcion" class="mt-3 inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class='bx bx-plus mr-1'></i> Agregar opción
            </button>
        </div>

        <!-- Mostrar errores -->
        <?php if (!empty($errores)): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class='bx bx-error-circle text-red-500'></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Error al guardar la pregunta</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <?php foreach ($errores as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Botones de acción -->
        <div class="flex justify-end space-x-4">
            <button type="reset" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-md">
                Limpiar
            </button>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                Guardar Pregunta
            </button>
        </div>
    </form>
</div>

<!-- Script para manejar opciones de respuesta -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoRespuesta = document.querySelectorAll('input[name="tipo_respuesta"]');
    const opcionesContainer = document.getElementById('opciones-container');
    const opcionesList = document.getElementById('opciones-list');
    const agregarOpcionBtn = document.getElementById('agregar-opcion');
    
    // Mostrar/ocultar opciones cuando cambia el tipo de respuesta
    tipoRespuesta.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'opcion_multiple') {
                opcionesContainer.classList.remove('hidden');
                if (opcionesList.children.length === 0) {
                    agregarOpcion();
                }
            } else {
                opcionesContainer.classList.add('hidden');
            }
        });
    });
    
    // Verificar al cargar si ya está seleccionado opción múltiple
    if (document.querySelector('input[name="tipo_respuesta"]:checked').value === 'opcion_multiple') {
        opcionesContainer.classList.remove('hidden');
    }
    
    // Función para agregar nueva opción
    function agregarOpcion(texto = '', valor = '') {
        const opcionId = Date.now();
        const opcionHtml = `
        <div class="flex gap-2 items-center opcion-item" data-id="${opcionId}">
            <input type="text" name="opciones[${opcionId}][texto]" 
                   placeholder="Texto de la opción" value="${texto}"
                   class="flex-1 m-0 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            <input type="text" name="opciones[${opcionId}][valor]" 
                   placeholder="Valor" value="${valor || texto}"
                   class="w-1/4 m-0 px-3 py-2 border-t border-b border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            <button type="button" class="eliminar-opcion px-3 py-2 border border-gray-300 rounded-md bg-gray-100 hover:bg-gray-200">
                <i class='bx bx-trash text-red-500'></i>
            </button>
        </div>`;
        
        opcionesList.insertAdjacentHTML('beforeend', opcionHtml);
        
        // Agregar evento al botón eliminar
        const nuevoItem = opcionesList.lastElementChild;
        nuevoItem.querySelector('.eliminar-opcion').addEventListener('click', function() {
            nuevoItem.remove();
        });
    }
    
    // Agregar opción inicial si es necesario
    agregarOpcionBtn.addEventListener('click', function() {
        agregarOpcion();
    });
    
    // Si estamos editando y hay opciones, cargarlas
    <?php if (isset($pregunta->opciones) && !empty($pregunta->opciones)): ?>
        <?php foreach($pregunta->opciones as $opcion): ?>
            agregarOpcion('<?php echo addslashes($opcion->texto_opcion); ?>', '<?php echo addslashes($opcion->valor); ?>');
        <?php endforeach; ?>
    <?php endif; ?>
});
</script>