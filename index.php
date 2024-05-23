<?php
session_start();

if(isset($_SESSION['rol'])) 
{
    if($_SESSION['rol']==1){
        header("Location: medico.php"); 
    }
    else
    {
        header("Location: editarcita.php");
    }
}
?>
