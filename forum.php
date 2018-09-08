<?php
require_once 'db_connect.php';
$db = DB::getConnection();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<link type="text/css" rel="stylesheet" href="css.css"/>
	<title>Forum</title>
</head>
<body>
	<h1>Forum</h1>
	<?php

	//Ako je kreirana nova tema s početne stranice
	if(isset($_POST['autorNoveTeme']) && isset($_POST['naslovNoveTeme']) && isset($_POST['prviPost'])){

		//Kreiraj novu temu
		$time = date ("Y-m-d H:i:s");
		try{
			$st = $db->prepare( 'INSERT INTO tema(vrijeme_tema, autor, naslov) VALUES (:vrijeme_tema, :autor, :naslov)' );
			$st->execute( array( 'vrijeme_tema' => $time, 'autor' => $_POST["autorNoveTeme"], 'naslov' => $_POST["naslovNoveTeme"]) );
		}
		catch( PDOException $e ) { exit( "PDO error #5: " . $e->getMessage() ); }

		// Fetch id nove teme (da znamo u koju temu ubaciti novi, prvi post)
		try{
			$st = $db->prepare( "SELECT id_tema FROM tema WHERE '" .$_POST["autorNoveTeme"]. "'=autor AND '" .$_POST["naslovNoveTeme"] . "'=naslov" );
			$st->execute();
		}
		catch( PDOException $e ) { exit( "PDO error #6: " . $e->getMessage() ); }
		foreach( $st->fetchAll() as $row )
			$idNoveTeme = $row['id_tema'];

		//Kreiraj prvi post u novoj temi
		try{
			$st = $db->prepare( 'INSERT INTO post(id_tema, vrijeme_post, autor, sadrzaj, gore, dolje) VALUES (:id_tema, :vrijeme_post, :autor, :sadrzaj, :gore, :dolje)' );
			$st->execute( array( 'id_tema' => $idNoveTeme, 'vrijeme_post' => $time, 'autor' => $_POST["autorNoveTeme"], 'sadrzaj' => $_POST["prviPost"], 'gore' => 0, 'dolje' => 0 ) );
		}
		catch( PDOException $e ) { exit( "PDO error #7: " . $e->getMessage() ); }
 
 		// Prebaci korisnika u novo kreiranu temu
		header("Location: forum.php?id=" . $idNoveTeme);
	}
	

	// Ako je korisnik postavio novi post u temi
	if(isset($_POST['ime_noviPost']) && isset($_POST['sadrzaj_noviPost'])){

		// Fetch id teme za koju trenutno citamo post-ove
		try{
			$st = $db->prepare( "SELECT id_tema FROM tema WHERE id_tema = '" . $_GET['id'] . "'" );
			$st->execute();
		}
		catch( PDOException $e ) { exit( "PDO error #8: " . $e->getMessage() ); }
		foreach( $st->fetchAll() as $row )
			$idTrenutneTeme = $row['id_tema'];

		$time = date ("Y-m-d H:i:s");
		try{
			$st = $db->prepare( 'INSERT INTO post(id_tema, vrijeme_post, autor, sadrzaj, gore, dolje) VALUES (:id_tema, :vrijeme_post, :autor, :sadrzaj, :gore, :dolje)' );
			$st->execute( array( 'id_tema' => $idTrenutneTeme, 'vrijeme_post' => $time, 'autor' => $_POST["ime_noviPost"], 'sadrzaj' => $_POST["sadrzaj_noviPost"], 'gore' => 0, 'dolje' => 0 ) );
		}
		catch( PDOException $e ) { exit( "PDO error #9: " . $e->getMessage() ); }
	}

	require_once 'vote.php';

	//Pocetna stranica
	//Ispisi postojece teme
	if(!isset($_GET['id'])){
		try{
			$st = $db->prepare( 'SELECT id_tema, vrijeme_tema, autor, naslov FROM tema' );
			$st->execute();
		}
		catch( PDOException $e ) { exit( "PDO error #10: " . $e->getMessage() ); }

		echo "<h2>Dobrodošli na forum.</h2><br/><h3>Popis postojećih tema:</h3><br/>";

		foreach( $st->fetchAll() as $row ){
			//sort padajuce po datumu
			echo "<a href=\"forum.php?id=" . $row['id_tema'] . "\"><span>" . $row['naslov'] . ". " . "</span></a><br/>";
			echo 'Temu pokrenuo: <span class="user">' . $row['autor'] . "</span>. ";
			echo "Tema kreirana: " . $row['vrijeme_tema'] . ". ";
			echo "<hr/>";
		}
		require_once 'Forma_unosTeme.html';
	}

	//Ispisi post-ove u odabranoj temi (set-an je $_GET['id'])
	else{
		$id = $_GET['id'];
		try{
			// Izvuci one post-ove koji pripadaju trazenoj temi
			$st1 = $db->prepare( 'SELECT * FROM post WHERE ' .$id.' = id_tema' );
			$st1->execute();
		}
		catch( PDOException $e ) { exit( "PDO error #11: " . $e->getMessage() ); }

		try{
			// Izvuci onu temu u kojoj se trenutno nalazim (u svrhu ispisa naslove teme)
			$st2 = $db->prepare( 'SELECT naslov FROM tema WHERE ' .$id.' = id_tema' );
			$st2->execute();
		}
		catch( PDOException $e ) { exit( "PDO error #12: " . $e->getMessage() ); }

		// Tocno jedna tema
		foreach( $st2->fetchAll() as $row ){
			echo "<h2>Tema: " . $row['naslov'] . "</h2><hr/>";
		}

		// Moguce 0, 1, ili vise post-ova
		foreach( $st1->fetchAll() as $row ){

			echo '<span class="user">' . $row['autor'] . '</span> ' . $row['vrijeme_post'] . "<br/>";
			
			// Klikabilni gore-dolje
			echo "<form action=\"forum.php?id=" . $id . "\" method=\"post\">";				

				// Da znamo kojemu post-u povecati / smanjiti palac gore / dolje
				echo '<input type="text" class="sakrij" name="' . $row['id_post'] . '" value="' . $row['id_post'] . '" />';
				echo $row['gore'];
				?>
				<button type="submit" name="palacGore">+</button>
				<?php
				echo $row['dolje'];
				?>
				<button type="submit" name="palacDolje">-</button>
			</form>
			<br/>
			<?php
			echo $row['sadrzaj'] . "<hr/>";
		}

		// Forma za unos novog post-a
		require_once 'Forma_unosPost.php';
	}
	?>
</body>
</html>