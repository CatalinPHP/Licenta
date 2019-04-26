<?php

namespace Models;

use atk4\core\Exception;
use Entities\AdminEntity;
use atk4\dsql\Expression;

class AdminModel extends BasicModel
{
  /**
   * Save new admin in database.
   *
   * @param AdminEntity $adminObject
   */
  public function saveAdmin($adminObject)
  {
    $email = $adminObject->getEmail();
    $password = $adminObject->getPassword();
    $query = $this->db2->dsql();
    $query->table('admin')
      ->set('email', $email)
      ->set('password', Md5($password))
      ->insert();
  }

  /**
   * Check if email is already in use.
   *
   * @param string $email
   *
   * @return bool
   *   TRUE if email is already used, FALSE otherwise.
   */
  private function isEmailInUse($email)
  {
    $query = $this->db2->dsql();
    $results = $query->table('admin')
      ->where('email', '=', $email)
      ->getRow();
    if ($results) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Validation for the register
   *
   * @return array|bool
   *    TRUE if there are no error , FALSE if there are no errors -array
   */
  public function validateRegister()
  {
    $errors = array();

    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($email)) {
      $errors['email'][] = "Email can't be empty.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'][] = "Invalid email provided.";
    }
    if ($this->isEmailInUse($email)) {
      $errors['email'][] = "Email is used already.";
    }

    if (empty($password)) {
      $errors['password'][] = "Password can't be empty.";
    } else {
      if (strlen($password) < 6) {
        $errors['password'][] = "Password can't be lower than 6 characters.";
      }
      if ($password != $confirm_password) {
        $errors['password'][] = "Password and Confirm Password don't match.";
      }
    }

    return !empty($errors) ? array('errors' => $errors) : TRUE;

  }

  /**
   * Validation for the login
   *
   * @return array|bool
   *    TRUE if there are no error , FALSE if there are no errors -array
   */
  public function validateLogin()
  {
    $errors = array();

    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email)) {
      $errors['email'][] = "Email can't be empty.";
    } else {
      if (empty($password)) {
        $errors['password'][] = "Password can't be empty.";
      }
    }
    return !empty($errors) ? array('errors' => $errors) : TRUE;
  }

  /**
   * Makes a session with the user
   *
   * @param string $email
   *
   * @param string $password
   *
   * @return bool|AdminEntity
   *      IF there isn't already a user login with that email it will make a session , FALSE otherwise
   */
  public function login($email, $password)
  {

    $query = $this->db2->dsql();
    $results = $query->table('admin')
      ->where('email', '=', $email)
      ->where('password', '=', Md5($password))
      ->getRow();
    if ($results ) {
      $admin = new AdminEntity($results['email']);
      $admin->setPassword($results['password']);
      $admin->setAdminId($results['admin_id']);
      session_start();
      $_SESSION['admin'] = $admin;
      return $admin;
    }
    return FALSE;

  }

  /**
   * Unregister a session variable
   */
  public function logout()
  {

    if (isset($_SESSION['admin'])) {
      unset($_SESSION['admin']);
    }
  }

}