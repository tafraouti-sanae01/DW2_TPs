<h1> Modifier la zone <?=$zone['idZ']?></h1>

<form method="POST" action="../updateZSave/<?=$zone['idZ']?>">
    <p>
        <label>Nom de la zone :</label><br>
        <input type="text" name="nomZ" value="<?= $zone['nomZ'] ?>" required>
    </p>
    
    <p>
        <label>Ville :</label><br>
        <input type="text" name="VilleZ" value="<?= $zone['VilleZ'] ?>" required>
    </p>
    
    <p>
        <label>Services publics :</label><br>
        <input type="text" name="ServicesPublics" value="<?= $zone['Services publics'] ?>" required>
    </p>
    
    <p>
        <input type="submit" value="Modifier la zone">
        <a href="../index">Annuler</a>
    </p>
</form>

