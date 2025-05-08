<main class="flex-grow container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl mx-auto">
            <h2 class="text-xl font-semibold text-purple-700 mb-4">Escanear código QR</h2>
            
            <div class="scanner-container">
                <video id="qr-video"></video>
                <div id="status-indicator" class="status-indicator"></div>
            </div>

            <div id="result-container">
                <p id="result-message"></p>
                <button id="rescan-btn" class="hidden">Escanear otro</button>
            </div>
        </div>

        <!-- Modal para ingreso manual -->
        <div id="manual-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
                <h3 class="text-lg font-semibold text-purple-700 mb-4">Ingresar código manualmente</h3>
                <form id="manual-form">
                    <div class="mb-4">
                        <label for="token-input" class="block text-gray-700 mb-2">Código de asistencia:</label>
                        <input type="text" id="token-input" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-300" required>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" id="cancel-manual" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                            Cancelar
                        </button>
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                            Registrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
