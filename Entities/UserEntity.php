<?php
/**
 * Created by PhpStorm.
 * User: Catalin
 * Date: 12.02.2019
 * Time: 12:21
 */

namespace Entities;


class UserEntity
{


  protected $user_id ;
  protected $username ;
  protected $password ;
  protected $image = 0;



  public function __construct($username,$password)
  {

    $this->username = $username;
    $this->password = $password;
  }

  //edit existing method
  public function updateSession()
  {
    $_SESSION['user'] = $this;
  }

  public function logout()
  {
    unset($_SESSION['user']);
  }

  public function getUsername(){
    return $this->username;
  }

  public function setUsername($username){
    $this->username = $username;
  }

  public function getUserId(){
    return $this->user_id;
  }

  public function setUserId($id){

    $this->user_id = $id;
  }

  /**
   * @return mixed
   */
  public function getPassword()
  {
    return $this->password;
  }
  /**
   * @return mixed
   */
  public function getImage()
  {
    return $this->image;
  }

  /**
   * @param mixed $image
   */
  public function setImage($image)
  {
    $this->image = $image;
  }



}