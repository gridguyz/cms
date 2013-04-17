<?php

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

<?php p( $_ENV, '<strong>$_ENV</strong>' ) ?>

<hr />
<?php p( $_SERVER, '<strong>$_SERVER</strong>' ) ?>

<hr />
<?php p( $_REQUEST, '<strong>$_REQUEST</strong>' ) ?>

<hr />
<?php p( $_SESSION, '<strong>$_SESSION</strong>' ) ?>

<a href="?clearSession=1">Clear session</a>
