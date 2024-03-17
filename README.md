# User Upload Script Readme

This script is a PHP class that reads a CSV file and inserts the data into a MySQL database. It also includes functionality to create the users table in the database.

## Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher

## Usage

To use the script, simply run it with the appropriate options:

```php
php user_upload.php [options]
```
The available options are:

- '--file [csv file name]': Specify the CSV file to be parsed. This is a required option.
- '--create_table': Build the MySQL users table and exit.
- '--help': Show this help message and exit.
- '--dry_run': Parse the CSV file but do not insert into the database.
- '-u [MySQL username]': MySQL username (default: root).
- '-p [MySQL password]': MySQL password (default: root).
- '-h [MySQL host]': MySQL host (default: localhost).
- '-d [MySQL db name]': MySQL db name (default: database_name).

##How it Works
The script defines a user_upload class that handles the following tasks:

1. Connects to the MySQL database using the provided credentials.
2. If the --create_table option is provided, drops and recreates the users table.
3. Parses the CSV file and inserts the data into the users table.

The users table has the following schema:

- id: an auto-incrementing primary key.
- name: a required varchar(50) field.
- surname: a required varchar(50) field.
- email: a required unique varchar(100) field.

The script uses the getopt function to parse the command-line options. It also includes a printHelp function that displays the usage message.
