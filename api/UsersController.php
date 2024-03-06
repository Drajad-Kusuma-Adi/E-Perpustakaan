<?php
require_once "CRUDController.php";
class UsersController extends CRUDController
{
  private $conn;
  private $controller;

  public function __construct($conn)
  {
    $this->conn = $conn;
    $this->controller = new CRUDController($conn);
  }

  public function getUserDataById($id) {
    return $this->controller->readByValue('users', 'id', $id, 1);
  }
  public function getAllUserData() {
    return $this->controller->readMultiple('users', null);
  }
  public function createUser($username, $password, $level)
  {
    $image = file_get_contents('assets/user-placeholder.jpg');
    $base64_image = '![IMAGE]' . base64_encode($image);
    $this->controller->create('users', 'username, password, level, image', "'$username', '$password', '$level', '$base64_image'");
  }
  public function updateUser($id, $username, $password, $level)
  {
    $data=array(
      "username" => $username,
      "password" => $password,
      "level" => $level
    );
    $this->controller->update('users', $data, $id);
  }
  public function deleteUser($id)
  {
    $this->controller->delete('users', 'id', $id);
    $this->controller->delete('borrows', 'user_id', $id);
    $this->controller->delete('favorites', 'user_id', $id);
  }
}