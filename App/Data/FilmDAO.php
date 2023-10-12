<?php 
//App/Data/FilmDAO.php

declare(strict_types=1);

namespace App\Data;
use App\Entities\Film;
use App\Exceptions\TitelBestaatException;
use PDO;

class FilmDAO {

  private PDO $db;

  public function __construct() {
    $this->db = DatabaseConnection::getInstance();
  }

  public function getAll(): array {
    $sql = "SELECT * FROM films";
    $resultSet = $this->db->query($sql);

    $films = array();
    foreach ($resultSet as $row) {
      $film = new Film((int)$row["id"], $row["titel"]);
      array_push($films, $film);
    }

    return $films;
  }


  public function getById(int $id): ?Film {
    $sql = "SELECT * FROM films WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) return null;

    return new Film((int)$row['id'], $row['titel']);
  }

  public function create(string $titel): Film {
    // Controleren of een film met dezelfde titel al bestaat
    $sqlCheck = "SELECT id FROM films WHERE titel = :titel";
    $stmtCheck = $this->db->prepare($sqlCheck);
    $stmtCheck->bindParam(':titel', $titel, PDO::PARAM_STR);
    $stmtCheck->execute();

    if ($stmtCheck->fetch()) {
        throw new TitelBestaatException("Een film met de titel $titel bestaat al.");
    }

    // Film toevoegen aan de database
    $sql = "INSERT INTO films (titel) VALUES (:titel)";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':titel', $titel, PDO::PARAM_STR);
    $stmt->execute();

    return new Film((int)$this->db->lastInsertId(), $titel);
}


  public function delete(int $id): bool {
    $sql = "DELETE FROM films WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->rowCount() > 0;
  }

  
}
