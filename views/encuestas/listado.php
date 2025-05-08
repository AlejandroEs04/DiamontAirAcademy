<?php use Model\RespuestaEncuesta; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-purple-700 mb-6">Mis Encuestas Pendientes</h1>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            ¡Gracias! Tu encuesta ha sido enviada correctamente.
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php 
                echo match($_GET['error']) {
                    'ya_respondiste' => 'Ya has completado esta encuesta anteriormente.',
                    default => 'Ocurrió un error al procesar tu encuesta.'
                };
            ?>
        </div>
    <?php endif; ?>

    <div class="grid gap-6 md:grid-cols-2">
        <?php foreach ($encuestas as $encuesta): ?>
            <?php 
                // Verificar si el usuario ya respondió
                $respondida = RespuestaEncuesta::whereManyCondition([
                    'usuario_id' => $_SESSION['usuario_id'],
                    'encuesta_id' => $encuesta->id
                ]);
            ?>
            
            <?php if (empty($respondida)): ?>
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <h2 class="text-xl font-semibold text-purple-600"><?= htmlspecialchars($encuesta->titulo) ?></h2>
                    <p class="mt-2 text-gray-600"><?= htmlspecialchars($encuesta->descripcion) ?></p>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-sm text-gray-500">
                            Publicada: <?= date('d/m/Y', strtotime($encuesta->fecha_creacion)) ?>
                        </span>
                        <a 
                            href="/encuestas/contestar?id=<?= $encuesta->id ?>" 
                            class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition-colors"
                        >
                            Contestar
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
        
        <?php if (count($encuestas) === 0): ?>
            <div class="col-span-2 text-center py-8">
                <p class="text-gray-500">No tienes encuestas pendientes en este momento.</p>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mt-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        <?php endif; ?>
    </div>
</div>