<h1> Liste des clients </h1>
<p><a href="../clients/addC"> >> Ajouter un client</a></p>
<table border="1">
<tr>
    <th>
        Nom
    </th>
    <th>
        Prénom
    </th>
    <th>
        Actions
    </th>
</tr>
<?php foreach($clients as $client):?>
<tr>
    <td><?= $client['nom'] ?></td>
    <td><?= $client['prenom'] ?></td> 
    <td>
        <a href="../clients/getC/<?=$client['id']?>">Afficher</a> | 
        <a href="../clients/updateC/<?=$client['id']?>">Modifier</a> | 
        <a href="../clients/deleteC/<?=$client['id']?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?')">Supprimer</a>
    </td>
</tr>
<?php endforeach ?>
</table>




