<?php
// Ako je korisnik kliknuo na palac gore-dolje
if(isset($_POST['palacGore']) || isset($_POST['palacDolje'])){

	// Fetch palac, povacaj / smanji
	try{
		// Izvuci onaj post na koji je kliknut palac gore / dolje 
		$st = $db->prepare( 'SELECT id_post FROM post' );
		$st->execute();
	}
	catch( PDOException $e ) { exit( "PDO error #13: " . $e->getMessage() ); }

	foreach( $st->fetchAll() as $row ){

		if(isset($_POST[$row["id_post"]])){

			$idTrenutniPost = $_POST[$row["id_post"]];

			// Upvote
			if(isset($_POST['palacGore'])){				

				// Povecaj broj upvotova
				try{

					$stUp = $db->prepare( "UPDATE post SET gore = gore + 1 WHERE id_post =:id_post");
					$stUp->execute(array( 'id_post' => $idTrenutniPost ));
				}
				catch( PDOException $e ) { exit( "PDO error #14: " . $e->getMessage() ); }
				unset($_POST['palacGore']);
			}

			// Downvote
			else{

				// Povecaj broj downvotova
				try{
					$stUp = $db->prepare( "UPDATE post SET dolje = dolje + 1 WHERE id_post =:id_post");
					$stUp->execute(array( 'id_post' => $idTrenutniPost ));
				}
				catch( PDOException $e ) { exit( "PDO error #15: " . $e->getMessage() ); }
				unset($_POST['palacDolje']);
			}
		}
	}
}
?>