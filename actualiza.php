<?php

require_once "CAD.php";
//session_start();
if (isset($_GET['idCita']))
    $idCita = $_GET['idCita'];
//echo "$idCita";


$datosModificar = "";

$bandTS = false;
$bandDate = false;
$bandPhone = false;
$bandMail = false;
$bandName = false;


if (isset($_POST['timeslot'])) {
    if ($_POST['timeslot'] != "") {
        $timeslot = $_POST['timeslot'];
        $datosModificar = "timeslot='$timeslot'";
        $bandTS = true;
    }
}
if (isset($_POST['date'])) {
    if ($_POST['date'] != "") {
        $date = $_POST['date'];
        if ($bandTS) {
            $aux = $datosModificar;
            $datosModificar = "date= '$date'," . $aux;
        } else {
            $datosModificar = "date= '$date'";
        }
        $bandDate = true;
    }
}

if (isset($_POST['phone'])) {
    if ($_POST['phone'] != "") {
        $phone = $_POST['phone'];
        if ($bandTS || $bandDate) {
            $aux = $datosModificar;
            $datosModificar = "phone= '$phone'," . $aux;
        } else {
            $datosModificar = "phone= '$phone'";
        }
        $bandPhone = true;
    }
}

if (isset($_POST['email'])) {
    if ($_POST['email'] != "") {
        $email = $_POST['email'];
        if ($bandTS || $bandDate || $bandPhone) {
            $aux = $datosModificar;
            $datosModificar = "email= '$email'," . $aux;
        } else {
            $datosModificar = "email= '$email'";
        }
        $bandMail = true;
    }
}

if (isset($_POST['name'])) {
    if ($_POST['name'] != "") {
        $name = $_POST['name'];
        if ($bandTS || $bandDate || $bandPhone || $bandMail) {
            $aux = $datosModificar;
            $datosModificar = "name= '$name'," . $aux;
        } else {
            $datosModificar = "name= '$name'";
        }
        $bandName = true;
    }
}

//echo $datosModificar . "<br>";

if ($bandTS || $bandDate || $bandPhone || $bandMail || $bandName) {
    $cad = new CAD();
    if (isset($_POST['idCita']))
        $idCita = $_POST['idCita'];

    if ($cad->modificaCita($datosModificar, $idCita)) {

        echo "La cita se ha actualizado correctamente";
    }
} else {
    echo "No se ha modificado nada";
}

unset($_POST['name']);
unset($_POST['email']);
unset($_POST['phone']);
unset($_POST['date']);
unset($_POST['timeslot']);
$datosModificar = "";
?>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Editar Cita</title>
</head>

<body>
    <main class="flex items-center justify-center w-full p-3">
        <!-- Author: FormBold Team -->
        <div class="mx-auto w-full max-w-[550px] bg-white">
            <form action="actualiza.php" method="POST" autocomplete="off">
                <?php
                echo "<input hidden name='idCita' value='$idCita'>";
                ?>
                <div class="-mx-3 flex flex-wrap">
                    <!-- //titulo de registro de usuario -->
                    <div class="w-full sm:w-full py-2">
                        <h1 class="text-2xl font-semibold text-[#07074D]">
                            Editar Cita
                        </h1>
                    </div>
                    <div class="-mx-3 flex flex-wrap">
                        <div class="w-full px-3 sm:w-1/2">
                            <div class="mb-2">
                                <label for="fName" class="mb-3 block text-base font-medium text-[#07074D]">
                                    Nombre
                                </label>
                                <input type="text" name="name" id="fName" placeholder="First Name" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                            </div>
                        </div>
                        <div class="w-full px-3 sm:w-1/2">
                            <div class="mb-2">
                                <label for="lName" class="mb-3 block text-base font-medium text-[#07074D]">
                                    Telefono
                                </label>
                                <input type="text" name="phone" id="lName" placeholder="Last Name" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                            </div>
                        </div>
                    </div>
                    <div class="mb-2 w-11/12">
                        <label for="guest" class="mb-3 block text-base font-medium text-[#07074D]">
                            Correo electronico
                        </label>
                        <input type="text" name="email" id="guest" placeholder="pedro@gmail.com" min="0" class="w-full appearance-none rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                    </div>
                    <div class="-mx-3 flex flex-wrap">
                        <div class="w-full px-3 sm:w-1/2">
                            <div class="mb-5">
                                <label for="date" class="mb-3 block text-base font-medium text-[#07074D]">
                                    Date
                                </label>
                                <input type="date" name="date" id="date" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                            </div>
                        </div>
                        <div class="w-full px-3 sm:w-1/2">
                            <div class="mb-5">
                                <label for="time" class="mb-3 block text-base font-medium text-[#07074D]">
                                    Time
                                </label>
                                <input type="time" name="timeslot" id="time" class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                            </div>
                        </div>
                    </div>
                    <div class=" w-11/12 flex gap-3 items-center justify-end">
                        <button type="submit" class="hover:shadow-form rounded-md bg-blue-600 py-2 px-3 text-center text-base font-semibold text-white outline-none">
                            Actualizar Cita
                        </button>
                        <button type="button" class="hover:shadow-form rounded-md bg-red-600 py-2 px-3 text-center text-base font-semibold text-white outline-none">
                            <a href="medico.php" class="no-underline">
                                Cancelar Actualizacion
                            </a>
                        </button>
                    </div>
            </form>
        </div>
    </main>

</body>

</html>