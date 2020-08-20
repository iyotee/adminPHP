<?php require 'inc/header.php'; ?>
<?php require 'inc/functions.php'; ?>
<?php session_start(); ?>
<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  require 'vendor/phpmailer/phpmailer/src/Exception.php';
  require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
  require 'vendor/phpmailer/phpmailer/src/SMTP.php';

  // Include autoload.php file
  require 'vendor/autoload.php';
?>

<?php ini_set('display_errors', 1); ?>


<?php
    if(!empty($_POST)){
      $errors = array();
      require_once 'inc/db.php';
      
        if(empty($_POST['username']) || !preg_match('/^[a-z\d_]{2,20}$/i',$_POST['username'])){
            $errors['username'] = "Votre username n'est pas valide (alphanumérique)";
        }else{
            $q = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $q->execute(array($_POST['username']));
            $userID = $q->fetch();
            if($userID){
                $errors['username'] = "Ce username est déjà pris";
            }

        }

        if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $errors['email'] = "Votre email n'est pas valide (alphanumérique)";
        }
        else{
            $q = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $q->execute(array($_POST['email']));
            $userEmail = $q->fetch();
            if($userEmail){
                $errors['email'] = "Ce email est déjà pris";
            }

        }


        if(empty($_POST['password'])){
            $errors['password'] = "Votre mot de passe n'est pas valide";
        }

        if($_POST['cpassword'] != $_POST['password']){
            $errors['password'] = "Les mots de passes ne correspondent pas";
        }

        if(empty($errors)){

            $q = $pdo->prepare("INSERT INTO users SET username = ?, email = ?, password = ?, confirmation_token = ?");
            $cryptedPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $token = str_random(60);
            $q->execute(array($_POST['username'], $_POST['email'], $cryptedPassword, $token));
            $user_ID = $pdo->lastInsertId();

            /* PHPMailer Class */
            // Create object of PHPMailer class
            $mail = new PHPMailer(true);


            if (isset($_POST['btn_sendSignUpForm'])) {
                $email = $_POST['email'];
                $subject = "Confirmation d'inscription";
                $message = "Afin de confirmer votre adresse mail merci de cliquer sur le lien ci-dessous\n\nhttps://helpmenow.ch/admin2/confirm.php?id=$user_ID&token=$token";

                try {
                $mail->isSMTP();
                $mail->Host = 'mail.infomaniak.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'YOUR EMAIL';
                $mail->Password = 'YOUR PASSWORD';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Email d'envoie
                $mail->setFrom('contact@helpmenow.ch');
                // Email de récéption
                $mail->addAddress($_POST['email']);

                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = "<h3>Votre adresse mail : $email <br>Sujet du message: $subject<br>Message : $message</h3>";

                $mail->send();
                
                } catch (Exception $e) {
                    echo "Une erreur s'est produite, merci de nous contacter à hello@helpmenow.ch";
                }
            }
            /* End of PHPMailClass */
            $_SESSION['flash']['success'] = 'Un email de confirmation vous a été envoyé pour valider votre compte. Vous pouvez maintenant fermer cette fenêtre.';
            header('Location: login.php');
            exit();
        }
    }
    
?>


<div class="container-md">

    <h1>SignUp</h1>

    <?php if(!empty($errors)): ?>
        <div class="alert alert-danger">
            <p>Vous n'avez pas rempli le formulaire correctement</p>
            <ul>
                <?php foreach($errors as $error): ?>
                    <li><?= $error; ?></li>                
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="" method="POST">

        <div class="form-group">
            <input type="text" class="form-control" name="username" id="username" placeholder="Votre username..."><br>
        </div>
        <div class="form-group">
            <input type="email" class="form-control" name="email" id="email" placeholder="Votre email..."><br>
        </div>
        <div class="form-group">
            <input type="password" class="form-control" name="password" id="password" placeholder="Votre mot de passe..." ><br>
        </div>
        <div class="form-group">
            <input type="password" class="form-control" name="cpassword" id="cpassword" placeholder="Confirmer mot de passe..."><br>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="btn_sendSignUpForm">SignUp</button>
        </div>
    </form>

</div>



<?php require 'inc/footer.php'; ?>
