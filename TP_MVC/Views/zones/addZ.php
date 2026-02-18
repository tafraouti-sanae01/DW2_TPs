<h1> Ajouter une nouvelle zone </h1>

<form method="POST" action="../zones/saveZ">
    <p>
        <label>Nom de la zone :</label><br>
        <input type="text" name="nomZ" required>
    </p>
    
    <p>
        <label>Ville :</label><br>
        <input type="text" name="VilleZ" required>
    </p>
    
    <p>
        <label>Services publics :</label><br>
        <input type="text" name="ServicesPublics" required>
    </p>
    
    <p>
        <input type="submit" value="Ajouter la zone">
        <a href="../zones/index">Annuler</a>
    </p>
</form>

