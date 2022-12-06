<?php
require_once 'includes/config.php'; 
// requiero la aplicación del pdf
require_once 'vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;

$msgresultado = "";



try {
  $pagesize = 3; // numero de registros por página
  $pagina = 1; // muestra la página en la que estamos de inicio

  // cambio la página de inicio a partir del $_GET
  if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $pagina = $_GET['page'];
  }
  // indica el índice desde el que empieza mostrar
  $empezardesde = ($pagina - 1) * $pagesize;


  $sqltotal = "select * from usuarios";

  $resultado = $conexion->prepare($sqltotal);



  if ($resultado) {
    $msgresultado = '<div class="alert alert-success">' . "Consulta realizada correctamente" . '</div>';
    $resultado->execute(array());

    $numfilas = $resultado->rowCount();

    $totalpaginas = ceil($numfilas / $pagesize);

    // imprimimos en pdf cuando lo solicite
    if(isset($_POST['submit'])&&$resultado->rowCount()!=0){
      ob_end_clean();
      $html = "hola";
      $html2pdf = new Html2Pdf();
      $html2pdf->writeHTML("<h1>Listado de usuarios</h1>");
      while($fila = $resultado->fetch()){
        $html2pdf->writeHTML("Nombre: ".$fila['nombre']."<br>");
        $html2pdf->writeHTML("Apellidos: ".$fila['apellidos']."<br>");
        $html2pdf->writeHTML("Biografía: ".$fila['biografia']."<br>");
        $html2pdf->writeHTML("Email: ".$fila['email']."<br>");
        // $html2pdf->writeHTML("Imagen de perfil: "."<img src='uploads/".$fila['imagen']."'/>"."<br>");
        $html2pdf->writeHTML("<br>");
      }
      $html2pdf->output();
      ob_end_clean();
    }

    $resultado->closeCursor(); // cierro el cursor

    $sqllimite = "select * from usuarios limit $empezardesde, $pagesize";
    $resultado = $conexion->prepare($sqllimite);
    $resultado->execute(array());
  }
  if (isset($_GET['delete'])&&$_GET['delete']=="true") {
    $msgresultado = '<div class="alert alert-success">' . "Usuario eliminado" . '</div>';
  }else if(isset($_GET['delete'])&&$_GET['delete']=="false"){
    $msgresultado = '<div class="alert alert-danger">' . "Usuario no eliminado" . '</div>';
  }
} catch (PDOException $ex) {
  $msgresultado = '<div class="alert alert-danger">' . "No se ha hecho la consulta" . '</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<?php require_once 'includes/head.php' ?>

<body>
  <div class="container center">
    <h1>Listar usuarios</h1>
    <?php echo $msgresultado?>

    <!-- Tabla del listado-->
    <table class="table table-striped">
      <tr>
        <th>Nombre</th>
        <th>Email</th>
        <th>Foto</th>
        <th>Operaciones</th>
      </tr>
      <?php

      while ($fila = $resultado->fetch()) {
        echo '<tr>';
        echo '<td>' . $fila['nombre'] . '</td>';
        echo '<td>' . $fila['email'] . '</td>';
        echo $fila['imagen'] != null ? "<td><img src='uploads/{$fila['imagen']}' width='40'/>{$fila['imagen']}</ td>" : "<td>----</td>";
        echo '<td>' . '<a href=detalle.php?id=' . $fila['id'] . '>Detalle</a> <a href=actuser.php?id=' . $fila['id'] . '>Editar</a> <a href=deluser.php?id=' . $fila['id'] . '>Eliminar</a>' . '</td>';
        echo '</tr>';
      }

      ?>

    </table>
    <?php echo $resultado->rowCount()==0? '<br><h5 class="text-center">No hay usuarios registrados</h5>':'' ?>
    
    <!-- Bloque de paginación del listado -->
    <ul class="pagination" <?php echo $resultado->rowCount()<=$pagesize? 'style= "display: none;"': ""?>>
      <li class="page-item">
        <a class="page-link" 
          <?php
            if (isset($_GET['page']) && $_GET['page'] > 1) {
              $page = $_GET['page'] - 1;
              echo 'href="listado.php?page=' . $page . '"';
            } else {
              echo 'href="listado.php?page=' . $totalpaginas . '"';
            }
          ?>.">
        Anterior</a>
      </li>
      <?php
      for ($i = 1; $i <= $totalpaginas; $i++) {
        echo '<li class="page-item"><a class="page-link" href="listado.php?page=' . $i . '">' . $i . '</a></li>';
      }
      ?>
      <li class="page-item">
        <a class="page-link" 
          <?php
            if (isset($_GET['page']) && $_GET['page'] < $totalpaginas) {
              $page = $_GET['page'] + 1;
              echo 'href="listado.php?page=' . $page . '"';
            } else if (isset($_GET['page']) && $_GET['page'] == $totalpaginas) { // si está ya en la última página
              echo 'href="listado.php?page=1"'; // se vuelve a la primera
            } else { // se encuentra al inicio, solo puede pasar a una página 2 (si hay)
              echo 'href="listado.php?page=2"';
            }
          ?>.">
        Siguiente</a>
      </li>
    </ul>
    <br>
    <!-- Bloque de imprimir en PDF -->
    <form action="listado.php" method="post" <?php echo $resultado->rowCount()<=$pagesize? 'style= "display: none;"': ""?>>
      <input name="submit" class="btn btn-success" type="submit" value="Imprimir en PDF">
    </form>
  </div>
</body>

</html>
