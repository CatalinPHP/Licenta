<?php

namespace Entities;

use Models\TermModel;

class BookEntity
{
  public $id;
  public $title;
  public $description;
  public $rating;
  public $ISBN_13;
  public $ISBN_10;
  public $image;
  public $language;
  public $price;
  public $currency;
  public $buy_link;
  protected $authorsIds = array();
  protected $categoryIds = array();
  public $authorNames;
  public $categoryNames;

  public function __construct($entry = array())
  {
    foreach ($entry as $key => $value) {
      $this->{$key} = $value;
    }
  }

  public function getId()
  {
    return $this->id;
  }

  public function setId($id)
  {
    $this->id = $id ? intval($id) : NULL;
  }

  public function getTitle()
  {
    return $this->title;
  }


  public function setTitle($title)
  {
    $this->title = $title;
  }

  public function getDescription()
  {
    return $this->description;
  }

  public function setDescription($description)
  {
    $this->description = $description;
  }

  public function getRating()
  {
    return $this->rating;
  }

  public function setRating($rating)
  {
    $this->rating = floatval($rating);
  }

  public function getISBN_13()
  {
    return $this->ISBN_13;
  }

  public function setISBN_13($ISBN_13)
  {
    $this->ISBN_13 = $ISBN_13;
  }

  public function getISBN_10()
  {
    return $this->ISBN_10;
  }

  public function setISBN_10($ISBN_10)
  {
    $this->ISBN_10 = $ISBN_10;
  }

  public function getImage()
  {
    return $this->image;
  }

  public function setImage($image)
  {
    $this->image = $image;
  }

  public function getLanguage()
  {
    return $this->language;
  }

  public function setLanguage($language)
  {
    $this->language = $language;
  }

  public function getPrice()
  {
    return $this->price;
  }

  public function setPrice($price)
  {
    $this->price = floatval($price);
  }

  public function getCurrency()
  {
    return $this->currency;
  }

  public function setCurrency($currency)
  {
    $this->currency = $currency;
  }

  public function getBuy_link()
  {
    return $this->buy_link;
  }

  public function setBuy_link($buy_link)
  {
    $this->buy_link = $buy_link;
  }

   public function setAuthorNames($authorNames)
  {
    $this->authorNames = $authorNames;
  }

  public function getAuthorsIds()
  {
    return $this->authorsIds;
  }
  
  public function getCategoriesIds()
  {
    return $this->categoryIds;
  }

  public function populateReferences() {
    $termModel = new TermModel();

    if (!empty($this->authorsIds) && is_array($this->authorsIds)) {
      foreach ($this->authorsIds as $tid) {
        $term =  $termModel->getTerm($tid, 'authors');
        if ($term) {
          $this->authorNames[] = $term->getName();
        }
      }
    }
    if (!empty($this->categoryIds) && is_array($this->categoryIds)) {
      foreach ($this->categoryIds as $tid) {
        $term =  $termModel->getTerm($tid, 'categories');
        if ($term) {
          $this->categoryNames[] = $term->getName();
        }
      }
    }
  }

  public function setCategoriesIds($categories)
  {
    $this->categoryIds = $categories;
  }

  public function getCategoryNames(){
    return $this->categoryNames;
  }

  public function setAuthorsIds($authors)
  {

    $this->authorsIds = $authors;

  }

  public function getAuthorNames(){
    return $this->authorNames;
  }
}