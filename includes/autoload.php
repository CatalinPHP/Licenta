<?php

/**
 * Default autoloader.
 *
 * @param string $className
 *   e.g. Controllers/Admin
 */
function google_books_autoloader($className) {
  $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
  if(file_exists(SITE_ROOT . DIRECTORY_SEPARATOR . $className . '.php')) {
    include_once SITE_ROOT . DIRECTORY_SEPARATOR . $className . '.php';
  }
}

spl_autoload_register('google_books_autoloader');