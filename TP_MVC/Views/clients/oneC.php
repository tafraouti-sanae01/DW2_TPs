<h1> Clients <?=$client['id']?></h1>
<table border="1">
<tr>
    <th>
        Nom
    </th>
    <th>
        Prenom
    </th>
    <th>
        Adresse
    </th>
</tr>
    <tr>
    <td><?= $client['nom'] ?>
    </td>
    <td>
    <?= $client['prenom'] ?>
    </td>
    <td>
    <?= $client['adresse'] ?>
    </td>
</tr>
</table>

<p>
    <a href="../updateC/<?=$client['id']?>">Modifier</a> | 
    <a href="../deleteC/<?=$client['id']?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?')">Supprimer</a> | 
    <a href="../index">Retour à la liste</a>
</p>