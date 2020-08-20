<?php
    session_start();
    setcookie('remember', NULL, -1);
    unset($_SESSION['auth']);
    $_SESSION['flash']['success'] = "Yihaa ! Vous avez bien été déconnecté de votre compte";
    header('Location: login.php');
?>