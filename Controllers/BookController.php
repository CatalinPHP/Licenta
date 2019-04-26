<?php
/**
 * Created by PhpStorm.
 * User: Monica
 * Date: 8/28/2018
 * Time: 11:37 AM
 */

namespace Controllers;


use Entities\CommentEntity;
use Models\BookModel;
use Models\CommentModel;
use Models\TermModel;

class BookController extends BasicController
{
  public function __construct()
  {
    parent::__construct();
    $this->addCSS("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css");
    $this->addCSS("/css/style.css");
    $this->addScript("/js/admin.js");
    $this->addScript("/js/wish.js");
  }


  /**
   * @param int $id
   * The id of the book selected.
   *
   * @throws \atk4\core\Exception]
   *
   * Creates a page with the information of a book.
   */
  public function bookPageAction($id)
  {
    $bookModel = new BookModel();
    $book = $bookModel->getBook($id);
    if($book) {
      $title = $book->getTitle();
      $description = $book->getDescription();
      $shortDescription = substr($description, 0, 200);
      $rating = $book->getRating();
      $image = $book->getImage();
      $buy_link = $book->getBuy_link();
      $language = $book->getLanguage();
      $price = $book->getPrice();
      $ISBN_13 = $book->getISBN_13();
      $id = $book->getId();
      if(!$price) {
        $price = "FREE";
      }
      $currency = $book->getCurrency();
      $book->populateReferences();
      $authorNames = $book->getAuthorNames();
      $authors = "";
      foreach ($authorNames as $name) {
        $authors .= ", " . $name;
      }
      $authors = substr($authors, 1);
      $categoryNames = $book->getCategoryNames();
      $category = "";
      foreach ($categoryNames as $name) {
        $category .= ", " . $name;
      }
      $category = substr($category, 1);
      $stars = "";

      for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
          $stars .= '<span class="fa fa-star checked"></span>';
        } else {
          $stars .= '<span class="fa fa-star"></span>';
        }
      }

      $this->content = $this->render('/views/book_content.tpl.php',
        array(
          'title' => $title,
          'authors' => $authors,
          'category' => $category,
          'description' => $shortDescription,
          'longDescription' => $description,
          'rating' => $rating,
          'stars' => $stars,
          'image' => $image,
          'language' => $language,
          'price' => $price . " " . $currency,
          'buyLink' => $buy_link,
          'ISBN_13' => $ISBN_13));
    } else {
      $this->content = "This book does not exist";
    }
    $comment = $this->getComments($id);
    $this->content .=$this->render('views/coment_section.php',
      array(
        'id' => $id ,
        'comment' => $comment));
    $this->renderLayout('/views/layouts/basic.tpl.php');

  }

  public function wishBooksAddRemove(){
    $id_user = $_SESSION['user']->getUserId();
    $title = $_GET['BookTitle'];
    $book = new BookModel();
    $result = $book->existWishBook($id_user,$title);
    if(!$result){
      $book->addWishBook($id_user,$title);
      redirect('/home?add');

    } else {
      $book->deleteWishBook($id_user,$title);
      redirect('/wishBooks?delete');

    }
  }

  public function setComments(){
    $idBook = $_POST['idBook'];
    $username = $_POST['username'];
    $date = $_POST['date'];
    $comment = $_POST['comment'];
    $Comment = new CommentEntity($username,$idBook,$date,$comment);
    $setComm = new CommentModel();
    $setComm->addComment($Comment);
    $path = '/book/'.$idBook;
    redirect($path);
  }

  public function getComments($id){
    $Comments = new CommentModel();
    $comment = $Comments->getComment($id);
    $commentAria = "<div>";
    foreach ($comment as $comm){
      $commentAria .= "<div class='comment-box'>";
      $commentAria .= "<h4>".$comm['username']."</h4>";
      $commentAria .= $comm['date'];
      $commentAria .= "<p>".$comm['comment']."</p>";
      $commentAria .= "</div>";
    }
    $commentAria .= "</div>";
    return $commentAria;

  }

}