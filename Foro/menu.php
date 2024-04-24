<?php
/**
 * Este archivo imprime una barra de menú.
 *
 * La barra de menú incluye enlaces a la página de inicio, a la página de temas y a la página de acceso de usuarios.
 *
 * @category Foro
 * @package  Foro
 */
print('
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EXERCICIO FORO - menú</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <style>
        body {
            background: -webkit-linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1));
            background: linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1))                
            }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3"> <!-- Aquí se aplica la modificación -->
        <div class="container-fluid">
            <a class="navbar-brand" href="inicio.php">EXERCICIO FORO</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="inicio.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="temas.php">Temas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Acceso usuarios</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>    
</body>
</html>
');