<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite07ba1bce5f4c54c76dc6418918c5099
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite07ba1bce5f4c54c76dc6418918c5099::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite07ba1bce5f4c54c76dc6418918c5099::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}