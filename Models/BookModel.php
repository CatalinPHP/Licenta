<?php

namespace Models;

use atk4\core\Exception;
use Entities\BookEntity;
use Models\BasicModel;
use atk4\dsql\Expression;
use Scripts\BooksFetcher;
use Models\TermModel;
use Entities\TermEntity;

class BookModel extends BasicModel
{

  /**
   * Save book into database
   *
   * @param BookEntity $book
   *   BookEntity object.
   *
   * @throws Exception
   */
  public function saveBook($book)
  {
    $title = $book->gettitle();
    $description = $book->getDescription();
    $rating = $book->getRating();
    $ISBN_13 = $book->getISBN_13();
    $ISBN_10 = $book->getISBN_10();
    $image = $book->getImage();
    $language = $book->getLanguage();
    $price = $book->getPrice();
    $currency = $book->getCurrency();
    $buy_link = $book->getBuy_link();
    $authors = $book->getAuthorsIds();
    $categories = $book->getCategoriesIds();

    $query = $this->db2->dsql();
    $query->table('books')
      ->set('title', $title)
      ->set('description', $description)
      ->set('rating', $rating)
      ->set('ISBN_13', $ISBN_13)
      ->set('ISBN_10', $ISBN_10)
      ->set('image', $image)
      ->set('language', $language)
      ->set('price', $price)
      ->set('currency', $currency)
      ->set('buy_link', $buy_link)
      ->insert();
    $bookId = intval($this->db2->lastInsertID());

    foreach ($authors as $tid) {
      $query = $this->db2->dsql();
      $query->table('field_authors')
        ->set('entity_id', $bookId)
        ->set('entity_type', 'book')
        ->set('term_id', intval($tid))
        ->insert();
    }
    foreach ($categories as $tid) {
      $query = $this->db2->dsql();
      $query->table('field_categories')
        ->set('entity_id', $bookId)
        ->set('entity_type', 'book')
        ->set('term_id', intval($tid))
        ->insert();
    }
  }


  /**
   * Get book from database based on id
   *
   * @param int $id
   *   Book id.
   *
   * @return BookEntity/null
   *
   * @throws Exception
   */
  public function getBook($id)
  {
    $term = FALSE;
    $query = $this->db2->dsql();
    $resultRow = $query->table('books', 'B')
      ->join('field_authors FA', new Expression('B.id = FA.entity_id'))
      ->join('field_categories FC', new Expression('B.id=FC.entity_id'))
      ->where('id', $id)
      ->field(new Expression('GROUP_CONCAT(DISTINCT FA.term_id)'), 'authorsIds')
      ->field(new Expression('GROUP_CONCAT(DISTINCT FC.term_id)'), 'categoryIds')
      ->field('B.*')
      ->group('B.id')
      ->getRow();
    if ($resultRow) {
      $resultRow['authorsIds'] = explode(',', $resultRow['authorsIds']);
      $resultRow['categoryIds'] = explode(',', $resultRow['categoryIds']);
      $term = new BookEntity($resultRow);
    }
    return $term;
  }


  /**
   * Find Books in database based on title and price
   *
   * @param string $title
   *   Book title.
   * @param null|int $priceFrom (optional)
   *   PriceFrom if we need to filter by price
   *   NULL - default value, no price imposed.
   * @param null|int $priceTo (optional)
   *   PriceTo if we need to filter by price
   *   NULL - default value, no price imposed.
   * @param null|string $author (optional)
   *   The name of the author
   *   NULL - default value, no author imposed.
   * @param null|string $category (optional)
   *   The category of the book
   *    NULL - default value, no category imposed.
   * @param bool|int $limit (optional)
   *    Limit search results to provided number.
   *    FALSE - default value, not limit imposed.
   *
   * @return array
   *   Returns a list of BookEntity objects or empty list if nothing found.
   *
   * @throws Exception
   */
  public function findBooks($title, $priceFrom = NULL, $priceTo = NULL, $author = NULL, $category = NULL, $limit = FALSE)
  {
    $books = array();
    $query = $this->db2->dsql();
    $results = $query->table('books', 'B')
      ->join('field_authors FA', new Expression('B.id = FA.entity_id'))
      ->join('terms T', new Expression('T.tid = FA.term_id'))
      ->join('vocabulary V', new Expression('T.vid = V.vid'))
      ->join('field_categories FC', new Expression('B.id=FC.entity_id'))
      ->join('terms Tm', new Expression('Tm.tid=FC.term_id'))
      ->join('vocabulary V2', new Expression('Tm.vid=V2.vid'))
      ->field('B.image')
      ->field(new Expression('GROUP_CONCAT(DISTINCT FA.term_id)'), 'authorsIds')
      ->field(new Expression('GROUP_CONCAT(DISTINCT FC.term_id)'), 'categoryIds')
      ->field('B.title')
      ->field('B.description')
      ->field('T.name')
      ->field('B.buy_link')
      ->field('B.id')
      ->field('B.language')
      ->field('B.rating')
      ->group('B.id')
      ->where('V.vocabulary', 'authors')
      ->where('V2.vocabulary', 'categories')
      ->where('FA.entity_type', 'book')
      ->where('FC.entity_type', 'book')
      ->where('B.title', 'like', "%$title%");

    if ($priceFrom) {
      $query->where('B.price', '>=', $priceFrom);
    }
    if ($priceTo) {
      $query->where('B.price', '<=', $priceTo);
    }
    if ($author) {
      $query->join('field_authors FAC', new Expression('B.id = FAC.entity_id'));
      $query->join('terms TC', new Expression('TC.tid = FAC.term_id'));
      $query->where('TC.name', '=', "$author");
    }
    if ($category) {
      $query->join('field_categories FC2', new Expression('B.id=FC2.entity_id'));
      $query->join('terms T2', new Expression('T2.tid=FC2.term_id'));
      $query->where('T2.name', 'like', "%$category%");
    }
    if ($limit && is_numeric($limit)) {
      $first = rand(1, 600);
      if ($first < 600 - $limit) {
        $second = $first + $limit;
      } else {
        $second = $first - $limit;

      }
      $query->where($query->expr(
        '`B`.`id` between [] and []', [$first, $second]));
    }


    $query->get();
    foreach ($results as $entry) {
      $entry['authorsIds'] = explode(',', $entry['authorsIds']);
      $entry['categoryIds'] = explode(',', $entry['categoryIds']);
      $book = new BookEntity($entry);
      $book->populateReferences();
      $books[] = $book;
    }
    return $books;
  }

  /**
   * Find the first book in the database based on title and price
   *
   * @param string $title
   *   Book title.
   * @param null|int $priceFrom (optional)
   *   PriceFrom if we need to filter by price
   * @param null|int $priceTo (optional)
   *   PriceTo if we need to filter by price
   *
   * @return BookEntity|NULL
   *   Returns a BookEntity object or NULL if nothing found.
   *
   * @throws Exception
   */
  public function findFirstBook($title, $priceFrom = NULL, $priceTo = NULL, $author = NULL, $category = NULL)
  {
    $results = $this->findBooks($title, $priceFrom, $priceTo, $author, $category);
    return reset($results);

  }

  /**
   * Checks if there already is a book in the database
   *
   * @param string $title
   *  Book's title
   * @param null|string $ISBN_10 (optional)
   *  Book's ISBN_10
   * @param null|string $ISBN_13 (optional)
   *  Book's ISBN_13
   *
   * @return array
   *
   * @throws \atk4\dsql\Exception
   */
  public function findDuplicateBooks($title, $ISBN_10 = NULL, $ISBN_13 = NULL)
  {
    $query = $this->db2->dsql();
    $checkISBNs = $query->orExpr();

    $result = $query->table('books')
      ->where('title', $title);
    if ($ISBN_10) {
      $checkISBNs->where('ISBN_10', $ISBN_10);
    }
    if ($ISBN_13) {
      $checkISBNs->where('ISBN_13', $ISBN_13);
    }
    if ($ISBN_10 || $ISBN_13) {
      $query->where($checkISBNs);
    }
    $book = $result->get();
    return $book;
  }

  /**
   * Validates the priceFrom and priceTo fields from the search form
   * to have only numeric values.
   *
   * @param $params
   *  All the params received from the search form after submit.
   *
   * @return array
   *  Returns an array with messages for the validation errors found
   *  or an empty array if no errors found.
   */
  public function validateSearch($params)
  {
    $errors = array();
    if (isset($params['priceFrom'])) {
      $priceFrom = $params['priceFrom'];
    }
    if (isset($params['priceTo'])) {
      $priceTo = $params['priceTo'];
    }

    if (isset($priceFrom) && !(is_numeric($priceFrom)) && !empty($priceFrom)) {
      $validationMessage = $priceFrom . " is not numeric.Please enter a numeric value for Price From!";
      array_push($errors, $validationMessage);
    }
    if (isset($priceTo) && !(is_numeric($priceTo)) && !empty($priceTo)) {
      $validationMessage = $priceTo . " is not numeric. Please enter a numeric value for Price To!";
      array_push($errors, $validationMessage);
    }

    return $errors;
  }

  /**
   * @param $id
   * @throws \atk4\dsql\Exception
   */
  public function delete($id)
  {
    $query = $this->db2->dsql();
    $query->table('books')
      ->where('id', $id)
      ->delete();

    $query = $this->db2->dsql();
    $query->table('field_categories')
      ->where('entity_id', $id)
      ->where('entity_type', 'book')
      ->delete();

    $query = $this->db2->dsql();
    $query->table('field_authors')
      ->where('entity_id', $id)
      ->where('entity_type', 'book')
      ->delete();
  }

  /**
   * Edit book's description based on id.
   *
   * @param int $id
   *  Book's id.
   * @param string $description
   *  Book's description
   *
   * @throws \atk4\dsql\Exception
   */
  public function updateBook($id, $description)
  {
    $query = $this->db2->dsql();
    $query->table('books')
      ->where('id', $id)
      ->set('description', $description)
      ->update();
  }

  /**
   *  Validation callback for homepage search.
   *
   * @param array $params
   *  Form inputs default values.
   *
   * @return bool
   *  TRUE if valid parameters, FALSE otherwise.
   */
  public function validateHomeSearch($params)
  {
    $valid = TRUE;
    $searchPriceFrom = $params['priceFrom'];
    $searchPriceTo = $params['priceTo'];

    if (!empty($searchPriceFrom)) {
      if (!is_numeric($searchPriceFrom)) {
        set_message('error', $searchPriceFrom . " is not numeric. Please enter a numeric value!");
        $valid = FALSE;
      }
    }

    if (!empty($searchPriceTo)) {

      if (!is_numeric($searchPriceTo)) {
        set_message('error', $searchPriceTo . " is not numeric. Please enter a numeric value!");
        $valid = FALSE;
      }
    }
    return $valid;
  }

  /**
   * @param $id_user
   * @param $title
   * @throws \atk4\dsql\Exception
   */
  public function addWishBook($id_user, $title)
  {
    $query = $this->db2->dsql();
    $query->table('wish_books')
      ->set('id_user', $id_user)
      ->set('book_title', $title)
      ->insert();
  }

  /**
   * @param $id_user
   * @param $title
   * @throws \atk4\dsql\Exception
   */
  public function deleteWishBook($id_user, $title)
  {
    $query = $this->db2->dsql();
    $query->table('wish_books')
      ->where('id_user', '=', $id_user)
      ->where('book_title', '=', $title)
      ->delete();
  }

  public function existWishBook($id_user, $title)
  {
    $query = $this->db2->dsql();
    $result = $query->table('wish_books')
      ->where('id_user', '=', $id_user)
      ->where('book_title', '=', $title)
      ->getRow();
    return $result;
  }

  public function takeAllWishBook()
  {
    $user_id = $_SESSION['user']->getUserId();
    $query = $this->db2->dsql();
    $result = $query->table('wish_books')
      ->where('id_user', '=', $user_id)
      ->get();
    return $result;

  }

  public function latestBooks($ISBN_13){

    $query = $this->db2->dsql();
    $result = $query->table('books')
      ->field('title');
  }
}
