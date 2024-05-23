<?php
$mysqli = new mysqli('127.0.0.1', 'root', 'root', 'proyecto_citas_noe');
if (isset($_GET['date'])) {
    $date = $_GET['date'];
    $stmt = $mysqli->prepare("SELECT * FROM citas WHERE DATE = ?");
    $stmt->bind_param('s', $date);
    $bookings = array();
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $bookings[] = $row['timeslot'];
            }
            $stmt->close();
        }
    }
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $timeslot = $_POST['timeslot'];
    $stmt = $mysqli->prepare('SELECT * FROM citas WHERE DATE=? AND timeslot=?');
    $stmt->bind_param('ss', $date, $timeslot);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $msg = "<div class='alert alert-danger'>No disponible</div>";
        } else {
            $stmt = $mysqli->prepare("INSERT INTO citas(name, timeslot, email, phone, date) VALUES(?,?,?,?,?)");
            $stmt->bind_param('sssss', $name, $timeslot, $email, $phone, $date);
            $stmt->execute();
            $bookings[] = $timeslot;
            $stmt->close();
            $mysqli->close();
            header("Location: confirmacita.html");
        }
    }
}

$duration = 30;
$cleanup = 0;
$start = "10:00";
$end = "19:00";

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <h1 class="text-center">Agendar para: <?php echo date('m/d/Y', strtotime($date)); ?></h1>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <?php echo (isset($msg)) ? $msg : ""; ?>
            </div>
            <?php $timeslots = timeslots($duration, $cleanup, $start, $end);
            foreach ($timeslots as $ts) {
            ?>
                <div class="col-md-2">
                    <div class="form-group">
                        <?php if (in_array($ts, $bookings)) { ?>
                            <button class="btn btn-danger"><?php echo $ts; ?></button>
                        <?php } else { ?>
                            <button class="btn btn-success book" data-timeslot="<?php echo $ts; ?>"><?php echo $ts; ?></button>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
        <a class="btn btn-danger" href="paciente.php">Cancelar</a>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <!--button type="button" class="close" data-dismiss="modal">&times;</button-->
                    <h4 class="modal-title "><span id="slot"></span></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="" method="POST" autocomplete="off">
                                <div class="form-group">
                                    <label for="">Hora</label>
                                    <input required type="text" readonly name="timeslot" id="timeslot" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Nombre</label>
                                    <input required type="text" name="name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Correo</label>
                                    <input required type="email" name="email" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Telefono</label>
                                    <input required type="text" name="phone" class="form-control">
                                </div>
                                <div class="form-group pull-right">
                                    <button class="btn btn-primary" type="submit" name="submit">Agendar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script>
        $(".book").click(function() {
            var timeslot = $(this).attr('data-timeslot');
            $("#slot").html(timeslot);
            $("#timeslot").val(timeslot);
            $("#myModal").modal("show");
        })
    </script>
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
</div>

<div class="col-md-6 col-md-offset-3">
    <form action="" method="post" autocomplete="off">
        <div class="form-group">
            <label for="">Nombre</label>
            <input type="text" class="form-control" name="name">
        </div>
        <div class="form-group">
            <label for="">Correo</label>
            <input type="email" class="form-control" name="email">
        </div>
        <div class="form-group">
            <label for="">Telefono</label>
            <input type="text" class="form-control" name="phone">
        </div>
        <button class="btn btn-primary" type="submit" name="submit">Agendar</button>
    </form>
</div-->