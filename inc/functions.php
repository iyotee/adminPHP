<?php
    function debug($variable){
        echo '<pre>' . print_r($variable, true) . '</pre>';
    }

    //Homemade Function to generate a token
    function str_random($length){
        $alphabet = "0123456789qwertzuiopasdfghjklyxcvbnmQWERTZUIOPASSDFGHJKLYXCVBNM";
        return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length); // return the generated token
    }

    function logged_only(){
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }
        if(!isset($_SESSION['auth'])){
            $_SESSION['flash']['danger'] = "Vous n'avez pas la permissions d'accéder à cette page";
            header('Location: login.php');
            exit();
        }
    }

    function  reconnect_from_cookie(){   
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }
        if(isset($_COOKIE['remember']) && !isset($_SESSION['auth'])){
            require_once 'inc/db.php';
            if(!isset($pdo)){
                global $pdo;
            }
            $remember_token = $_COOKIE['remember'];
            $parts = explode('==', $remember_token);
            $user_ID = $parts[0];
            $q = $pdo->prepare('SELECT * FROM users WHERE id = ?'); 
            $q->execute(array($user_ID));
            $userInfos = $q->fetch();
            if($userInfos){
                $expected = $user_ID . '=='. $userInfos->remember_token . sha1($user_ID . 'idealponytail');
                if($expected == $remember_token){
                    session_start();
                    $_SESSION['auth'] = $userInfos;
                    setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 7);
                }
            }else{
                setcookie('remember', NULL, -1);
            }
        }else{
            setcookie('remember', NULL, -1);
        }
    }