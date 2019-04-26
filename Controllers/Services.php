<?php
/**
 * Created by PhpStorm.
 * User: Monica
 * Date: 8/23/2018
 * Time: 11:09 AM
 */

namespace Controllers;


use Models\BookModel;
use Models\UserModel;

class Services extends BasicController
{
  public function __construct()
  {
    parent::__construct();
  }

  public function searchBooksAction()
  {
    $values = $_POST;

    if (isset($values['priceFrom'])) {
      $priceFrom = $values['priceFrom'];
    }
    if (isset($values['priceTo'])) {
      $priceTo = $values['priceTo'];
    }
    if (isset($values['title'])) {
      $title = $values['title'];
    }
    if (isset($values['author'])) {
      $author = $values['author'];
    }
    if (isset($values['category'])) {
      $category = $values['category'];
    }

    $bookModel = new BookModel();
    $errors = $bookModel->validateSearch($values);

    if ($errors == NULL) {
      $books = $bookModel->findBooks($title, $priceFrom, $priceTo, $author, $category);
      $booksFound = ["errors" => [], "books" => $books];
    } else {
      $booksFound = ["errors" => $errors, "books" => []];
    }

    header('Content-Type: application/json');
    echo json_encode($booksFound);
    exit();


  }
  public function takeUsers(){
    $userModel = new UserModel();
    $users = $userModel->getUsers();
    header('Content-Type: application/json');
    echo json_encode($users);
    exit();
  }

}