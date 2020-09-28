<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="./css/style.css"/>
</head>
<body>
<div id="centre">
<h1>Qui a vu ou cliqué sur mon dossier ?</h1>
<span>Les événements se trouvent dans le tableau ci-dessous.</span><br><br>
<span>Note: Il n'est pas garanti qu'un clic (manuel) d'un utilisateur soit obligatoirement à l'origine de l'action (si le dossier est renommé, déplacé ou encore visible depuis un dossier parent, l'événement s'affiche également)</span>
<br><br>Les événements consécutifs à moins d'une minute d'intervalle ne sont pas comptabilisés.<br>
<a href="./index.php">Retour à l'accueil</a>
<!--Un concept proposé par Michel Kartner <a href="https://cyberini.com">@cyberini</a>.--><br>
<hr>

<?php

require __DIR__ . "/infos_bdd.php";

if(isset($_GET["id"]) && strlen($_GET['id']) == 13) {
	
	$mysqli = new mysqli($host, $user, $password, $database_name); // Connexion BDD (utilisateur et mot de passe définis dans infos_bdd.sql)
	if ($mysqli->connect_errno) {
		die("Échec de la connexion"); 
	}
	$mysqli->set_charset("utf8");
	$rows=$mysqli->query('SELECT * FROM `table1` WHERE id="' . $mysqli->real_escape_string($_GET['id']) . '"');

	if ($rows->num_rows == 0) {
		echo "Aucune donnée pour le moment.";
	}   
	else {
		while($row = $rows->fetch_array()) //Boucle pour chaque ligne (chaque clic)
		{
			if($row['val'] == "" || $row['val'] == "") {
				echo "Pas encore de données pour le moment";
			} else {
				echo "<table><thead><td>Nom ordinateur</td><td>Date vue / clic</td></thead><tbody><tr>";
				//echo "<td>" . htmlentities($row['id'])."</td>";
				echo "<td>" . htmlentities($row['val'])."</td>";
				echo "<td> " . htmlentities($row['date'])." (GMT+1)</td>";
				echo "</tr></tbody></table>";
			}
		}
	}
	$mysqli->close();
}

?>
</div></body></html>