<?php
require_once 'includes/config.php';
$msgresultado = "";
$resultado;
try {
  $sql = "select * from usuarios where id=:id";
  $query = $conexion->prepare($sql);
  $query->execute(['id' => $_GET['id']]);

  // si falla al obtener el usuario
  if (!$query) {
    $msgresultado = '<div class="alert alert-danger">Fallo al obtener el usuario</div>';
  } else {
    $resultado = $query->fetch();
  }
} catch (PDOException $ex) {
  $msgresultado = '<div class="alert alert-danger">' . "Fallo al registrar <br>" . $ex->getMessage() . '</div>';
  // die();
}


?>


<!DOCTYPE html>
<html lang="en">
<?php require_once 'includes/head.php' ?>

<body>
  <?php echo $msgresultado ?>
  <div class="container center">
    <h1>Detalles del usuario</h1><br>
    <label for="txtnombre">Nombre:
      <input type="text" name="txtnombre" class="form-control" value=<?php echo $resultado['nombre'] ?> readonly>
    </label>
    <br />
    <label for="txtapellidos"> Apellidos:
      <input type="text" name="txtapellidos" class="form-control" value=<?php echo $resultado['apellidos'] ?> readonly>
    </label>
    <br />
    <label for="txtbio">Biografia:
      <textarea name="txtbio" class="form-control" value=<?php echo $resultado['biografia'] ?> readonly></textarea>
    </label>
    <br />
    <label for="txtemail">Correo:
      <input type="email" name="txtemail" class="form-control" value=<?php echo $resultado['email'] ?> readonly>
    </label>
    <br />
    <label for="image">Imagen:
      <img width="40" <?php echo isset($resultado['imagen']) ? 'src="uploads/' . $resultado['imagen'] . '"' : "" ?>>
    </label>
  </div>
</body>

</html>