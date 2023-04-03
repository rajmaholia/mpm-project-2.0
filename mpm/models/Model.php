<?php
namespace Mpm\Models;

class Model {
  protected $id;
  protected $created_at;
  protected $updated_at;

  public function __construct($id = null, $created_at = null, $updated_at = null) {
    $this->id = $id;
    $this->created_at = $created_at;
    $this->updated_at = $updated_at;
  }

  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function getCreatedAt() {
    return $this->created_at;
  }

  public function setCreatedAt($created_at) {
    $this->created_at = $created_at;
  }

  public function getUpdatedAt() {
    return $this->updated_at;
  }

  public function setUpdatedAt($updated_at) {
    $this->updated_at = $updated_at;
  }

  public function save() {
    $table = strtolower(get_called_class());
    $props = get_object_vars($this);
    $columns = implode(", ", array_keys($props));
    $values = implode(", ", array_map(function ($value) {
      return is_null($value) ? "NULL" : "'$value'";
    }, array_values($props)));

    if ($this->id) {
      $updates = array_map(function ($key, $value) {
        return "$key='$value'";
      }, array_keys($props), array_values($props));
      $set = implode(", ", $updates);
      $sql = "UPDATE $table SET $set WHERE id=$this->id";
    } else {
      $sql = "INSERT INTO $table ($columns) VALUES ($values)";
    }

    $db = new PDO('mysql:host=localhost;dbname=mydb', 'username', 'password');
    $db->query($sql);
    $this->id = $db->lastInsertId();
  }

  public function delete() {
    $table = strtolower(get_called_class());

    if ($this->id) {
      $sql = "DELETE FROM $table WHERE id=$this->id";
      $db = new PDO('mysql:host=localhost;dbname=mydb', 'username', 'password');
      $db->query($sql);
      $this->id = null;
      $this->created_at = null;
      $this->updated_at = null;
    }
  }

  public static function find($id) {
    $table = strtolower(get_called_class());
    $sql = "SELECT * FROM $table WHERE id=$id";
    $db = new PDO('mysql:host=localhost;dbname=mydb', 'username', 'password');
    $result = $db->query($sql);

    if ($result) {
      $row = $result->fetch(PDO::FETCH_ASSOC);
      return new static($row['id'], $row['created_at'], $row['updated_at']);
    } else {
      return null;
    }
  }

  public static function all() {
    $table = strtolower(get_called_class());
    $sql = "SELECT * FROM $table";
    $db = new PDO('mysql:host=localhost;dbname=mydb', 'username', 'password');
    $result = $db->query($sql);

    if ($result) {
      $objects = array();

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $object = new static($row['id'], $row['created_at'], $row['updated_at']);
        $objects[] = $object;
      }
      return $objects;
    }
  }
  
 public function generate_form(){
  var_dump(get_object_vars($this));
 }
}

