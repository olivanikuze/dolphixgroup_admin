// config/functions.php
<?php
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function redirectTo($path) {
    header("Location: " . $path);
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function checkAdminAuth() {
    if (!isLoggedIn()) {
        redirectTo(ADMIN_URL . '/login.php');
    }
}
?>