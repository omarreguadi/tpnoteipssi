<?php
 class MeetingManagerPDO extends MeetingManager
{
    /**
     * Attribut contenant l'instance représentant la BDD.

     */
    protected $db;


    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @see MeetingManager::add()
     */
    protected function add(Meeting $meeting)
    {
        $requete = $this->db->prepare('INSERT INTO community(user, title, description, startDate, endDate) VALUES(:user, :title, :description, NOW(), NOW())');

        $requete->bindValue(':title', $meeting->title());
        $requete->bindValue(':user', $meeting->user());
        $requete->bindValue(':description', $meeting->description());

        $requete->execute();
    }

    /**
     * @see CommunityManager::count()
     */
    public function count()
    {
        return $this->db->query('SELECT COUNT(*) FROM Community')->fetchColumn();
    }

    /**
     * @see CommunityManager::delete()
     */
    public function delete($id)
    {
        $this->db->exec('DELETE FROM community WHERE id = '.(int) $id);
    }

    /**
     * @see CommunityManager::getList()
     */
    public function getList($debut = -1, $limite = -1)
    {
        $sql = 'SELECT id, user , title, description, startDate, endDate FROM community ORDER BY id DESC';

        // On vérifie l'intégrité des paramètres fournis.
        if ($debut != -1 || $limite != -1)
        {
            $sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
        }

        $requete = $this->db->query($sql);
        $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Community');

        $listeMeeting = $requete->fetchAll();

        // On parcourt notre liste de meeting pour pouvoir placer des instances de DateTime en guise de dates d'ajout et de fin.
        foreach ($listeMeeting as $meeting)
        {
            $meeting->setStartDate(new DateTime($meeting->startDate()));
            $meeting->setEndDate(new DateTime($meeting->endDate()));

        $requete->closeCursor();

        return $listeMeeting;
    }

    /**
     * @see MeetingManager::getUnique()
     */
    public function getUnique($id)
    {
        $requete = $this->db->prepare('SELECT id, user, title, description, startDate, endDate FROM community WHERE id = :id');
        $requete->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $requete->execute();

        $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Community');

        $meeting = $requete->fetch();

        $meeting->setStartDate(new DateTime($meeting->startDate()));
        $meeting->setEndDate(new DateTime($meeting->endDate()));

        return $meeting;
    }

    /**
     * @see meetingsManager::update()
     */
    protected function update(Meeting $meeting)
    {
        $requete = $this->db->prepare('UPDATE community SET user = :user, title = :title, description = :description, dateModif = NOW() WHERE id = :id');

        $requete->bindValue(':title', $meeting->title());
        $requete->bindValue(':user', $meeting->user());
        $requete->bindValue(':description', $meeting->description());
        $requete->bindValue(':id', $meeting->id(), PDO::PARAM_INT);

        $requete->execute();
    }
}