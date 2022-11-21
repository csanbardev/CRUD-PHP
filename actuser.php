<?php
  require_once 'includes/config.php';

  $msgresultado = "hola";

  $valnombre="";
  $valemail="";

  if(isset($_POST['submit'])){
    $id = $_POST['id'];

    $nuevonombre = $_POST['txtnombre'];
    $nuevoemail = $_POST['txtemail'];

    try{
      $sql = "update usuarios set nombre=:nombre, email=:email where id=:id";
      $query = $conexion->prepare($sql);
      $query->execute(['id'=>$id, 'nombre'=>$nuevonombre, 'email'=>$nuevoemail]);

      if($query){
        $msgresultado = '<div class="alert alert-success">'."El usuario se ha actualizado correctamente".'</div>';
      }
    }catch(PDOException $ex){
      $msgresultado = '<div class="alert alert-danger">'."El usuario no se ha actualizado <br>".
      $ex->getMessage().'</div>';
    }

    $valnombre = $nuevonombre;
    $valemail=$nuevoemail;
  }else{

    if(isset($_GET['id'])&&is_numeric($_GET['id'])){
      $id = $_GET['id'];

      try{
        $sql = "select * from usuarios where id=:id";
        $query = $conexion->prepare($sql);
        $query->execute(['id'=>$id]);

        if($query){
          $msgresultado = '<div class="alert alert-success">'."Los datos se obtuvieron correctamente".'</div>';
          $fila = $query->fetch(PDO::FETCH_ASSOC);

          $valnombre=$fila['nombre'];
          $valemail = $fila['email'];
        }
      }catch(PDOException $ex){
        $msgresultado = '<div class="alert alert-danger">'."No se han obtenido los datos de los usuarios<br>".
        $ex->getMessage().'</div>';
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

    <form action="actuser.php" method="post">
      <label for="txtnombre">Nombre
        <input type="text" class="form-control" name="txtnombre" value=<?php echo $valnombre; ?> required>
      </label>
      <br>
      <label for="txtemail">Email
        <input type="text" class="form-control" name="txtemail" value=<?php echo $valemail; ?> required>
      </label>
      <input type="hidden" name="id" value=<?php echo $id ?>>
      <br>
      <input type="submit" value="Actualizar" name="submit" class="btn btn-success">
    </form>
  </div>
</body>
</html>