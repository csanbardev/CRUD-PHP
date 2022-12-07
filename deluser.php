<?php
require_once 'includes/config.php';
$msgresultado = "";

// si pulsa al botón input de eliminar
if (isset($_POST['delete'])) {
  $id = $_POST['id'];
  try {
    $sql = "delete from usuarios where id=:id";
    $query = $conexion->prepare($sql);
    $query->execute(['id' => $id]);

    if ($query) {
      insertarLog("eliminar", $conexion);
      // redirige al listado
      header("Location: listado.php?delete=true");
    }
  } catch (PDOException $ex) {
    echo '<div class="alert alert-success">' . "Ha fallado la eliminación del usuario<br>" . $ex->getMessage() . '</div>';
  }

  // si pulsa al botón input de cancelar
} else if (isset($_POST['cancel'])) {
  // redirige al listado
  header("Location: listado.php?delete=false");
}

?>

<!DOCTYPE html>
<html lang="es">
<?php require_once 'includes/head.php' ?>

<body>
  <div class="container center">
    <h1>Confirmar eliminación</h1>
    <div class="container" style="padding-top: 3rem">
    <form action="deluser.php" method="post">
      <input name="delete" class="btn btn-danger" type="submit" value="Eliminar">
      <input name="cancel" class="btn btn-light" type="submit" value="Cancelar">
      <input name="id" type="hidden" value=<?php echo $_GET['id'] ?>>
    </form>
    </div>
    


  </div>

</body>

</html>