<!DOCTYPE html>
<html lang="en" class="flex h-full" >
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
    <body class="flex-1 overflow-x-hidden" >
        <header>
            <nav>
                <ul>
                    <li><a href="/">Inicio</a></li>
                    <li><a href="/nosotros">Nosotros</a></li>
                    <li><a href="/eventos">Cursos</a></li>
                    <li><a href="/contactanos">¡Contactanos!</a></li>
                    <li><a href="/login">Iniciar Sesión</a></li>
                </ul>
            </nav>
        </header>
        <div class="w-full h-full">
            <main class="container">
                <?php echo $contenido; ?>
            </main>
        </div>
    </body>
    <script src="/build/js/app.js"></script>
</html>