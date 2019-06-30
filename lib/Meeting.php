<?php



class Meeting
{
    protected $erreurs = [],
        $id,
        $user,
        $title,
        $description,
        $startDate,
        $endDate;

    /**
     * Constantes relatives aux erreurs possibles
     */
    const USER_INVALIDE = 1;
    const TITLE_INVALIDE = 2;
    const DESCRIPTION_INVALIDE = 3;


    /**
     * Constructeur de la classe qui assigne les données spécifiées en paramètre aux attributs correspondants.

     */
    public function __construct($valeurs = [])
    {
        if (!empty($valeurs))
        {
            $this->hydrate($valeurs);
        }
    }

    /**
     * Méthode assignant les valeurs spécifiées aux attributs correspondant.
     */
    public function hydrate($donnees)
    {
        foreach ($donnees as $attribut => $valeur) {
            $methode = 'set' . ucfirst($attribut);

            if (is_callable([$this, $methode])) {
                $this->$methode($valeur);
            }
        }
    }

    /**
     * Méthode permettant de savoir si la meeting est nouveau.
     * @return bool
     */
    public function isNew()
    {
        return empty($this->id);
    }

    /**
     * Méthode permettant de savoir si le meeting  est valide.
     * @return bool
     */
    public function isValid()
    {
        return !(empty($this->auteur) || empty($this->titre) || empty($this->contenu));
    }


    // SETTERS //

    public function setId($id)
    {
        $this->id = (int)$id;
    }

    public function setUser($user)
    {
        if (!is_string($user) || empty($user)) {
            $this->erreurs[] = self::USER_INVALIDE;
        } else {
            $this->user = $user;
        }
    }

    public function setTitle($title)
    {
        if (!is_string($title) || empty($title)) {
            $this->erreurs[] = self::TITLE_INVALIDE;
        } else {
            $this->title = $title;
        }
    }

    public function setDescription($description)
    {
        if (!is_string($description) || empty($description)) {
            $this->erreurs[] = self::DESCRIPTION_INVALIDE;
        } else {
            $this->description = $description;
        }
    }

    public function setStartDate(DateTime $startDate)
    {
        $this->startDate = $startDate;
    }

    public function setEndDATE(DateTime $endDate)
    {
        $this->endDate = $endDate;
    }

    // GETTERS //

    public function erreurs()
    {
        return $this->erreurs;
    }

    public function id()
    {
        return $this->id;
    }

    public function user()
    {
        return $this->user;
    }

    public function title()
    {
        return $this->title;
    }

    public function description()
    {
        return $this->description;
    }

    public function startDate()
    {
        return $this->startDate;
    }

    public function endDate()
    {
        return $this->endDate;
    }
}