<?php
    ini_set('display_errors', 1);
    require 'inc/functions.php';
    logged_only();

    if(!empty($_POST) && isset($_POST['btn_sendMofifyPasswordForm'])){
        
        if(empty($_POST['password']) || ($_POST['password'] != $_POST['cpassword'])){
            $_SESSION['flash']['danger'] = "Les mots de passe ne correspondent pas";
        }else{
            $user_id = $_SESSION['auth']->id;
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            require_once 'inc/db.php'; 
            $q = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
            $q->execute(array($password, $user_id));
            $_SESSION['flash']['success'] = "Votre mot de passe à bien été mis à jour";
        }
    }
    if(!empty($_POST) && isset($_POST['btn_sendMofifyUsernameForm'])){

        require_once 'inc/db.php'; 
        
        if(empty($_POST['username'])){
            $_SESSION['flash']['danger'] = "L'utilisateur ne peux pas être vide";
        }
        else{
            if(!preg_match('/^[a-z\d_]{2,20}$/i',$_POST['username'])){
                $_SESSION['flash']['danger'] = "Votre username n'est pas valide (alphanumérique)";
            }
            else{
                $q = $pdo->prepare("SELECT * FROM users WHERE username = ?");
                $q->execute(array($_POST['username']));
                $userInfos = $q->fetch();

                if($userInfos->username){
                    if($userInfos->username == $_POST['username'] && $userInfos->id == $_SESSION['auth']->id){
                        $_SESSION['flash']['danger'] = "Le username est le même que celui déjà enregistré dans notre base de données";
                    }elseif($userInfos->username == $_POST['username']){
                        $_SESSION['flash']['danger'] = "Le username est déjà prit";

                    }
                }
                else{
                    $user_id = $_SESSION['auth']->id;
                    $username = $_POST['username'];
                    $q = $pdo->prepare('UPDATE users SET username = ? WHERE id = ?');
                    $q->execute(array($username, $user_id));
                    $_SESSION['auth']->username = $username;
                    $_SESSION['flash']['success'] = "Votre username à bien été mis à jour";
                }
            }
        }
    }
                





    require 'inc/header.php'; 
?>

    <h1>Welcome <?= $_SESSION['auth']->username; ?> </h1>
    <hr>
    <h5>Changer le mot de passe</h5>
    <form action="" method="POST">
        <div class="form-row">
            <div class="col-md">
                <input type="password" class="form-control" name="password" id="password" placeholder="Nouveau mot de passe..." ><br>
            </div>
            <div class="col-md">
                <input type="password" class="form-control" name="cpassword" id="cpassword" placeholder="Confirmer le nouveau mot de passe..."><br>
            </div>
            <div class="col-lg">
                <button type="submit" class="btn btn-primary" name="btn_sendMofifyPasswordForm">Save</button>
            </div>
        </div>
    </form>
    <br>
    <h5>Changer le username</h5>
    <form action="" method="POST">
        <div class="form-row">
            <div class="col">
                <input type="text" class="form-control" name="username" id="username" placeholder="<?= $_SESSION['auth']->username;?>" ><br>
            </div>
            <div class="form-col">
                <button type="submit" class="btn btn-primary" name="btn_sendMofifyUsernameForm">Save</button>
            </div>
        </div>

    </form>



<?php require 'inc/footer.php'; ?>
