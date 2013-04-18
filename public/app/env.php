<?php

use Zend\Debug\Debug;
use Zork\Session\ReadOnlyHandler;

include './init.php';

if ( empty( $_REQUEST['clearSession'] ) )
{
    session_set_save_handler( new ReadOnlyHandler, true );
}

session_start();

if ( ! empty( $_REQUEST['clearSession'] ) )
{
    session_unset();
    header( 'Location: ?' );
    die();
}

?>

<?php Debug::dump( $_ENV, '<strong>$_ENV</strong>' ) ?>

<hr />
<?php Debug::dump( $_SERVER, '<strong>$_SERVER</strong>' ) ?>

<hr />
<?php Debug::dump( $_REQUEST, '<strong>$_REQUEST</strong>' ) ?>

<hr />
<?php Debug::dump( $_SESSION, '<strong>$_SESSION</strong>' ) ?>

<a href="?clearSession=1">Clear session</a>
