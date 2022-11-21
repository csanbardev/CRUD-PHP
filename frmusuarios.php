<html>
  <?php require 'includes/head.php'; ?>
<body>
<?php require 'includes/valform.php'; ?>

  <div class="container">
    <h1>Datos de usuario</h1>
    <br>

    <form action="frmusuarios.php" method="post" enctype="multipart/form-data">
      <label for="name">Nombre:
        <input type="text" name="name" class="form-control"
          value=<?php echo isset($_POST['name'])? $_POST['name']:""  ?> >
          <?php echo mostrar_error($errors, "name"); ?>
      </label>
      <br/>
      <label for="surname"> Apellidos:
        <input type="text" name="surname" class="form-control"
          value=<?php echo isset($_POST['surname'])? $_POST['surname']:""  ?> >
        <?php echo mostrar_error($errors, "surname"); ?>
      </label>
      <br/>
      <label for="bio">Biografia:
        <textarea name="bio" class="form-control">
          <?php if(isset($_POST["bio"])){ echo $_POST["bio"]; } ?> </textarea>
        <?php echo mostrar_error($errors, "bio"); ?>
      </label>
      <br/>
      <label for="email">Correo:
        <input type="email" name="email" class="form-control"
        value=<?php echo isset($_POST['email'])? $_POST['email']:""  ?> >
        <?php echo mostrar_error($errors, "email"); ?>
      </label>
      <br/>
      <label for="image">Imagen:
        <input type="file" name="image" class="form-control" />
        <?php echo mostrar_error($errors, "image"); ?>
      </label>
      <br/>
      <label for="password">Contraseña:
        <input type="password" name="password" class="form-control"
          <?php if(isset($_POST["password"]))
            { echo "value='{$_POST["password"]}'";} ?> />
        <?php echo mostrar_error($errors, "password"); ?>
      </label>
      <br/>
      
      <input type="submit" value="Enviar" name="submit" class="btn btn-success" />
    </form>
  </div>

</body>
</html>