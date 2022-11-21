<?php
  require_once 'includes/config.php';

  $msgresultado="";

  if(isset($_POST['submit'])){
    $nombre = $_POST['txtnombre'];
    $password = sha1($_POST['txtpass']);
    $email = $_POST['txtemail'];

    try{
      $sql = "insert into usuarios(nombre, pass, email) values (:nombre, :password, :email)";

      $query = $conexion->prepare($sql);

      $query->execute(['nombre' => $nombre, 'password'=> $password, 'email'=>$email]);

      if($query){
        $msgresultado = '<div class="alert alert-success">'."Usuario registrado correctamente".'</div>';
      }
    }catch(PDOException $ex){
      $msgresultado = '<div class="alert alert-danger">'."Fallo al registrar <br>".$ex->getMessage().'</div>';
      // die();
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
    <form action="adduser.php" method="post">
      <label for="txtnombre">Nombre
        <input type="text" class="form-control" name="txtnombre" required>
      </label>
      <br>
      <label for="txtemail">Email
        <input type="text" class="form-control" name="txtemail" required>
      </label>
      <br>
      <label for="txtpass">Contraseña
        <input type="password" class="form-control" name="txtpass" required>
      </label>
      <br>
      <input type="submit" value="Guardar" name="submit" class="btn btn-success">
    </form>
  </div>
</body>
</html>