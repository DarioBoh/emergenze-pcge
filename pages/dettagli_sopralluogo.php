<?php 
// Start the session
session_start();
//$_SESSION['user']="MRZRRT84B01D969U";

$id=$_GET["id"];
$subtitle="Dettagli sopralluogo n. ".$id;


?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="roberto" >

    <title>Gestione emergenze</title>
<?php 
require('./req.php');

require('/home/local/COMGE/egter01/emergenze-pcge_credenziali/conn.php');

require('./check_evento.php');


$query_evento_aperto="SELECT s.id,
       e.valido 
		FROM segnalazioni.t_sopralluoghi s
		JOIN eventi.t_eventi e on e.id=s.id_evento
		WHERE s.id=".$id.";";

$result_e=pg_query($conn, $query_evento_aperto);
while($r_e = pg_fetch_assoc($result_e)) {
	if($r_e['valido']=='f') {
		$table='v_sopralluoghi_eventi_chiusi';
		//echo "false";
	} else {
		$table='v_sopralluoghi';
		//echo "true";
	}
}

?>
    
</head>

<body>

    <div id="wrapper">

        <?php 
            require('./navbar_up.php')
        ?>  
        <?php 
            require('./navbar_left.php')
        ?> 
            

        <div id="page-wrapper">
            <!--div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Titolo pagina</h1>
                </div>
            </div-->
            <!-- /.row -->
            
            <br><br>
            <div class="row">
            <div class="col-md-6">
				<?php
				$query= "SELECT *, st_x(st_transform(geom,4326)) as lon , st_y(st_transform(geom,4326)) as lat FROM segnalazioni.".$table." WHERE id=".$id." ORDER BY data_ora_stato DESC LIMIT 1;";
				//echo $query
        
				$result=pg_query($conn, $query);
				while($r = pg_fetch_assoc($result)) {
					$id_squadra_attiva=$r['id_squadra'];
					//$check_operatore=0;
					$id_squadra=$r['id_squadra'];
               $id_profilo=$r['id_profilo'];
			   //echo $id_profilo;
			   //echo "<br>";
					require('./check_operatore.php');
					?>            
            	
            	
               <h4><br><b>Squadra</b>: <?php echo $r['descrizione_uo'];?>
               <?php
               if ($check_squadra==1){
						echo ' ( <i class="fas fa-user-check" style="color:#5fba7d"></i> )';
				}
					require('./check_responsabile.php');
					?>
               </h4>
               <h4><br><b>Descrizione sopralluogo</b>: <?php echo $r['descrizione']; ?></h4>
               <h4><br><b>Data e ora invio sopralluogo</b>: <?php echo $r['data_ora_invio']; ?></h4>
               <?php //echo $id_squadra_attiva; ?>
               <hr>
            	
						
						<?php 
						$lon=$r['lon'];
						$lat=$r['lat'];
						$zoom=16;
            		$id_lavorazione=$r['id_lavorazione'];
            		if ( $id_lavorazione>0){ 
            			$check_segnalazione=1;
            		} else {
            			$check_segnalazione=0;
            		}
					$id_evento=$r['id_evento'];
						echo "<h2>";
						//1;"Inviato ma non ancora preso in carico"
						//2;"Preso in carico"
						//3;"Chiuso"
						//4;"Rifiutato"
						
						$stato_attuale=$r["id_stato_sopralluogo"];
						if ($r["id_stato_sopralluogo"]==1){
							echo '<i class="fas fa-pause" style="color:orange"></i> ';
						} else if  ($r["id_stato_sopralluogo"]==2) {
							if ($r['time_start']!=null) {
								echo '<i class="fas fa-play" style="color:green"></i> ';
							} else {
								echo '<i class="fas fa-play" style="color:orange"></i> ';
							}
						} else if  ($r["id_stato_sopralluogo"]==3) {
							echo '<i class="fas fa-stop"></i> ';
						} else if  ($r["id_stato_sopralluogo"]==4) {
							echo '<i class="fas fa-times-circle"></i> ';
						}
						
						
						
						echo $r['descrizione_stato'];
						if ($r["parziale"]=='t'){
							echo '<br><br><i class="fas fa-battery-quarter"></i>  Presa in carico parziale';
						}
						echo "</h2><hr>";
						if ($r["id_stato_sopralluogo"]==1){
						?>
				      <div style="text-align: center;">
				      <?php 
				      	$check_mail=0; //check se ci sono mail a sistema
				      	$query2="SELECT mail FROM users.t_mail_squadre WHERE cod='".$r['id_squadra']."';";
							$result2=pg_query($conn, $query2);
							while($r2 = pg_fetch_assoc($result2)) {
							  $check_mail=1; //check se ci sono mail a sistema
							}
							if($check_mail==1 and $check_operatore==1) {
								//echo $r['id_squadra'];
								echo '<a class="btn btn-info" href="sopralluoghi/sollecito.php?id='.$id.'&u='.$r['id_squadra'].'"> <i class="fas fa-at"></i> Invia sollecito </a> ';
							
							}
						if ($check_squadra==1 or $check_operatore==1){
				      ?>
				      <button type="button" class="btn btn-success"  data-toggle="modal" data-target="#accetta"><i class="fas fa-thumbs-up"></i> Presa in carico</button>

						<?php } ?>
						<!--button type="button" class="btn btn-danger"  data-toggle="modal" data-target="#rifiuta"><i class="fas fa-thumbs-down"></i> Rifiuta (DEMO)</button-->
						</div>
						
						<!-- Modal accetta-->
						<div id="accetta" class="modal fade" role="dialog">
						  <div class="modal-dialog">
						
						    <!-- Modal content-->
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal">&times;</button>
						        <h4 class="modal-title">Previsioni esecuzione sopralluogo</h4>
						      </div>
						      <div class="modal-body">
						      
						
						   <form autocomplete="off" action="sopralluoghi/accetta.php?id=<?php echo $id; ?>" method="POST">
							<input type="hidden" name="uo" value="<?php echo $r['descrizione_uo'];?>" />
							<input type="hidden" name="squadra" value="<?php echo $r['id_squadra'];?>" />
							<input type="hidden" name="id_lavorazione" value="<?php echo $r['id_lavorazione'];?>" />
							<!--input type="hidden" name="uo" value="<?php echo $r['descrizione_uo'];?>" /-->
								
									 <div class="form-group">
						<label for="data_inizio" >Data prevista per eseguire l'sopralluogo (AAAA-MM-GG) </label>  <font color="red">*</font>                 
						<input type="text" class="form-control" name="data_inizio" id="js-date" required>
						<div class="input-group-addon">
							<span class="glyphicon glyphicon-th"></span>
						</div>
					</div> 
					
					<div class="form-group"-->

                <label for="ora_inizio"> Ora inizio:</label> <font color="red">*</font>

              <div class="form-row">
   
   
    				<div class="form-group col-md-6">
                  <select class="form-control"  name="hh_start" required>
                  <option name="hh_start" value="" > Ora </option>
                    <?php 
                      $start_date = 0;
                      $end_date   = 24;
                      for( $j=$start_date; $j<=$end_date; $j++ ) {
                      	if($j<10) {
                        	echo '<option value="0'.$j.'">0'.$j.'</option>';
                        } else {
                        	echo '<option value="'.$j.'">'.$j.'</option>';
                        }
                      }
                    ?>
                  </select>
                  </div>	
                  
      				<div class="form-group col-md-6">
                  <select class="form-control"  name="mm_start" required>
                  <option name="mm_start" value="00" > 00 </option>
                    <?php 
                      $start_date = 0;
                      $end_date   = 59;
                      $incremento = 10; 
                      for( $j=$start_date; $j<=$end_date; $j+=$incremento) {
                      	if($j<10) {
                        	echo '<option value="0'.$j.'">0'.$j.'</option>';
                        } else {
                        	echo '<option value="'.$j.'">'.$j.'</option>';
                        }
                      }
                    ?>
                  </select>
                  </div>                
                  
                </div>  
                </div>
								
					<!--div class="form-group">		
							<div class="radio-inline">
							  <label><input type="radio" name="parziale" value='f' required="">Presa in carico regolare</label>
							</div>
							<div class="radio-inline">
							  <label><input type="radio" name="parziale" value='t'>Presa in carico parziale</label>
							</div>				
						</div-->		
								
						           <div class="form-group">
									    <label for="note">Note</label>
									    <textarea class="form-control" id="note" name="note" rows="3"></textarea>
									  </div>    
						
						
						
						        <button  id="conferma" type="submit" class="btn btn-primary">Prendi in carico sopralluogo</button>
						            </form>
						
						      </div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
						      </div>
						    </div>
						
						  </div>
						</div>
						

						<!-- Modal rifiuta-->
						<div id="rifiuta" class="modal fade" role="dialog">
						  <div class="modal-dialog">
						
						    <!-- Modal content-->
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal">&times;</button>
						        <h4 class="modal-title">Rifiuta sopralluogo</h4>
						      </div>
						      <div class="modal-body">
						      
						
						        <form autocomplete="off" action="sopralluoghi/rifiuta.php?id=<?php echo $id; ?>" method="POST">
									<input type="hidden" name="uo" value="<?php echo $r['descrizione_uo'];?>" />
									<input type="hidden" name="squadra" value="<?php echo $r['id_squadra'];?>" />
									<input type="hidden" name="id_lavorazione" value="<?php echo $r['id_lavorazione'];?>" />
										 <div class="form-group">
									    <label for="note_rifiuto">Note rifiuto</label>  <font color="red">*</font>
									    <textarea required="" class="form-control" id="note_rifiuto"  name="note_rifiuto" rows="3"></textarea>
									  </div>
						
						
						
						        <button  id="conferma" type="submit" class="btn btn-primary">Rifiuta sopralluogo</button>
						            </form>
						
						      </div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
						      </div>
						    </div>
						
						  </div>
						</div>
						
						
						<hr>
						
						<?php
							
							
							
						} else if ($r["id_stato_sopralluogo"]==2) {
							
							
							$check_richiesta_cambio=0;
							$query3="SELECT * FROM segnalazioni.t_sopralluoghi_richiesta_cambi WHERE id_sopralluogo=".$id." AND eseguito='f';";
							//echo $query3 . "<br>";
							$result3=pg_query($conn, $query3);
							while($r3 = pg_fetch_assoc($result3)) {
							  $check_richiesta_cambio=1; //check se ci sono richieste cambi
							}
							$query3="SELECT * FROM segnalazioni.t_sopralluoghi_richiesta_cambi WHERE id_sopralluogo=".$id." AND eseguito is null;";
							//echo $query3 . "<br>";
							$result3=pg_query($conn, $query3);
							while($r3 = pg_fetch_assoc($result3)) {
							  $check_richiesta_cambio=-1; //check se ci sono richieste cambi
							 
							}
							if($check_richiesta_cambio==0) {
								if ($check_squadra==1 or $check_operatore==1){
						?>
						
						
						   <a type="button" class="btn btn-warning"  href="./sopralluoghi/chiedi_cambio.php?id=<?php echo $id;?>&l=<?php echo $id_lavorazione;?>"><i class="fas fa-exchange-alt"></i> Richiesta di cambio squadra</a>
				      	<?php
				      }
				      } else if($check_richiesta_cambio==-1){
				      	?>
							<h4> Richiesta cambio in corso ( 
							<?php
							$querys="SELECT * FROM segnalazioni.join_sopralluoghi_squadra WHERE id_sopralluogo=".$id." and valido is null; ";
							//echo $querys;
							$results=pg_query($conn, $querys);
							while($rs = pg_fetch_assoc($results)) {
								$old_id = $rs['id_squadra'];
							}
							$results=pg_query($conn, $querys);
							while($rs = pg_fetch_assoc($results)) {
								echo $rs['nome']. " in uscita";
							}
							
							
							?>
							)</h4>
							<?php
							if ($check_squadra==1 or $check_operatore==1){
							?>
							<a type="button" class="btn btn-warning"  href="./sopralluoghi/cambio2.php?id=<?php echo $id;?>&os=<?php echo $old_id;?>"><i class="fas fa-exchange-alt"></i> Conferma che il cambio squadra<br>è stato portato a termine</a>

						<?php
						}
						 
				      } else {
				      	?>
				      	<div style="text-align: center;">
				      	<h3> <i class="fa fa-exclamation fa-fw" style="color:red"></i>
				      	Richiesto cambio squadra
				      	<i class="fa fa-exclamation fa-fw" style="color:red"></i>
				      	</h3>
				      	<?php 
							if ($check_operatore==1){
							?>
				      	<button type="button" class="btn btn-warning"  data-toggle="modal" data-target="#cambio"><i class="fas fa-exchange-alt"></i> Cambio squadra</button>
							<?php
							}
							?>
							</div>
						<!-- Modal incarico interno-->
						<div id="cambio" class="modal fade" role="dialog">
						  <div class="modal-dialog">

							<!-- Modal content-->
							<div class="modal-content">
							  <div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">Cambio squadra sopralluogo</h4>
							  </div>
							  <div class="modal-body">
							  

								<form autocomplete="off" action="sopralluoghi/cambio.php?id=<?php echo $id; ?>&l=<?php echo $id_lavorazione; ?>" method="POST">
								<input type="hidden" name="uo_old" value="<?php echo $r['descrizione_uo'];?>" />
								<input type="hidden" name="id_squadra_old" value="<?php echo $r['id_squadra'];?>" />

									<?php
									$query2="SELECT * FROM users.v_squadre WHERE id_stato=2 ORDER BY nome ";
									$result2 = pg_query($conn, $query2);
									?>
									<div class="form-group">
									  <label for="id_civico">Seleziona squadra:</label> <font color="red">*</font>
										<select class="form-control" name="uo" id="uo-list" class="demoInputBox" required="">
										<option  id="uo" name="uo" value="">Seleziona la squadra</option>
										<?php    
										while($r2 = pg_fetch_assoc($result2)) { 
											$valore=  $r2['cf']. ";".$r2['nome'];            
										?>
													
												<option id="uo" name="uo" value="<?php echo $r2['id'];?>" ><?php echo $r2['nome'].' ('.$r2['id'].')';?></option>
										 <?php } ?>
									</select>
									<small> Se non trovi una squadra adatta vai alla <a href="gestione_squadre.php" >gestione squadre</a>. </small>
									 </div>       
									
										  



								<button  id="conferma" type="submit" class="btn btn-primary">Cambia squadra</button>
									</form>

							  </div>
							  <div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
							  </div>
							</div>

						  </div>
						</div>
						
						
						
						
						
						
						
						<?php 
				      } 
				      ?>
						
							<h4><br><b>Ora prevista per eseguire il sopralluogo</b>: <?php echo $r['time_preview']; ?></h4>
							<?php if ($r['time_start']==''){
								if ($check_squadra==1 or $check_operatore==1){
							?>
								<a class="btn btn-success" href="./sopralluoghi/start.php?id=<?php echo $id;?>"><i class="fas fa-play"></i> La squadra è sul posto </a><br><br>
							<?php 
								}
							} else { ?>
								<h4><br><b>Ora inizio esecuzione sopralluogo</b>: <?php echo $r['time_start']; ?></h4>
							<?php } 
								if ($check_squadra==1 or $check_operatore==1){
							?>
							
							<button type="button" class="btn btn-danger"  data-toggle="modal" data-target="#chiudi"><i class="fas fa-stop"></i> Chiudi</button>
						
						<?php	
							}
						} else if ($r["id_stato_sopralluogo"]==3) {
						?>
							<h4><br><b>Ora prevista per eseguire il sopralluogo</b>: <?php echo $r['time_preview']; ?></h4>
							<h4><br><b>Ora inizio esecuzione sopralluogo</b>: 
							<?php 
							if($r['time_start']!=''){
								echo $r['time_start']; 
							} else {
								echo 'n.d (non in corso o avvio non inserito a sistema)';
							}
							?>
							</h4>
							<h4><br><b>Ora chiusura sopralluogo</b>: <?php echo $r['time_stop']; ?></h4><hr>
							<h4><br><b>Note chiusura sopralluogo</b>: <?php echo $r['note_ente']; ?></h4><hr>
						
						<?php	
						} else if ($r["id_stato_sopralluogo"]==4) {
						?>	
							<h4><br><b>Note rifiuto</b>: <?php echo $r['note_rifiuto']; ?></h4>
						<?php	
						}
					?>
					
					
					<!-- Modal rifiuta-->
						<div id="chiudi" class="modal fade" role="dialog">
						  <div class="modal-dialog">
						
						    <!-- Modal content-->
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal">&times;</button>
						        <h4 class="modal-title">Chiudi sopralluogo</h4>
						      </div>
						      <div class="modal-body">
						      
						
						        <form autocomplete="off" action="sopralluoghi/chiudi.php?id=<?php echo $id; ?>" method="POST">
									<input type="hidden" name="uo" value="<?php echo $r['descrizione_uo'];?>" />
									<input type="hidden" name="squadra" value="<?php echo $id_squadra_attiva;?>" />
									<input type="hidden" name="id_lavorazione" value="<?php echo $r['id_lavorazione'];?>" />
										 <div class="form-group">
									    <label for="note_rifiuto">Note chiusura</label>  <font color="red">*</font>
									    <textarea required="" class="form-control" id="note_rifiuto"  name="note_rifiuto" rows="3"></textarea>
									  </div>
						
						
						
						        <button  id="conferma" type="submit" class="btn btn-primary">Chiudi sopralluogo</button>
						            </form>
						
						      </div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
						      </div>
						    </div>
						
						  </div>
						</div>
					
					<?php

					
					}
					echo "<hr>";
					if ($check_segnalazione==1){
						include 'incarichi/panel_comunicazioni.php';
					} else{
						include 'sopralluoghi/panel_comunicazioni.php';
					}
					if ($stato_attuale<3){
					?>
					<div style="text-align: center;">
					<?php 
					if ($check_squadra==1 or $check_operatore==1){
					?>
					<button type="button" class="btn btn-info"  data-toggle="modal" data-target="#comunicazione_da_UO"><i class="fas fa-comment"></i> Invia comunicazione a Centrale</button>
					<?php }
					if ($check_operatore==1){ ?>
					<button type="button" class="btn btn-info"  data-toggle="modal" data-target="#comunicazione_a_UO"><i class="fas fa-comment"></i> Invia comunicazione a Squadra</button>
					<?php } ?>
					</div>
					
					<!-- Modal comunicazione da UO-->
						<div id="comunicazione_da_UO" class="modal fade" role="dialog">
						  <div class="modal-dialog">
						
						    <!-- Modal content-->
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal">&times;</button>
						        <h4 class="modal-title">Comunicazione a centrale responsabile sopralluogo</h4>
						      </div>
						      <div class="modal-body">
						      
						
						        <form autocomplete="off"  enctype="multipart/form-data"  action="sopralluoghi/comunicazione_da_UO.php?id=<?php echo $id; ?>" method="POST">
									<input type="hidden" name="uo" value="<?php echo $r['descrizione_uo'];?>" />
									<input type="hidden" name="id_lavorazione" value="<?php echo $r['id_lavorazione'];?>" />
									<input type="hidden" name="id_evento" value="<?php echo $id_evento;?>" />
										 <div class="form-group">
									    <label for="note">Testo comunicazione</label>  <font color="red">*</font>
									    <textarea required="" class="form-control" id="note"  name="note" rows="3"></textarea>
									  </div>
									
									<!--	RICORDA	  enctype="multipart/form-data" nella definizione del form    -->
									<div class="form-group">
									   <label for="note">Eventuale allegato</label>
										<input type="file" class="form-control-file" name="userfile" id="userfile">
									</div>
						
						        <button  id="conferma" type="submit" class="btn btn-primary">Invia comunicazione</button>
						            </form>
						
						      </div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
						      </div>
						    </div>
						
						  </div>
						</div>
					
					
					<!-- Modal comunicazione a UO-->
						<div id="comunicazione_a_UO"  class="modal fade" role="dialog">
						  <div class="modal-dialog">
						
						    <!-- Modal content-->
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal">&times;</button>
						        <h4 class="modal-title">Comunicazione a squadra responsabile sopralluogo</h4>
						      </div>
						      <div class="modal-body">
						      
						
						        <form autocomplete="off"  enctype="multipart/form-data" action="sopralluoghi/comunicazione_a_UO.php?id=<?php echo $id; ?>" method="POST">
									<input type="hidden" name="uo" value="<?php echo $id_squadra_attiva;?>" />
									<input type="hidden" name="id_lavorazione" value="<?php echo $r['id_lavorazione'];?>" />
									<input type="hidden" name="id_evento" value="<?php echo $id_evento;?>" />
										 <div class="form-group">
									    <label for="note">Testo comunicazione </label>
									    <textarea required="" class="form-control" id="note"  name="note" rows="3"></textarea>
									  </div>
									  
									<!--	RICORDA	  enctype="multipart/form-data" nella definizione del form    -->
									<div class="form-group">
									   <label for="note">Eventuale allegato</label>
										<input type="file" class="form-control-file" name="userfile" id="userfile">
									</div>
						
						
						        <button  id="conferma" type="submit" class="btn btn-primary">Invia comunicazione e mail</button>
						            </form>
						
						      </div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
						      </div>
						    </div>
						
						  </div>
						</div>
					
					
					
					
					
					
					
					
					<hr>
					<?php
					}
					if ($check_segnalazione==1){
					?>
					<h3><i class="fas fa-list-ul"></i> Segnalazioni collegate al sopralluogo </h3><br>

					<?php
					// fine $query che verifica lo stato
					$query= "SELECT * FROM segnalazioni.".$table." WHERE id=".$id." and id_stato_sopralluogo =".$stato_attuale."  ORDER BY id_segnalazione;";
					
					
					//echo $query
        
					$result=pg_query($conn, $query);
					while($r = pg_fetch_assoc($result)) {
						//echo '<b>Unità operativa</b>: '.$r['descrizione_uo'];
						
						
					?>
						
						
									<div class="panel-group">
									  <div class="panel panel-info">
									    <div class="panel-heading">
									      <h4 class="panel-title">
									        <a data-toggle="collapse" href="#segnalazione_<?php echo $r["id_segnalazione"];?>"><i class="fas fa-map-marker-alt"></i> Segnalazione n. <?php echo $r['id_segnalazione'];?> </a>
									      </h4>
									    </div>
									    <div id="segnalazione_<?php echo $r["id_segnalazione"];?>" class="panel-collapse collapse">
									      <div class="panel-body"-->
										<?php
										if($r['rischio'] =='t') {
											echo '<i class="fas fa-circle fa-1x" style="color:#ff0000"></i> Persona a rischio';
										} else if ($r['rischio'] =='f') {
											echo '<i class="fas fa-circle fa-1x" style="color:#008000"></i> Non ci sono persone a rischio';
										} else {
											echo '<i class="fas fa-circle fa-1x" style="color:#ffd800"></i> Non è specificato se ci siano persone a rischio';
										}
										?>
										<!--h4><i class="fas fa-list-ul"></i> Generalità </h4-->
										<br><b>Descrizione</b>: <?php echo $r['descrizione_segnalazione']; ?>
										<br><b>Tipologia</b>: <?php echo $r['criticita']; ?>
										<br> <a class="btn btn-info" href="./dettagli_segnalazione.php?id=<?php echo $r['id_segnalazione']; ?>" > Vai alla pagina della segnalazione </a>
										<hr>
										<?php
										$id_segnalazione=$r['id_segnalazione'];
										include './segnalazioni/section_oggetto_rischio.php';
										?>
										
							
							
										
									
									
												</div>
									    </div>
									  </div>
									</div>
						
						<a class="btn btn-info" href="dettagli_segnalazione.php?id=<?php echo $r["id_segnalazione"];?>"><i class="fas fa-undo"></i> Torna alla segnalazione <?php echo $r["id_segnalazione"];?></a>
						<br><br>
						<?php
						
						
						} // chiudi if
					}
					?>
						
						<br>
						
						<br>
						</div> 
						<div class="col-md-6">
						<h4> <i class="fas fa-map-marked-alt"></i> Mappa </h4>
						<!--div id="map_dettaglio" style="width: 100%; padding-top: 100%;"></div-->
						
						<!--div style="width: 100%; padding-top: 100%;"-->
							<iframe class="embed-responsive-item" style="width:100%; padding-top:0%; height:600px;" src="./mappa_leaflet.php#16/<?php echo $lat;?>/<?php echo $lon;?>"></iframe>
						<!--/div-->
						<hr>
						
						</div>
			
					


            </div>
            <!-- /.row -->
    </div>
    <!-- /#wrapper -->

<?php 

require('./footer.php');

require('./req_bottom.php');


?>


<script type="text/javascript">
						
		var lat=<?php echo $lat;?>;
		var lon=<?php echo $lon;?>;
		var mymap = L.map('map_dettaglio', {scrollWheelZoom:false}).setView([lat, lon], 16);
	
		L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
			maxZoom: 18,
			attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
				'<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
				'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
			id: 'mapbox.streets'
		}).addTo(mymap);
	
		L.marker([lat, lon]).addTo(mymap)
    		.bindPopup('Segnalazione n. <?php echo $id;?>');
    		//.openPopup();
	
	
		
		var segn_non_lav = [
        
        <?php 
        $query_g="SELECT id, ST_AsGeoJson(geom) as geo, rischio, criticita, descrizione, note FROM segnalazioni.v_segnalazioni WHERE lavorazione=0 and st_distance(st_transform('<?php echo $geom_s;?>'::geometry(point,4326),3003),st_transform(geom,3003))< 200 and id_evento=<?php echo $id_evento;?;";


			// GeoJson Postgis: {"type":"Point","coordinates":[8.90092674245687,44.4828501691802]}
			

    		$i=0;
			$result_g = pg_query($conn, $query_g);
	      while($r_g = pg_fetch_assoc($result_g)) {
				if ($i==0){ 
					echo '{"type": "Feature","properties": {"id":'.$r_g["id"].', "rischio": "';
					echo $r_g["rischio"].'", "criticita": "'.$r_g["criticita"].'", "descrizione": "'.str_replace('"',' ',$r_g["descrizione"]).'"},"geometry":';
					echo $r_g["geo"].'}';
				} else {
					//echo ",". $r_g["geo"];
					echo ',{"type": "Feature","properties": {"id":'.$r_g["id"].', "rischio": "';
					echo $r_g["rischio"].'", "criticita": "'.$r_g["criticita"].'", "descrizione": "'.str_replace('"',' ',$r_g["descrizione"]).'"},"geometry":';
					echo $r_g["geo"].'}';
					
				}
				$i=$i+1;
			}
			?>
			];
			
			
			
			
			
			
			var stile_non_lavorazione = {
		    radius: 8,
		    fillColor: "#FFD700",
		    color: "#000",
		    weight: 1,
		    opacity: 1,
		    fillOpacity: 0.8
		};
		
		var stile_lavorazione = {
		    radius: 8,
		    fillColor: "#228B22",
		    color: "#000",
		    weight: 1,
		    opacity: 1,
		    fillOpacity: 0.8
		};
		/*var layer_v_segnalazioni_0 = new L.geoJson(geojsonFeature, {
		    pointToLayer: function (feature, latlng) {
		        return L.circleMarker(latlng, geojsonMarkerOptions);
		    }
		}).addTo(map);*/
        
        
        //var markers0 = L.markerClusterGroup();
        var markers1 = L.markerClusterGroup();   
		  
		  var layer_v_segnalazioni_0 = L.geoJson(segn_non_lav, {
		    pointToLayer: function (feature, latlng) {
		        return L.circleMarker(latlng, stile_non_lavorazione);
		    }
		    ,
			onEachFeature: function (feature, layer) {
				layer.bindPopup('<div align="right" style="color:grey"><i class="fas fa-pause-circle"></i> Da prendere in carico </div>'+
				'<h4><b>Tipo</b>: '+
				feature.properties.criticita+'</h4>'+
				'<a class="btn btn-primary active" role="button" target="_new" href="./dettagli_segnalazione.php?id='+
				feature.properties.id +
				'"> Dettagli segnalazione </a>' );
			}
		});
		
	   mymap.addLayer(layer_v_segnalazioni_0);
</script>


<script type="text/javascript" >

$('input[type=radio][name=invio]').attr('disabled', true);

(function ($) {
    'use strict';
    
    
    $('[type="radio"][name="risolta"][value="f"]').on('change', function () {
        if ($(this).is(':checked')) {
            $('input[type=radio][name=invio]').removeAttr('disabled');
            return true;
        }
    });
    
	$('[type="checkbox"][id="cat"]').on('change', function () {
        if ($(this).is(':checked')) {
            $('#conferma_chiudi').removeAttr('disabled');
            return true;
        }
        
    });
}(jQuery));





$(document).ready(function() {
    $('#js-date').datepicker({
        format: "yyyy-mm-dd",
        clearBtn: true,
        autoclose: true,
        todayHighlight: true
    });
});


</script>  

</body>

</html>