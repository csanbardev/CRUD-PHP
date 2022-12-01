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
  
  /**
   * Llama al procedimiento de la base de datos pasándole la conexión y el tipo de función
   */
  function insertarLog($tipo, $conexion){
    $sql = "CALL insertarLog(:fecha, :hora, :tipo)";
    $query = $conexion->prepare($sql);
    $query->execute(['fecha' => date('y-m-d'), 'hora' => date('H:i:s'), 'tipo' => $tipo]);
  }
?>