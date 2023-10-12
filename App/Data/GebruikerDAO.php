<?php 
//App/Data/GebruikerDAO.php
declare(strict_types=1);

namespace App\Data;
use App\Entities\Gebruiker;
use PDO;

class GebruikerDAO {

    private PDO $db;

    public function __construct() {
        $this->db = DatabaseConnection::getInstance();
    }

    public function getByNaamWachtwoord(string $naam, string $wachtwoord): ?Gebruiker {
        $sql = "SELECT * FROM gebruikers WHERE naam = :naam AND wachtwoord = :wachtwoord";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':naam', $naam, PDO::PARAM_STR);
        $stmt->bindParam(':wachtwoord', $wachtwoord, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$row) return null;
    
        return new Gebruiker((int)$row['id'], $row['naam'], $row['wachtwoord']);
    }
    public function getByNaam(string $naam): ?Gebruiker {
        $sql = "SELECT * FROM gebruikers WHERE naam = :naam";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':naam', $naam, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) return null;
        
        return new Gebruiker((int)$row['id'], $row['naam'], $row['wachtwoord']);
    }
    

}
?>