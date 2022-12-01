<?php
require_once 'includes/config.php';

$msgresultado = "";
$errors = array();
$valnombre = "";
$valemail = "";
$valimagen = "user.png";
$valbio = "";
$valapellidos = "";

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

if (isset($_POST['submit'])) {
  $id = $_POST['id'];
  $nombre = "";
  $apellidos = null;
  $mibiograf = null;
  $password = "";
  $email = "";
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
  } else if (empty($_POST['txtapellidos'])) {
    // se permitiría no incluir apellidos
  } else {
    $errors["txtapellidos"] = "Los apellidos introducidos no son válidos :(";
  }

  // validamos la biografía
  if (!empty($_POST["txtbio"])) {
    $mibiograf = $_POST["txtbio"];
    $mibiograf = trim($mibiograf); // Eliminamos espacios en blanco
    $mibiograf = htmlspecialchars($mibiograf); //Caract especiales a HTML
    $mibiograf = stripslashes($mibiograf); //Elimina barras invertidas
  }

  // validamos el email
  if (!empty($_POST["txtemail"]) && filter_var($_POST['txtemail'], FILTER_VALIDATE_EMAIL)) {
    $email = filter_var($_POST["txtemail"], FILTER_SANITIZE_EMAIL);
  } else {
    $errors["txtemail"] = "La dirección email introducida no es válida :(";
  }


  $nuevonombre = $nombre;
  $nuevoemail = $email;
  $nuevaimagen = "";
  $nuevabio = $mibiograf;
  $nuevosapellidos = $apellidos;

  $imagen = null;

  if (isset($_FILES['imagen']) && !empty($_FILES['imagen']['tmp_name'])) {

    if (!is_dir('uploads')) {
      $dir = mkdir("uploads", 0777, true);
    } else {
      $dir = true;
    }

    if ($dir) {
      $nombrefichimg = time() . "-" . $_FILES['imagen']['name'];
      $movfichimg = move_uploaded_file($_FILES['imagen']['tmp_name'], "uploads/" . $nombrefichimg);
      $imagen = $nombrefichimg;

      if ($movfichimg) {
        $imagencargada = true;
      } else {
        $imagencargada = false;
        $errors['imagen'] = "Error: La imagen no se ha cargado";
      }
    }
  }
  $nuevaimagen = $imagen;

  // actualizará el usuario si no ha habido errores
  if (count($errors) == 0) {
    try {
      $sql = "update usuarios set nombre=:nombre, email=:email, imagen=:imagen, apellidos=:apellidos, biografia=:biografia where id=:id";
      $query = $conexion->prepare($sql);
      $query->execute(['nombre' => $nuevonombre, 'biografia' => $nuevabio, 'apellidos' => $nuevosapellidos, 'email' => $nuevoemail, 'imagen' => $nuevaimagen, 'id' => $id]);

      if ($query) {
        $msgresultado = '<div class="alert alert-success">' . "El usuario se ha actualizado correctamente" . '</div>';
        insertarLog("actualizar", $conexion);
      }
    } catch (PDOException $ex) {
      $msgresultado = '<div class="alert alert-danger">' . "El usuario no se ha actualizado <br>" .
        $ex->getMessage() . '</div>';
    }
  }else{
    $msgresultado = '<div class="alert alert-danger">' . "Los datos del usuario no son válidos" . '</div>';
  }



  $valnombre = $nuevonombre;
  $valemail = $nuevoemail;
  $valimagen = $nuevaimagen;
  $valbio = $nuevabio;
  $valapellidos = $nuevosapellidos;
} else {

  if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    try {
      $sql = "select * from usuarios where id=:id";
      $query = $conexion->prepare($sql);
      $query->execute(['id' => $id]);

      if ($query) {
        $msgresultado = '<div class="alert alert-success">' . "Los datos se obtuvieron correctamente" . '</div>';
        $fila = $query->fetch(PDO::FETCH_ASSOC);

        $valnombre = $fila['nombre'];
        $valemail = $fila['email'];
        $valimagen = $fila['imagen'];
        $valbio = $fila['biografia'];
        $valapellidos = $fila['apellidos'];
      }
    } catch (PDOException $ex) {
      $msgresultado = '<div class="alert alert-danger">' . "No se han obtenido los datos de los usuarios<br>" .
        $ex->getMessage() . '</div>';
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php require_once 'includes/head.php' ?>

<body>
  <div class="container center">
    <h1>Actualizar usuario</h1>
    <br>
    <?php echo $msgresultado ?>

    <form action="actuser.php" method="post" enctype="multipart/form-data">
      <label for="txtnombre">Nombre
        <input type="text" class="form-control" name="txtnombre" value=<?php echo $valnombre; ?> required>
        <?php echo mostrar_error($errors, "txtnombre"); ?>
      </label>
      <br>
      <label for="txtapellidos"> Apellidos:
        <input type="text" name="txtapellidos" class="form-control" value=<?php echo $valapellidos; ?>>
        <?php echo mostrar_error($errors, "txtapellidos"); ?>
      </label>
      <br />
      <label for="txtbio">Biografia:
        <textarea name="txtbio" class="form-control" value=<?php echo $valbio; ?>></textarea>
        <?php echo mostrar_error($errors, "txtbio"); ?>
      </label>
      <br>
      <label for="txtemail">Email
        <input type="text" class="form-control" name="txtemail" value=<?php echo $valemail; ?> required>
        <?php echo mostrar_error($errors, "txtemail"); ?>
      </label>
      <br>
      <?php if ($valimagen != null) { ?>
        <br>Imagen de perfil: <img src="uploads/<?php echo $valimagen; ?>" width="60" /> <br>
      <?php } ?>
      <label for="txtemail">Actualizar imagen de perfil
        <input type="file" class="form-control" name="imagen">
      </label>
      <br>
      <input type="hidden" name="id" value=<?php echo $id ?>>
      <br>
      <input type="submit" value="Actualizar" name="submit" class="btn btn-success">
    </form>
  </div>
</body>

</html>