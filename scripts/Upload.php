<?php

namespace Scripts;

class Upload
{
  public function __construct()
  {
    $this->db = db_get_connection();
    $this->db2 = db_get_dsql_connection();
  }

  public function uploadImage()
  {
    if (isset($_POST['submit'])) {
      $fileName = $_FILES['file']['name'];
      $fileTmpName = $_FILES['file']['tmp_name'];
      $fileSize = $_FILES['file']['size'];
      $fileError = $_FILES['file']['error'];

      $fileExt = explode('.', $fileName);
      $fileActualExt = strtolower(end($fileExt));
      $AllowExt = array('jpeg', 'jpg', 'png');

      if (in_array($fileActualExt, $AllowExt)) {
        if ($fileError === 0) {
          if($fileSize > 50000) {
            if (isset($_SESSION['user'])) {
              $username = $_SESSION['user']->getUsername();
              $fileNameNew = $username . "." . $fileActualExt;
              $fileDestination = 'images/' . $fileNameNew;
              $_SESSION['user']->setImage(1);
              $this->updateImageDb($_SESSION['user']->getUserId());
              if(file_exists($fileDestination)){
                unlink($fileDestination);
              }
              move_uploaded_file($fileTmpName, $fileDestination);
              redirect('/user?uploadsucces');
            } else {
              echo 'User not login';
            }
          } else {
            echo "to large file";
          }
        } else {
          echo 'Some errors ocures';
        }
      } else {
        echo 'You can not upload files like that';
      }
    }
  }

  public function  updateImageDb($id){
      $query = $this->db2->dsql();
      $query->table('users')
        ->where('user_id','=',$id)
        ->set('image',1)
        ->update();
}
}

