<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0691d92e4a7f8796d2beeae99c7ad54c
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PhpOffice\\PhpWord\\' => 18,
        ),
        'L' => 
        array (
            'Laminas\\Escaper\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PhpOffice\\PhpWord\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpoffice/phpword/src/PhpWord',
        ),
        'Laminas\\Escaper\\' => 
        array (
            0 => __DIR__ . '/..' . '/laminas/laminas-escaper/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0691d92e4a7f8796d2beeae99c7ad54c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0691d92e4a7f8796d2beeae99c7ad54c::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit0691d92e4a7f8796d2beeae99c7ad54c::$classMap;

        }, null, ClassLoader::class);
    }
}
