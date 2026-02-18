<h1> Liste des zones </h1>
<p><a href="../zones/addZ"> >> Ajouter une zone</a></p>
<table border="1">
<tr>
    <th>
        Nom de la zone
    </th>
    <th>
        Ville
    </th>
    <th>
        Services publics
    </th>
    <th>
        Actions
    </th>
</tr>
<?php foreach($zones as $zone):?>
<tr>
    <td><?= $zone['nomZ'] ?></td>
    <td><?= $zone['VilleZ'] ?></td> 
    <td><?= $zone['Services publics'] ?></td>
    <td>
        <a href="../zones/getZ/<?=$zone['idZ']?>">Afficher</a> | 
        <a href="../zones/updateZ/<?=$zone['idZ']?>">Modifier</a> | 
        <a href="../zones/deleteZ/<?=$zone['idZ']?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette zone ?')">Supprimer</a>
    </td>
</tr>
<?php endforeach ?>
</table>

