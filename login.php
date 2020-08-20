<?php require 'inc/header.php';?>
<?php ini_set('display_errors', 1); ?>
<?php require_once 'inc/functions.php'; ?>

<?php
    reconnect_from_cookie();
    if(isset($_SESSION['auth'])){
        header('Location: myaccount.php');
        exit();
    }
    if(!empty($_POST) && !empty($_POST['username']) && !empty($_POST['password'])){
        require_once 'inc/db.php';

        $q = $pdo->prepare('SELECT * FROM users WHERE (username = :username OR email = :username) AND confirmed_at IS NOT NULL');
        $q->execute(['username' => $_POST['username']]);
        $userInfos = $q->fetch();
        $user_ID = $userInfos->id;

        if(password_verify($_POST['password'], $userInfos->password)){
            session_start();
            $_SESSION['auth'] = $userInfos;
            $_SESSION['flash']['success'] = "Youppii ! Vous êtes maintenant connecté";
            if($_POST['remember']){
                $remember_token = str_random(250);
                $q = $pdo->prepare('UPDATE users SET remember_token = ? WHERE id = ?');
                $q->execute(array($remember_token, $user_ID));
                setcookie('remember', $user_ID . '=='. $remember_token . sha1($user_ID . 'idealponytail'), time() + (60 * 60 * 24 * 7 ));
            }
            header('Location: myaccount.php');
            exit();
        }else{
            $_SESSION['flash']['danger'] = "Arrffh ! L'identifiant ou le mot de passe sont incorrect. Tu peux t'aider à l'aide de l'outil Mot de passe oublié ? se trouvant plus bas.";
        }
    }
?>


    <h1> LogIn </h1>

    <form action="" method="POST">

        <div class="form-group">
            <label for="">Username ou email</label>
            <input type="text" class="form-control" name="username" id="username" placeholder="Votre Username ou email..."><br>
        </div>
        <div class="form-group">
            <label for="">Mot de passe <i><a href="forgotPassword.php">Mot de passe oublié ?</a></i></label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Votre mot de passe..." ><br>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="remember" value="1"/>  
                Se sourvenir de moi
            </label>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="btn_sendLogInForm">LogIn</button>
        </div>
    </form>

<?php require 'inc/footer.php'; ?>