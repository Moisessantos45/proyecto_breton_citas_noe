<?php
function build_calendar($month, $year)
{
    $mysqli = new mysqli('127.0.0.1', 'root', 'root', 'proyecto_citas_noe');

    $daysOfWeek = array('Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado');
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
    $calendar = "<center><h2>$monthName $year</h2>";
    $calendar .= "<a class='btn btn-dark btn-xs' href='?month=" . $prev_month . "&year=" . $prev_year . "'>Mes Anterior</a>";
    $calendar .= "<a class='btn btn-dark btn-xs' href='?month=" . date('m') . "&year=" . date('Y') . "'>Mes Actual</a>";
    $calendar .= "<a class='btn btn-dark btn-xs' href='?month=" . $next_month . "&year=" . $next_year . "'>Mes Proximo</a></center>";
    $calendar .= "<br><table class='table table-bordered'>";
    $calendar .= "<tr>";
    foreach ($daysOfWeek as $day) {
        $calendar .= "<th class='header'>$day</th>";
    }
    $calendar .= "</tr><tr>";
    $currentDay = 1;
    if ($dayOfWeek > 0) {
        for ($k = 0; $k < $dayOfWeek; $k++) {
            $calendar .= "<td class='empty'></td>";
        }
    }

    $month = str_pad($month, 2, "0", STR_PAD_LEFT);
    while ($currentDay <= $numberDays) {
        if ($dayOfWeek == 7) {
            $dayOfWeek = 0;
            $calendar .= "</tr><tr>";
        }

        $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
        $date = "$year-$month-$currentDayRel";
        $dayName = strtolower(date('l', strtotime($date)));
        $today = $date == date('Y-m-d') ? 'today' : '';

        if ($dayName == 'saturday' || $dayName == 'sunday') {
            $calendar .= "<td class='$today'><h4>$currentDay</h4><button class='btn btn-secondary btn-xs'>N/D</button></td>";
        } else if ($date < date('Y-m-d')) {
            $calendar .= "<td class='$today'><h4>$currentDay</h4><button class='btn btn-secondary btn-xs'>N/D</button></td>";
        } else {
            $totalbookings = checkSlots($mysqli, $date);
            if ($totalbookings == 18) {
                $calendar .= "<td class='$today'><h4>$currentDay</h4><a href='#' class='btn btn-danger btn-xs'>Lleno</a></td>";
            } else {
                $availableslots = 18 - $totalbookings;
                $calendar .= "<td class='$today'><h4>$currentDay</h4><a href='book.php?date=" . $date . "' class='btn btn-success btn-xs'>Agendar</a><small><i><br>Lugares disponibles: $availableslots</i></small></td>";
            }
        }

        $currentDay++;
        $dayOfWeek++;
    }

    if ($dayOfWeek < 7) {
        $remainingDays = 7 - $dayOfWeek;
        for ($i = 0; $i < $remainingDays; $i++) {
            $calendar .= "<td class='empty'></td>";
        }
    }

    $calendar .= "</tr></table>";
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
            $stmt->close();
        }
    }

    return $totalbookings;
}

?>

<html>

<head>
    <title>Dr. Breton</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link href="css/estilospaginas.css" rel="stylesheet" type="text/css">
    <link href="css/estilospaciente.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="css/estiloscalendariopaciente.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="contenedor">
        <div class="header">
            <div class="btnNombre"></div>
            <div class="menuCont">
                <div class="menuBtns">
                    <a href="#"></a>
                    <a href="#"></a>
                    <a href="citas.php">Citas</a>
                    <a href="index.html">Salir</a>
                </div>
            </div>
        </div>
        <div class="banner">
            <div class="smText">
                <p>Entrust your health to our doctors</p>
            </div>
            <div class="lgText">
                <h1>Medical Services that you can trust</h1>
            </div>

        </div>
        <div class="middle">
            <!--https://demo.mobiscroll.com/javascript/calendar/appointment-booking-->
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
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
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="footer">
                    <div class="fcol1">
                        <div class="f_logo">
                            <img src="img/medsym.png">
                            <h3>Servicios Médicos</h3>
                        </div>
                        <div class="f_info">texto texto mucho texto texto texto
                            mucho texto texto texto mucho texto texto texto mucho
                            texto texto texto mucho texto texto texto mucho texto</div>
                    </div>
                    <div class="f_headOffice">
                        <h3>Head Office</h3>
                        <p>Constitución 705 int.2, San Luis Potoí, S.L.P., 78000</p>
                        <p><a href="#"><span>drbreton@gmail.com</span></a></p>
                        <p id="num"><a href="#">444 200 9988</a></p>
                        <p>Lun - Jue: 9:30 - 21:00</p>
                        <p>Vier: 6:00 - 21:00</p>
                        <p>Sab: 10:00 - 15:00</p>
                    </div>
                    <div class="f_QL">
                        <h3>Quick Links</h3>
                        <div class="smcont">
                            <a href="#"><img src="img/facebook.png"></a>
                            <a href="#"><img src="img/insta.png"></a>
                            <a href="#"><img src="img/twt.png"></a>
                            <a href="#"><img src="img/wha.png"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>