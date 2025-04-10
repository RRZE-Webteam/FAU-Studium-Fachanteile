<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4077359738e681d44fbf3663b6f906a3
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Fau\\DegreeProgram\\Shares\\' => 25,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Fau\\DegreeProgram\\Shares\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4077359738e681d44fbf3663b6f906a3::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4077359738e681d44fbf3663b6f906a3::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit4077359738e681d44fbf3663b6f906a3::$classMap;

        }, null, ClassLoader::class);
    }
}
