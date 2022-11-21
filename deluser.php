<?php
  require_once 'includes/config.php';
  $msgresultado = "";
  $id = $_GET['id'];

  if(isset($_GET['id'])&&is_numeric($_GET['id'])){
    
    try{
      $sql = "delete from usuarios where id=:id";
      $query = $conexion->prepare($sql);
      $query->execute(['id'=>$id]);

      if($query){
        header("Location: listado.php?delete=true");
      }
    }catch(PDOException $ex){
      echo '<div class="alert alert-success">'."Ha fallado la eliminación del usuario<br>".$ex->getMessage().'</div>';
    }
  }else{
    echo '<div class="alert alert-success">'."Fallo al acceder al id del usuario<br>".$ex->getMessage().'</div>';
  }
?>