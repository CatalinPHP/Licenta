# Google Books

Import and manage your favourite google books.

## Install
1. git clone git@github.com:CatalinPHP/Licenta.git
2. cd google-books
3. Create google_books database.
4. Import sql file from "sql/install.sql" into "google_books" database.
    ```
    mysql -u{usermysql} -p google_books < sql/install.sql
    ```
5. Install composer
6. composer install
7. create local_config.php file under "config" folder.
8. add the following code to have database credentials available:
    ```
    $conf['database'] = array(
      'db_name' => 'your_database_name',
      'user' => 'your_user_name',   
      'password' => 'your_password',
      'port' => '3306',
    ); 
    ```

## Import books

To execute an import you must be admin and throught the form import books you can import them .



