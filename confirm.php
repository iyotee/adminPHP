<?php 


$user_ID = $_GET['id'];
$token = $_GET['token'];

require 'inc/db.php';


$q = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$q->execute(array($user_ID));
$userInfos = $q->fetch();
session_start();

if($userInfos->confirmation_token == $token){
    $pdo->prepare('UPDATE users SET confirmation_token = NULL, confirmed_at = NOW() WHERE id = ?')->execute(array($user_ID));
    $_SESSION['flash']['success'] = "Yihha ! Votre compte à bien été validé par HelpMeNow!";
    $_SESSION['auth'] = $userInfos;
    header('Location: myaccount.php');
}else{
    $_SESSION['flash']['danger'] = "Ce token n'est plus valide";
    header('Location: login.php');
}