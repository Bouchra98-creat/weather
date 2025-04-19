<?php
// Connexion à la base de données
$host = 'localhost'; // Remplace par ton serveur
$dbname = 'station_meteo'; // Remplace par le nom de ta base
$user = 'root'; // Ton utilisateur
$password = ''; // Ton mot de passe

try {
    // Création d'une connexion PDO à la base de données avec gestion des erreurs
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Active les erreurs sous forme d'exceptions
} catch (PDOException $e) {
    // En cas d'erreur de connexion, affiche un message et arrête le script
    die("Erreur de connexion : " . $e->getMessage());
}


// Récupérer la dernière valeur mesurée
$query = $pdo->query("SELECT * FROM donnees_meteo ORDER BY id DESC LIMIT 1");
$dernieres_donnees = $query->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Station Météo</title>
    <link rel="stylesheet" href="style.css?v=2">
    <script defer src="script.js"></script>
</head>
<body>
    <h1>Station ☁️ Météo</h1>
    <hr/>
    <nav class="menu-nav">
        <ul>
            <li class="btn"><a href="index.php"><i class="fas fa-home"></i> Accueil</a></li>
            <li class="btn"><a href="Historique des données.php"><i class="fas fa-chart-line"></i> Historique des données</a></li>
        </ul>
    </nav>

    <div class="weather-dashboard">
        <div class="location">
            <h2>Oran</h2>
            <span class="temperature" id="temperature"><?php echo htmlspecialchars($dernieres_donnees['temperature']); ?>°C</span>
        </div>
        <div class="weather-details" id="weather-data">
            <div class="circle"> <?php echo htmlspecialchars($dernieres_donnees['pression']); ?> hPa <br> Pression</div>
            <div class="circle"> <?php echo htmlspecialchars($dernieres_donnees['luminosite']); ?> lux <br> Luminosité</div>
            <div class="circle"> <?php echo htmlspecialchars($dernieres_donnees['humidite']); ?> %<br> Humidite</div>
            
        </div>
    </div>

    <script>
       function updateWeather() {
    fetch('data.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('temperature').innerText = data.temperature + '°C';
            document.getElementById('weather-data').innerHTML = `
                <div class="circle"> ${data.pression} hPa <br> Pression</div>
                <div class="circle"> ${data.luminosite} lux <br> Luminosité</div>
                <div class="circle"> ${data.humidite} <br> Humidite</div>
            `;  // <-- Ici, le guillemet fermant manquait
        })
        .catch(error => console.error('Erreur lors de la mise à jour des données :', error));
}

        setInterval(updateWeather, 600000); // Met à jour toutes les 10 minutes
    </script>
</body>
</html>
