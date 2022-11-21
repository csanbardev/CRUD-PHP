<?php
  $dbHost = 'localhost';
  $dbName= 'bdusuarios';
  $dbUser='root';
  $dbPass='';

  try{
    $conexion = new PDO("mysql:host=$dbHost; dbname=$dbName", $dbUser, $dbPass);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // HABILITO EL COPNTROL DE EXCEPCIONES
    echo '<div class="alert alert-success">'."Conectado a la base de datos de usuarios".'</div>';
  }catch(PDOException $ex){
    echo '<div class="alert alert-danger">'."No se ha podido conectar a la base de datos <br>".$ex->getMessage().'</div>';
  }
?>