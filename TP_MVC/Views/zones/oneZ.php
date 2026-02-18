<h1> Zone <?=$zone['idZ']?></h1>
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
</tr>
    <tr>
    <td><?= $zone['nomZ'] ?>
    </td>
    <td>
    <?= $zone['VilleZ'] ?>
    </td>
    <td>
    <?= $zone['Services publics'] ?>
    </td>
</tr>
</table>

<p>
    <a href="../updateZ/<?=$zone['idZ']?>">Modifier</a> | 
    <a href="../deleteZ/<?=$zone['idZ']?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette zone ?')">Supprimer</a> | 
    <a href="../index">Retour à la liste</a>
</p>

