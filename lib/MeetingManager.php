<?php
abstract class MeetingManager
{
    /**
     * Méthode permettant d'ajouter un meeting.

     */
    abstract protected function add(Meeting $meeting);

    /**
     * Méthode renvoyant le nombre de meeting total.

     */
    abstract public function count();

    /**
     * Méthode permettant de supprimer un meeting.

     */
    abstract public function delete($id);

    /**
     * Méthode retournant une liste de meeting demandée.

     */
    abstract public function getList($debut = -1, $limite = -1);

    /**
     * Méthode retournant un meeting précis

     */
    abstract public function getUnique($id);

    /**
     * Méthode permettant d'enregistrer un meeting.

     */
    public function save(Meeting $meeting)
    {
        if ($meeting->isValid())
        {
            $meeting->isNew() ? $this->add($meeting) : $this->update($meeting);
        }
        else
        {
            throw new RuntimeException('le meeting doit être valide pour être enregistrée');
        }
    }


}