<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Encuestas</h1>
        <a href="/admin/encuestas/crear" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            Nueva Encuesta
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-3 px-4 text-left">Título</th>
                    <th class="py-3 px-4 text-left">Estado</th>
                    <th class="py-3 px-4 text-left">Fecha</th>
                    <th class="py-3 px-4 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach($encuestas as $encuesta): ?>
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4"><?php echo htmlspecialchars($encuesta->titulo); ?></td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-1 rounded-full text-xs <?php echo $encuesta->activa ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                            <?php echo $encuesta->activa ? 'Activa' : 'Inactiva'; ?>
                        </span>
                    </td>
                    <td class="py-3 px-4"><?php echo date('d/m/Y', strtotime($encuesta->fecha_creacion)); ?></td>
                    <td class="py-3 px-4 flex space-x-2">
                        <a href="/admin/encuestas/editar?id=<?php echo $encuesta->id; ?>" class="text-blue-500 hover:text-blue-700">
                            Editar
                        </a>
                        <form action="/admin/encuestas/eliminar" method="POST" class="inline">
                            <input type="hidden" name="id" value="<?php echo $encuesta->id; ?>">
                            <button type="submit" onclick="return confirm('¿Eliminar esta encuesta?')" class="text-red-500 hover:text-red-700">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>