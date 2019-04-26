<?php

namespace Controllers;

use Entities\TermEntity;
use Models\BookModel;
use Models\Config;
use Models\TermModel;

class HomeController extends BasicController
{
  public function __construct()
  {
    parent::__construct();
    $this->title = "Homepage";
    $this->addScript('/js/plugins/chosen/chosen.jquery.min.js');
    $this->addScript('/js/home.js');
    $this->addCSS('/js/plugins/chosen/chosen.css');
  }

  /**
   * Get call from '/' or '/home' and show 12 books
   */
  public function get()
  {
    $this->sidebar = $this->getHomepageSearchFormHTML();
    $bookModel = new BookModel();
    $configModel = new Config();
    $limit = $configModel->get('customer_default_max_books_results_per_page', 12);
    $books = $bookModel->findBooks(FALSE, FALSE, FALSE, FALSE, FALSE, $limit);
    if (isset($books)) {
      $this->content = "<div class='row'>";
      foreach ((array)$books as $book) {
        $this->content .= $this->render('/views/homepage_content.tpl.php', array('book' => $book));
      }
      $this->content .= "</div>";
    }
    $this->renderLayout('/views/layouts/sidebar_page.tpl.php');
  }

  /**
   * Get call from /search
   */
  public function homeSearchPage()
  {
    $limit = FALSE;
    $params = array();
    $params['title'] = isset($_GET['search-title']) ? $_GET['search-title'] : '';
    $params['priceFrom'] = isset($_GET['search-priceFrom']) ? $_GET['search-priceFrom'] : '';
    $params['priceTo'] = isset($_GET['search-priceTo']) ? $_GET['search-priceTo'] : '';
    $params['author'] = isset($_GET['search-author']) ? $_GET['search-author'] : '';


    $bookModel = new BookModel();
    $validationHomeSearch = $bookModel->validateHomeSearch($params);

    if ($validationHomeSearch === TRUE) {
      $books = call_user_func_array(array($bookModel, 'findBooks'), array_merge($params, array('category' => FALSE, 'limit' => $limit)));
      if (isset($books)) {
        $this->content = "<div class='row'>";
        foreach ((array)$books as $book) {
          $this->content .= $this->render('/views/homepage_content.tpl.php', array('book' => $book));
        }
        $this->content .= "</div>";
      }
      $this->sidebar = $this->getHomepageSearchFormHTML($params);
    }

    $this->renderLayout('/views/layouts/sidebar_page.tpl.php');
  }

  /**
   * Get authors and put them in a list <option>
   *
   * @param array $params(optional)
   *   Form inputs default values. Default being empty array.
   *
   * @return string
   *   Form html output.
   *
   * @throws \Exception
   */
  public function getHomepageSearchFormHTML($params = array())
  {
    $termModel = new TermModel();
    $authors = $termModel->findTermsByVocabulary('authors');

    $author_options = '';

    /** @var TermEntity $author */
    foreach ($authors as $author) {
      if(isset($params['author']) && $author->getName() == $params['author']){
        $author_options .= "<option value ='" . $author->getName() . "' selected>" . $author->getName() . "</option>";
      } else {
        $author_options .= "<option value ='" . $author->getName() . "' >" . $author->getName() . "</option>";
      }
    }

    $output = $this->render('/views/forms/searchhome_form.tpl.php', array_merge($params, array('author_options' => $author_options)));

    return $output;
  }

}