<?php 
    require 'inc/header.php';

?>
<?php ini_set('display_errors', 1); ?>

<?php 
    if(isset($_GET['id']) && isset($_GET['token'])){
        $user_ID = $_GET['id'];
        $token = $_GET['token'];
        require_once 'inc/db.php';
        $q = $pdo->prepare('SELECT * FROM users WHERE id = ? AND reset_token IS NOT NULL AND reset_token = ? AND reseted_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)');
        $q->execute([$user_ID, $token]);
        $userInfos = $q->fetch();

        if($userInfos){
            if(isset($_POST['btn_sendModifyPasswordForm'])){
                if(!empty($_POST['password']) && $_POST['cpassword'] == $_POST['password']){
                    require_once 'inc/db.php';
                    require_once 'inc/functions.php';
                    $cryptedPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
                    $q = $pdo->prepare('UPDATE users SET password = ?, reset_at = NULL, reset_token = NULL');
                    $q->execute(array($cryptedPassword));
                    session_start();
                    $_SESSION['flash']['success'] = "Youpi ! Votre mot de passe à bien été changé.";
                    $_SESSION['auth'] = $userInfos;
                    header('Location: myaccount.php');
                    exit();

    
                }else{
                    $_SESSION['flash']['danger'] = "Hohhhh nonn ! Les mots de passe ne correspondent pas.";
                }
    
            }

        }else{
            session_start();
            $_SESSION['flash']['danger'] = "Ce token n'est pas valide";
            header('Location: login.php');
            exit();
        }
    }else{
        header('Location: login.php');
        exit();
    }
?>

    <h1> Changer le mot de passe </h1>

    <form action="" method="POST">
        <div class="form-group">
            <label for="">Mot de passe</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Votre mot de passe..." ><br>
        </div>

        <div class="form-group">
            <label for="">Confirmer le Mot de passe </label>
            <input type="password" class="form-control" name="cpassword" id="cpassword" placeholder="Confirmez votre mot de passe..." ><br>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="btn_sendModifyPasswordForm">Save</button>
        </div>
    </form>

<?php require 'inc/footer.php'; ?>