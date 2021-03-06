<?php

// conteggi per pannelli notifiche della dashboard

//****************************************
// CONTEGGI
//****************************************
// segnalazioni totali
$query= "SELECT count(id) FROM segnalazioni.v_segnalazioni;";
$result = pg_query($conn, $query);
while($r = pg_fetch_assoc($result)) {
	$segn_tot = $r['count'];	
}

// segnalazioni in lavorazione
$query= "SELECT count(id) FROM segnalazioni.v_segnalazioni WHERE in_lavorazione='t';";
$result = pg_query($conn, $query);
while($r = pg_fetch_assoc($result)) {
	$segn_lav = $r['count'];	
}

// segnalazioni chiuse
$query= "SELECT count(id) FROM segnalazioni.v_segnalazioni WHERE in_lavorazione='f';";
$result = pg_query($conn, $query);
while($r = pg_fetch_assoc($result)) {
	$segn_chiuse = $r['count'];	
}

// segnalazioni da elaborare
/*$query= "SELECT count(id) FROM segnalazioni.v_segnalazioni WHERE in_lavorazione is null;";
$result = pg_query($conn, $query);
while($r = pg_fetch_assoc($result)) {
	$segn_limbo = $r['count'];	
}*/



// incarichi assegnati e non ancora presi in carico


/*$query= "SELECT count(id) FROM segnalazioni.v_incarichi_last_update WHERE id_stato_incarico=1;";
$result = pg_query($conn, $query);
while($r = pg_fetch_assoc($result)) {
	$inc_limbo = $r['count'];	
}


$query= "SELECT count(id) FROM segnalazioni.v_incarichi_last_update WHERE id_stato_incarico=2;";
$result = pg_query($conn, $query);
while($r = pg_fetch_assoc($result)) {
	$inc_carico = $r['count'];	
}


$query= "SELECT count(id) FROM segnalazioni.v_incarichi_last_update WHERE id_stato_incarico=3;";
$result = pg_query($conn, $query);
while($r = pg_fetch_assoc($result)) {
	$inc_chiusi = $r['count'];	
}


$query= "SELECT count(id) FROM segnalazioni.v_incarichi_last_update WHERE id_stato_incarico=4;";
$result = pg_query($conn, $query);
while($r = pg_fetch_assoc($result)) {
	$inc_rifiutati = $r['count'];	
}
*/



// squadre PC

$query= "SELECT count(id) FROM users.v_squadre WHERE id_stato=1 AND cod_afferenza='com_PC';";
$result = pg_query($conn, $query);
while($r = pg_fetch_assoc($result)) {
	$squadre_in_azione = $r['count'];	
}

$query= "SELECT count(id) FROM users.v_squadre WHERE id_stato=2 AND cod_afferenza='com_PC';";
$result = pg_query($conn, $query);
while($r = pg_fetch_assoc($result)) {
	$squadre_disposizione = $r['count'];	
}



/*$query= "SELECT count(id) FROM users.v_squadre WHERE id_stato=3 AND cod_afferenza='com_PC';";
$result = pg_query($conn, $query);
while($r = pg_fetch_assoc($result)) {
	$squadre_riposo = $r['count'];	
}*/



/*$query= "SELECT count(id) FROM users.v_squadre;";
$result = pg_query($conn, $query);
while($r = pg_fetch_assoc($result)) {
	$squadre_tot = $r['count'];	
}*/

// Conteggi sopralluoghi

/*$query= "SELECT count(id) FROM segnalazioni.v_sopralluoghi_conteggio where id_stato_sopralluogo=1;";
$result = pg_query($conn, $query);
while($r = pg_fetch_assoc($result)) {
	$sopralluoghi_assegnati = $r['count'];	
}


$query= "SELECT count(id) FROM segnalazioni.v_sopralluoghi_conteggio where id_stato_sopralluogo=2;";
$result = pg_query($conn, $query);
while($r = pg_fetch_assoc($result)) {
	$sopralluoghi_corso = $r['count'];	
}


$query= "SELECT count(id) FROM segnalazioni.v_sopralluoghi_conteggio where id_stato_sopralluogo=3;";
$result = pg_query($conn, $query);
while($r = pg_fetch_assoc($result)) {
	$sopralluoghi_conclusi = $r['count'];	
}

$query= "SELECT count(id) FROM segnalazioni.v_sopralluoghi_conteggio;";
$result = pg_query($conn, $query);
while($r = pg_fetch_assoc($result)) {
	$sopralluoghi_tot = $r['count'];	
}
*/


// Conteggi provvedimenti cautelari

$query= "SELECT count(id) FROM segnalazioni.v_provvedimenti_cautelari_last_update where id_stato_provvedimenti_cautelari=1;";
$result = pg_query($conn, $query);
while($r = pg_fetch_assoc($result)) {
	$pc_assegnati = $r['count'];	
}


$query= "SELECT count(id) FROM segnalazioni.v_provvedimenti_cautelari_last_update where id_stato_provvedimenti_cautelari=2;";
$result = pg_query($conn, $query);
while($r = pg_fetch_assoc($result)) {
	$pc_corso = $r['count'];	
}


$query= "SELECT count(id) FROM segnalazioni.v_provvedimenti_cautelari_last_update where id_stato_provvedimenti_cautelari=3;";
$result = pg_query($conn, $query);
while($r = pg_fetch_assoc($result)) {
	$pc_conclusi = $r['count'];	
}

/*$query= "SELECT count(id) FROM segnalazioni.v_provvedimenti_cautelari_last_update;";
$result = pg_query($conn, $query);
while($r = pg_fetch_assoc($result)) {
	$pc_tot = $r['count'];	
}*/




?>