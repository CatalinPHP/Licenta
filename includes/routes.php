<?php

function routes_definitions()
{
  return array(
    '/home' => 'Controllers\HomeController',
    '/' => 'Controllers\HomeController',
    '/admin' => 'Controllers\AdminDashboardController',
    '/admin/settings' => array(
      'GET' => 'Controllers\AdminDashboardController::adminSettingsPage',
      'POST' => 'Controllers\AdminDashboardController::adminSettingsAction',
    ),
    '/admin/books/delete' => array(
      'POST' => 'Controllers\AdminDashboardController::deleteAction',
    ),
    '/admin/books/delete/{id:([0-9]+)}' => 'Controllers\AdminDashboardController::deleteAction',
    '/login' => array(
      'GET' => 'Controllers\LoginController',
      'POST' => 'Controllers\LoginController::loginAction',
    ),
    '/loginUser'=>array(
      'GET' => 'Controllers\LoginController::getUser',
      'POST'=> 'Controllers\LoginController::loginUser',
    ),
    '/register' => array(
      'GET' => 'Controllers\LoginController::registerPage',
      'POST' => 'Controllers\LoginController::registerAction'
    ),
    '/registerUser' => array(
      'GET' => 'Controllers\LoginController::registerUserPage',
      'POST' => 'Controllers\LoginController::registerUser',
    ),
    '/logout' => array(
      'GET' => 'Controllers\LoginController::logoutAction'
    ),
    '/logoutUser' => array(
      'GET' => 'Controllers\LoginController::logoutUserAction'
    ),
    '/services/search-books' => array(
      'GET' =>'Controllers\Services::searchBooksPage',
      'POST' => 'Controllers\Services::searchBooksAction',
    ),
    '/services/take-users' => 'Controllers\Services::takeUsers',
    '/search' => array(
      'GET' =>'Controllers\HomeController::homeSearchPage',
    ),
    '/admin/edit/{$id:([0-9]+)}' => array(
      'GET' => 'Controllers\AdminDashboardController::editBookPage',
      'POST' => 'Controllers\AdminDashboardController::editBookAction'
    ),
    '/book/{$id:([0-9]+)}' => 'Controllers\BookController::bookPageAction',
    '/user'=> 'Controllers\UserController',
    '/wishBooks' => 'Controllers\UserController::wishBooks',
    '/uploadImage' => 'Controllers\UserController::uploadImage',
    '/wishBooksAddRemove'=> 'Controllers\BookController::wishBooksAddRemove',
    '/setComments' =>'Controllers\BookController::setComments',
    '/importBooks' =>'Controllers\AdminDashboardController::uploadBooks'
  );
}

function routes_execute_handler()
{

  $routes = routes_definitions();
  $request_method = strtoupper($_SERVER['REQUEST_METHOD']);

  $controllerClass = FALSE;
  $controllerAction = 'get';
  $actionArguments = array();
  $regex_routes = array();

  foreach ($routes as $route_path => $route_definition) {
    $segments = explode('/', $route_path);
    foreach ($segments as $index => &$segment) {
      // We have a dynamic parameter.
      if (strpos($segment, '{') !== FALSE) {
        $segment = str_replace(array('{', '}'), array('', ''), $segment);
        $segment_parts = explode(':', $segment);
        $routes_arguments[$route_path][$segment_parts[0]] = $index;
        $segment = $segment_parts[1];
      }
    }
    $regex_path = implode('/', $segments);
    $regex_path = str_replace('/', '\/', $regex_path);
    $regex_routes[$regex_path]['definition'] = $route_definition;
    $regex_routes[$regex_path]['original_route'] = $route_path;
  }

  foreach ($regex_routes as $regex => $routeInfo) {
    $routeDefinition = $routeInfo['definition'];
    if (preg_match('/^' . $regex . '$/', $_GET['q'])) {
      if (is_array($routeDefinition) && isset($routeDefinition[$request_method])) {
        $routeParts = explode('::', $routeDefinition[$request_method]);

      } else {
        $routeParts = explode('::', $routeDefinition);
      }

      if (!empty($routeParts)) {
        $controllerClass = $routeParts[0];
        if (isset($routeParts[1])) {
          $controllerAction = $routeParts[1];
        }
      }
      if (!empty($routes_arguments[$routeInfo['original_route']])) {
        $path_segments = explode('/', $_GET['q']);
        foreach ($routes_arguments[$routeInfo['original_route']] as $argument_name => $index) {
          $actionArguments[$argument_name] = $path_segments[$index];
        }
      }
    }

  }

  if ($controllerClass) {
    $controller = new $controllerClass();
    call_user_func_array(array($controller, $controllerAction), $actionArguments);
  } else {
    echo '404 not found';
  }

}