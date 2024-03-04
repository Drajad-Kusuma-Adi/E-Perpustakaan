<?php
    class CRUDController {
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }

        // CRUD Operations
        public function create($table, $columns, $data) {
            $sql = "INSERT INTO $table ($columns) VALUES ($data)";
            $result = $this->conn->query($sql);
            if (!$result) {
                return "Request failed to send, please try again";
            }
        }
        public function readMultiple($table, $limit) {
            if ($limit != null) {
                $sql = "SELECT * FROM $table LIMIT $limit";
            } else {
                $sql = "SELECT * FROM $table";
            }
            $result = $this->conn->query($sql);
            if (!$result) {
                return "Request failed to send, please try again";
            } else {
                return $result->fetch_all(MYSQLI_ASSOC);
            }
        }
        public function readByValue($table, $valName, $value, $limit) {
            if ($limit != null) {
                $sql = "SELECT * FROM $table WHERE $valName = '$value' LIMIT $limit";
            } else {
                $sql = "SELECT * FROM $table WHERE $valName = '$value'";
            }
            $result = $this->conn->query($sql);
            if (!$result) {
                return "Request failed to send, please try again";
            } else {
                return $result->fetch_all(MYSQLI_ASSOC);
            }
        }
        public function readByLike($table, $valName, $value, $limit) {
            if ($limit != null) {
                $sql = "SELECT * FROM $table WHERE $valName LIKE '%$value%' LIMIT $limit";
            } else {
                $sql = "SELECT * FROM $table WHERE $valName LIKE '%$value%'";
            }
            $result = $this->conn->query($sql);
            if (!$result) {
                return "Request failed to send, please try again";
            } else {
                return $result->fetch_all(MYSQLI_ASSOC);
            }
        }
        public function update($table, $columns, $data) {
            $sql = "UPDATE $table SET $columns = $data";
            $result = $this->conn->query($sql);
            if (!$result) {
                return "Request failed to send, please try again";
            }
        }
        public function delete($table, $columns, $data) {
            $sql = "DELETE FROM $table WHERE $columns = $data";
            $result = $this->conn->query($sql);
            if (!$result) {
                return "Request failed to send, please try again";
            }
        }
    }
