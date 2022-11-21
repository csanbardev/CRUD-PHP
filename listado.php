<?php
  require_once 'includes/config.php';

  $msgresultado = "";

  try{
    $sql = "select * from usuarios";

    $resultsquery = $conexion->query($sql);

    if($resultsquery){
      $msgresultado = '<div class="alert alert-success">'."Consulta realizada correctamente".'</div>';
    }
    if(isset($_GET['delete'])){
      $msgresultado = '<div class="alert alert-success">'."Usuario eliminado".'</div>';
    }
  }catch(PDOException $ex){
    $msgresultado = '<div class="alert alert-danger">'."No se ha hecho la consulta".'</div>';
  }
?>

<!DOCTYPE html>
<html lang="en">
<?php require_once 'includes/head.php' ?>
<body>
  <div class="container center">
    <h1>Listar usuarios</h1>
    <?php echo $msgresultado; ?>

    <table class="table table-striped">
      <tr>
        <th>Nombre</th>
        <th>Email</th>
        <th>Operaciones</th>
      </tr>
      <?php 
        while($fila = $resultsquery->fetch()){
          echo '<tr>';
          echo '<td>'.$fila['nombre'].'</td>';
          echo '<td>'.$fila['email'].'</td>';
          echo '<td>'.'<a href=actuser.php?id='.$fila['id'].'>Editar</a>'.'</td>';
          echo '<td>'.'<a href=deluser.php?id='.$fila['id'].'>Eliminar</a>'.'</td>';
          echo '</tr>';
        }

      ?>
    </table>
  </div>
</body>
</html>