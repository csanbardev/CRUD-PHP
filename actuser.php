<?php
  require_once 'includes/config.php';

  $msgresultado = "hola";
  $errores=array();
  $valnombre="";
  $valemail="";
  $valimagen="";

  if(isset($_POST['submit'])){
    $id = $_POST['id'];

    $nuevonombre = $_POST['txtnombre'];
    $nuevoemail = $_POST['txtemail'];
    $nuevaimagen="";

    $imagen = null;

    if(isset($_FILES['imagen'])&&!empty($_FILES['imagen']['tmp_name'])){

      if(!is_dir('uploads')){
        $dir = mkdir("uploads", 0777, true);
      }else{
        $dir=true;
      }

      if($dir){
        $nombrefichimg = time()."-".$_FILES['imagen']['name'];
        $movfichimg = move_uploaded_file($_FILES['imagen']['tmp_name'], "uploads/".$nombrefichimg);
        $imagen=$nombrefichimg;

        if($movfichimg){
          $imagencargada=true;
        }else{
          $imagencargada=false;
          $errores['imagen']="Error: La imagen no se ha cargado";
        }
      }
    }
    $nuevaimagen = $imagen;
    if(count($errores)==0){
      try{
        $sql = "update usuarios set nombre=:nombre, email=:email, imagen=:imagen where id=:id";
        $query = $conexion->prepare($sql);
        $query->execute(['nombre'=>$nuevonombre, 'email'=>$nuevoemail, 'imagen'=>$nuevaimagen, 'id'=>$id]);
  
        if($query){
          $msgresultado = '<div class="alert alert-success">'."El usuario se ha actualizado correctamente".'</div>';
        }
      }catch(PDOException $ex){
        $msgresultado = '<div class="alert alert-danger">'."El usuario no se ha actualizado <br>".
        $ex->getMessage().'</div>';
      }
    }

    

    $valnombre = $nuevonombre;
    $valemail=$nuevoemail;
    $valimagen=$nuevaimagen;
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
          $valimagen = $fila['imagen'];
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

    <form action="actuser.php" method="post" enctype="multipart/form-data">
      <label for="txtnombre">Nombre
        <input type="text" class="form-control" name="txtnombre" value=<?php echo $valnombre; ?> required>
      </label>
      <br>
      <label for="txtemail">Email
        <input type="text" class="form-control" name="txtemail" value=<?php echo $valemail; ?> required>
      </label>
      <br>
      <?php if($valimagen !=null){ ?>
        <br>Imagen de perfil: <img src="uploads/<?php echo $valimagen; ?>" width="60" /> <br>
      <?php }?>
      <label for="txtemail">Actualizar imagen de perfil
        <input type="file" class="form-control" name="imagen" required>
      </label>
      <br>
      <input type="hidden" name="id" value=<?php echo $id ?>>
      <br>
      <input type="submit" value="Actualizar" name="submit" class="btn btn-success">
    </form>
  </div>
</body>
</html>