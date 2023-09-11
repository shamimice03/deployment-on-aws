<?php
// Load environment variables from the .env file
require 'vendor/autoload.php'; // Load the Composer autoloader (if you installed vlucas/phpdotenv)

// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv = Dotenv\Dotenv::createImmutable('/var/www/env', '.env');
$dotenv->load();

// Access the environment variables
$db_user = $_ENV['DBUser'];
$db_password = $_ENV['DBPassword'];
$db_hostname = $_ENV['DBHostname'];
$db_name = $_ENV['DBName']; // Add DBName variable

// Database connection
function connectToDatabase($db_user, $db_password, $db_hostname, $db_name) {
    $conn = new mysqli($db_hostname, $db_user, $db_password, $db_name); // Include DBName in the connection
    return $conn;
}

// Attempt to connect to the database
$conn = connectToDatabase($db_user, $db_password, $db_hostname, $db_name);

// Check the database connection
if ($conn->connect_error) {
    $db_status = "Database connection failed: " . $conn->connect_error;
} else {
    $db_status = "Database connection successful!";
}

// Fetch user data from the database
$user_info_table = ""; // Initialize user info table

$sql = "SELECT * FROM userinfo";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user_info_table .= '<h2>User Information</h2>';
    $user_info_table .= '<table border="1">';
    $user_info_table .= '<tr><th>Username</th><th>User Address</th><th>User Phone Number</th></tr>';
    
    while ($row = $result->fetch_assoc()) {
        $user_info_table .= '<tr>';
        $user_info_table .= '<td>' . $row['username'] . '</td>';
        $user_info_table .= '<td>' . $row['user_address'] . '</td>';
        $user_info_table .= '<td>' . $row['user_phone_number'] . '</td>';
        $user_info_table .= '</tr>';
    }

    $user_info_table .= '</table>';
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Information</title>
</head>
<body>
    <!-- Display the user info table -->
    <?php echo $user_info_table; ?>
</body>
</html>
