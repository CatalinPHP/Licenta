<?php
/**
 * Created by PhpStorm.
 * User: Catalin
 * Date: 12.02.2019
 * Time: 10:58
 */

namespace Controllers;

use Models\BookModel;
use Scripts\Upload;
use Models\Config;

class UserController extends BasicController
{
  /**
   * UserController constructor.
   */
  public function __construct()
  {
    parent::__construct();
    $this->title = "Users";
    global $user;
    if ($user === NULL) {
      redirect('/loginUser');
    }
    unset($this->menu['/']);
    $this->menu['/wishBooks'] = 'Favorite';
  }

  public function get()
  {

    $this->content = "User definition";
    $this->sidebar = $this->render('/views/user_sidebar.php');
    $this->renderLayout('/views/layouts/sidebar_page.tpl.php');
    // $this->renderLayout('/views/layouts/basic.tpl.php');
  }

  public function wishBooks()
  {
    $bookModel = new BookModel();
    $wishBooks = $bookModel->takeAllWishBook();
    $books = array();
    foreach ($wishBooks as $item) {
      $title = $item['book_title'];
      array_push($books, $bookModel->findBooks($title, FALSE, FALSE, FALSE, FALSE));
    }
    $this->messages = "Favorite book of ";
    if (isset($books)) {
      $this->content = "<div id='messages'><p>Favorites books of ".$_SESSION['user']->getUsername()."</p></div>";
      $this->content .= "<div class='row'>";
      foreach ((array)$books as $book) {
        $this->content .= $this->render('/views/wish_books.tpl.php', array('book' => $book));
      }
      $this->content .= "</div>";
    }

    $this->renderLayout('/views/layouts/basic.tpl.php');
  }

  public function uploadImage()
  {
    $uploadImage = new Upload();
    $uploadImage->uploadImage();
  }



}