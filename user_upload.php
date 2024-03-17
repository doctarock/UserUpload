<?php
// Command line options
$options = getopt("f:r", ["file:", "rebuild"]);

// Database configuration
$db_host = 'localhost';
$db_username = 'username';
$db_password = 'password';
$db_name = 'database_name';

// Connect to MySQL database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if table needs to be rebuilt
if (isset($options['rebuild']) || isset($options['r'])) {
    $sql = "DROP TABLE IF EXISTS users";
    if ($conn->query($sql) === TRUE) {
        echo "Table 'users' dropped successfully\n";
    } else {
        echo "Error dropping table: " . $conn->error . "\n";
    }
}

// Create users table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    surname VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    echo "Table 'users' created/rebuilt successfully\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}

// Function to capitalize name and surname, and validate email
function validateAndInsert($name, $surname, $email, $conn) {
    $name = ucfirst(strtolower($name));
    $surname = ucfirst(strtolower($surname));
    $email = strtolower($email);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format: $email\n";
        return;
    }
    $sql = "INSERT INTO users (name, surname, email) VALUES ('$name', '$surname', '$email')";
    if ($conn->query($sql) === TRUE) {
        echo "Record inserted successfully: $name $surname $email\n";
    } else {
        echo "Error inserting record: " . $conn->error . "\n";
    }
}

// Process CSV file
$filename = isset($options['file']) ? $options['file'] : (isset($options['f']) ? $options['f'] : '');
if (!empty($filename)) {
    if (($handle = fopen($filename, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);
            if ($num == 3) {
                validateAndInsert($data[0], $data[1], $data[2], $conn);
            } else {
                echo "Invalid CSV format: Each row must contain exactly 3 columns\n";
            }
        }
        fclose($handle);
    } else {
        echo "Error opening file: $filename\n";
    }
} else {
    echo "Please provide a CSV file using --file or -f option\n";
}

// Close database connection
$conn->close();
?>