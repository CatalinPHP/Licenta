<?php

define('SITE_ROOT', getcwd());
include(SITE_ROOT . '/includes/bootstrap.php');

use scripts\BooksFetcher;

if ((empty($argv[1]) && empty($argv[2]) && empty($argv[3]))) {
  echo "Your text input is not valid. Please enter a title and/or an author.";
} else if (isset($argv[4])) {
  echo "Too many arguments provided.";
} else {
  $title = $argv[1];
  $author = isset($argv[2]) ? $argv[2] : NULL;
  $category = isset($argv[3]) ? $argv[3] : NULL;
  echo "You searched for title: " . $title . ", author: " . $author . ", category: " . $category;
  $booksFetcher = new BooksFetcher();
  try {
    $booksFetcher->InsertBooks($title, $author, $category);
    echo "Finished import...";
  } catch (\atk4\dsql\Exception $e) {
  } catch (\atk4\core\Exception $e) {
  }

}


