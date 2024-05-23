<?php
require_once "CAD.php";
session_start();



#cad -> eliminar cita    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <main class="flex items-center justify-center w-full p-3 flex-col">
        <div class=" w-full flex items-center p-2 m-2">
            <a href="medico.php" class="bg-blue-600 rounded-lg text-white p-2">Regresar a Medicos</a>
        </div>
        <div class="max-w-6xl mx-auto bg-white p-4 rounded-lg shadow-md">
            <h1 class="text-2xl font-semibold text-gray-800 mb-4">
                Lista de Citas
            </h1>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 ">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID Cita
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nombre
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Correo
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Telefono
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Hora
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Eliminar
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Editar
                            </th>
                        </tr>
                    </thead>
                    <tbody className="bg-white divide-y divide-gray-200">
                        <?php
                        $cad = new CAD();
                        $datos = $cad->traeCitas();

                        if ($datos) {
                            foreach ($datos as $registro) {
                                echo "<tr>";
                                $idCita = $registro['idCita'];

                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>" . $registro['idCita'] . "</td>";

                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>" . $registro['name'] . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>" . $registro['email'] . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>" . $registro['phone'] . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>" . $registro['date'] . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>" . $registro['timeslot'] . "</td>";

                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'><a href='citas.php?idCita=$idCita'>Eliminar</a></td>";

                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'><a href='actualiza.php?idCita=$idCita'>Editar</a></td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
</body>

</html>