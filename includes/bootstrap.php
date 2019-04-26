<?php

if (!empty($_SERVER['REQUEST_URI'])) {
  $path = rtrim(strtok($_SERVER['REQUEST_URI'], "?"), '/');
  $_GET['q'] = strlen($path) === 0 ? '/' : $path;
}

// Local includes
include SITE_ROOT . '/includes/autoload.php';
include SITE_ROOT . '/includes/config.php';
include SITE_ROOT . '/includes/db.php';
include SITE_ROOT . '/includes/helpers.php';
include SITE_ROOT . '/includes/routes.php';


