<?php
/**
 * Created by PhpStorm.
 * User: Monica
 * Date: 8/22/2018
 * Time: 10:58 AM
 */

namespace Models;

use atk4\core\Exception;
use Entities\TermEntity;
use atk4\dsql\Expression;
use Models\BasicModel;

class TermModel extends BasicModel
{

  /**
   * Save Term object into database.
   *
   * @param TermEntity $term
   *   Term entity object.
   *
   * @return string
   *   Returns last inserted id.
   *
   * @throws Exception
   */
  public function saveTerm($term)
  {
    $vid = $term->getVid();
    $name = $term->getName();
    $query = $this->db2->dsql();
    $query->table('terms')
      ->set('vid', $vid)
      ->set('name', $name)
      ->insert();
    return $this->db2->lastInsertID();
  }

  /**
   * Get Term object from database based on tid provided.
   *
   * @param int $tid
   *   Term tid.
   *
   * @return TermEntity|NULL
   *    Returns TermEntity if found, NULL otherwise.
   *
   * @throws Exception
   */
  public function getTerm($tid)
  {
    $term = FALSE;
    $query = $this->db2->dsql();
    $resultRow = $query->table('vocabulary', 'V')
      ->join('terms T', new Expression('T.vid = V.vid'))
      ->field('V.vid')
      ->field('T.tid')
      ->field('V.vocabulary')
      ->field('T.name')
      ->where('tid', $tid)
      ->getRow();

    if ($resultRow) {
      $term = new TermEntity($resultRow);
      $term->setVocabularyName($resultRow['vocabulary']);
    }
    return $term;

  }

  /**
   * Find Terms in database based on name and vocabulary id.
   *
   * @param string $name
   *   Term name.
   * @param null|string $vocabularyName (optional)
   *   Vocabulary name if we need to filter by vocabulary.
   *   NULL otherwise and look in all vocabularies.
   *
   * @return array
   *   Returns a list of TermEntity objects or empty list if nothing found.
   *
   * @throws Exception
   */
  public function findTerms($name = NULL, $vocabularyName = NULL)
  {
    $terms = array();
   if ($vocabularyName !== NULL) {
      $query = $this->db2->dsql();
      $results = $query->table('vocabulary', 'V')
        ->join('terms T', new Expression('T.vid=V.vid'))
        ->field('V.vid')
        ->field('T.tid')
        ->field('V.vocabulary')
        ->field('T.name')
        ->where('T.name', $name)
        ->where('V.vocabulary', $vocabularyName)
        ->get();
    } else {
      $query = $this->db2->dsql();
      $results = $query->table('vocabulary', 'V')
        ->join('terms T', new Expression('T.vid=V.vid'))
        ->field('V.vid')
        ->field('T.tid')
        ->field('V.vocabulary')
        ->field('T.name')
        ->where('T.name', $name)
        ->get();
    }
    foreach ($results as $entry) {
      $term = new TermEntity($entry);
      $terms[$entry['tid']] = $term;
    }
    return $terms;
  }


  /**
   * Finds the first term in database based on name and vocabulary id.
   *
   * @param $name
   *   Term name.
   * @param string|null $vocabularyName (optional)
   *   Vocabulary name if we need to filter by vocabulary.
   *   Null otherwise.
   *
   * @return TermEntity|NULL
   *    Returns first TermEntity found or NULL if nothing found.
   *
   * @throws Exception
   */
  public function findFirstTerm($name, $vocabularyName = NULL)
  {
    $results = $this->findTerms($name, $vocabularyName);
    return reset($results);
  }

  /**
   * @param $vocabularyName
   * @return array
   * @throws \atk4\dsql\Exception
   */
  public function findTermsByVocabulary($vocabularyName){

    $terms = array();
    $query = $this->db2->dsql();
    $results = $query->table('vocabulary', 'V')
      ->join('terms T', new Expression('T.vid=V.vid'))
      ->field('V.vid')
      ->field('T.tid')
      ->field('V.vocabulary')
      ->field('T.name')
      ->where('V.vocabulary', $vocabularyName)
      ->get();

    foreach ($results as $entry) {
      $term = new TermEntity($entry);
      $terms[$entry['tid']] = $term;
    }
    return $terms;

  }

  /**
   * @param $vocabularyName
   * @return string
   *
   * @throws \atk4\dsql\Exception
   */
  public function getVocabularyId($vocabularyName) {
    $query = $this->db2->dsql();
    $vid = $query->table('vocabulary', 'V')
      ->field('V.vid')
      ->where('V.vocabulary', $vocabularyName)
      ->getOne();
    return !empty($vid) ? intval($vid) : NULL;
  }
}

