<?php
require "config.php";

$full_name    = trim($_POST['full_name'] ?? '');
$email        = trim($_POST['email'] ?? '');
$college_name = trim($_POST['college_name'] ?? '');
$location     = trim($_POST['location'] ?? '');
$event        = trim($_POST['event'] ?? '');
$password     = $_POST['password'] ?? '';

$errors = [];

if ($full_name === '' || $email === '' || $college_name === '' || $location === '' || $event === '' || $password === '') {
    $errors[] = "All fields are required.";
}
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Enter a valid email address.";
}
if ($password !== '' && strlen($password) < 6) {
    $errors[] = "Password must be at least 6 characters.";
}

$new_id = null;

if (empty($errors)) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    try {
        $stmt = $pdo->prepare(
            "INSERT INTO students (Full_Name, email, College_Name, Location, Event, password)
             VALUES (:full_name, :email, :college_name, :location, :event, :password)"
        );
        $stmt->execute([
            ':full_name'    => $full_name,
            ':email'        => $email,
            ':college_name' => $college_name,
            ':location'     => $location,
            ':event'        => $event,
            ':password'     => $hashed_password,
        ]);
        $new_id = $pdo->lastInsertId();
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $errors[] = "That email is already registered. Try logging in instead.";
        } else {
            $errors[] = "Something went wrong while saving your details. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register — CampusConnect 2026</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="page narrow">

  <header class="topbar">
    <a class="brand" href="index.html">CampusConnect<span class="brand-year">2026</span></a>
  </header>

  <main class="form-main">
    <?php if (empty($errors)): ?>

      <div class="ic-card status-card success">
        <div class="pin-row"></div>
        <h1>You're registered</h1>
        <p>Your student badge has been created. Save these details, you'll need them to log in.</p>
        <dl class="badge-details">
          <div><dt>Student ID</dt><dd>#<?= htmlspecialchars($new_id) ?></dd></div>
          <div><dt>Name</dt><dd><?= htmlspecialchars($full_name) ?></dd></div>
          <div><dt>Event</dt><dd><?= htmlspecialchars($event) ?></dd></div>
        </dl>
        <a class="btn primary" href="login.html">Log in now</a>
      </div>

    <?php else: ?>

      <div class="ic-card status-card error">
        <div class="pin-row"></div>
        <h1>Registration failed</h1>
        <ul class="error-list">
          <?php foreach ($errors as $err): ?>
            <li><?= htmlspecialchars($err) ?></li>
          <?php endforeach; ?>
        </ul>
        <a class="btn" href="register.html">Back to register</a>
      </div>

    <?php endif; ?>
  </main>

</div>
</body>
</html>
