<?php
if (isset($_GET['date'])) {
    $date = $_GET['date'];
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $mysqli = new mysqli('127.0.0.1', 'root', 'root', 'proyecto_citas_noe');
    $stmt = $mysqli->prepare("INSERT INTO citas(name, email, phone, date) VALUES(?,?,?,?)");
    $stmt->bind_param('ssss', $name, $email, $phone, $date);
    $stmt->execute();
    header("Location: confirmacita.html");
    $stmt->close();
    $mysqli->close();
}

$duration = 30;
$cleanup = 0;
$start = "10:00";
$end = "17:00";

function timeslots($duration, $cleanup, $start, $end)
{
    $start = new DateTime($start);
    $end = new DateTime($end);
    $interval = new DateInterval("PT" . $duration . "M");
    $cleanupInterval = new DateInterval("PT" . $cleanup . "M");
    $slots = array();

    for ($intStart = $start; $intStart < $end; $intStart->add($interval)->add($cleanupInterval)) {
        $endPeriod = clone $intStart;
        $endPeriod->add($interval);
        if ($endPeriod > $end) {
            break;
        }

        $slots[] = $intStart->format("H:iA") . "-" . $endPeriod->format("H:iA");
    }

    return $slots;
}
?>

<html>

<head>
    <title>Dr. Breton</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link href="css/estiloagendacita.css" rel="stylesheet" type="text/css">
    <link href="css/estilospaginas.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="css/estiloscalendariopaciente.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="container">
        <h1 class="text-center">Agendar para: <?php echo date('m/d/Y', strtotime($date)); ?></h1>
        <hr>
        <div class="row">
            <?php $timeslots = timeslots($duration, $cleanup, $start, $end);
            foreach ($timeslots as $ts) {
            ?>
                <div class="col-md-2">
                    <div class="form-group">
                        <button class="btn btn-success"><?php echo $ts; ?></button>
                    </div>
                </div>
            <?php } ?>

            <!--div class="col-md-6 col-md-offset-3">
                <form action="" method="post" autocomplete="off">
                    <div class="form-group">
                        <label for="">Nombre</label>
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="form-group">
                        <label for="">Correo</label>
                        <input type="text" class="form-control" name="email">
                    </div>
                    <div class="form-group">
                        <label for="">Telefono</label>
                        <input type="text" class="form-control" name="phone">
                    </div>
                    <button class="btn btn-primary" type="submit" name="submit">Agendar</button>
                </form>
            </div-->
        </div>
        <a href="paciente.php">Cancelar</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>

<!--div class="container">
    <div class="datos">
        <form action="" method="post" autocomplete="off">
            <h1>Agendar Cita</h1>
            <p>Nombre completo:</p>
            <input type="text" class="form-control" name="name">
            <p>Correo:</p>
            <input type="text" class="form-control" name="email">
            <p>Numero de telefono</p>
            <input type="text" class="form-control" name="phone">
            <p>Horario Seleccionado</p>
            <!?php echo date('F d, Y', strtotime($date)); ?>
            <p>Fecha: <br>Horario: </p>
            <input type="submit" name="Agendar cita">
        </form>
        <a class="btn btn-danger btn-xs" href="paciente.php">Cancelar</a>
    </div>
    <div class="img"></div>
</div-->