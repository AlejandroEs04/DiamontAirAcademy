<div class="flex flex-col md:flex-row items-center justify-center md:justify-between p-4 md:p-0 gap-10">
    <!-- Sección del formulario - Tarjeta flotante -->
    <div class="w-full md:w-1/2 bg-white rounded-xl shadow-lg p-8 z-10">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Iniciar Sesión</h1>
            <p class="text-gray-500 mt-2">Ingresa tus credenciales para continuar</p>
        </div>

        <form method="POST" action="/login" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                <input type="password" id="password" name="contrasena" placeholder="••••••••" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
            </div>

            <button type="submit" 
                    class="w-full bg-indigo-500 hover:bg-indigo-600 text-white py-3 px-4 rounded-lg font-medium transition-colors shadow-sm">
                Ingresar
            </button>
        </form>
    </div>

    <!-- Sección de la imagen - Ocupa el espacio restante -->
    <div class="hidden md:flex w-full md:w-1/2 overflow-hidden relative justify-center">
    <img src="/build/img/bailarinav2.png" alt="Imagen de la bailarina" class="h-auto w-full max-w-lg">    </div>
</div>