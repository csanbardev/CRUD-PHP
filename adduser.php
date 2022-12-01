<?php
  require_once 'includes/config.php';

  $msgresultado="";
  $errors=[];

  /**
   * Muestra el error si existe debajo del campo correspondiente
   */
  function mostrar_error($errors, $campo)
  {
    $alert = "";
    if (isset($errors[$campo]) && (!empty($campo))) {
      $alert = '<div class="alert alert-danger" style="margin-top:5px;">'
        . $errors[$campo] . '</div>';
    }
    return $alert;
  }
  

  if(isset($_POST['submit'])&&!empty($_POST)){
    

    // validamos el formulario
    require_once 'includes/valform.php';
    
    // si no hay errores, insertará los datos en la mysql
    if(count($errors)==0){
      try{
        $sql = "insert into usuarios(nombre, apellidos, biografia, pass, email, imagen) values (:nombre, :apellidos, :biografia, :pass, :email, :imagen)";
  
        $query = $conexion->prepare($sql);
  
        $query->execute(['nombre' => $nombre, 'apellidos'=> $apellidos, 'biografia'=>$mibiograf,'pass'=> $password, 'email'=>$email, 'imagen'=>$imagen]);
  
        if($query){
          $msgresultado = '<div class="alert alert-success">'."Usuario registrado correctamente".'</div>';

          // llamo al procedimiento para registrar en el log

          insertarLog("insertar", $conexion);
        }

        
      }catch(PDOException $ex){
        $msgresultado = '<div class="alert alert-danger">'."Fallo al registrar <br>".$ex->getMessage().'</div>';
        // die();
      }
    }else{
      $msgresultado= '<div class="alert alert-danger">'."Completa bien todos los campos".'</div>';
    }

  }
  
?>

<!DOCTYPE html>
<html lang="en">
<?php require_once 'includes/head.php' ?>
<body>
  <div class="container centrar">
    <h1>Añadir usuario</h1>
    <br>
    <?php echo $msgresultado ?>
    <?php require_once 'includes/frmusuarios.php' ?>
  </div>
</body>
</html>