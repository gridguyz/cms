<?php

const PUBLIC_DIR    = './public';
const DATA_DIR      = './data/update';

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir( dirname( __DIR__ ) );

/**
 * Send message(s)
 *
 * @param   int             $result
 * @param   string|array    $messages
 * @return  void
 */
function sendMessage( $result, $messages )
{
    $file = DATA_DIR . '/status.json';

    if ( ! is_array( $messages ) )
    {
        $messages = array( (string) $messages => array() );
    }

 /* foreach ( $messages as $message => $values )
    {
        echo vsprintf( $message, (array) $values ), PHP_EOL;
    } */

    if ( file_exists( $file ) )
    {
        $old = @ json_decode( file_get_contents( $file ), true );

        if ( ! empty( $old['messages'] ) )
        {
            $messages = array_replace( $old['messages'], $messages );
        }
    }

    @ file_put_contents( $file, json_encode( array(
        'messages'  => $messages,
        'result'    => $result,
    ) ) );

    if ( null !== $result )
    {
        if ( is_file( './composer.json.backup' ) )
        {
            if ( $result )
            {
                @ unlink( './composer.json' );
                @ rename( './composer.json.backup', './composer.json' );
            }
            else
            {
                @ unlink( './composer.json.backup' );
            }
        }

        if ( is_file( PUBLIC_DIR . '/maintenance.php' ) )
        {
            @ unlink( PUBLIC_DIR . '/maintenance.php' );
        }

        exit( (int) $result );
    }
}

/**
 * Send to output
 *
 * @param   string  $text
 * @return  void
 */
function sendOutput( $text )
{
    $filePath   = DATA_DIR . '/output.txt';
    $fileExists = is_file( $filePath );
    $file       = @ fopen( $filePath, 'a' );

    if ( is_resource( $file ) )
    {
        if ( $fileExists )
        {
            @ fwrite( $file, PHP_EOL );
        }

        @ fwrite( $file, $text );
        @ fclose( $file );
        @ chmod( $filePath, 0777 );
    }
}

/**
 * Run a process
 *
 * @param   string  $cmd
 * @param   array   $args
 * @param   array   $env
 * @return  void
 */
function runProcess( $cmd, array $args = array(), array $env = array() )
{
    $outputFilepath = DATA_DIR . '/output.txt';
    $descriptorspec = array(
     // 0 => array( 'pipe', 'r' ), // stdin
        1 => array( 'file', $outputFilepath, 'a' ), // stdout
        2 => array( 'file', $outputFilepath, 'a' ), // stderr
    );

    $pipes = array();
    $cmd   = escapeshellcmd( $cmd );

    foreach ( $args as $arg )
    {
        $cmd .= ' ' . escapeshellarg( $arg );
    }

    sendOutput( '$ ' . $cmd . PHP_EOL );

    if ( empty( $_ENV ) )
    {
        $global = array();

        foreach ( $_SERVER as $key => $value )
        {
            $value = getenv( $key );

            if ( false !== $value )
            {
                $global[$key] = $value;
            }
        }
    }
    else
    {
        $global = $_ENV;
    }

    if ( empty( $env ) )
    {
        $env = $global;
    }
    else
    {
        $env = array_merge( $global, $env );
    }

    $proc = proc_open( $cmd, $descriptorspec, $pipes, getcwd(), $env );

    if ( ! is_resource( $proc ) )
    {
        sendMessage( 2, array(
            'admin.packages.process.cannotRun.%s' => array( $cmd ),
        ) );
    }

    foreach ( $pipes as $pipe )
    {
        @ fclose( $pipe );
    }

    $result = proc_close( $proc );

    if ( $result )
    {
        sendMessage( 3, array(
            'admin.packages.process.returned.%s.%d' => array( $cmd, $result ),
        ) );
    }
}

@ unlink( DATA_DIR . '/output.txt' );
@ unlink( DATA_DIR . '/status.json' );
@ file_put_contents( DATA_DIR . '/status.json', json_encode( array(
    'messages'  => array(),
    'result'    => null,
) ) );

@ chmod( DATA_DIR . '/status.json', 0777 );

if ( ! is_file( PUBLIC_DIR . '/maintenance.php' ) &&
     !  @ copy( PUBLIC_DIR . '/app/maintenance.php', PUBLIC_DIR . '/maintenance.php' ) )
{
    sendMessage( 1, 'admin.packages.maintenance.failed' );
}

if ( is_dir( './.git' ) )
{
    runProcess( 'git', array( 'pull', getcwd() ) );
    sendMessage( null, 'admin.packages.git.pulled' );
}

if ( ! ( $home = getenv( 'HOME' ) ) &&
     ! ( $home = getenv( 'COMPOSER_HOME' ) ) )
{
    @ mkdir( $home = './data/update/composer', 0777 );
    $home = realpath( $home );
}

sendOutput( 'using ' . escapeshellarg( $home ) . ' as composer home' . PHP_EOL );

runProcess(
    'php',
    array(
        realpath( './composer.phar' ),
        'update',
        'gridguyz/module-installer',
        '--no-dev',
        '--no-interaction'
    ),
    array(
        'COMPOSER_HOME' => $home,
    )
);

sendMessage( null, 'admin.packages.update.installer' );

runProcess(
    'php',
    array(
        realpath( './composer.phar' ),
        'update',
        '--no-dev',
        '--no-interaction'
    ),
    array(
        'COMPOSER_HOME' => $home,
    )
);

sendMessage( 0, 'admin.packages.update.done' );
