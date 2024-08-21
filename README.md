# Carlos Blog Website

Welcome to the Carlos Blog Website! Follow these simple steps to get started:

## Setup Instructions

1. **Initialize the Database**

   - Go to the file `datenbankinitialisierung.php` located in the project directory.
   - Open the file in your browser (e.g., `http://localhost/datenbankinitialisierung.php`). This will set up the database and create the necessary tables.

2. **Configure Database Connection**

   - After initializing the database, open the file `includes/PDOConnection.inc.php` in a text editor.
   - Modify the `__construct` method to include the database name:

     ```php
     public function __construct() {
         $dsn = 'mysql:host=localhost;port=3306;dbname=carlos_blog'; // Add the database name here
         $user = 'root';  // 
         $pwd = '';       // 

         try {
             $this->db = new PDO($dsn, $user, $pwd);
             $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         } catch (PDOException $e) {
             echo "Error: " . $e->getMessage();
             die();
         }
     }
     ```

3. **Open the Application**

   - After configuring the database connection, open `index.php` in your browser (e.g., `http://localhost/index.php`).
   - You can now use the application.

## Admin Credentials

- **Username:** carlos
- **Password:** 123

Use these credentials to log in as an admin and manage the website.

## Notes

- Make sure you have PHP and MySQL installed and configured.
- Ensure that the `PDOConnection.inc.php` file is set up with your database details.

If you need help or encounter any issues, please check the documentation or contact support.

Happy blogging!
