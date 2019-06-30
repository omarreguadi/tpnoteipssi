<?php
require 'lib/autoload.php';

$db = DBFactory::getMysqlConnexionWithPDO();
$manager = new MeetingManagerPDO($db);

if (isset($_GET['modifier']))
{
    $meeting = $manager->getUnique((int) $_GET['modifier']);
}

if (isset($_GET['supprimer']))
{
    $manager->delete((int) $_GET['supprimer']);
    $message = 'Le meeting a bien été supprimée !';
}

if (isset($_POST['user']))
{
    $meeting = new Meeting(
        [
            'user' => $_POST['user'],
            'title' => $_POST['title'],
            'description' => $_POST['description']
        ]
    );

    if (isset($_POST['id']))
    {
        $meeting->setId($_POST['id']);
    }

    if ($meeting->isValid())
    {
        $manager->save($meeting);

        $message = $meeting->isNew() ? 'La meeting a bien été ajoutée !' : 'La meeting a bien été modifiée !';
    }
    else
    {
        $erreurs = $meeting->erreurs();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Administration</title>
    <meta charset="utf-8" />

    <style type="text/css">
        table, td {
            border: 1px solid black;
        }

        table {
            margin:auto;
            text-align: center;
            border-collapse: collapse;
        }

        td {
            padding: 3px;
        }
    </style>
</head>

<body>
<p><a href=".">Accéder à l'accueil du site</a></p>

<form action="admin.php" method="post">
    <p style="text-align: center">
        <?php
        if (isset($message))
        {
            echo $message, '<br />';
        }
        ?>
        <?php if (isset($erreurs) && in_array(Meeting::USER_INVALIDE, $erreurs)) echo 'L\'UTILISATEUR est invalide.<br />'; ?>
        User : <input type="text" name="user" value="<?php if (isset($meeting)) echo $meeting->user(); ?>" /><br />

        <?php if (isset($erreurs) && in_array(  Meeting::TITLE_INVALIDE, $erreurs)) echo 'Le titre est invalide.<br />'; ?>
        Title : <input type="text" name="title" value="<?php if (isset($meeting)) echo $meeting->title(); ?>" /><br />

        <?php if (isset($erreurs) && in_array(Meeting::DESCRIPTION_INVALIDE, $erreurs)) echo 'La description est invalide.<br />'; ?>
        Description :<br /><textarea rows="8" cols="60" name="description"><?php if (isset($meeting)) echo $meeting->description(); ?></textarea><br />
        <?php
        if(isset($meeting) && !$meeting->isNew())
        {
            ?>
            <input type="hidden" name="id" value="<?= $meeting->id() ?>" />
            <input type="submit" value="Modifier" name="modifier" />
            <?php
        }
        else
        {
            ?>
            <input type="submit" value="Ajouter" />
            <?php
        }
        ?>
    </p>
</form>

<p style="text-align: center">Il y a actuellement <?= $manager->count() ?> meeting. Et voici la liste :</p>

<table>
    <tr><th>User</th><th>Title</th><th>Description</th><th>Date de debut</th><th>Date de fin</th><th>Action</th></tr>
    <?php
    foreach ($manager->getList() as $meeting)
    {
        echo '<tr><td>', $meeting->user(), '</td><td>', $meeting->title(), '</td><td>', $meeting->description(), '</td><td>', ($meeting->startDate() == $meeting->endDate() ? '-' : $meeting->endDate()->format('d/m/Y à H\hi')), '</td><td><a href="?modifier=', $meeting->id(), '">Modifier</a> | <a href="?supprimer=', $meeting->id(), '">Supprimer</a></td></tr>', "\n";
    }
    ?>
</table>
</body>
</html>
