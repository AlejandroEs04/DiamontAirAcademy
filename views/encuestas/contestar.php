<div class="container mx-auto px-4 py-8 max-w-3xl">
    <h1 class="text-2xl font-bold text-purple-700 mb-2"><?= htmlspecialchars($encuesta->titulo) ?></h1>
    <p class="text-gray-600 mb-6"><?= htmlspecialchars($encuesta->descripcion) ?></p>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            Por favor completa todos los campos requeridos
        </div>
    <?php endif; ?>

    <form method="POST" action="/encuestas/guardar-respuestas?id=<?php echo $encuesta->id ?>" class="bg-white p-6 rounded-lg shadow-md">
        <input type="hidden" name="encuesta_id" value="<?= $encuesta->id ?>">
        
        <?php foreach ($preguntas as $pregunta): ?>
            <div class="mb-6 border-b pb-6 last:border-b-0">
                <label class="block text-gray-700 font-medium mb-2">
                    <?= htmlspecialchars($pregunta->texto_pregunta) ?>
                    <?php if ($pregunta->requerida): ?>
                        <span class="text-red-500">*</span>
                    <?php endif; ?>
                </label>
                
                <?php 
                // Mostrar campo según el tipo de pregunta
                switch ($pregunta->tipo_respuesta):
                    case 'texto': ?>
                        <textarea 
                            name="respuestas[<?= $pregunta->id ?>]"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-300"
                            rows="3"
                            <?= $pregunta->requerida ? 'required' : '' ?>
                        ></textarea>
                        <?php break; ?>
                        
                    case 'opcion_multiple': ?>
                        <?php if (!empty($pregunta->opciones)): ?>
                            <div class="space-y-2">
                                <?php foreach ($pregunta->opciones as $opcion): ?>
                                    <label class="flex items-center space-x-2">
                                        <input 
                                            type="radio" 
                                            name="respuestas[<?= $pregunta->id ?>]" 
                                            value="<?= $opcion->id ?>"
                                            class="text-purple-600 focus:ring-purple-500"
                                            <?= $pregunta->requerida ? 'required' : '' ?>
                                        >
                                        <span><?= htmlspecialchars($opcion->texto_opcion) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-red-500">Esta pregunta no tiene opciones configuradas</p>
                        <?php endif; ?>
                        <?php break; ?>
                        
                    case 'escala': ?>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-500">1 (Mínimo)</span>
                            <div class="flex space-x-2">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <label class="inline-flex items-center">
                                        <input 
                                            type="radio" 
                                            name="respuestas[<?= $pregunta->id ?>]" 
                                            value="<?= $i ?>"
                                            class="h-5 w-5 text-purple-600 focus:ring-purple-500"
                                            <?= $pregunta->requerida ? 'required' : '' ?>
                                        >
                                        <span class="ml-1"><?= $i ?></span>
                                    </label>
                                <?php endfor; ?>
                            </div>
                            <span class="text-sm text-gray-500">5 (Máximo)</span>
                        </div>
                        <?php break; ?>
                        
                    case 'fecha': ?>
                        <input 
                            type="date" 
                            name="respuestas[<?= $pregunta->id ?>]"
                            class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-300"
                            <?= $pregunta->requerida ? 'required' : '' ?>
                        >
                        <?php break; ?>
                        
                <?php endswitch; ?>
            </div>
        <?php endforeach; ?>
        
        <button type="submit" class="w-full bg-purple-600 text-white py-3 px-4 rounded-lg hover:bg-purple-700 transition-colors">
            Enviar Respuestas
        </button>
    </form>
</div>