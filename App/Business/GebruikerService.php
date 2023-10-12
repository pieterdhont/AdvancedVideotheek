<?php 
//App/Business/GebruikerService.php
declare(strict_types=1);

namespace App\Business;
use App\Data\GebruikerDAO;
use App\Entities\Gebruiker;
use App\Exceptions\{UserNotFoundException, IncorrectPasswordException};


class GebruikerService {

    private GebruikerDAO $gebruikerDAO;

    public function __construct(GebruikerDAO $gebruikerDAO) {
        $this->gebruikerDAO = $gebruikerDAO;
    }

   
    public function login(string $naam, string $wachtwoord): ?Gebruiker {
        $gebruiker = $this->gebruikerDAO->getByNaam($naam);
        
        if (!$gebruiker) {
            throw new UserNotFoundException("Gebruiker niet gevonden.");
        }
        if ($gebruiker->getWachtwoord() !== $wachtwoord) {
            throw new IncorrectPasswordException("Onjuist wachtwoord.");
        }
        
        return $gebruiker;
    }
    

    public function authenticateUser(array &$session): ?Gebruiker {
        if (!isset($session["gebruiker_id"])) {
            header("Location: index.php");  
            exit;
        }
        
        return null;
    }
        
    
} 