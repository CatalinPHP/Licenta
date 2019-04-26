<?php

//define('SITE_ROOT', getcwd());
//include(SITE_ROOT . '/includes/bootstrap.php');

use scripts\BooksFetcher;

function uploadBooks($titleBook, $autorBook, $categoryBook)
{
  if ((empty($titleBook) && empty($autorBook) && empty($categoryBook))) {
    echo "Your text input is not valid. Please enter a title and/or an author.";
  } else if (isset($argv[4])) {
    echo "Too many arguments provided.";
  } else {
    $title = $titleBook;
    $author = isset($autorBook) ? $autorBook : NULL;
    $category = isset($categoryBook) ? $categoryBook : NULL;
    echo "You searched for title: " . $title . ", author: " . $author . ", category: " . $category;
    $booksFetcher = new BooksFetcher();
    try {
      $booksFetcher->InsertBooks($title, $author, $category);
      echo "Finished import...";
    } catch (\atk4\dsql\Exception $e) {
    } catch (\atk4\core\Exception $e) {
    }
  }
}


