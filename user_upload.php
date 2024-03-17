<?php
// Command line options
$options = getopt("f:u:p:h:d:", ["file:", "create_table", "dry_run", "help"]);

// Help message function
function printHelp() {
    echo "Usage: php user_upload.php [options]\n";
    echo "Options:\n";
    echo "  --file [csv file name]     Specify the CSV file to be parsed\n";
    echo "  --create_table             Build the MySQL users table and exit\n";
    echo "  --dry_run                  Parse the CSV file but do not insert into the database\n";
    echo "  -u                         MySQL username\n";
    echo "  -p                         MySQL password\n";
    echo "  -h                         MySQL host\n";
    echo "  -d                         MySQL db name\n";
    echo "  --help                     Show this help message\n";
}

// Print help message if --help option is provided
if (isset($options['help'])) {
    printHelp();
    exit;
}

// Database configuration
$db_host = isset($options['h']) ? $options['h'] : 'localhost';
$db_username = isset($options['u']) ? $options['u'] : 'root';
$db_password = isset($options['p']) ? $options['p'] : 'root';
$db_name = isset($options['d']) ? $options['d'] : 'database_name';

// Connect to MySQL database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create users table if --create_table option is provided
if (isset($options['create_table'])) {
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        surname VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE
    )";
    if ($conn->query($sql) === TRUE) {
        echo "Table 'users' created successfully\n";
    } else {
        echo "Error creating table: " . $conn->error . "\n";
    }
    exit;
}

// Function to capitalize name and surname, and validate email
function validateAndInsert($name, $surname, $email, $conn, $dry_run) {
    $name = ucfirst(strtolower($name));
    $surname = ucfirst(strtolower($surname));
    $email = trim(strtolower($email));

    if (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$/", $email)) {
        echo "Invalid email address " . $email . ".\n";
        return;
    }

    if (!$dry_run) {
        $sql = "INSERT INTO users (name, surname, email) VALUES ('$name', '$surname', '$email')";
        if ($conn->query($sql) === TRUE) {
            echo "Record inserted successfully: $name $surname $email\n";
        } else {
            echo "Error inserting record: " . $conn->error . "\n";
        }
    } else {
        echo "Dry run: Record not inserted: $name $surname $email\n";
    }
}

// Process CSV file
$filename = isset($options['file']) ? $options['file'] : (isset($options['f']) ? $options['f'] : '');
if (!empty($filename)) {
    if (($handle = fopen($filename, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);
            if ($num == 3) {
                validateAndInsert($data[0], $data[1], $data[2], $conn, isset($options['dry_run']));
            } else {
                echo "Invalid CSV format: Each row must contain exactly 3 columns\n";
            }
        }
        fclose($handle);
    } else {
        echo "Error opening file: $filename\n";
    }
} else {
    echo "Please provide a CSV file using --file option\n";
}

// Close database connection
$conn->close();
?>