<?php
session_start();
include 'utiles/config.php';
include 'utiles/functions.php';

if (isset($_POST['submit'])) {
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  // Realizar validaciones aquí (campos vacíos, correo electrónico válido, etc.)

  if (isset($_POST['username']) && isset($_POST['password'])) 
    $username = $_POST['username'];
    $password = $_POST['password'];

  // Consulta para verificar si el usuario existe
  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    // El usuario existe, verificar contraseña
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
      // Contraseña correcta, iniciar sesión
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      redirect("index.php");
    } else {
      $error = "Contraseña incorrecta.";
    }
  } else {
    $error = "No se encontró ninguna cuenta con ese correo electrónico.";
  }
}

include 'templates/header.php';
?>

<!-- Formulario de inicio de sesión -->
<form action="login.php" method="post">
  <label for="email">Correo electrónico:</label>
  <input type="email" name="email" required>
  
  <label for="password">Contraseña:</label>
  <input type="password" name="password" required>
  
  <input type="submit" name="submit" value="Iniciar sesión">
</form>

<?php
include 'templates/footer.php';
?>
