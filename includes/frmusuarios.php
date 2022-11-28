

    <form action="adduser.php" method="post" enctype="multipart/form-data">
      <label for="txtnombre">Nombre:
        <input type="text" name="txtnombre" class="form-control" >
          <?php echo mostrar_error($errors, "txtnombre"); ?>
      </label>
      <br/>
      <label for="txtapellidos"> Apellidos:
        <input type="text" name="txtapellidos" class="form-control" >
        <?php echo mostrar_error($errors, "txtapellidos"); ?>
      </label>
      <br/>
      <label for="txtbio">Biografia:
        <textarea name="txtbio" class="form-control"></textarea>
        <?php echo mostrar_error($errors, "txtbio"); ?>
      </label>
      <br/>
      <label for="txtemail">Correo:
        <input type="email" name="txtemail" class="form-control" >
        <?php echo mostrar_error($errors, "txtemail"); ?>
      </label>
      <br/>
      <label for="image">Imagen:
        <input type="file" name="imagen" class="form-control" />
        <?php echo mostrar_error($errors, "image"); ?>
      </label>
      <br/>
      <label for="txtpass">Contraseña:
        <input type="password" name="txtpass" class="form-control" />
        <?php echo mostrar_error($errors, "txtpass"); ?>
      </label>
      <br/>
      
      <input type="submit" value="Guardar" name="submit" class="btn btn-success" />
    </form>
  