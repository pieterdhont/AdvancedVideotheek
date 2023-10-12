<?php
// App/Data/ExemplaarDAO.php

declare(strict_types=1);

namespace App\Data;

use App\Entities\Exemplaar;
use App\Entities\Film;
use App\Exceptions\ExemplaarBestaatException;
use PDO;

class ExemplaarDAO {

    private PDO $db;
    private FilmDAO $filmDAO; 
   
    public function __construct(FilmDAO $filmDAO) {
        $this->db = DatabaseConnection::getInstance();
        $this->filmDAO = $filmDAO;
    } 

    public function create(int $filmId, int $nr): Exemplaar {
        // Controleren of een exemplaar met het gegeven nummer al bestaat
        $sqlCheck = "SELECT id FROM exemplaren WHERE nr = :nr";
        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->bindParam(':nr', $nr, PDO::PARAM_INT);
        $stmtCheck->execute();

        if ($stmtCheck->fetch()) {
            throw new ExemplaarBestaatException("Een exemplaar met nummer $nr bestaat al.");
        }

        // Exemplaar toevoegen aan de database
        $sql = "INSERT INTO exemplaren (filmid, nr) VALUES (:filmId, :nr)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':filmId', $filmId, PDO::PARAM_INT);
        $stmt->bindParam(':nr', $nr, PDO::PARAM_INT);
        $stmt->execute();

        $newExemplaarId = (int) $this->db->lastInsertId();

        $film = $this->filmDAO->getById($filmId); // Gebruik de geïnjecteerde filmDAO
        return new Exemplaar($newExemplaarId, $nr, $film, true);
    }

    public function delete(int $id): bool {
        $sql = "DELETE FROM exemplaren WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function getByNr(int $nr): array {
      $sql = "SELECT e.id, e.nr, e.aanwezig, f.id as film_id, f.titel 
              FROM exemplaren e 
              JOIN films f ON e.filmid = f.id
              WHERE e.nr = :nr";
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(':nr', $nr, PDO::PARAM_INT);
      $stmt->execute();
  
      $exemplaren = [];
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $film = new Film((int)$row['film_id'], $row['titel']);
          $exemplaar = new Exemplaar((int)$row['id'], (int)$row['nr'], $film, (bool)$row['aanwezig']);
          $exemplaren[] = $exemplaar;
      }
  
      return $exemplaren;
  }

  public function getAllByFilmId(int $filmId): array {
    $sql = "SELECT e.id, e.nr, e.aanwezig, f.id as film_id, f.titel 
            FROM exemplaren e 
            JOIN films f ON e.filmid = f.id
            WHERE e.filmid = :filmId";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':filmId', $filmId, PDO::PARAM_INT);
    $stmt->execute();

    $exemplaren = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $film = new Film((int)$row['film_id'], $row['titel']);
        $exemplaar = new Exemplaar((int)$row['id'], (int)$row['nr'], $film, (bool)$row['aanwezig']);
        $exemplaren[] = $exemplaar;
    }

    return $exemplaren;
}
public function huurExemplaar(int $nr): bool {
    $sql = "UPDATE exemplaren SET aanwezig = 0 WHERE nr = :nr AND aanwezig = 1";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':nr', $nr, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->rowCount() > 0; // Geeft terug of er daadwerkelijk een record is bijgewerkt
}

public function terugbrengExemplaar(int $nr): bool {
    $sql = "UPDATE exemplaren SET aanwezig = 1 WHERE nr = :nr AND aanwezig = 0";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':nr', $nr, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->rowCount() > 0; // Geeft terug of er daadwerkelijk een record is bijgewerkt
}
  
}
