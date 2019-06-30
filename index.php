<?php
require 'lib/autoload.php';

$db = DBFactory::getMysqlConnexionWithPDO();
$manager = new MeetingManagerPDO($db);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Accueil du site</title>
    <meta charset="utf-8" />
</head>

<body>
<p><a href="admin.php">Accéder à l'administration</a></p>
<?php
if (isset($_GET['id']))
{
    $meeting = $manager->getUnique((int) $_GET['id']);

    echo '<p>Par <em>', $meeting->user(), '</em>, le ', $meeting->startDate()->format('d/m/Y à H\hi'), '</p>', "\n",
    '<h2>', $meeting->title(), '</h2>', "\n",
    '<p>', nl2br($meeting->description()), '</p>', "\n";

    if ($meeting->startDate() != $meeting->endDate())
    {
        echo '<p style="text-align: right;"><small><em>finis le ', $meeting->endDate()->format('d/m/Y à H\hi'), '</em></small></p>';
    }
}

else
{
    echo '<h2 style="text-align:center">Liste des 15 dernièrs meeting</h2>';

    foreach ($manager->getList(0, 15) as $meeting)
    {
        if (strlen($meeting->description()) <= 200)
        {
            $description = $meeting->description();
        }

        else
        {
            $debut = substr($meeting->description(), 0, 200);
            $debut = substr($debut, 0, strrpos($debut, ' ')) . '...';

            $description = $debut;
        }

        echo '<h4><a href="?id=', $meeting->id(), '">', $meeting->titre(), '</a></h4>', "\n",
        '<p>', nl2br($description), '</p>';
    }
}
?>
</body>
</html>