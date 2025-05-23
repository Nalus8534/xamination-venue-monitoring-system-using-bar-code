<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc603e47bd073a3cf46a79310de29877f
{
    public static $files = array (
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'U' => 
        array (
            'User\\ExaminationVenueMonitoringSystem\\' => 38,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'User\\ExaminationVenueMonitoringSystem\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
    );

    public static $prefixesPsr0 = array (
        'S' => 
        array (
            'Smalot\\PdfParser\\' => 
            array (
                0 => __DIR__ . '/..' . '/smalot/pdfparser/src',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc603e47bd073a3cf46a79310de29877f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc603e47bd073a3cf46a79310de29877f::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitc603e47bd073a3cf46a79310de29877f::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitc603e47bd073a3cf46a79310de29877f::$classMap;

        }, null, ClassLoader::class);
    }
}
