<?php
/**
 * Created by PhpStorm.
 * User: Caius
 * Date: 8/23/2018
 * Time: 10:21 AM
 */

namespace Models;


use atk4\core\Exception;

class Config extends BasicModel
{
  /**
   * Insert name and value in database.
   *
   * @param string $name
   *  Name to enter in database.
   * @param int $value
   *  Value to enter in database.
   *
   * @throws Exception
   */
  public function set($name, $value)
  {
    $value = serialize($value);

    $dbValue = $this->get($name);
    $query = $this->db2->dsql();
    if (is_null($dbValue)) {

      $query->table('configuration')
        ->set('name', $name)
        ->set('value', $value)
        ->insert();
    }
    else {
      $query->table('configuration')
        ->where('name', $name)
        ->set('value', $value)
        ->update();
    }
  }

  /**
   * Get the values of an given name from database.
   *
   * @param string $name
   *  Name of an elemnt from database.
   * @param mixed $default
   *  Defaault value used if nothing is found in database.
   *
   * @return mixed
   *   Value for config key.
   *
   * @throws Exception
   */
  public function get($name, $default = NULL)
  {
    $query = $this->db2->dsql();
    $resultRow = $query->table('configuration')
      ->field('value')
      ->where('name', $name)
      ->getRow();
    return $resultRow ? unserialize($resultRow["value"]) : $default;
  }

  /**
   * Insert multiple names and values in database.
   *
   * @param array $multiple
   *  Array of values to add in database.
   * @throws Exception
   */
  public function setMultiple($multiple = array())
  {
    foreach ($multiple as $name => $value) {
      $this->set($name, $value);
    }
  }

  /**
   * Validation callback for admin settings form.
   *
   * @return array|bool
   *  TRUE if valid
   *  List error messages if not valid.
   */
  public function adminSettingsValidation()
  {
    $errors = array();
    $maxResults = $_POST['customer_default_max_books_results_per_page'];
    $url = $_POST['google_api_endpoint'];

    // Validate Google API Endpoint.
    if (empty($url)) {
      $errors[] = "Google Api Endpoint - This field can't be empty.";
    } else if (!filter_var($url, FILTER_VALIDATE_URL)) {
      $errors[] = "Google Api Endpoint - This field must contain a valid url.";
    }

    // Validate max books displayed for customer.
    if (!preg_match('/^[0-9]*$/', $maxResults)) {
      $errors[] = "Max Books - This field must contain just digits.";
    }

    return !empty($errors) ? array('errors' => $errors) : TRUE;
  }

  /**
   * Submit admin settings in database.
   */
  public function adminSettingsSubmit()
  {

    if (isset($_POST['google_api_endpoint'])) {
      $this->set('google_api_endpoint', $_POST['google_api_endpoint']);
    }

    if (isset($_POST['customer_default_max_books_results_per_page'])) {
      $this->set('customer_default_max_books_results_per_page', intval($_POST['customer_default_max_books_results_per_page']));
    }

  }

}