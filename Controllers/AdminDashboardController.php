<?php

namespace Controllers;

use Entities\BookEntity;
use Exception;
use Models\BasicModel;
use Models\Config;
use Scripts\BooksFetcher;

use Entities\TermEntity;
use Models\BookModel;
use Models\TermModel;

class AdminDashboardController extends BasicController
{

  public function __construct()
  {
    parent::__construct();
    $this->title = "Admin Dashboard";
    $this->addScript('/js/plugins/chosen/chosen.jquery.min.js');
    $this->addScript('/js/admin.js');
    $this->addScript('/js/adminSettings.js');
    $this->addCSS('/js/plugins/chosen/chosen.css');
    $this->addCSS("/css/style.css");
    $this->addCSS("/css/settingsAdmin.css");
    unset($this->menu["/user"]);
    $this->menu["/admin"] = "Admin";
    global $admin;
    if ($admin === NULL) {
      redirect('/login');
    }
  }

  /**
   * GET callback for /admin page.
   */
  public function get()
  {
    $this->sidebar = $this->getSearchFormHtml();
    $this->content = $this->searchResultsHtml();
    $this->renderLayout('/views/layouts/sidebar_page.tpl.php');
  }

  /**
   * GET callback for /admin/settings page.
   */
  public function adminSettingsPage()
  {
    $config = new Config();
    $apiUrl = $config->get('google_api_endpoint');
    $maxBooks = $config->get('customer_default_max_books_results_per_page');
    $this->content = $this->render('/views/admin_settings_sidebar.php');
    $this->content .= $this->render('/views/forms/admin_settings_form.tpl.php', array('apiUrl' => $apiUrl, 'maxBooks' => $maxBooks));
    $this->renderLayout('/views/layouts/basic.tpl.php');
  }


  public function searchResultsHtml($entries = array())
  {

    $body = '<tbody>';
    foreach ($entries as $entry) {
      $body .= "";
    }
    $body .= '</tbody>';

    $output = $this->render('/views/search_results.tpl.php', array('body' => $body));

    return $output;
  }

  public function getSearchFormHtml()
  {

    $termModel = new TermModel();
    $authors = $termModel->findTermsByVocabulary('authors');
    $categories = $termModel->findTermsByVocabulary('categories');
    $optionsAuthor = '';
    $optionsCategory = '';

    /** @var TermEntity $author */
    foreach ($authors as $author) {
      $optionsAuthor .= "<option value ='" . $author->getName() . "' >" . $author->getName() . "</option>";
    }
    /**@var TermEntity $category */
    foreach ($categories as $category) {
      $optionsCategory .= "<option value ='" . $category->getName() . "' >" . $category->getName() . "</option>";

    }
    $output = $this->render('/views/forms/search-form.tpl.php', array('optionsAuthor' => $optionsAuthor, 'optionsCategory' => $optionsCategory));
    return $output;
  }

  /**
   * Check validation then submit in database.
   * Autofill form fields with info from last submit.
   */
  public function adminSettingsAction()
  {
    $config = new Config();

    $validationResult = $config->adminSettingsValidation();
    if ($validationResult === TRUE) {
      $config->adminSettingsSubmit();
      redirect('/admin/settings');
    } else {
      $errors = generate_messages($validationResult);
      $apiUrl = $_POST['google_api_endpoint'];
      $maxBooks = $_POST['customer_default_max_books_results_per_page'];
      $this->content = $this->render('/views/forms/admin_settings_form.tpl.php',
        array('errors' => $errors, 'apiUrl' => $apiUrl, 'maxBooks' => $maxBooks));
      $this->renderLayout('/views/layouts/basic.tpl.php');
    }
  }

  /**
   * Delete book entry.
   *
   * @param int $id
   * Book's id.
   *
   * @throws \atk4\dsql\Exception
   */
  public function deleteAction($id = NULL)
  {
    $book = new BookModel();
    if (!empty($_POST)) {

      $book->delete($_POST['bookId']);
      redirect('/admin');

    } else {
      $this->content = $this->render('/views/forms/admin_delete_book_form.tpl.php', array('id' => $id));
      $this->renderLayout('/views/layouts/basic.tpl.php');
    }
  }

  /**
   * Edit book Entry.
   *
   * @param int $id
   *  Book's id.
   *
   * Update book's description.
   */
  public function editBookAction($id)
  {
    $description = $_POST['description'];
    $bookModel = new BookModel();
    try {
      $bookModel->updateBook($id, $description);
      set_message('status', 'Saved book.');
    } catch (Exception $e) {
      set_message('error', 'Failed to save book');
    }
    redirect("/admin/edit/$id");
  }

  /**
   * @param int $id
   *  Book's id.
   *
   * @throws \atk4\core\Exception
   *
   * GET callback for /admin/edit/$id page.
   */
  public function editBookPage($id)
  {

    $bookModel = new BookModel();
    $book = $bookModel->getBook($id);
    if ($book) {
      $this->content = $this->render('/views/forms/edit_books_form.tpl.php', array('id' => $book->getId(), 'title' => $book->getTitle(), 'ISBN_10' => $book->getISBN_10(), 'ISBN_13' => $book->getISBN_13(), 'description' => $book->getDescription()));

    } else {
      $this->content = "There is no book with id: $id";
    }
    $this->renderLayout('/views/layouts/basic.tpl.php');
  }

  public function uploadBooks()
  {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $bookFetcher = new BooksFetcher();
    $bookFetcher->InsertBooks($title, $author, $category);
    $message = $bookFetcher->getMessages();
    $this->content = $this->render('/views/forms/admin_settings_form.tpl.php', array('id' => $message));
    $this->renderLayout('/views/layouts/sidebar_page.tpl.php');
  }

}

