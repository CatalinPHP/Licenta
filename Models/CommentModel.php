<?php
/**
 * Created by PhpStorm.
 * User: Catalin
 * Date: 21.03.2019
 * Time: 10:20
 */

namespace Models;


class CommentModel extends BookModel
{
    public function addComment($Comment){
      $userN = $Comment->getUsername();
      $idBook = $Comment->getIdBook();
      $date = $Comment->getDate();
      $comment = $Comment->getComment();
      $query = $this->db2->dsql();
      $query->table('comments')
        ->set('username',$userN)
        ->set('idBook',$idBook)
        ->set('date',$date)
        ->set('comment',$comment)
        ->insert();
    }

    public function getComment($bookId){
      $query = $this->db2->dsql();
      $rows = $query->table('comments')
        ->where('idBook','=',$bookId)
        ->get();
      return $rows;
    }
}