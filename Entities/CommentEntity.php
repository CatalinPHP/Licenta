<?php
/**
 * Created by PhpStorm.
 * User: Catalin
 * Date: 21.03.2019
 * Time: 10:14
 */

namespace Entities;


class CommentEntity
{
  protected $comid;
  protected $idBook;
  protected $username;
  protected $date;
  public $comment;

  public function __construct($username,$idBook,$date,$comment)
  {
    $this->username = $username;
    $this->idBook = $idBook;
    $this->date = $date;
    $this->comment = $comment;
  }

  /**
   * @return mixed
   */
  public function getIdBook()
  {
    return $this->idBook;
  }

  /**
   * @param mixed $idBook
   */
  public function setIdBook($idBook)
  {
    $this->idBook = $idBook;
  }

  /**
   * @return mixed
   */
  public function getComid()
  {
    return $this->comid;
  }

  /**
   * @param mixed $comid
   */
  public function setComid($comid)
  {
    $this->comid = $comid;
  }

  /**
   * @return mixed
   */
  public function getUsername()
  {
    return $this->username;
  }

  /**
   * @param mixed $username
   */
  public function setUsername($username)
  {
    $this->username = $username;
  }

  /**
   * @return mixed
   */
  public function getDate()
  {
    return $this->date;
  }

  /**
   * @param mixed $date
   */
  public function setDate($date)
  {
    $this->date = $date;
  }

  /**
   * @return mixed
   */
  public function getComment()
  {
    return $this->comment;
  }

  /**
   * @param mixed $comment
   */
  public function setComment($comment)
  {
    $this->comment = $comment;
  }


}