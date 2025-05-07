<?php 
use Model\Clase;
use Model\Modalidad;
?>

<div class="container mx-auto p-0">
    <h1 class="text-2xl font-bold mb-6">Mis Horarios</h1>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Mis Clases Activas</h2>
            
            <?php if (!empty($horariosActivos)): ?>
                <div class="space-y-4">
                    <?php foreach ($horariosActivos as $horario): 
                        $clase = Clase::find($horario->clase_id);
                        $modalidad = Modalidad::find($horario->modalidad_id);
                    ?>
                        <div class="border-l-4 border-blue-500 pl-4 py-3">
                            <h3 class="font-medium text-blue-700"><?php echo htmlspecialchars($clase->nombre); ?></h3>
                            <p class="text-sm text-gray-600">
                                <i class='bx bxs-calendar'></i> 
                                <?php echo obtenerNombreDia($horario->dia_semana); ?> 
                                de <?php echo date('H:i', strtotime($horario->hora_inicio)); ?> a <?php echo date('H:i', strtotime($horario->hora_fin)); ?>
                            </p>
                            <p class="text-sm text-gray-500">
                                Modalidad: <?php echo htmlspecialchars($modalidad->nombre); ?>
                            </p>
                            <p class="text-sm mt-1">
                                <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                    Activo
                                </span>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500">No tienes horarios activos actualmente.</p>
            <?php endif; ?>
        </div>

        <!-- Columna derecha - Generador de QR -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Generar Código QR</h2>
            
            <div class="space-y-4">
                <div class="mb-4">
                    <label for="id-selection" class="block text-gray-700 mb-2">Seleccionar horario:</label>
                    <select id="id-selection" class="w-full p-2 border rounded">
                        <option value="">-- Seleccione un horario --</option>
                        <?php foreach ($horariosActivos as $horario): 
                            $clase = Clase::find($horario->clase_id);
                        ?>
                            <option value="<?php echo $horario->id; ?>">
                                <?php echo htmlspecialchars($clase->nombre); ?> - 
                                <?php echo obtenerNombreDia($horario->dia_semana); ?> 
                                <?php echo date('H:i', strtotime($horario->hora_inicio)); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <button id="generate-qr" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded disabled:bg-gray-300 disabled:cursor-not-allowed"
                        disabled>
                    <i class='bx bx-qr'></i> Generar QR
                </button>
                
                <div class="mt-6">
                    <h3 class="text-lg font-medium mb-3">Código QR:</h3>
                    <div id="qrcode" class="flex justify-center p-4 border border-gray-200 rounded-lg bg-white">
                        <p class="text-gray-500 text-center">Selecciona un horario y genera tu código QR</p>
                    </div>
                    
                    <button id="save-as-image" 
                            class="mt-4 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded hidden">
                        <i class='bx bx-download'></i> Guardar QR como imagen
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    function obtenerNombreDia($numeroDia) {
        $dias = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles', 
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo'
        ];
        return $dias[$numeroDia] ?? 'Desconocido';
    } 
?>

<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
<script>
    const horariosActivos = <?php echo json_encode($horariosActivos); ?>;
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const idSelection = document.getElementById('id-selection');
    const generateButton = document.getElementById('generate-qr');
    const qrCodeContainer = document.getElementById('qrcode');
    const saveAsImageButton = document.getElementById('save-as-image');
    let qrcode = null;

    // Limpiar contenedor inicial
    qrCodeContainer.innerHTML = '<p class="text-gray-500 text-center">Selecciona un horario y genera tu código QR</p>';

    idSelection.addEventListener('change', () => {
        generateButton.disabled = !idSelection.value;
    });

    generateButton.addEventListener('click', () => {
        const selectedId = idSelection.value;
        if (selectedId) {
            // Buscar el horario seleccionado en los datos ya cargados
            const horarioSeleccionado = horariosActivos.find(h => h.id == selectedId);
            
            if (horarioSeleccionado) {
                // Crear objeto con los datos mínimos necesarios
                const qrData = {
                    horario_id: horarioSeleccionado.id,
                    alumno_id: <?php echo $_SESSION['id']; ?>,
                    clase_id: horarioSeleccionado.clase_id,
                    fecha: new Date().toISOString().split('T')[0], // Fecha actual YYYY-MM-DD
                    token: '<?php echo bin2hex(random_bytes(8)); ?>' // Token único para seguridad
                };

                // Limpiar contenedor
                qrCodeContainer.innerHTML = '';
                
                // Generar o actualizar QR
                if (qrcode) {
                    qrcode.clear();
                    qrcode.makeCode(JSON.stringify(qrData));
                } else {
                    qrcode = new QRCode(qrCodeContainer, {
                        text: JSON.stringify(qrData),
                        width: 200,
                        height: 200,
                        colorDark: "#000000",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.H
                    });
                }
                
                saveAsImageButton.classList.remove('hidden');
            } else {
                qrCodeContainer.innerHTML = '<p class="text-red-500 text-center">Error: Horario no encontrado</p>';
                saveAsImageButton.classList.add('hidden');
            }
        }
    });

    // Función para guardar el QR (igual que antes)
    saveAsImageButton.addEventListener('click', saveQRCodeAsImage);

    function saveQRCodeAsImage() {
        const canvas = qrCodeContainer.querySelector('canvas');
        if (canvas) {
            const canvasWithMargin = document.createElement('canvas');
            const margin = 20;
            canvasWithMargin.width = canvas.width + margin * 2;
            canvasWithMargin.height = canvas.height + margin * 2;
            
            const ctx = canvasWithMargin.getContext('2d');
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, canvasWithMargin.width, canvasWithMargin.height);
            ctx.drawImage(canvas, margin, margin);
            
            const link = document.createElement('a');
            link.download = `qr-horario-${idSelection.value}-<?php echo $_SESSION['id']; ?>.png`;
            link.href = canvasWithMargin.toDataURL('image/png');
            link.click();
        } else {
            alert("Primero genera un código QR antes de intentar guardarlo.");
        }
    }
});
</script>