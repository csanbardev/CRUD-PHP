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
    $nombre = "";
    $apellidos = null;
    $mibiograf = null;
    $password = "";
    $email = $_POST['txtemail'];
    $imagen = null;


    // validamos el nombre
    if (
      !empty($_POST["txtnombre"])
      && (!preg_match("/[0-9]/", $_POST["txtnombre"]))
      && (strlen($_POST["txtnombre"]) < 15)
    ) {
      $nombre = trim($_POST["txtnombre"]);
      $nombre = filter_var($nombre, FILTER_UNSAFE_RAW);
    } else {
      $errors["txtnombre"] = "El nombre introducido no es válido :(";
    }

    // validamos los apellidos
    if (
      !empty($_POST["txtapellidos"])
      && (!preg_match("/[0-9]/", $_POST["txtapellidos"]))
      && (strlen($_POST["txtapellidos"]) < 20)
    ) {
      $apellidos = trim($_POST["txtapellidos"]);
      $apellidos = filter_var($apellidos, FILTER_UNSAFE_RAW);
    } else {
      $errors["txtapellidos"] = "Los apellidos introducidos no son válidos :(";
    }

    // validamos la biografía
    if (!empty($_POST["txtbio"])) {
      $mibiograf = $_POST["txtbio"];
      $mibiograf = trim($mibiograf); // Eliminamos espacios en blanco
      $mibiograf = htmlspecialchars($mibiograf); //Caract especiales a HTML
      $mibiograf = stripslashes($mibiograf); //Elimina barras invertidas
    } else {
      $errors["txtbio"] = "La biografía no puede esta vacía :(";
    }

    // validamos el email
    if (!empty($_POST["txtemail"])) {
      $email = filter_var($_POST["txtemail"], FILTER_SANITIZE_EMAIL);
    } else {
      $errors["txtemail"] = "La dirección email introducida no es válida :(";
    }
    
    // validamos la contraseña
    if (
      !empty($_POST["txtpass"]) && (strlen($_POST["txtpass"]) > 6)
      && (strlen($_POST["txtpass"]) <= 10)
    ) {
      $password = sha1($txtpass);
    } else {
      $errors["txtpass"] = "Introduzca una contraseña válida (6-10
  caracteres) :(";
    }


    // comprobamos que se haya insertado un imagen
    if(isset($_FILES['imagen'])&&!empty($_FILES['imagen']['tmp_name'])){

      // conmprobamos que exista la carpeta de subidas
      if(!is_dir('uploads')){
        $dir = mkdir("uploads", 0777, true);
      }else{
        $dir = true;
      }

      // cargo la imagen a la carpeta y genero el nombre personalizado para guardarlo en la base de datos
      if($dir){
        $nombrefichimg = time()."-".$_FILES['imagen']['name'];
        $movfichimg = move_uploaded_file($_FILES['imagen']['tmp_name'],"uploads/".$nombrefichimg);
        $imagen=$nombrefichimg;

        if($movfichimg){
          $imagencargada=true;
        }else{
          $imagencargada=false;
          $errors['imagen']="Error: La imagen no se ha cargado";
        }
      }

      
    }else{ // si no ha especificado imagen, se le inserta una por defecto
      $imagen="user.png";
    }

    // si no hay errores, insertará los datos en la mysql
    if(count($errors)==0){
      try{
        $sql = "insert into usuarios(nombre, apellidos, biografia, pass, email, imagen) values (:nombre, :apellidos, :biografia, :pass, :email, :imagen)";
  
        $query = $conexion->prepare($sql);
  
        $query->execute(['nombre' => $nombre, 'apellidos'=> $apellidos, 'biografia'=>$mibiograf,'pass'=> $password, 'email'=>$email, 'imagen'=>$imagen]);
  
        if($query){
          $msgresultado = '<div class="alert alert-success">'."Usuario registrado correctamente".'</div>';
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