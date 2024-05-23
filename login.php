<?php

require_once "CAD.php";
require_once "conexion.php";

session_start();

if (isset($_POST['correo']) && isset($_POST['passw'])) {
    $correo = $_POST['correo'];
    $passw = $_POST['passw'];
    echo $correo . "-" . $passw;
    $con = new Conexion();
    $query = $con->conectar()->prepare("SELECT * FROM usuario WHERE correo = :correo and passw = :passw");
    $query->bindParam(':correo', $correo);
    $query->bindParam(':passw', $passw);

    if ($query->execute()) {
        //un solo registro 
        $row = $query->fetch(PDO::FETCH_NUM);
        if ($row) {
            echo "El usuario existe";
            $_SESSION['idUsuario'] = $row[0];
            $_SESSION['rol'] = $row[3];
            header("Location: index.php");
        } else {
            echo "El usuario no existe";
        }
    }
}


unset($_POST['correo']);
unset($_POST['passw']);
?>

<html>

<head>
    <title>Dr. Breton</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link href="css/estilologin.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>
    <section class="flex flex-col items-center pt-6">
        <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                    Inicia sesión
                </h1>
                <form class="space-y-4 md:space-y-6" action="login.php" method="POST" autocomplete="off">
                    <div>
                        <label for="correo" class="block mb-2 text-sm font-medium text-gray-900">
                            Correo electrónico
                        </label>
                        <input type="text" name="correo" id="correo" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 " placeholder="emelia_erickson24" required="">
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900">
                            Contraseña
                        </label>
                        <input type="password" name="passw" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5" required="">
                    </div>
                    <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Iniciar sesión
                    </button>
                </form>
            </div>
        </div>
    </section>
    <!-- <div class="loginbox">
        <a href="index.html">Cancelar</a>
        <h1>Log in</h1>
        <form action="login.php" method="POST" autocomplete="off">
            <span>Username:</span>
            <input type="text" name="username">
            <br>
            <span>Contraseña:</span>
            <input type="password" name="passw">
            <br>
            <input type="submit" name="Inicia Sesión">
        </form>
    </div> -->
</body>

</html>

<!--p>Usuario:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text"></p>
<p>Contraseña: <input type="password"></p>
<p><input type="submit" value="Ingresar"></p-->