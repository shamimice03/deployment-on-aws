<?php
// Load environment variables from the .env file
require 'vendor/autoload.php'; // Load the Composer autoloader (if you installed vlucas/phpdotenv)

//$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
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

// Get the local (private) IP address of the EC2 instance
$local_ip = file_get_contents('http://169.254.169.254/latest/meta-data/local-ipv4');

// Process the form submission
$form_status = ""; // Initialize form status

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $user_address = $_POST['user_address'];
    $user_phone_number = $_POST['user_phone_number'];

    // Insert user data into the database
    $sql = "INSERT INTO userinfo (username, user_address, user_phone_number) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $user_address, $user_phone_number);

    if ($stmt->execute()) {
        $form_status = "Success: User data inserted successfully!";
    } else {
        $form_status = "Failure: Error - " . $stmt->error;
    }

    // Close the database connection
    $stmt->close();
}

// Fetch all user data from the database
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
    <title>Simple Webapp</title>
</head>
<body>
    <!-- Display the image -->
    <h2> Content from EFS </h2>
    <img src="../media/imagefromefs.jpg" alt="EFS Image">
    <img src="/static/welcome.jpg" alt="Static Image">
    <h2>Local (Private) IP Address: <?php echo $local_ip; ?></h2>
    <p><?php echo $db_status; ?></p>
    <?php if (!$conn->connect_error): ?>
        <p>DB Hostname: <?php echo $db_hostname; ?></p>
        <p>DB Name: <?php echo $db_name; ?></p>
    <?php endif; ?>

    <!-- Display the user entry form -->
    <h2>User Entry Form</h2>
    <div id="formStatus" style="display: none;"><?php echo $form_status; ?></div>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="displayFormStatus('Submitting...');">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br><br>
        
        <label for="user_address">User Address:</label>
        <input type="text" name="user_address" required><br><br>
        
        <label for="user_phone_number">User Phone Number:</label>
        <input type="text" name="user_phone_number" required><br><br>
        
        <input type="submit" value="Submit">
    </form>

    <!-- Display the user info table -->
    <?php echo $user_info_table; ?>

    <!-- JavaScript function to display the form status -->
    <script>
        function displayFormStatus(status) {
            var statusElement = document.getElementById("formStatus");
            statusElement.innerText = status;
            statusElement.style.display = "block";
        }
    </script>

</body>
</html>