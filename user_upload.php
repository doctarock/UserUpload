<?php
// Check if the user_upload class doesn't already exist, if it does, the code won't be executed again
if (!class_exists("user_upload")) :

    class user_upload {
        // Constructor function that initializes the class and processes the CSV file
        function __construct($options){
            
            // Print help message if --help option is provided
            if (isset($options['help'])) {
                $this->printHelp();
                exit;
            }

            $conn = $this->databaseConnect($options);

            // Create users table if --create_table option is provided
            if (isset($options['create_table'])) {
                $this->dropCreateTable($conn);
            }

            // Process CSV file
            $this->processFile($conn, $options);

            // Close database connection
            $conn->close();
        }

        // Function to process the CSV file, validate and insert data into the database
        function processFile($conn, $options){

            // Get the CSV file name from the options
            $filename = isset($options['file']) ? $options['file'] : (isset($options['f']) ? $options['f'] : '');
            if (!empty($filename)) {
                // Open the CSV file
                if (($handle = fopen($filename, "r")) !== FALSE) {
                    // Loop through each row in the CSV file
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $num = count($data);
                        // Check if each row contains exactly 3 columns
                        if ($num == 3) {
                            $this->validateAndInsert($data[0], $data[1], $data[2], $conn, isset($options['dry_run']));
                        } else {
                            // Print error message if the CSV format is invalid
                            echo "Invalid CSV format: Each row must contain exactly 3 columns\n";
                        }
                    }
                    fclose($handle);
                } else {
                    // Print error message if the file cannot be opened
                    echo "Error opening file: $filename\n";
                }
            } else {
                // Print error message if the file is not supplied
                echo "Please provide a CSV file using --file option\n";
            }

        }
        
        // Function to capitalize name and surname, and validate email
        function validateAndInsert($name, $surname, $email, $conn, $dry_run) {
            // Trim and format input
            $name = trim(ucfirst(strtolower($name)));
            $surname = trim(ucfirst(strtolower($surname)));
            $email = trim(strtolower($email));

            // Regular expression to validate email address
            if (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$/", $email)) {
                echo "Invalid email address " . $email . ".\n";
                return;
            }

            if (!$dry_run) {
                // Prepare a SQL statement with placeholders
                $sql = "INSERT INTO users (name, surname, email) VALUES (?, ?, ?)";
                
                // Prepare the statement
                $stmt = $conn->prepare($sql);
                
                // Bind parameters to the statement
                $stmt->bind_param("sss", $name, $surname, $email);

                try {
                
                    // Execute the statement
                    if ($stmt->execute()) {
                        echo "Record inserted successfully: $name $surname $email\n";
                    }

                } catch (Exception $ex) {

                    // Fail gracefully
                    echo "Error inserting record: " . $conn->error . "\n";

                }
            } else {
                // Print message if executing a dry run
                echo "Dry run: Record not inserted: $name $surname $email\n";
            }
        }

        function dropCreateTable($conn) {
            // Drop the 'users' table if it already exists
            if ($conn->query("DROP TABLE IF EXISTS users") === TRUE) {
                echo "Table 'users' cleared successfully\n";
            } else {
                // If there's an error clearing the table, display the error message and exit
                die("Error clearing table: " . $conn->error);
            }

            // Create the 'users' table with the following columns
            $sql = "CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(50) NOT NULL,
                surname VARCHAR(50) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE
            )";

            if ($conn->query($sql) === TRUE) {
                echo "Table 'users' created successfully\n";
            } else {
                // If there's an error creating the table, display the error message and exit
                die("Error clearing table: " . $conn->error);
            }
            
            // Close database connection
            $conn->close();

            // Exit the script
            exit;
        }

        function databaseConnect($options) {
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

            return $conn;
        }
        
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
    } // end class

    new user_upload(getopt("f:u:p:h:d:", ["file:", "create_table", "dry_run", "help"]));	
endif;