<?php
session_start();
require "config.php";

$identifier = trim($_POST['identifier'] ?? '');
$password   = $_POST['password'] ?? '';

$student = null;

if ($identifier !== '' && $password !== '') {
    if (ctype_digit($identifier)) {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE Student_id = :id");
        $stmt->execute([':id' => $identifier]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE email = :email");
        $stmt->execute([':email' => $identifier]);
    }
    $row = $stmt->fetch();

    if ($row && password_verify($password, $row['password'])) {
        $student = $row;
        $_SESSION['student_id'] = $row['Student_id'];
        $_SESSION['full_name']  = $row['Full_Name'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Log in — CampusConnect 2026</title>
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
    <?php if ($student): ?>

      <div class="ic-card status-card success">
        <div class="pin-row"></div>
        <h1>Welcome to CampusConnect!</h1>
        <p>You're signed in as <?= htmlspecialchars($student['Full_Name']) ?>.</p>
        <dl class="badge-details">
          <div><dt>Student ID</dt><dd>#<?= htmlspecialchars($student['Student_id']) ?></dd></div>
          <div><dt>College</dt><dd><?= htmlspecialchars($student['College_Name']) ?></dd></div>
          <div><dt>Event</dt><dd><?= htmlspecialchars($student['Event']) ?></dd></div>
        </dl>
        <a class="btn" href="logout.php">Log out</a>
      </div>

    <?php else: ?>

      <div class="ic-card status-card error">
        <div class="pin-row"></div>
        <h1>Invalid username or password.</h1>
        <p>Double check your email or student ID and password, then try again.</p>
        <a class="btn" href="login.html">Back to login</a>
      </div>

    <?php endif; ?>
  </main>

</div>
</body>
</html>
