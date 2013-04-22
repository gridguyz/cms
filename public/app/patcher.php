<?php

use Zork\Patcher\Patcher;
use Zork\Iterator\CallbackMapIterator;

include './init.php';

$paths = new CallbackMapIterator(
    new CallbackFilterIterator(
        new RecursiveIteratorIterator(
            new RecursiveCallbackFilterIterator(
                new RecursiveDirectoryIterator(
                    './vendor',
                    RecursiveDirectoryIterator::CURRENT_AS_SELF |
                    RecursiveDirectoryIterator::KEY_AS_FILENAME |
                    RecursiveDirectoryIterator::SKIP_DOTS |
                    RecursiveDirectoryIterator::UNIX_PATHS
                ),
                function ( $current, $key ) {
                    return '.' !== $key[0];
                }
            ),
            RecursiveIteratorIterator::SELF_FIRST
        ),
        function ( $current, $key ) {
            return $current->isDir() && $key === 'sql' &&
                   preg_match( '#[^/]+/[^/]+/(module/[^/]+/)?sql$#', $current->getSubPathname() );
        }
    ),
    function ( $current ) {
        return './vendor/' . $current->getSubPathname();
    },
    CallbackMapIterator::FLAG_GENERATE_KEYS
);

$config  = require './config/autoload/db.local.php';
$patcher = new Patcher( $config['db'] );
$patcher->patch( $paths );
