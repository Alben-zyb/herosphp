<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite49851fe2d5d7f24da155a5b802ac199
{
    public static $files = array (
        '8d46b3d7cb038bc787c3c95b79fb488d' => __DIR__ . '/..' . '/herosphp/framework/src/functions.core.php',
        '707cafee30ed37d22eaa089d3a6607f5' => __DIR__ . '/..' . '/herosphp/framework/src/Heros.const.php',
        '6e67798cfd950d15538c015b068ae3ba' => __DIR__ . '/../..' . '/app/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'h' => 
        array (
            'herosphp\\' => 9,
        ),
        'c' => 
        array (
            'client\\' => 7,
        ),
        'a' => 
        array (
            'app\\' => 4,
        ),
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'herosphp\\' => 
        array (
            0 => __DIR__ . '/..' . '/herosphp/framework/src',
        ),
        'client\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app/client',
        ),
        'app\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app/modules',
        ),
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite49851fe2d5d7f24da155a5b802ac199::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite49851fe2d5d7f24da155a5b802ac199::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInite49851fe2d5d7f24da155a5b802ac199::$classMap;

        }, null, ClassLoader::class);
    }
}
