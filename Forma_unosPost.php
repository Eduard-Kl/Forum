<?php
echo "<form action=\"forum.php?id=" . $id . "\" method=\"post\">";
?>
	<h4>Unesi novi post</h4>
	<br/>
	<label>Ime korisnika</label><input type="text" name="ime_noviPost" />
	<br/>
	<label>Sadržaj poruke</label><textarea name="sadrzaj_noviPost"></textarea>
	<br/>
	<p>Oba polja su obavezna</p>
	<button type="submit">Pošalji</button>
</form>