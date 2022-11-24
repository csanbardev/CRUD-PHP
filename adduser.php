<?php
  require_once 'includes/config.php';

  $msgresultado="";
  $errores=array();

  if(isset($_POST['submit'])&&!empty($_POST)){
    $nombre = $_POST['txtnombre'];
    $password = sha1($_POST['txtpass']);
    $email = $_POST['txtemail'];
    $imagen = null;

    if(isset($_FILES['imagen'])&&!empty($_FILES['imagen']['tmp_name'])){

      if(!is_dir('uploads')){
        $dir = mkdir("uploads", 0777, true);
      }else{
        $dir = true;
      }

      if($dir){
        $nombrefichimg = time()."-".$_FILES['imagen']['name'];
        $movfichimg = move_uploaded_file($_FILES['imagen']['tmp_name'],"uploads/".$nombrefichimg);
        $imagen=$nombrefichimg;
      }

      if($movfichimg){
        $imagencargada=true;
      }else{
        $imagencargada=false;
        $errores['imagen']="Error: La imagen no se ha cargado";
      }
    }

    if(count($errores)==0){
      try{
        $sql = "insert into usuarios(nombre, pass, email, imagen) values (:nombre, :password, :email, :imagen)";
  
        $query = $conexion->prepare($sql);
  
        $query->execute(['nombre' => $nombre, 'password'=> $password, 'email'=>$email, 'imagen'=>$imagen]);
  
        if($query){
          $msgresultado = '<div class="alert alert-success">'."Usuario registrado correctamente".'</div>';
        }
      }catch(PDOException $ex){
        $msgresultado = '<div class="alert alert-danger">'."Fallo al registrar <br>".$ex->getMessage().'</div>';
        // die();
      }
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
    <form action="adduser.php" method="post" enctype="multipart/form-data">
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
      <label for="imagen">Imagen
        <input type="file" class="form-control" name="imagen">
      </label>
      <br>
      <input type="submit" value="Guardar" name="submit" class="btn btn-success">
    </form>
  </div>
</body>
</html>