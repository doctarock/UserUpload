# 1. User Upload Script

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

## How it Works
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

# 2. FooBar

This script will do the following:
- Output the numbers from 1 to 100
- Where the number is divisible by three (3) output the word “foo”
- Where the number is divisible by five (5) output the word “bar”
- Where the number is divisible by three (3) and (5) output the word “foobar”
- Only be a single PHP file

## Prerequisites

- PHP 7.4 or higher

## Usage

To use the script, simply execute the php file.

```php
php foobar.php
```

# 2.1 FooBar Unit Test

This script will validate the correct output for foobar.php

## Prerequisites

- PHP 7.4 or higher
- PHPUnit

## Installation

To install PHPUnit and its dependencies, including PHPUnit\Framework\TestCase, you'll need to use Composer, which is a dependency manager for PHP. Follow these steps to install PHPUnit:

1. Install Composer: If you haven't already installed Composer, you can do so by following the instructions on the official Composer website: https://getcomposer.org/download/.

2. Create a composer.json file: Create a file named composer.json in your project directory and add the following content:

```json
{
    "require-dev": {
        "phpunit/phpunit": "^9.0"
    }
}
```
This specifies that PHPUnit is a development dependency for your project.

3. Run Composer install: Open a terminal or command prompt, navigate to your project directory where the composer.json file is located, and run the following command:

```sh
composer install
```
This will download and install PHPUnit and its dependencies, including PHPUnit\Framework\TestCase.

## Usage
You can run these tests by executing PHPUnit from the command line:

```sh
phpunit FooBarText.php
```

If PHPUnit is not available in your system path, you may need to execute the following:

```sh
php vendor/bin/phpunit FooBarText.php
```