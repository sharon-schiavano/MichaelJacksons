<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classifica Utenti</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" type="image/x-icon" href="../assets/favicon.ico" />
</head>
<body>
  
  <?php include '../sidebar/sidebar.html'; ?>
      
    <div class="container">
        <h1>Classifica Utenti</h1>
        <!-- Dropdown per la selezione del criterio di ordinamento -->
        <label for="sortCriteria">Ordina per:</label>
        <select id="sortCriteria">
            <option value="mjc">MJC guadagnati</option>
            <option value="billie_jean">Punteggio: Billie Jean</option>
            <option value="beat_it">Punteggio: Beat It</option>
            <option value="rock_with_you">Punteggio: Rock With You</option>
            <option value="smooth_criminal">Punteggio: Smooth Criminal</option>
            <option value="thriller">Punteggio: Thriller</option>
        </select>
        <div id="leaderboard"></div>
    </div>

    <script src="script.js"></script>
</body>
</html>
