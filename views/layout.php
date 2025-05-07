<!DOCTYPE html>
<html lang="en" class="flex h-full">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/dist/output.css">
    <link rel="stylesheet" href="/dist/styles.css">
    <title>Diamond air dance</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Great+Vibes&family=M+PLUS+Rounded+1c:wght@800&family=Madimi+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="flex-1 overflow-x-hidden">
    <?php 
    if (isset($_SESSION['login']) && $_SESSION['login']): 
        if ($_SESSION['type'] == 1): // Admin ?>
            <header class="bg-indigo-800 text-white">
                <nav>
                    <ul class="container flex justify-between items-center">
                        <li class="text-2xl font-bold">Diamond Air Admin</li>
                        <div class="flex items-center space-x-6">
                            <li><a href="/admin" class="hover:text-indigo-200"><i class='bx bxs-dashboard'></i> Dashboard</a></li>
                            <li><a href="/admin/clases" class="hover:text-indigo-200"><i class='bx bxs-calendar'></i> Clases</a></li>
                            <li><a href="/admin/usuarios" class="hover:text-indigo-200"><i class='bx bxs-user'></i> Usuarios</a></li>
                            <li><a href="/admin/encuestas" class="hover:text-indigo-200"><i class='bx bxs-notepad'></i> Encuestas</a></li>
                            <li>
                                <form action="/logout" method="POST" class="inline">
                                    <button type="submit" class="hover:text-indigo-200">
                                        <i class='bx bx-log-out'></i> Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </div>
                    </ul>
                </nav>
            </header>
        <?php else: // Alumno ?>
            <header class="bg-purple-700 text-white">
                <nav>
                    <ul class="container flex justify-between items-center">
                        <li class="text-2xl font-bold">Diamond Air</li>
                        <div class="flex items-center space-x-6">
                            <li><a href="/" class="hover:text-purple-200"><i class='bx bxs-home'></i> Inicio</a></li>
                            <li><a href="/encuestas/responder" class="hover:text-purple-200"><i class='bx bxs-book'></i>Encuestas</a></li>
                            <li><a href="/alumno" class="hover:text-purple-200"><i class='bx bxs-user'></i> Mi Perfil</a></li>
                            <li>
                                <form action="/logout" method="POST" class="inline">
                                    <button type="submit" class="hover:text-purple-200">
                                        <i class='bx bx-log-out'></i> Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </div>
                    </ul>
                </nav>
            </header>
        <?php endif; ?>
    <?php else: // No autenticado ?>
        <header class="bg-pink-600 text-white">
            <nav>
                <ul class="flex justify-between items-center container">
                    <li class="text-2xl font-bold">Diamond Air Dance</li>
                    <div class="flex space-x-6">
                        <li><a href="/" class="hover:text-pink-200">Inicio</a></li>
                        <li><a href="/nosotros" class="hover:text-pink-200">Nosotros</a></li>
                        <li><a href="/cursos" class="hover:text-pink-200">Cursos</a></li>
                        <li><a href="/contactanos" class="hover:text-pink-200">¡Contactanos!</a></li>
                        <li><a href="/login" class="hover:text-pink-200">Iniciar Sesión</a></li>
                    </div>
                </ul>
            </nav>
        </header>
    <?php endif; ?>

    <div class="w-full h-full">
        <main class="container mx-auto py-8">
            <?php echo $contenido; ?>
        </main>
    </div>
    <script src="/build/js/app.js"></script>
</body>
</html>