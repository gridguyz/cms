<?php

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir( dirname( __DIR__ ) );

// Setup autoloading
include 'vendor/autoload.php';

function isDirButNotDot( $current, $_, $iterator )
{
    return $current->isDir() && ! $iterator->isDot();
}

$phpunit = 'vendor' . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . 'phpunit';
$vendors = new CallbackFilterIterator(
    new FilesystemIterator( 'vendor' ),
    'isDirButNotDot'
);

echo PHP_EOL;

foreach ( $vendors as $vendor )
{
    $vendorName = $vendor->getFilename();
    $vendorPath = $vendor->getPathname();

    $packages = new CallbackFilterIterator(
        new FilesystemIterator( $vendorPath ),
        'isDirButNotDot'
    );

    foreach ( $packages as $package )
    {
        $packageName = $package->getFilename();
        $packagePath = $package->getPathname();
        $vndnpckName = $vendorName . '/' . $packageName;
        $packageJson = $packagePath . DIRECTORY_SEPARATOR . 'composer.json';
        $phpunitXml  = $packagePath . DIRECTORY_SEPARATOR . 'tests'
                                    . DIRECTORY_SEPARATOR . 'phpunit.xml';

        if ( ! file_exists( $packageJson ) )
        {
            echo 'Skipping ', $vndnpckName, ': composer.json not found', PHP_EOL;
            continue;
        }

        $packageMeta = @ json_decode( file_get_contents( $packageJson ), true );

        if ( empty( $packageMeta ) )
        {
            echo 'Skipping ', $vndnpckName, ': composer.json is not valid', PHP_EOL;
            continue;
        }

        if ( empty( $packageMeta['name'] ) ||
             strtolower( $packageMeta['name'] ) != strtolower( $vndnpckName ) )
        {
            echo 'Skipping ', $vndnpckName, ': package name not match', PHP_EOL;
            continue;
        }

        if ( empty( $packageMeta['type'] ) ||
             ! preg_match( '/^gridguyz-modules?$/', $packageMeta['type'] ) )
        {
            echo 'Skipping ', $vndnpckName, ': package is not a gridguyz-module', PHP_EOL;
            continue;
        }

        if ( ! file_exists( $phpunitXml ) )
        {
            echo 'Skipping ', $vndnpckName, ': tests/phpunit.xml not found', PHP_EOL;
            continue;
        }

        $pipes   = array();
        $process = proc_open(
            $cmd = sprintf(
                '%s -c %s',
                escapeshellarg( $phpunit ),
                escapeshellarg( $phpunitXml )
            ),
            array(),
            $pipes,
            __DIR__,
            null
        );

        if ( ! is_resource( $process ) )
        {
            echo 'Skipping ', $vndnpckName, ': command `', $cmd, '` cannot run', PHP_EOL;
            continue;
        }

        foreach ( $pipes as $pipe )
        {
            if ( is_resource( $pipe ) )
            {
                fclose( $pipe );
            }
        }

        $returnCode = proc_close( $process );

        if ( $returnCode )
        {
            echo 'Package ', $vndnpckName, ' failed with code #', $returnCode, PHP_EOL;
            exit( $returnCode );
        }
    }
}
