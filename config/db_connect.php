

<?php
//config.php
$db_host = 'localhost';
$db_name = 'admin';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
   die("Connection failed: " . $e->getMessage());
}

// Start session on all pages
//session_start();

// Function to check if user is logged in
// function isLoggedIn() {
//     return isset($_SESSION['admin']);
// }

// // Function to redirect if not logged in
// function requireLogin() {
//     if (!isLoggedIn()) {
//         header("Location: login.php");
//         exit();
//     }
// }
?>
<!-- <?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
// define('DB_SERVER', 'localhost');
// define('DB_USERNAME', 'root');
// define('DB_PASSWORD', '');
// define('DB_NAME', 'admin');
 
// /* Attempt to connect to MySQL database */
// $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// // Check connection
// if($link === false){
//     die("ERROR: Could not connect. " . mysqli_connect_error());
// }
?> -->