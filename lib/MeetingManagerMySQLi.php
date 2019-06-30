<?php

class MeetingManagerMySQLi extends MeetingManager
{
    /**
     * Attribut contenant l'instance représentant la BDD.
     * @type MySQLi
     */
    protected $db;

    /**
     * Constructeur étant chargé d'enregistrer l'instance de MySQLi dans l'attribut $db.
     * @param $db MySQLi Le DAO
     * @return void
     */
    public function __construct(MySQLi $db)
    {
        $this->db = $db;
    }

    /**
     * @see NewsManager::add()
     */
    protected function add(Meeting $meeting)
    {
        $requete = $this->db->prepare('INSERT INTO meeting(user, title, description, startDate, endDate) VALUES(?, ?, ?, NOW(), NOW())');

        $requete->bind_param('sss', $meeting->user(), $meeting->title(), $meeting->description());

        $requete->execute();
    }

    /**
     * @see NewsManager::count()
     */
    public function count()
    {
        return $this->db->query('SELECT id FROM community')->num_rows;
    }

    /**
     * @see NewsManager::delete()
     */
    public function delete($id)
    {
        $id = (int)$id;

        $requete = $this->db->prepare('DELETE FROM community WHERE id = ?');

        $requete->bind_param('i', $id);

        $requete->execute();
    }

    /**
     * @see NewsManager::getList()
     */
    public function getList($debut = -1, $limite = -1)
    {
        $listeMeeting = [];

        $sql = 'SELECT id, user, title, description, startDate, endDate FROM community ORDER BY id DESC';

        // On vérifie l'intégrité des paramètres fournis.
        if ($debut != -1 || $limite != -1) {
            $sql .= ' LIMIT ' . (int)$limite . ' OFFSET ' . (int)$debut;
        }

        $requete = $this->db->query($sql);

        while ($meeting = $requete->fetch_object('Community')) {
            $meeting->setStartDate(new DateTime($meeting->startDate()));
            $meeting->setEndDate(new DateTime($meeting->endDate()));

            $listeMeeting[] = $meeting;
        }

        return $listeMeeting;
    }

    /**
     * @see NewsManager::getUnique()
     */
    public function getUnique($id)
    {
        $id = (int)$id;

        $requete = $this->db->prepare('SELECT id, user, title, description, startDate, endDate FROM Community WHERE id = ?');
        $requete->bind_param('i', $id);
        $requete->execute();

        $requete->bind_result($id, $auteur, $titre, $contenu, $dateAjout, $dateModif);

        $requete->fetch();

        return new Community([
            'id' => $id,
            'auteur' => $auteur,
            'titre' => $titre,
            'contenu' => $contenu,
            'dateAjout' => new DateTime($dateAjout),
            'dateModif' => new DateTime($dateModif)
        ]);
    }

    /**
     * @see NewsManager::update()
     */
    protected function update(Meeting $meeting)
    {
        $requete = $this->db->prepare('UPDATE Community SET user = ?, title = ?, description = ?, dateModif = NOW() WHERE id = ?');

        $requete->bind_param('sssi', $meeting->user(), $meeting->title(), $meeting->description(), $meeting->id());

        $requete->execute();
    }
}