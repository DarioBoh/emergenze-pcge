<?php 

$subtitle="Form inserimento nuova segnalazione"

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

                <!--div class="col-lg-12">
                    <h1 class="page-header">Titolo pagina</h1>
                </div-->
                <!-- /.col-lg-12 -->
			<form action="segnalazioni/add_segnalazioni2.php" method="POST">
        

                    <div class="row">
       
            
            
            
            <h4><i class="fa fa-address-card"></i> Generalità segnalante:</h4> 
            <div class="form-group">
                <label for="nome"> Identificativo segnalante</label> <font color="red">*</font>
                <input type="text" name="nome" class="form-control" required>
              </div>
            <hr>
            <h4><i class="fa fa-tasks"></i> Oggetto della segnalazione:</h4> 
            
             <div class="form-group col-md-6">
              <label for="naz">Tipo criticità:</label> <font color="red">*</font>
                            <select class="form-control" name="crit" id="crit">
                            <option name="crit" value="" > ... </option>
            <?php            
            $query2="SELECT * FROM segnalazioni.tipo_criticita WHERE valido='t' ORDER BY descrizione;";
            echo $query2;
	        $result2 = pg_query($conn, $query2);
            //echo $query1;    
            while($r2 = pg_fetch_assoc($result2)) { 
            ?>    
                    <option name="crit" value="<?php echo $r2['id'];?>" ><?php echo $r2['descrizione'];?></option>
             <?php } ?>

             </select>            
             </div>
             
             
             
      
             
             
             
            <div class="form-group col-md-6">
                <label for="nome"> Descrizione</label> <font color="red">*</font>
                <input type="text" name="descrizione" class="form-control" required>
              </div>

				</div> 
            <div class="row">       

<div class="form-group col-md-6">
					<label for="nome"> Ci sono persone a rischio?</label> <font color="red">*</font><br>
					<label class="radio-inline"><input type="radio" name="optradio" value="" checked>Non specificato</label>
					<label class="radio-inline"><input type="radio" name="optradio" value="t">Sì</label>
					<label class="radio-inline"><input type="radio" name="optradio"value="f">Nessuna persona a rischio</label>
				</div>


<div class="form-group col-md-6">
            <label for="nome"> Evento</label> <font color="red">*</font>  
 				<?php 
           $len=count($eventi_attivi);	               
				                
				if($len==1) {   
			   ?>


                <select disabled="" class="form-control"  name="evento" required>
                 
                    <?php 
                     for ($i=0;$i<$len;$i++){
                      
                        echo '<option value='.$tipo_eventi_attivi[$i][0].'>'. $tipo_eventi_attivi[0][1].' (id='.$tipo_eventi_attivi[0][0].')</option>';
                      }
                    ?>
                  </select>
                                  <small id="emailHelp" class="form-text text-muted">Un solo evento attivo (per trasparenza lo mostriamo ma possiamo anche decidere di non farlo).</small>
             
            <?php } else {
            	?>

                  <select class="form-control"  name="evento" required>
                 
                    <?php 
                     for ($i=0;$i<$len;$i++){
                      
                        echo '<option value='.$tipo_eventi_attivi[$i][0].'>'. $tipo_eventi_attivi[0][1].' (id='.$tipo_eventi_attivi[0][0].')</option>';
                      }
                    ?>
                  </select>

            	<?php
            	}
            	?>
              
            </div>

				
				
				</div> 
            <div class="row">

 				<hr>
            <h4><i class="fa fa-map-marker-alt"></i> Geolocalizzazione:</h4> 


				<div class="form-group">
					<label for="nome"> Ci sono persone a rischio?</label> <font color="red">*</font><br>
					<label class="radio-inline"><input type="radio" name="georef" id="civico">Tramite civico</label>
					<label class="radio-inline"><input type="radio" name="georef" id="mappa">Tramite mappa</label>
					<label class="radio-inline"><input type="radio" name="georef" id="coord">Con coordinate note</label>
				</div>


				</div> 
            <div class="row">
            
            
            <script>
            function getCivico(val) {
	            $.ajax({
	            type: "POST",
	            url: "get_civico.php",
	            data:'cod='+val,
	            success: function(data){
		            $("#comune-list").html(data);
	            }
	            });
            }

            </script>



				<div class="col-md-6"> 
             <div class="form-group  ">
              <label for="provincia">Provincia:</label> <font color="red">*</font>
                            <select disabled="" id="provincia-list" class="selectpicker show-tick form-control" data-live-search="true" onChange="getCivico(this.value);" required>
                            <option value="">Seleziona la via</option>
            <?php            
            $query2="SELECT * From \"geodb\".\"vie\";";
	        $result2 = pg_query($conn, $query2);
            //echo $query1;    
            while($r2 = pg_fetch_assoc($result2)) { 
                $valore=  $r2['codvia']. ";".$r2['desvia'];            
            ?>
                        
                    <option name="cod" value="<?php echo $r2['codvia'];?>" ><?php echo $r2['desvia'];?></option>
             <?php } ?>

             </select>            
             </div>


            <div class="form-group">
              <label for="comune">Comune:</label> <font color="red">*</font>
                <select disabled="" class="form-control" name="comune" id="comune-list" class="demoInputBox" required>
                <option value="">Seleziona il civico</option>
            </select>         
             </div>


				</div>
				<div class="col-md-6"> 
				
	

				
					<div class="form-group">
                <label for="nome"> Latitudine </label> <font color="red">*</font>
                <input disabled="" type="text" name="lat" id="lat" class="form-control" required>
                <small id="addrHelp" class="form-text text-muted"> Qua è possibile specificare altre annotazioni, </small> 
              </div>
					
					<div class="form-group">
                <label for="nome"> Longitudine </label> <font color="red">*</font>
                <input disabled="" type="text" name="lon" id="lon" class="form-control" required>
                <small id="addrHelp" class="form-text text-muted"> Qua è possibile specificare altre annotazioni, </small> 
              </div>
					
				</div>
				
				</div> 
            <div class="row">
								<div id="mapid" style="width: 100%; height: 600px;"></div>
            </div> 
            <div class="row">

					<hr>
               <h4><i class="fa fa-plus"></i> Altro:</h4>      
                    
                     
              <div class="form-group">
                <label for="nome"> Note</label> <font color="red">*</font>
                <input type="text" name="note" class="form-control" required>
                <small id="addrHelp" class="form-text text-muted"> Qua è possibile specificare altre annotazioni, </small> 
              </div>

                             



            <button disabled="" type="submit" class="btn btn-primary">Aggiungi</button> (Form DEMO- discutere di gestione DB)
            </div>
            <!-- /.row -->
            

            </form>                
                
                
                
                
                

            <br><br>
            <div class="row">

            </div>
            <!-- /.row -->
    </div>
    <!-- /#wrapper -->

<?php 

require('./footer.php');

require('./req_bottom.php');


?>


<script>

var mymap = L.map('mapid').setView([44.411156, 8.932661], 13);

	L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
		maxZoom: 18,
		attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
			'<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
			'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
		id: 'mapbox.streets'
	}).addTo(mymap);






	var popup = L.popup();

	function onMapClick(e) {
		    document.getElementById('lat').value = e.latlng.lat.toString();
			 document.getElementById('lon').value = e.latlng.lng.toString();
		
		/*popup
			.setLatLng(e.latlng)
			.setContent("Le coordinate di questo punto sulla mappa sono le seguenti lat:" + e.latlng.lat.toString() +" e lon:"+ e.latlng.lng.toString() +" e sono state automaticamente inserite nel form")
			.openOn(mymap);*/
			
			popup
			.setLatLng(e.latlng)
			.setContent("Le coordinate di questo punto sulla mappa sono state automaticamente inserite nel form sottostante")
			.openOn(mymap);
	}




(function ($) {
    'use strict';
    
    $('[type="radio"][id="civico"]').on('change', function () {
        if ($(this).is(':checked')) {
            $('#provincia-list').removeAttr('disabled');
            $('#comune-list').removeAttr('disabled');
            $('#lat').attr('disabled', true);
            $('#lon').attr('disabled', true);
            $('#lat').val('');
            $('#lon').val('');
            $("#mapid").off("onclick");
            	mymap.off('click', onMapClick);
            return true;
        }
        $('#catName').attr('disabled', 'disabled');
    });
      $('[type="radio"][id="coord"]').on('change', function () {
        if ($(this).is(':checked')) {
            $('#lat').removeAttr('disabled');
            $('#lon').removeAttr('disabled');
            $('#provincia-list').val('');
            $('#comune-list').val('');
            $('#lat').val('');
            $('#lon').val('');
            $('#provincia-list').attr('disabled', true);
            $('#comune-list').attr('disabled', true);
            mymap.off('click', onMapClick);
            return true;
        }
        $('#catName').attr('disabled', 'disabled');
    });  
    
    $('[type="radio"][id="mappa"]').on('change', function () {
        if ($(this).is(':checked')) {
            $('#lat').attr('disabled', true);
            $('#lon').attr('disabled', true);
             $('#lat').val('');
            $('#lon').val('');
            $('#provincia-list').val('');
            $('#comune-list').val('');
            $('#provincia-list').attr('disabled', true);
            $('#comune-list').attr('disabled', true);
            var offset = -200; //Offset of 100px
            	mymap.on('click', onMapClick);

    $('html, body').animate({
        scrollTop: $("#mapid").offset().top + offset
    }, 2000);
            
            return true;
        }
        $('#catName').attr('disabled', 'disabled');
    });  
        /*$('[type="radio"][id="mappa"]').on('change', function () {
        if ($(this).is(':checked')) {
        	
        	
        	$('#my-modal').modal({
        show: 'false'
    }
            
        }
    });*/
    
    
    
}(jQuery));

    

</script> 




<script>




</script>
    

</body>

</html>
