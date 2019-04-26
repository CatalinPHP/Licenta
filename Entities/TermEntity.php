<?php
/**
 * Created by PhpStorm.
 * User: Monica
 * Date: 8/22/2018
 * Time: 10:52 AM
 */

namespace Entities;


class TermEntity
{
  protected $tid = 0;
  protected $vid = 0;
  protected $name = "";
  protected $vocabularyName = "";

  public function __construct($entry = array())
  {
    foreach ($entry as $key => $value) {
      $this->{$key} = $value;
    }
  }

  public function getTid()
  {
    return $this->tid;
  }

  public function getVid()
  {
    return $this->vid;
  }

  public function getName()
  {
    return $this->name;
  }

  public function setTid($tid)
  {
    $this->tid = $tid;
  }

  public function setVid($vid)
  {
    $this->vid = $vid;
  }

  public function setName($name)
  {
    $this->name = $name;
  }

  public function setVocabularyName($vocabularyName)
  {
    $this->vocabularyName = $vocabularyName;
  }
}