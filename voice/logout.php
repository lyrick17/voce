<?php // based on PHP manual about session_destroy();
include("mysql/mysqli_registration.php");

session_start();
echo $_SESSION['username'];
logs("logout", $_SESSION['username'] , $dbcon);
// unset all session variables
$_SESSION = array();

// will destroy the session, not just the session data.
if(ini_get("session.use_cookies"))  {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}


session_destroy();
header("location: index.php");
exit();

?>