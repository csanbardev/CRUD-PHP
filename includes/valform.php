<?php

/**
 * Script que muestra en una tabla los valores enviados por el usuario a
 * través del formulario utilizando el método POST
 */
// Definimos e inicializamos el array de errores
$errors = [];
// Función que muestra el mensaje de error bajo el campo que no ha
// superado el proceso de validación
function mostrar_error($errors, $campo)
{
  $alert = "";
  if (isset($errors[$campo]) && (!empty($campo))) {
    $alert = '<div class="alert alert-danger" style="margin-top:5px;">'
      . $errors[$campo] . '</div>';
  }
  return $alert;
}
// Verificamos si todos los campos han sido validados
function validez($errors)
{
  if (isset($_POST["submit"]) && (count($errors) == 0)) {
    return '<div class="alert alert-success" style="margin-top:5px;">
Formulario validado correctamente!! :) </div>';
  }
}


if (isset($_POST["submit"])) {
  if (
    !empty($_POST["txtnombre"])
    && (!preg_match("/[0-9]/", $_POST["txtnombre"]))
    && (strlen($_POST["txtnombre"]) < 15)
  ) {
    $nombre = trim($_POST["txtnombre"]);
    $nombre = filter_var($nombre, FILTER_UNSAFE_RAW);
    echo "Nombre:" . $nombre . "<br/>";
  } else {
    $errors["txtnombre"] = "El nombre introducido no es válido :(";
  }


  if (
    !empty($_POST["txtapellidos"])
    && (!preg_match("/[0-9]/", $_POST["txtapellidos"]))
    && (strlen($_POST["txtapellidos"]) < 20)
  ) {
    $apellidos = trim($_POST["txtapellidos"]);
    $apellidos = filter_var($apellidos, FILTER_UNSAFE_RAW);
    echo "Apellidos:" . $apellidos . "<br/>";
  } else {
    $errors["txtapellidos"] = "Los apellidos introducidos no son válidos :(";
  }


  if (!empty($_POST["txtbio"])) {
    $mibiograf = $_POST["txtbio"];
    $mibiograf = trim($mibiograf); // Eliminamos espacios en blanco
    $mibiograf = htmlspecialchars($mibiograf); //Caract especiales a HTML
    $mibiograf = stripslashes($mibiograf); //Elimina barras invertidas
    echo "Biografía:" . $mibiograf . "<br/>";
  } else {
    $errors["txtbio"] = "La biografía no puede esta vacía :(";
  }

  if (!empty($_POST["txtemail"])) {
    $correo = filter_var($_POST["txtemail"], FILTER_SANITIZE_EMAIL);
    if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
      echo "email:" . $correo . "<br/>";
    }
  } else {
    $errors["txtemail"] = "La dirección email introducida no es válida :(";
  }
  
  if (
    !empty($_POST["txtpass"]) && (strlen($_POST["txtpass"]) > 6)
    && (strlen($_POST["txtpass"]) <= 10)
  ) {
    echo "Contraseña:" . sha1($_POST["txtpass"]) . "<br/>";
  } else {
    $errors["txtpass"] = "Introduzca una contraseña válida (6-10
caracteres) :(";
  }
  
  if (isset($_FILES["imagen"]) && !empty($_FILES["imagen"]["tmp_name"])) {
    echo "Fotografía:" . "La imagen nos ha llegado ;)";
  } else {
    $errors["imagen"] = "Seleccione una imagen válida :(";
  }
}
