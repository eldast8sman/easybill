# EasyBill Assessment

This project is based on Laravel 11. It is a Backend project providing API endpoints. There are two modules:
- User
- Transactions

## How to Set up and Run the application

### Requirements
- PHP 8.2
- MySQL
- Apache
- Composer

### Set up and Run
- Clone the project to your local machine
- Open a terminal and naigate to the folder containing the project
- Create a .env file in the root folder of the project
- Copy the content of .env.example into the .env file
- Create a Database called easybill in your MySQL.
- In the .env update the DB credentials with those of your newly created Database
- Run the command "composer install" to download and install all the required dependencies
- Run the command "php artisan key:generate --ansi" to create your application key
- Run the command "php artisan migrate" to create your database tables
- Run the command "php artisan serve" to serve your application

### Testing and Documentation
In order to test the application, you can run the Command "php artisan test" to run the Automated Testing of the Application. The test files are in the tests/Feature folder of the project. The documentation can be found in here <a href="https://documenter.getpostman.com/view/12132264/2sAXxS9CGH">https://documenter.getpostman.com/view/12132264/2sAXxS9CGH</a>
