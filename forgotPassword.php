<?php require 'inc/header.php';?>
<?php ini_set('display_errors', 1); ?>
<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  require 'vendor/phpmailer/phpmailer/src/Exception.php';
  require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
  require 'vendor/phpmailer/phpmailer/src/SMTP.php';

  // Include autoload.php file
  require 'vendor/autoload.php';
?>

<?php
    if(!empty($_POST) && !empty($_POST['email'])){
        if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $errors['email'] = "Votre email n'est pas valide (alphanumérique)";
        }
        else{
            require_once 'inc/db.php';
            require_once 'inc/functions.php';
            $q = $pdo->prepare('SELECT * FROM users WHERE email = ? AND confirmed_at IS NOT NULL');
            $q->execute(array($_POST['email']));
            $userInfos = $q->fetch();

            if($userInfos){
                $reset_token = str_random(60);
                
                $q = $pdo->prepare('UPDATE users SET reset_token = ?, reseted_at = NOW() WHERE id = ?');
                $q->execute([$reset_token, $userInfos->id]);

                /* PHPMailer Class */
                // Create object of PHPMailer class
                $mail = new PHPMailer(true);

                $email = $_POST['email'];
                $subject = "Reinitialisation de votre mot de passe";
                $message = "Hello ! \n\nAfin de changer votre mot de passe, merci de cliquer sur le lien ci-dessous\n\nhttps://helpmenow.ch/admin2/reset.php?id={$userInfos->id}&token=$reset_token";

                try {
                $mail->isSMTP();
                $mail->Host = 'YOUR MAILING HOST';
                $mail->SMTPAuth = true;
                $mail->Username = 'YOURMAIL';
                $mail->Password = 'YOURPASSWORD';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Email d'envoie
                $mail->setFrom('YOUR SENT EMAIL NAME (SHOWN ON THE MAIL)');
                // Email de récéption
                $mail->addAddress($_POST['email']);

                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = "<h3>Votre adresse mail : $email <br>Sujet du message: $subject<br>Message : $message</h3>";

                $mail->send();
                        
                } 
                catch (Exception $e) {
                    echo "Hooohh mince ! Une erreur s'est produite, merci de nous contacter à hello@helpmenow.ch";
                }

                    $_SESSION['flash']['success'] = "Les instructions du rappel du mot de passe vous à été envoyées par email";
                    header('Location: login.php');
                    exit();
                }else{
                    $_SESSION['flash']['danger'] = "Arfffh ! Tu as vraiment perdu la mémoire ! Aucuns compte ne correspond à cette adresse mail.";

                }
            
            /* End of PHPMailClass */
        }
    }
?>


    <h1> Mot de passe oublié </h1>

    <form action="" method="POST">

        <div class="form-group">
            <label for="">Votre Email</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Votre email..."><br>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="btn_sendForgotPasswordForm">Envoyer</button>
        </div>
    </form>

<?php require 'inc/footer.php'; ?>
