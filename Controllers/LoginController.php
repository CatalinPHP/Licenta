<?php

namespace Controllers;

use Entities\AdminEntity;
use Entities\UserEntity;
use Models\AdminModel;
use Models\UserModel;

/**
 * Class LoginController
 *
 * @package Controllers
 */
class LoginController extends BasicController
{
  /**
   * Login contructs overrides BasicController construct
   *
   * LoginController constructor.
   */
  public function __construct()
  {
    parent::__construct();
    $this->title = 'Login';
    $this->addCSS('css/login.css');


  }

  //========================Admin Login=====================

  /**
   * ON GET, Get the login template
   */
  public function get()
  {
    $this->content = $this->render('/views/forms/login_form.tpl.php', array('errors' => ""));
    $this->renderLayout('/views/layouts/basic.tpl.php');
  }

  /**
   * On POST,  validate the user entry
   */
  public function loginAction()
  {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $adminModel = new AdminModel();
    $validationResult = $adminModel->validateLogin();

    if ($validationResult === TRUE) {
      $admin = $adminModel->login($email, $password);
      if ($admin) {
        redirect('/admin');
      } else {
        $validationResult = array('errors' => array('Wrong user or password.'));
      }
    }

    $errors = generate_messages($validationResult);
    $this->content = $this->render('/views/forms/login_form.tpl.php', array('errors' => $errors));
    $this->renderLayout('/views/layouts/basic.tpl.php');

  }

  /**
   * On GET, end the 'user' entry-logout
   */
  public function logoutAction()
  {
    $adminModel = new AdminModel();
    $adminModel->logout();
    redirect('/');
  }

  /**
   * ON GET , get the register template
   */
  public function registerPage()
  {
    $this->content = $this->render('/views/forms/register_form.tpl.php');
    $this->renderLayout('/views/layouts/basic.tpl.php');
  }

  /**
   * ON POST , validate the register and save it in database
   */
  public function registerAction()
  {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $adminModel = new AdminModel();
    $validationResult = $adminModel->validateRegister();

    if ($validationResult === TRUE) {
      $adminObject = new AdminEntity($email);
      $adminObject->setPassword($password);
      $adminModel->saveAdmin($adminObject);
      $this->content = 'Your account ' . $adminObject->getEmail() . ' has been created';
      $this->content .= $this->render('/views/forms/login_form.tpl.php');
    } elseif ($validationResult !== TRUE) {
      $errors = generate_messages($validationResult);
      $this->content = $this->render('/views/forms/register_form.tpl.php', array('errors' => $errors));
    }
    $this->renderLayout('/views/layouts/basic.tpl.php');
  }

//=================================== UserLogin =================================

  public function getUser(){
    $this->content = $this->render('/views/forms/login_user.php', array('errors' => ""));
    $this->renderLayout('/views/layouts/basic.tpl.php');
  }

  public function  loginUser()
  {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $userModel = new UserModel();
    $userValid = $userModel->validateUser($username, $password);
    if ($userValid) {
      $user = $userModel->loginUser($username,$password);
      redirect('/user');
    } else {
      $errors = "Nu sunt valabile userul si parola.";
      $this->content = $this->render('/views/forms/login_user.php', array('errors' => $errors));
      $this->renderLayout('/views/layouts/basic.tpl.php');


    }
  }

  public function logoutUserAction(){
    $user = new UserModel();
    $user->logoutUser();
    redirect('/');
  }

  public function registerUserPage(){
    $this->content = $this->render('/views/forms/register_user.php', array('errors'=>''));
    $this->renderLayout('/views/layouts/basic.tpl.php');
  }

  public function registerUser(){
    $username = $_POST['username'];
    $password = $_POST['password'];


    $userModel = new UserModel();
    $userValRegister = $userModel->registerValidation();
    if(empty($userValRegister)){
      $user = new UserEntity($username,$password);
      $userModel->saveUser($user);
      redirect('/loginUser');
    }
    else
    {
      $errors = generate_messages($userValRegister);
      $this->content = $this->render('/views/forms/register_user.php', array('errors'=>$errors));
      $this->renderLayout('/views/layouts/basic.tpl.php');
    }
  }

}


