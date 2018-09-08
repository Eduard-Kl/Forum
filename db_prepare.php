<?php

// Inicijalizacija baze
require_once 'db_connect.php';
$db = DB::getConnection();

try{
	$st = $db->prepare( 
		'CREATE TABLE IF NOT EXISTS tema (' .
		'id_tema int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
		'vrijeme_tema datetime NOT NULL,' .
		'autor varchar(20) NOT NULL,' .
		'naslov varchar(255) NOT NULL)'
	);
	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error #1: " . $e->getMessage() ); }

echo "Napravio tablicu 'tema'.<br />";

try{
	$st = $db->prepare( 
		'CREATE TABLE IF NOT EXISTS post (' .
		'id_post int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
		'id_tema int NOT NULL,'.
		'vrijeme_post datetime NOT NULL,'.
		'autor varchar(20) NOT NULL,' .
		'sadrzaj varchar(1000) NOT NULL,'.
		'gore int NOT NULL,'.
		'dolje int NOT NULL)'
	);
	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error #2: " . $e->getMessage() ); }

echo "Napravio tablicu 'post'.<br />";



// Ubaci teme
try{
	$st = $db->prepare( 'INSERT INTO tema(id_tema, vrijeme_tema, autor, naslov) VALUES (:id_tema, :vrijeme_tema, :autor, :naslov)' );

	$st->execute( array( 'id_tema' => '1', 'vrijeme_tema' => '2017-05-20 09:10:36', 'autor' => 'Pero', 'naslov' => 'Windows') );
	$st->execute( array( 'id_tema' => '2', 'vrijeme_tema' => '2017-05-22 09:18:40', 'autor' => 'Mirko', 'naslov' => 'Linux') );
	$st->execute( array( 'id_tema' => '3', 'vrijeme_tema' => '2017-05-01 04:08:18', 'autor' => 'Slavko', 'naslov' => 'Mac OS') );
	$st->execute( array( 'id_tema' => '4', 'vrijeme_tema' => '2017-05-29 11:11:10', 'autor' => 'Ivan', 'naslov' => 'Android') );
	$st->execute( array( 'id_tema' => '5', 'vrijeme_tema' => '2017-02-03 07:01:30', 'autor' => 'Ana', 'naslov' => 'iOS') );
}
catch( PDOException $e ) { exit( "PDO error #3: " . $e->getMessage() ); }

echo "Ubacio popis tema u tablicu 'tema'.<br />";


//Ubaci post-ove
try{
	$st = $db->prepare( 'INSERT INTO post(id_post, id_tema, vrijeme_post, autor, sadrzaj, gore, dolje) VALUES (:id_post, :id_tema, :vrijeme_post, :autor, :sadrzaj, :gore, :dolje)' );

	$st->execute( array( 'id_post' => '1', 'id_tema' => '1', 'vrijeme_post' => '2017-05-22 09:18:40', 'autor' => 'Pero', 'sadrzaj' => 'Ne mogu izvrsiti update na Windows10 - ispise se greska 0xc1900107. Zna li netko sto to znaci?', 'gore' => 0, 'dolje' => 0 ) );
	$st->execute( array( 'id_post' => '2', 'id_tema' => '2', 'vrijeme_post' => '2017-05-22 09:18:40', 'autor' => 'Mirko', 'sadrzaj' => 'Kako ukljuciti firewall na linux-u?', 'gore' => 0, 'dolje' => 0 ) );
 	$st->execute( array( 'id_post' => '3', 'id_tema' => '2', 'vrijeme_post' => '2017-05-22 09:18:40', 'autor' => 'Mirko', 'sadrzaj' => 'Nema veze, pronasao sam rjesenje. Mozete izbrisati ovu temu.', 'gore' => 0, 'dolje' => 0 ) );
 	$st->execute( array( 'id_post' => '4', 'id_tema' => '3', 'vrijeme_post' => '2017-05-22 09:18:40', 'autor' => 'Slavko', 'sadrzaj' => 'Pozdrav, imam problem s otvaranjem aplikacija na macOS-u. Dobivam poruku "damaged and can’t be opened". Postoji li jednostavno rjesenje?', 'gore' => 0, 'dolje' => 0 ) );
 	$st->execute( array( 'id_post' => '5', 'id_tema' => '3', 'vrijeme_post' => '2017-05-22 09:18:40', 'autor' => 'Ivo', 'sadrzaj' => 'Ne znam, mislim da je najbolje kupiti novi macbook.', 'gore' => 0, 'dolje' => 0 ) );
}
catch( PDOException $e ) { exit( "PDO error #4: " . $e->getMessage() ); }

echo "Ubacio post-ove u tablicu 'post'.<br />";
?> 
