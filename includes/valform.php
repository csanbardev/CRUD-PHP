<?php


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
/*
if (!empty($_POST["txtemail"]) && filter_var($_POST['txtemail'], FILTER_VALIDATE_EMAIL)) {
  $email = filter_var($_POST["txtemail"], FILTER_SANITIZE_EMAIL);
} else {
  $errors["txtemail"] = "La dirección email introducida no es válida :(";
}
*/

// validamos la contraseña
if (
  !empty($_POST["txtpass"]) && (strlen($_POST["txtpass"]) >= 6)

) {
  $password = sha1($_POST['txtpass']);
} else {
  $errors["txtpass"] = "Introduzca una contraseña válida (mínimo 6
caracteres) :(";
}


// comprobamos que se haya insertado un imagen
if (isset($_FILES['imagen']) && !empty($_FILES['imagen']['tmp_name'])) {

  // conmprobamos que exista la carpeta de subidas
  if (!is_dir('uploads')) {
    $dir = mkdir("uploads", 0777, true);
  } else {
    $dir = true;
  }

  // cargo la imagen a la carpeta y genero el nombre personalizado para guardarlo en la base de datos
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
} else { // si no ha especificado imagen, se le inserta una por defecto
  $imagen = "user.png";
}
