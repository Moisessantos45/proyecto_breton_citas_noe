<?php
function build_calendar($month, $year)
{
    $mysqli = new mysqli('127.0.0.1', 'root', 'root', 'proyecto_citas_noe', 3306);
    if ($mysqli->connect_error) {
        die('Error en la conexión: ' . $mysqli->connect_error);
    }

    $daysOfWeek = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
    $numberDays = date('t', $firstDayOfMonth);
    $dateComponents = getdate($firstDayOfMonth);
    $monthName = $dateComponents['month'];
    $dayOfWeek = $dateComponents['wday'];
    $dateToday = date('Y-m-d');

    $prev_month = date('m', mktime(0, 0, 0, $month - 1, 1, $year));
    $prev_year = date('Y', mktime(0, 0, 0, $month - 1, 1, $year));
    $next_month = date('m', mktime(0, 0, 0, $month + 1, 1, $year));
    $next_year = date('Y', mktime(0, 0, 0, $month + 1, 1, $year));

    $calendar = "<div class='text-center mb-4'>";
    $calendar .= "<h2 class='text-2xl font-semibold'>$monthName $year</h2>";
    $calendar .= "<div class='flex justify-center space-x-4 mt-2'>";
    $calendar .= "<a href='?month=" . $prev_month . "&year=" . $prev_year . "' class='px-4 py-2 bg-gray-700 text-white rounded'>Mes Anterior</a>";
    $calendar .= "<a href='?month=" . date('m') . "&year=" . date('Y') . "' class='px-4 py-2 bg-gray-700 text-white rounded'>Mes Actual</a>";
    $calendar .= "<a href='?month=" . $next_month . "&year=" . $next_year . "' class='px-4 py-2 bg-gray-700 text-white rounded'>Mes Próximo</a>";
    $calendar .= "</div></div>";
    $calendar .= "<div class='overflow-x-auto'><table class='min-w-full bg-white border border-gray-300'>";
    $calendar .= "<thead><tr>";

    foreach ($daysOfWeek as $day) {
        $calendar .= "<th class='px-4 py-2 border border-gray-300 bg-gray-200'>$day</th>";
    }

    $calendar .= "</tr></thead><tbody><tr>";

    if ($dayOfWeek > 0) {
        for ($k = 0; $k < $dayOfWeek; $k++) {
            $calendar .= "<td class='border border-gray-300 bg-gray-100'>&nbsp;</td>";
        }
    }

    $currentDay = 1;
    $month = str_pad($month, 2, "0", STR_PAD_LEFT);

    while ($currentDay <= $numberDays) {
        if ($dayOfWeek == 7) {
            $dayOfWeek = 0;
            $calendar .= "</tr><tr>";
        }

        $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
        $date = "$year-$month-$currentDayRel";
        $dayName = strtolower(date('l', strtotime($date)));
        $today = $date == date('Y-m-d') ? 'bg-blue-200' : '';

        if ($dayName == 'saturday' || $dayName == 'sunday') {
            $calendar .= "<td class='border border-gray-300 $today'><div class='flex flex-col items-center'><h4 class='text-lg'>$currentDay</h4><button class='mt-2 px-2 py-1 bg-gray-500 text-white rounded text-xs'>N/D</button></div></td>";
        } else if ($date < date('Y-m-d')) {
            $calendar .= "<td class='border border-gray-300 $today'><div class='flex flex-col items-center'><h4 class='text-lg'>$currentDay</h4><button class='mt-2 px-2 py-1 bg-gray-500 text-white rounded text-xs'>N/D</button></div></td>";
        } else {
            $totalbookings = checkSlots($mysqli, $date);
            if ($totalbookings == 18) {
                $calendar .= "<td class='border border-gray-300 $today'><div class='flex flex-col items-center'><h4 class='text-lg'>$currentDay</h4><a href='#' class='mt-2 px-2 py-1 bg-red-500 text-white rounded text-xs'>Lleno</a></div></td>";
            } else {
                $availableslots = 18 - $totalbookings;
                $calendar .= "<td class='border border-gray-300 $today'><div class='flex flex-col items-center'><h4 class='text-lg'>$currentDay</h4><a href='book.php?date=" . $date . "' class='mt-2 px-2 py-1 bg-green-500 text-white rounded text-xs'>Agendar</a><small class='mt-1 text-gray-600'><i>Lugares disponibles: $availableslots</i></small></div></td>";
            }
        }

        $currentDay++;
        $dayOfWeek++;
    }

    if ($dayOfWeek < 7) {
        $remainingDays = 7 - $dayOfWeek;
        for ($i = 0; $i < $remainingDays; $i++) {
            $calendar .= "<td class='border border-gray-300 bg-gray-100'>&nbsp;</td>";
        }
    }

    $calendar .= "</tr></tbody></table></div>";
    return $calendar;
}

function checkSlots($mysqli, $date)
{
    $stmt = $mysqli->prepare('SELECT * FROM citas WHERE DATE = ?');
    $stmt->bind_param('s', $date);
    $totalbookings = 0;
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $totalbookings++;
            }
        }
        $stmt->close();
    }

    return $totalbookings;
}
?>



<html>

<head>
    <title>Dr. Breton</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link href="css/estilospaginas.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/css/">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <main class="contenedor">
        <header class="bg-white flex flex-wrap items-center justify-between p-2 shadow-md w-12/12">
            <div class="flex justify-between items-center w-4/12">
                <span class="btnNombre"></span>
            </div>
            <nav class=" w-5/12 flex items-center justify-end">
                <ul class="md:flex items-center justify-between text-base text-gray-700 w-10/12 p-2">
                    <li>
                        <a class="link_items md:p-4 py-3 px-0 block hover:bg-gray-100 rounded-md" href="registro.php">
                            Add usr
                        </a>
                    </li>
                    <li>
                        <a class="link_items md:p-4 py-3 px-0 block hover:bg-gray-100 rounded-md" href="elimina.php">
                            Eliminar Usuario
                        </a>
                    </li>
                    <li>
                        <a class="link_items md:p-4 py-3 px-0 block hover:bg-gray-100 rounded-md" href="citas.php">
                            Citas
                        </a>
                    </li>
                    <li>
                        <a class="link_items block px-3 bg-red-600 rounded-lg text-white p-2" href="index.html">
                            Salir
                        </a>
                    </li>
                </ul>
            </nav>
        </header>
        <div class="banner">
            <div class="smText">
                <p>Entrust your health to our doctors</p>
            </div>
            <div class="lgText">
                <h1>Medical Services that you can trust</h1>
            </div>
        </div>

        <div class="flex justify-center items-center min-h-screen">
            <div class="w-full max-w-6xl mx-auto">
                <div class="w-full">
                    <?php
                    $dateComponents = getdate();
                    if (isset($_GET['month']) && isset($_GET['year'])) {
                        $month = $_GET['month'];
                        $year = $_GET['year'];
                    } else {
                        $month = $dateComponents['mon'];
                        $year = $dateComponents['year'];
                    }
                    echo build_calendar($month, $year);
                    ?>
                </div>
            </div>
        </div>

        <footer class="flex flex-col md:flex-row justify-between bg-gray-800 text-white p-8 mt-5">
            <div class="mb-8 md:mb-0">
                <div class="flex items-center mb-4">
                    <img src="img/medsym.png" alt="Servicios Médicos" class="w-12 h-12 mr-4">
                    <h3 class="text-2xl font-bold">Servicios Médicos</h3>
                </div>
                <div class="text-sm text-gray-400">
                    texto texto mucho texto texto texto mucho texto texto texto mucho texto texto texto mucho texto texto texto mucho texto texto texto mucho texto texto texto mucho texto
                </div>
            </div>
            <div class="mb-8 md:mb-0">
                <h3 class="text-xl font-bold mb-2">Head Office</h3>
                <p>Constitución 705 int.2, San Luis Potoí, S.L.P., 78000</p>
                <p><a href="mailto:drbreton@gmail.com" class="text-gray-400 hover:text-white">drbreton@gmail.com</a></p>
                <p id="num"><a href="tel:4442009988" class="text-gray-400 hover:text-white">444 200 9988</a></p>
                <p>Lun - Jue: 9:30 - 21:00</p>
                <p>Vier: 6:00 - 21:00</p>
                <p>Sab: 10:00 - 15:00</p>
            </div>
            <div>
                <h3 class="text-xl font-bold mb-2">Quick Links</h3>
                <div class="flex space-x-4">
                    <a href="#"><img src="img/facebook.png" alt="Facebook" class="w-12 h-8"></a>
                    <a href="#"><img src="img/insta.png" alt="Instagram" class="w-12 h-8"></a>
                    <a href="#"><img src="img/twt.png" alt="Twitter" class="w-12 h-8"></a>
                    <a href="#"><img src="img/wha.png" alt="WhatsApp" class="w-12 h-8"></a>
                </div>
            </div>
        </footer>

    </main>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
</body>

</html>