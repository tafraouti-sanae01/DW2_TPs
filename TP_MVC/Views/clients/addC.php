<h1> Ajouter un nouveau client </h1>

<form method="POST" action="../clients/saveC">
    <p>
        <label>Nom :</label><br>
        <input type="text" name="nom" required>
    </p>
    
    <p>
        <label>Prénom :</label><br>
        <input type="text" name="prenom" required>
    </p>
    
    <p>
        <label>Adresse :</label><br>
        <input type="text" name="adresse" required>
    </p>
    
    <p>
        <input type="submit" value="Ajouter le client">
        <a href="../clients/index">Annuler</a>
    </p>
</form>

