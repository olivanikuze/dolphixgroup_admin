// config/constants.php
// Base URLs
<?php
define('BASE_URL', 'http://localhost/htdocs');
define('ADMIN_URL', BASE_URL . '/admin');
define('CLIENT_URL', BASE_URL . '/dolphix');

// Directory Paths
define('ROOT_PATH', dirname(dirname(__FILE__)));
define('ADMIN_PATH', ROOT_PATH . '/admin');
define('CLIENT_PATH', ROOT_PATH . '/dolphix');
define('CONFIG_PATH', ROOT_PATH . '/config');

// Database Tables
define('TBL_USERS', 'users');
define('TBL_PRODUCTS', 'products');
define('TBL_CHAT', 'chat_messages');
?>