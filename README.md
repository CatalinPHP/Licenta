# Google Books

Import and manage your favourite google books.

## Install
1. git clone git@github.com:laurentiu1981/google-books.git
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

To execute an import you must use:
```
php importbooks.php <title> <author> <category>
```

The first argument will be the title of the book you're searching while the second argument will be the author. The 3rd argument will be the category of the book.<br>
You must enter at least one argument and maximum three arguments. Here are some valid examples:
```
php importbooks.php "Dune" "" "Fiction"
php importbooks.php "Dune" "" ""
php importbooks.php "" "Frank Herbert" ""
php importbooks.php "" "Frank Herbert" "Fiction"
php importbooks.php "Dune" "Frank Herbert" "Fiction"
```
And these are some invalid examples: 
```
php importbooks.php
php importbooks.php "" "" 
php importbooks.php "Dune" "Frank Herbert" "Fiction "10"
```


