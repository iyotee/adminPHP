<?php

  if(session_status() == PHP_SESSION_NONE){
    session_start();
  }

?>
<?php include('inc/title.inc.php'); ?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Jérémy Noverraz, SwissLabs Group CEO">
    <title>HelpMeNow! - <?php echo htmlspecialchars($title);?></title>
    <!-- Favicons -->
    <link rel=icon href=imgs/favicon.png sizes="32x32" type="image/png">

    <!-- Custom styles for this template -->
    <link href="css/app.css" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar navbar-expand-md navbar-dark bg-primary fixed-top">
  <a class="navbar-brand" href="https://helpmenow.ch/admin2/<?php echo strtolower(htmlspecialchars($title));?>.php">HelpMeNow! - <?php echo htmlspecialchars($title);?> </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarsExampleDefault">
    <ul class="navbar-nav mr-auto">
      <?php if(isset($_SESSION['auth'])): ?>
        <li class="nav-item active"><a class="nav-link" href="logout.php">LogOut</a></li>
      <?php else: ?>
        <li class="nav-item active">
        <a class="nav-link" href="register.php">SignUp <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="login.php">LogIn</a>
      </li>
      <?php endif; ?>
    </ul>
<!--     <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
      <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
    </form> -->
  </div>
</nav>

<main role="main" class="container">
    
  <?php if(isset($_SESSION['flash'])): ?>
    <?php foreach($_SESSION['flash'] as $type => $message): ?>
      <div class="alert alert-<?= $type; ?>">
        <?= $message; ?>
      </div>
    <?php endforeach; ?>
    <?php unset($_SESSION['flash']); ?>
  <?php endif; ?>

