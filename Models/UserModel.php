<?php
/**
 * Created by PhpStorm.
 * User: Catalin
 * Date: 12.02.2019
 * Time: 12:33
 */

namespace Models;


use Entities\UserEntity;

class UserModel extends BasicModel
{
  /**
   * @param $userObj
   * @throws \atk4\dsql\Exception
   *
   */


  public function saveUser($userObj)
  {
    $username = $userObj->getUsername();
    $password = $userObj->getPassword();
    $query = $this->db2->dsql();
    $query->table('users')
      ->set('username', $username)
      ->set('password', Md5($password))
      ->insert();
  }

  /**
   * @param $username
   * @param $password
   * @return bool
   * @throws \atk4\dsql\Exception
   */
  public function validateUser($username, $password)
  {
    $query = $this->db2->dsql();
    $result = $query->table('users')
      ->where("username", '=', $username)
      ->where("password", '=', MD5($password))
      ->getRow();
    if ($result) {
      return TRUE;
    }

    return False;
  }

  /**
   * Validate user register
   *
   * Validate the username if is not empty and it is form
   * just with letters and numbers, the password to be at list of
   * 6 characters and to confirm it.
   *
   * @return array $errors
   *    An array with errors messages
   */
  function registerValidation()
  {
    $errors = array();

    // password validation
    if (strlen($_POST['password']) < 6) {
      $errors['password'][] = "Parola trebuie sa fie de cel putin 6 caractere";
    }
    if (!($_POST['password'] === $_POST['confirm-password'])) {
      $errors['confirm-password'][] = "Nu se potrivesc parolele";
    }

    // username validation
    if (!preg_match("/^[a-zA-Z0-9]+$/", $_POST['username'])) {
      $errors['username'][] = "Username-ul trebuie sa contina doar litere si cifre";
    }
    if (empty($_POST['username'])) {
      $errors['username'][] = "Username-ul nu poate fii gol";
    }
    $query = $this->db2->dsql();
    $result = $query->table('users')
      ->where('username', '=', $_POST['username'])
      ->getRow();
    if (!empty($result)) {
      $errors['username'][] = "Username-ul este folosit deja , alegeti altul.";
    }

    return $errors;
  }

  public function loginUser($username, $password)
  {
    $user = new UserEntity($username, $password);
    $query = $this->db2->dsql();
    $row = $query->table('users')
      ->where('username', '=', $username)
      ->getRow();
    if(row) {
      $user->setUserId($row['user_id']);
      $user->setImage($row['image']);
    }
    session_start();
    $_SESSION['user'] = $user;
  }

  /**
   * Unregister a session variable
   */
  public function logoutUser()
  {

    if (isset($_SESSION['user'])) {
      unset($_SESSION['user']);
    }
  }

  public function getUsers(){
    $query = $this->db2->dsql();
    $result = $query->table("users")
      ->get();
    return $result;
  }

}

