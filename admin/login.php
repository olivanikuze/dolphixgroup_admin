<?php
// Include the database connection
include('../config/db_connect.php');
$message = "";

try {
    // Check if the request method is POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve user inputs and sanitize them
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        // Check if inputs are not empty
        if (empty($username) || empty($password)) {
            $message = "Username and password are required.";
        } else {
            // Prepare the SQL query to fetch the user securely using PDO
            $query = "SELECT * FROM users WHERE username = :username";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();

            // Check if a user exists
            if ($stmt->rowCount() == 1) {
                // Fetch the user data
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verify the password
                if (password_verify($password, $user['password'])) {
                    // Start a session and redirect to the dashboard
                    session_start();
                    $_SESSION['user_id'] = $user['id']; // Store user ID in session
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $message = "Invalid username or password.";
                }
            } else {
                $message = "Invalid username or password.";
            }
        }
    }
} catch (Exception $e) {
    // Handle any unexpected errors
    $message = "An error occurred: " . htmlspecialchars($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="image/favicon.ico">
    <title>Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-actions {
            display: flex;
            justify-content: space-between;
        }
        button {
            padding: 10px 15px;
            font-size: 14px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button[type="submit"] {
            background-color: #28a745;
            color: white;
        }
        button[type="reset"] {
            background-color: #dc3545;
            color: white;
        }
        .error {
            color: #dc3545;
            font-size: 14px;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Hi Admin</h1>

        <?php if (!empty($message)) : ?>
            <div class="error"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form id="userForm" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username">
            </div>
        
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
            </div>
            
            <div class="form-actions">
                <button type="submit">Submit</button>
                <button type="reset">Reset</button>
            </div>
        </form>
    </div>
</body>
</html>
