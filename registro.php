<?php

require_once "cad.php";

if (isset($_POST['nombre']) && isset($_POST['correo']) && isset($_POST['rol']) && isset($_POST['username']) && isset($_POST['passw'])) {
    #Enviar a la bd

    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $rol = $_POST['rol'];
    $username = $_POST['username'];
    $passw = $_POST['passw'];

    $cad = new CAD();
    $cad->agregaUsuario($nombre, $correo, $rol, $username, $passw);
}

unset($_POST['nombre']);
unset($_POST['correo']);
unset($_POST['rol']);
unset($_POST['username']);
unset($_POST['passw']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario</title>
    <link rel="stylesheet" href="/css/styleRegistro.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <main class="flex items-center justify-center w-full p-3">
        <!-- Author: FormBold Team -->
        <div class="mx-auto w-full max-w-[550px] bg-white">
            <form>
                <div class="-mx-3 flex flex-wrap">
                    <!-- //titulo de registro de usuario -->
                    <div class="w-full px-3 sm:w-full py-2">
                        <h1 class="text-2xl font-semibold text-[#07074D]">
                            Registro de usuarios
                        </h1>
                    </div>
                    <div class="-mx-3 flex flex-wrap">
                        <div class="w-full px-3 sm:w-1/2">
                            <div class="mb-2">
                                <label for="fName" class="mb-3 block text-base font-medium text-[#07074D]">
                                    Nombre
                                </label>
                                <input type="text" name="nombre" id="fName" placeholder="First Name" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                            </div>
                        </div>
                        <div class="w-full px-3 sm:w-1/2">
                            <div class="mb-2">
                                <label for="lName" class="mb-3 block text-base font-medium text-[#07074D]">
                                    Usuario (username)
                                </label>
                                <input type="text" name="username" id="lName" placeholder="Last Name" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                            </div>
                        </div>
                    </div>
                    <div class="mb-2 w-11/12">
                        <label for="guest" class="mb-3 block text-base font-medium text-[#07074D]">
                            Correo electronico
                        </label>
                        <input type="text" name="correo" id="guest" placeholder="pedro@gmail.com" min="0" class="w-full appearance-none rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                    </div>
                    <div class="mb-2 w-11/12">
                        <label for="guest" class="mb-3 block text-base font-medium text-[#07074D]">
                            Contraseña
                        </label>
                        <input type="password" name="passw" id="guest" placeholder="pedro@gmail.com" min="0" class="w-full appearance-none rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                    </div>
                    <div class="mb-2 w-11/12">
                        <label for="guest" class="mb-3 block text-base font-medium text-[#07074D]">
                            Rol: 1-admin 0-trabajador
                        </label>
                        <input type="number" name="rol" id="guest" placeholder="Ingrese 0 o 1" min="0" max="1" class="w-full appearance-none rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                    </div>
                    <div class=" w-11/12 flex gap-3 items-center justify-end">
                        <button class="hover:shadow-form rounded-md bg-blue-600 py-2 px-3 text-center text-base font-semibold text-white outline-none">
                            Registrar Usuario
                        </button>
                        <button type="button" class="hover:shadow-form rounded-md bg-red-600 py-2 px-3 text-center text-base font-semibold text-white outline-none">
                            <a href="medico.php" class="no-underline">Cancelar Registro</a>
                        </button>
                    </div>
            </form>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>

</body>

</html>