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
}