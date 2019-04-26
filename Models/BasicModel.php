<?php

namespace Models;


class BasicModel
{
  public function __construct()
  {
    $this->db = db_get_connection();
    $this->db2 = db_get_dsql_connection();
  }

  protected function makeStatement($sql, $data = null)
  {
    $statement = $this->db->prepare($sql);
    try {
      $statement->execute($data);
    } catch (Exception $e) {
      // exception
    }
    return $statement;
  }
}