<?php
  require_once(dirname(__DIR__, 1) . "/const.php");
  require_once(dirname(__FILE__, 1) . "/dbClass.php");



function createTable($name, $query) {
    try {
        queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");
        echo "Table '$name' created or already exists.<br>";
    } catch (Exception $e) {
        echo "$e->getMessage()";
    }
}

function queryMysql($query) {
    $connection = new DB(DB_HOST, DB_NAME, DB_USER, DB_PASS);
    $result = $connection->connection->query($query);
    if (!$result) die ("SQL Error while creating table");
    return $result;
}

function destroy_session() {
    $_SESSION=array();

    if (session_id() != "" || isset($_COOKIE[session_name()]))
        setcookie(session_name(), '', time()-2592000, '/');

    session_destroy();
}


function validate_username($user)
{
    if (strlen($user) < 3 || (preg_match("/[^a-zA-Z0-9_-]/", $user)))
    {
        return false;
    }
    else return true;
}

function validate_pass($pass)
{
    if (strlen($pass) < 3)
    {
        return false;
    }
    else return true;
}


    // Display Errors:
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);


?>
