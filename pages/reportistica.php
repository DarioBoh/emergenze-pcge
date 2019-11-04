<?php 

$subtitle="Riepilogo evento";

$id=$_GET['id'];


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
//require('./tables/griglia_dipendenti_save.php');
require('./req.php');
require('/home/local/COMGE/egter01/emergenze-pcge_credenziali/conn.php');
//require('./conn.php');

require('./check_evento.php');


?>


<style type="text/css">
            
            .panel-allerta {
				  border-color: <?php echo $color_allerta; ?>;
				}
				.panel-allerta > .panel-heading {
				  border-color: <?php echo $color_allerta; ?>;
				  color: white;
				  background-color: <?php echo $color_allerta; ?>;
				}
				.panel-allerta > a {
				  color: <?php echo $color_allerta; ?>;
				}
				.panel-allerta > a:hover {
				  color: #337ab7;
				  /* <?php echo $color_allerta; ?>;*/
				}
            
            @media print
		   {
			  p.bodyText {font-family:georgia, times, serif;}
			  
			  .rows-print-as-pages .row {
				page-break-before: auto;
			  }
			  
			  
			   table,
				table tr td,
				table tr th {
					page-break-inside: avoid;
				}
			  .noprint
			  {
				display:none
			  }
			  
		   }
            
            
            </style>

    
</head>

<body>

    <div id="wrapper">

        <?php 
            require('./navbar_up.php')
        ?>  
        <?php 
            require('./navbar_left.php');
            
         

        ?> 
            

        <div id="page-wrapper">
            <div class="row">
                <!--div class="col-sm-12">
                    <h1 class="page-header">Dashboard</h1>
                </div-->
                <!-- /.col-sm-12 -->
            </div>
            <!-- /.row -->
            
            
            <?php //echo $note_debug; ?>
           

            
            <div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<h3>Evento n. <?php echo $id; ?> - Tipo: 
			<?php
			$query_e='SELECT e.id, tt.descrizione 
            FROM eventi.t_eventi e
            JOIN eventi.join_tipo_evento t ON t.id_evento=e.id
            JOIN eventi.tipo_evento tt on tt.id=t.id_tipo_evento
			 	WHERE e.id =' .$id.';';
				$result_e = pg_query($conn, $query_e);
				while($r_e = pg_fetch_assoc($result_e)) {
					echo $r_e['descrizione'];
				}
			?>
			<button class="btn btn-info noprint" onclick="printDiv('page-wrapper')"><i class="fa fa-print" aria-hidden="true"></i> Stampa pagina report (demo)</button>
			</h3>
			</div>
			</div>
			<hr>
			<div class="row">
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<?php if( $descrizione_allerta!= 'Nessuna allerta') {?>
					<h4> Allerta <?php echo $descrizione_allerta; ?> in corso 
					<em><i class="fas fa-circle fa-1x" style="color:<?php echo $color_allerta; ?>"></i></em>
					</h4>
				 <?php } else { ?>
					<h4> Nessuna allerta in corso <em><i class="fas fa-circle fa-1x" style="color:<?php echo $color_allerta; ?>"></i></em>
					</h4>
				 <?php }  ?> 
			</div>	
			
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<?php if( $descrizione_allerta!= 'Nessuna allerta') {?>
					<h4> Fase Operativa Comunale di <?php echo $descrizione_foc; ?> in corso 
					<em><i class="fas fa-circle fa-1x" style="color:<?php echo $color_foc; ?>"></i></em>
					</h4>
				 <?php } else { ?>
					<h4> Nessuna Fase Operativa Comunale in corso <em><i class="fas fa-circle fa-1x" style="color:<?php echo $color_foc; ?>"></i></em>
					</h4>
				 <?php }  ?> 
			</div>
			<hr>
			</div>
			
			<div class="row">
			 
			 <?php require('./monitoraggio_meteo_embed.php'); ?>
            
			</div>
			
			<hr>
			<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h3>Comunicazioni generiche</h3>
				<button type="button" class="btn btn-info"  data-toggle="modal" data-target="#comunicazione">
					   <i class="fas fa-plus"></i> Aggiungi comunicazione</button>
					   <ul>
	   					<?php
						$query='SELECT id, to_char(data_aggiornamento, \'DD/MM/YY HH24:MI\'::text) AS data_aggiornamento, testo, allegato FROM report.t_comunicazione 
						WHERE id_evento = '.$id.';';
						//echo $query;
						$result = pg_query($conn, $query);
						$c=0;
						while($r = pg_fetch_assoc($result)) {
							if ($c==0){
								echo "<h3>Elenco comunicazioni generiche</h3>";
							}
							$c=$c+1;
							//echo '<button type="button" class="btn btn-info noprint"  data-toggle="modal" 
							//data-target="#update_mon_'.$r['id'].'">
							//<i class="fas fa-edit"></i> Edit </button>';
							echo " <li><b>Comunicazione del ".$r['data_aggiornamento']."</b>: ";
							echo $r['testo'];
							if ($r['allegato']!=''){
								echo " (<a href=\"../../".$r['allegato']."\">Allegato</a>)";
							}
							echo "</li>";
						}
						echo "</ul><hr>";
						?>
						<!-- Modal comunicazione da UO-->
						<div id="comunicazione" class="modal fade" role="dialog">
						  <div class="modal-dialog">

							<!-- Modal content-->
							<div class="modal-content">
							  <div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">Comunicazioni sull'evento / Verbale COC</h4>
							  </div>
							  <div class="modal-body">
							  

								<form autocomplete="off"  enctype="multipart/form-data"  action="eventi/comunicazione.php?id=<?php echo $id; ?>" method="POST">
										 <div class="form-group">
										<label for="note">Testo comunicazione <?php echo $id_evento;?></label>  <font color="red">*</font>
										<textarea required="" class="form-control" id="note"  name="note" rows="3"></textarea>
									  </div>
									
									<!--	RICORDA	  enctype="multipart/form-data" nella definizione del form    -->
									<div class="form-group">
									   <label for="note">Eventuale allegato (es. verbale COC)</label>
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
			</div>
			</div>
			
			<div class="row">
			
            <?php require('./attivita_sala_emergenze_embed.php'); ?>
			
			</div>
			
			<hr>
            <div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h3>Comunicazioni e informazioni alla popolazione</h3>
			</div>
			</div>
			<div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h4>Attivazione numero verde: <?php echo $descrizione_nverde; ?></h4>
            </div>
            
            </div>
			<div class="row">
            
			<?php require('./operatore_nverde_embed.php'); ?>
            
           
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <hr>
            <h4>Numero chiamate ricevute</h4>
            
            <?php 
            /*$query_e="SELECT e.id, tt.descrizione 
            FROM eventi.t_eventi e
            JOIN eventi.join_tipo_evento t ON t.id_evento=e.id
            JOIN eventi.tipo_evento tt on tt.id=t.id_tipo_evento
			 	WHERE e.valido != 'f'
			   GROUP BY e.id, tt.descrizione;";
             
            $result_e = pg_query($conn, $query_e);
				//echo "<ul>";
				while($r_e = pg_fetch_assoc($result_e)) {*/
					//echo '<b>Tipo Evento</b>:'.$r_e['descrizione']. '<br>';
					$query="SELECT count(r.id)
					FROM segnalazioni.t_richieste_nverde r 
					WHERE r.id_evento = ".$id.";";
					//echo $query;
					$result = pg_query($conn, $query);
					while($r = pg_fetch_assoc($result)) {
						echo "<b>Richieste generiche:</b>".$r['count']."<br>";
					}
					$query="SELECT count(r.id)
					FROM segnalazioni.t_segnalazioni r 
					WHERE r.id_evento = ".$id." AND nverde='t';";
					//echo $query;
					$result = pg_query($conn, $query);
					while($r = pg_fetch_assoc($result)) {
						echo "<b>Segnalazioni n.verde:</b>".$r['count']."<br><br>";
					}
				/*}*/ 
            
            
            
            ?>
            
            
            </div>            
            </div>
            <!-- /.row -->            
            <hr>
            
            
            <?php 
             
            //require('./conteggi_dashboard.php');
            
            //require('./contatori.php');
            ?>
            
            <div class="row">
                
                
				<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <h4>Segnalazioni </h4>
<table  id="segnalazioni_count" class="table table-condensed" 
style="word-break:break-all; word-wrap:break-word;" data-toggle="table" 
data-url="./tables/griglia_segnalazioni_conteggi.php?id=<?php echo $id?>" 
data-show-export="false" data-search="false" data-click-to-select="false" 
data-pagination="false" data-sidePagination="false" data-show-refresh="true" 
data-show-toggle="false" data-show-columns="false" data-toolbar="#toolbar">

<thead>

<tr>
   <th data-field="criticita" data-sortable="false" data-visible="true" >Tipologia</th>
   <th data-field="count" data-sortable="true" data-visible="true">Pervenute</th>
   <th data-field="risolte" data-sortable="true" data-visible="true">Risolte</th>
</tr>
</thead>
</table>
                
                
                
                
                    <!--div id="panel-riepilogo" class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bell fa-fw"></i> Pannello riepilogo
                        </div>
                        
                        <div class="panel-body">
                            <div class="list-group">
                               
                                		<?php if($segn_limbo>0){?>
                                			 <a href="#segn_limbo_table" class="list-group-item">
	                                    <i class="fa fa-exclamation fa-fw" style="color:red"></i> Nuove segnalazioni da elaborare!
	                                    <span class="pull-right text-muted small"><em><?php echo $segn_limbo; ?></em>
	                                    </span>
	                                    </a>
                                    <?php }?>
                                
								
											<?php if($inc_limbo>0){?>
                                			 <div class="list-group-item" >
	                                    <i class="fa fa-exclamation fa-fw" style="color:red"></i> Nuovi incarichi ancora da prendere in carico!
	                                    <span class="pull-right text-muted small"><em><?php echo $inc_limbo; ?></em>
	                                    </span>
	                                    
	                                    </div>
                                    <?php }?>
								
								<div class="list-group-item" >
											
                                
                                    <i class="fa fa-users"></i> <b>Gestione squadre</b>
                                    <br><br>
                                     - <i class="fa fa-play"></i> Squadre in azione
                                    <span class="pull-right text-muted small"><em><?php echo $squadre_in_azione; ?></em>
                                    </span>
                                    
                                    <br>
                                     - <i class="fa fa-pause"></i> Squadre a disposizione
                                    <span class="pull-right text-muted small"><em><?php echo $squadre_disposizione; ?></em>
                                    </span>
                                    <br>
                                     - <i class="fa fa-stop"></i> Squadre a riposo
                                    <span class="pull-right text-muted small"><em><?php echo $squadre_riposo; ?></em>
                                    </span>
                                    <hr>
                                    Totale squadre eventi attivi:
                                    <span class="pull-right text-muted small"><em><?php echo $squadre_riposo; ?></em>
                                    </span>
                                </div>
                            
                            <a href="./gestione_squadre.php" class="btn btn-default btn-block">Vai alla gestione squadre</a>
							
							
							<div class="list-group-item" >
											
                                
                                    <i class="fa fa-pencil-ruler"></i> <b>Presidi</b>
                                    <br><br>
                                     - <i class="fa fa-pause"></i> Assegnati
                                    <span class="pull-right text-muted small"><em><?php echo $sopralluoghi_assegnati; ?></em>
                                    </span>
                                    
                                    <br>
                                     - <i class="fa fa-play"></i> In corso
                                    <span class="pull-right text-muted small"><em><?php echo $sopralluoghi_corso; ?></em>
                                    </span>
                                    <br>
                                     - <i class="fa fa-stop"></i> Conclusi
                                    <span class="pull-right text-muted small"><em><?php echo $sopralluoghi_conclusi; ?></em>
                                    </span>
                                    <hr>
                                    Totale presidi eventi attivi:
                                    <span class="pull-right text-muted small"><em><?php echo $sopralluoghi_tot; ?></em>
                                    </span>
                                </div>
                            
                            <a href="./nuovo_sopralluogo.php" class="btn btn-default btn-block">Crea un nuovo presidio</a>
							
							</div>
							
							<div class="list-group-item" >
											
                                
                                    <i class="fa fa-exclamation-triangle"></i> <b>Provvedimenti cautelari</b>
                                    <br><br>
                                     - <i class="fa fa-pause"></i> Assegnati
                                    <span class="pull-right text-muted small"><em><?php echo $pc_assegnati; ?></em>
                                    </span>
                                    
                                    <br>
                                     - <i class="fa fa-play"></i> In corso
                                    <span class="pull-right text-muted small"><em><?php echo $pc_corso; ?></em>
                                    </span>
                                    <br>
                                     - <i class="fa fa-stop"></i> Portati a termine
                                    <span class="pull-right text-muted small"><em><?php echo $pc_conclusi; ?></em>
                                    </span>
                                    <hr>
                                    Totale provvedimenti cautelari eventi attivi:
                                    <span class="pull-right text-muted small"><em><?php echo $pc_tot; ?></em>
                                    </span>
                                </div>
                            

                            <a href="./elenco_pc.php" class="btn btn-default btn-block">Elenco provvedimenti cautelari</a>
							
							</div>
                        
						
						
						
						

                    </div-->


                    
                    
                    
                    
                    
            </div> 
            
            
            
              
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <h4>Provvedimenti cautelari </h4>
<table  id="pc_count" class="table table-condensed" 
style="word-break:break-all; word-wrap:break-word;" data-toggle="table" 
data-url="./tables/griglia_pc_report.php?id=<?php echo $id?>" 
data-show-export="false" data-search="false" data-click-to-select="false" 
data-pagination="false" data-sidePagination="false" data-show-refresh="true" 
data-show-toggle="false" data-show-columns="false" data-toolbar="#toolbar">

<thead>

<tr>
   <th data-field="tipo_provvedimento" data-sortable="false" data-visible="true" >Tipologia</th>
   <th data-field="descrizione_stato" data-sortable="true" data-visible="true">Stato</th>
   <th data-field="count" data-sortable="true" data-visible="true">Totale</th>
</tr>
</thead>
</table>
               
               <?php
               $query="SELECT sum(residenti) from segnalazioni.v_residenti_allontanati 
               where id_evento=".$id.";";
               $result = pg_query($conn, $query);
					while($r = pg_fetch_assoc($result)) {
						echo "<br><br><b>Residenti allontanati in questo momento::</b>".$r['sum']."<br><br>";
					}
                
                
				?>
            </div>
                <!-- /.col-sm-4 -->
            </div>
            <!-- /.row -->
            <div class="row">
                
                
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php
$date = date_create(date(), timezone_open('Europe/Berlin'));
$data = date_format($date, 'd-m-Y');
$ora = date_format($date, 'H:i');
//$data = date("d-m-Y");
//$ora = date("H:i:s");
	echo "<hr><div align='center'>Il presente report è stato ottenuto in maniera automatica utilizzando il Sistema 
	di Gestione delle Emergenze in data ".$data ." alle ore " .$ora.". 
	</div>";

?>
             </div>
            </div> <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

<?php 

require('./footer.php');

require('./req_bottom.php');


?>

<script>

	/*var mymap = L.map('mapid').setView([44.411156, 8.932661], 12);

	L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
		maxZoom: 18,
		attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
			'<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
			'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
		id: 'mapbox.streets'
	}).addTo(mymap);

	L.marker([44.411156, 8.932661]).addTo(mymap)
		.bindPopup("<b>Hello world!</b><br />I am a leafletJS popup.").openPopup();




	var popup = L.popup();

	function onMapClick(e) {
		popup
			.setLatLng(e.latlng)
			.setContent("You clicked the map at " + e.latlng.toString())
			.openOn(mymap);
	}

	mymap.on('click', onMapClick);*/



  
$(document).ready(function() {
    $('#js-date').datepicker({
        format: "yyyy-mm-dd",
        clearBtn: true,
        autoclose: true,
        todayHighlight: true
    });
    $('#js-date2').datepicker({
        format: "yyyy-mm-dd",
        clearBtn: true,
        autoclose: true,
        todayHighlight: true
    });
      $('#js-date3').datepicker({
        format: "yyyy-mm-dd",
        clearBtn: true,
        autoclose: true,
        todayHighlight: true
    });
    $('#js-date4').datepicker({
        format: "yyyy-mm-dd",
        clearBtn: true,
        autoclose: true,
        todayHighlight: true
    });  
     $('#js-date5').datepicker({
        format: "yyyy-mm-dd",
        clearBtn: true,
        autoclose: true,
        todayHighlight: true
    });
    $('#js-date6').datepicker({
        format: "yyyy-mm-dd",
        clearBtn: true,
        autoclose: true,
        todayHighlight: true
    });
      $('#js-date7').datepicker({
        format: "yyyy-mm-dd",
        clearBtn: true,
        autoclose: true,
        todayHighlight: true
    });
    $('#js-date8').datepicker({
        format: "yyyy-mm-dd",
        clearBtn: true,
        autoclose: true,
        todayHighlight: true
    });  
    $('#js-date9').datepicker({
        format: "yyyy-mm-dd",
        clearBtn: true,
        autoclose: true,
        todayHighlight: true
    });
    $('#js-date10').datepicker({
        format: "yyyy-mm-dd",
        clearBtn: true,
        autoclose: true,
        todayHighlight: true
    });  
    
    
    $('#js-date12').datepicker({
        format: "yyyy-mm-dd",
        clearBtn: true,
        autoclose: true,
        todayHighlight: true
    });
    $('#js-date13').datepicker({
        format: "yyyy-mm-dd",
        clearBtn: true,
        autoclose: true,
        todayHighlight: true
    });
    
    
    $('#js-date100').datepicker({
        format: "yyyy-mm-dd",
        clearBtn: true,
        autoclose: true,
        todayHighlight: true
    }); 
});




function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}



</script>
    

</body>

</html>
