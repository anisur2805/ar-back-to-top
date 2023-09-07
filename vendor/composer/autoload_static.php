<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4aecd590e68a40c3bf6b37f6fe8e3901
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'Appsero\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Appsero\\' => 
        array (
            0 => __DIR__ . '/..' . '/appsero/client/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4aecd590e68a40c3bf6b37f6fe8e3901::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4aecd590e68a40c3bf6b37f6fe8e3901::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit4aecd590e68a40c3bf6b37f6fe8e3901::$classMap;

        }, null, ClassLoader::class);
    }
}
