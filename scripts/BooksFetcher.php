<?php

namespace Scripts;


use  atk4\core\Exception;
use Entities\BookEntity;
use Entities\TermEntity;
use Models\BookModel;
use Models\TermModel;

class BooksFetcher
{
  private $messages = "";

  /**
   * Make request to external API.
   *
   * @param string $title
   *  Book title (full or partial title).
   * @param string $author
   *  Book author (full or partial author).
   * @param int $startIndex
   *  Sets from where to start index.
   *
   * @return mixed
   */
  public function fetchBooks($title, $author, $category, $startIndex = 0)
  {
    $title = urlencode($title);
    $author = urlencode($author);
    $category = urlencode($category);

    // Get cURL resource
    $curl = curl_init();

    // Set some options - we are passing in a useragent too here
    curl_setopt_array($curl, array(
      CURLOPT_RETURNTRANSFER => TRUE,
      CURLOPT_URL => 'https://www.googleapis.com/books/v1/volumes?q="' . $title . '""&inauthor:' . $author . '&subject:' . $category . '&maxResults=40&startIndex=' . $startIndex . '',
    ));

    // Send the request & save response to $resp
    $resp = curl_exec($curl);

    // Close request to clear up some resources
    curl_close($curl);

    return $resp;
  }

  /**
   * Get default values for book properties.
   *
   * @return array
   *   A list with default values.
   */
  public function getBookDefaultValues()
  {
    return array(
      'ISBN_10' => NULL,
      'ISBN_13' => NULL,
      'authors' => NULL,
      'description' => NULL,
      'language' => NULL,
      'rating' => NULL,
      'categories' => NULL,
      'image' => NULL,
      'retailPrice' => NULL,
      'currencyCode' => NULL,
      'buyLink' => NULL
    );
  }

  /**
   * Process books info.
   *
   * @param array $booksInfo
   *  Raw data from API.
   *
   * @return array
   *  List with all processed books.
   */
  public function processBooksInfo($booksInfo)
  {
    $processedBooks = [];
    if (isset($booksInfo["items"])) {
      foreach ($booksInfo["items"] as $bookRawData) {

        $bookItem = array();
        $volumeInfo = !empty($bookRawData['volumeInfo']) ? $bookRawData['volumeInfo'] : FALSE;
        if ($volumeInfo) {
          $bookItem['title'] = $volumeInfo['title'];
          $bookItem['authors'] = !empty($volumeInfo['authors']) ? $volumeInfo['authors'] : array();
          $bookItem['description'] = !empty($volumeInfo['description']) ? $volumeInfo['description'] : '';
          $bookItem['language'] = !empty($volumeInfo['language']) ? $volumeInfo['language'] : '';
          $bookItem['rating'] = !empty($volumeInfo['averageRating']) ? floatval($volumeInfo['averageRating']) : NULL;
          $bookItem['categories'] = !empty($volumeInfo['categories']) ? $volumeInfo['categories'] : array();

        }

        $isbnInfo = !empty($volumeInfo['industryIdentifiers']) ? $volumeInfo['industryIdentifiers'] : array();

        // Add ISBN identifiers. Exclude any identifier that is not related to ISBN.
        foreach ($isbnInfo as $isbnItem) {
          if (strpos($isbnItem['type'], 'ISBN_') !== FALSE) {
            $bookItem[$isbnItem['type']] = $isbnItem['identifier'];
          }
        }

        $imageInfo = !empty($volumeInfo['imageLinks']) ? $volumeInfo['imageLinks'] : NULL;
        if ($imageInfo) {
          $bookItem['image'] = !empty($imageInfo['thumbnail']) ? $imageInfo['thumbnail'] : NULL;
        }

        $saleInfo = !empty($bookRawData['saleInfo']) ? $bookRawData['saleInfo'] : array();
        if ($saleInfo) {
          $bookItem['price'] = !empty($saleInfo['retailPrice']) ? $saleInfo['retailPrice']['amount'] : NULL;
          $bookItem['currency'] = !empty($saleInfo['retailPrice']) ? $saleInfo['retailPrice']['currencyCode'] : NULL;
          $bookItem['buy_link'] = !empty($saleInfo['buyLink']) ? $saleInfo['buyLink'] : NULL;
        }

        $processedBooks[] = $bookItem + $this->getBookDefaultValues();

      }
    }

    return $processedBooks;

  }

  /**
   * Get books from API based on title and author.
   *
   * @param string $title
   *   Book title (full or partial title).
   * @param string $author
   *   Book author (full or partial author).
   *
   * @return array
   *   A list with all books from external API that matched provided title/author.
   */
  public function getApiBooks($title, $author, $category)
  {
    $startIndex = 0;
    $totalItems = NULL;
    $totalItemsMax = 0;
    $allProcessedBooks = array();
    while (is_null($totalItems) || $startIndex < $totalItems) {
      $response = $this->fetchBooks($title, $author, $category, $startIndex);
      $startIndex += 40;
      $booksInfo = json_decode($response, true);
      if (isset($booksInfo['totalItems'])) {
        $totalItems = intval($booksInfo['totalItems']);
      }
      $allProcessedBooks = array_merge($allProcessedBooks, $this->processBooksInfo($booksInfo));
      if ($totalItemsMax < $totalItems) {
        $totalItemsMax = $totalItems;
      }
      $this->messages .= 'Imported ' . $startIndex . ' from a total of ' . $totalItemsMax;
    }
    return $allProcessedBooks;
  }

  /**
   * Insert books from API into the database
   *
   * @param string $title
   *  Book's title(full or partial)
   * @param string $author
   *  Book's author(full or partial)
   * @param string $category
   *  Book's category.
   *
   * @throws Exception
   * @throws \atk4\dsql\Exception
   */

  public function InsertBooks($title, $author, $category)
  {
    $termModel = new TermModel();
    $booksFetcher = new BooksFetcher();
    $bookModel = new BookModel();
    $booksRawData = $booksFetcher->getApiBooks($title, $author, $category);
    $this->messages = $booksFetcher->messages;
    $vids = array(
      'categories' => $termModel->getVocabularyId('categories'),
      'authors' => $termModel->getVocabularyId('authors'),
    );

    /** @var BookEntity $book */
    foreach ($booksRawData as $book) {
      $categories = [];
      // Save and load categories.
      foreach ($book['categories'] as $categoryName) {
        $term = $termModel->findFirstTerm($categoryName, 'categories');
        if ($term) {
          $categories[] = $term->getTid();
        } else {
          // save term and get last id.
          $term = new TermEntity();
          $term->setName($categoryName);
          $term->setVid($vids['categories']);
          $tid = $termModel->saveTerm($term);
          if ($tid) {
            $categories[] = $tid;
          }
        }
        $book['categoryIds'] = $categories;
      }

      // Save and load authors.
      $authors = [];
      foreach ($book['authors'] as $authorName) {
        $term = $termModel->findFirstTerm($authorName, 'authors');
        if ($term) {
          $authors[] = $term->getTid();
        } else {
          $term = new TermEntity();
          $term->setName($authorName);
          $term->setVid($vids['authors']);
          $tid = $termModel->saveTerm($term);
          if ($tid) {
            $authors[] = $tid;
          }
        }
        $book['authorsIds'] = $authors;
      }

      // Save book.
      $bookEntity = new BookEntity($book);
      try {
        if (!$bookModel->findDuplicateBooks($bookEntity->getTitle(), $bookEntity->getISBN_10(), $bookEntity->getISBN_13())) {
          $bookModel->saveBook($bookEntity);
        }
      } catch (Exception $e) {
        $this->messages .= 'Unable to save book with title: ' . $bookEntity->getTitle() . PHP_EOL;
      }


    }
  }

  public function getMessages()
  {
    return $this->messages;
  }
}
