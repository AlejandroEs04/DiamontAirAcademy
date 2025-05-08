<div class="container mx-auto px-4 py-8">
    <h1><?php echo $encuesta->id ? 'Editar' : 'Crear'; ?> Encuesta</h1>

    <form method="POST" class="bg-white shadow-md rounded-lg p-6 mt-1">
        <div class="mb-1">
            <label class="block text-gray-700 mb-1" for="titulo">Título</label>
            <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($encuesta->titulo); ?>" 
                   class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-1">
            <label class="block text-gray-700 mb-1" for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" rows="3" 
                      class="w-full p-2 border rounded"><?php echo htmlspecialchars($encuesta->descripcion); ?></textarea>
        </div>

        <div class="mb-4">
            <label class="flex justify-start items-center">
                <div class="flex items-center gap-4">
                    <span class="text-gray-700 w-max min-w-max">Encuesta activa</span>
                    <input type="checkbox" name="activa" value="1" <?php echo $encuesta->activa ? 'checked' : ''; ?> class="m-0" >
                </div>
            </label>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="/admin/encuestas" class="bg-gray-300 hover:bg-gray-400 w-min m-0 text-gray-800 px-4 py-2 rounded">
                Cancelar
            </a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Guardar Encuesta
            </button>
        </div>
    </form>
</div>