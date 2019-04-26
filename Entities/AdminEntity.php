<?php
/**
 * Created by PhpStorm.
 * User: DodoT
 * Date: 8/22/2018
 * Time: 1:36 PM
 */

namespace Entities;

/**
 * @Table(name="admin")
 *
 * @Entity(repositoryClass="Models\UserModel")
 */
class AdminEntity
{
  /**
   * @Column(type="integer")
   *
   * @admin_id
   *
   * @GeneratedValue(strategy="AUTO")
   */
  protected $admin_id;

  /**
   * @Column(type="varchar", length=128)
   *
   * @email
   */
  protected $email;

  /**
   * @Column(type="varchar", length=64)
   *
   * @password
   */
  protected $password;


  public function __construct($email)
  {
    $this->email = $email;
  }

  /**
   * @return mixed
   */
  public function getAdminId()
  {
    return $this->admin_id;
  }

  /**
   * @param mixed $admin_id
   */
  public function setAdminId($admin_id)
  {
    $this->admin_id = $admin_id;
  }

  /**
   * @return mixed
   */
  public function getEmail()
  {
    return $this->email;
  }

  /**
   * @param mixed $email
   */
  public function setEmail($email)
  {
    $this->email = $email;
  }

  /**
   * @return mixed
   */
  public function getPassword()
  {
    return $this->password;
  }

  /**
   * @param mixed $password
   */
  public function setPassword($password)
  {
    $this->password = $password;
  }


}