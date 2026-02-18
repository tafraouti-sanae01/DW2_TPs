<h1> Modifier le client <?=$client['id']?></h1>

<form method="POST" action="../updateCSave/<?=$client['id']?>">
    <p>
        <label>Nom :</label><br>
        <input type="text" name="nom" value="<?= $client['nom'] ?>" required>
    </p>
    
    <p>
        <label>Prénom :</label><br>
        <input type="text" name="prenom" value="<?= $client['prenom'] ?>" required>
    </p>
    
    <p>
        <label>Adresse :</label><br>
        <input type="text" name="adresse" value="<?= $client['adresse'] ?>" required>
    </p>
    
    <p>
        <input type="submit" value="Modifier le client">
        <a href="../index">Annuler</a>
    </p>
</form>

