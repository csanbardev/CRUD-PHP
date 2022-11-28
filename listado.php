<?php
require_once 'includes/config.php';

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

    // si pide que imprima pdf
    if (isset($_POST['submit'])) {
      ob_start();
      require 'fpdf/fpdf.php';

      // creo el documento
      $pdf = new FPDF();
      $pdf->AddPage();

      // le añado el texto
      $pdf->SetTitle("Listado de usuarios", true);
      $pdf->SetFont('Arial', '', 12);
      while ($fila = $resultado->fetch()) {
        $pdf->MultiCell(
          0,
          10,
          "Nombre: " . $fila['nombre'],
          0,
          1
        );
        $pdf->MultiCell(
          0,
          10,
          "Email: " . $fila['email'],
          0,
          1
        );
        $pdf->MultiCell(
          0,
          10,
          "Imagen de perfil: " . $fila['imagen'] != null ? $fila['imagen'] : "----",
          0,
          1
        );
        if ($fila['imagen'] != null) {
          $pdf->Ln();
          $pdf->Image("uploads/" . $fila['imagen'], null, null, 40);
        }
        $pdf->Ln();
      }

      // genero el archivo pdf
      $pdf->Output('I', 'prueba2.pdf', true);
      ob_end_flush();
    }

    $resultado->closeCursor(); // cierro el cursor

    $sqllimite = "select * from usuarios limit $empezardesde, $pagesize";
    $resultado = $conexion->prepare($sqllimite);
    $resultado->execute(array());
  }
  if (isset($_GET['delete'])) {
    $msgresultado = '<div class="alert alert-success">' . "Usuario eliminado" . '</div>';
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
    <?php echo $msgresultado; ?>

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
        echo '<td>' . '<a href=actuser.php?id=' . $fila['id'] . '>Editar</a> <a href=deluser.php?id=' . $fila['id'] . '>Eliminar</a>' . '</td>';
        echo '</tr>';
      }

      ?>

    </table>


    <ul class="pagination">
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
    <form action="listado.php" method="post">
      <input name="submit" class="btn btn-success" type="submit" value="Imprimir en PDF">
    </form>
  </div>
</body>

</html>
