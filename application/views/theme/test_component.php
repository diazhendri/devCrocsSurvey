<style>
#container {
    height: 300px; 
    min-width: 410px; 
    max-width: 800px; 
    margin: 0 auto; 
}
.loading {
    margin-top: 10em;
    text-align: center;
    color: gray;
}
</style>
<div class="panel panel-default">
	<div class="panel-heading" style="padding-top:0px;padding-bottom:0px;">
		<div class="s13">
			<div class="s13_fr s13_content"><h4 class="great">DASHBOARD</h4></div>
			<div class="s13_fl s13_ribbon"></div>
			<div class="s13_fr s13_ribbon"></div>
		</div>
	</div>
	<div class="panel-body" >
	<?php 
		$tot_nom_saldo = 0;
		$tot_nom_real = 0;
		$total_jml_penerima = 0;
		$total_jml_realisasi = 0;
		foreach($data_bantuan->result() as $res)
		{
			$tot_nom_saldo = $res->tot_nom_saldo;
			$tot_nom_real = $res->tot_nom_real;
			$total_jml_penerima = $res->total_jml_penerima;
			$total_jml_realisasi = $res->total_jml_realisasi;		
			$prosentase_penyerapan_nom = ($tot_nom_real/$tot_nom_saldo) * 100;
			$prosentase_penyerapan_jiwa = ($total_jml_realisasi/$total_jml_penerima) * 100;
	?>
	<div class="col-sm-12" style="border-style: solid;	border-color: #d9d9d9 ; margin:15px 0 0 0;">
		<div class="col-sm-4" >
			<div id="container_nom_<?=$res->kode_bantuan?>" style="width: 400px; height: 300px;"></div>			
		</div>		
		<div class="col-sm-2">
			<div class="form-group form-group-sm">
				<label for="nik" style="vertical-align:top;">PENYERAPAN 
				: <b>Rp <?=number_format($tot_nom_real,0,",",".")?> </b> </label>
			</div>
			<div class="form-group form-group-sm">
				<label for="nik" style="vertical-align:top;">PENYALURAN 			
				: <b>Rp <?=number_format($tot_nom_saldo,0,",",".")?> </b> </label>	
			</div>
			<div class="form-group form-group-sm">
				<label for="nik" style="vertical-align:top;">PROSENTASE PENYERAPAN 			
				: <b> <?=number_format($prosentase_penyerapan_nom,2,",",".")?> %</b> </label>	
			</div>
		</div>	
		<div class="col-sm-4" style="border-left:solid #d9d9d9;">
			<div id="container_jml_<?=$res->kode_bantuan?>" style="width: 400px; height: 300px; float: center"></div>
		</div>
		<div class="col-sm-2">
			<div class="form-group form-group-sm">
				<label for="nik" style="vertical-align:top;">PENYERAPAN 
				: <b> <?=$total_jml_realisasi?> Orang</b> </label>
			</div>
			<div class="form-group form-group-sm">
				<label for="nik" style="vertical-align:top;">PENYALURAN 			
				: <b> <?=$total_jml_penerima?> Orang</b> </label>	
			</div>
			<div class="form-group form-group-sm">
				<label for="nik" style="vertical-align:top;">PROSENTASE PENYERAPAN 			
				: <b> <?=number_format($prosentase_penyerapan_jiwa,2,",",".")?> %</b> </label>	
			</div>
		</div>		
	</div>
	<?php }?>
<script language="JavaScript">
$(document).ready(function() {  
$(function () {
	var chart = {      
		type: 'gauge',
		plotBackgroundColor: null,
		plotBackgroundImage: null,
		plotBorderWidth: 0,
		plotShadow: false
   };
    

   var pane = {
      startAngle: -90,
			endAngle: 90,
			size: '150%',
			center: ['50%', '90%'],
            background: [{
                backgroundColor: {
                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                    stops: [
                        [0, '#FFF'],
                        [1, '#333']
                    ]
                },
                borderWidth: 0,
                outerRadius: '109%'
            }, {
                backgroundColor: {
                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                    stops: [
                        [0, '#333'],
                        [1, '#FFF']
                    ]
                },
                borderWidth: 1,
                outerRadius: '107%'
            }, {
                // default background
            }, {
                backgroundColor: '#DDD',
                borderWidth: 0,
                outerRadius: '105%',
                innerRadius: '103%'
            }]
   };

   // the value axis
   var yAxis = {
       min: 0,
            max: 100,

            minorTickInterval: 'auto',
            minorTickWidth: 1,
            minorTickLength: 10,
            minorTickPosition: 'inside',
            minorTickColor: '#666',

            tickPixelInterval: 30,
            tickWidth: 2,
            tickPosition: 'inside',
            tickLength: 10,
            tickColor: '#666',
            labels: {
                step: 2,
                rotation: 'auto'
            },
            title: {
                text: '-'
            },
            plotBands: [{
                from: 0,
                to: 30,
                color: '#DF5353' // green
            }, {
                from: 30,
                to: 60,
                color: '#DDDF0D' // yellow
            }, {
                from: 60,
                to: 100,
                color: '#55BF3B' // red
            }]
   };

  
  <?php 
	foreach($data_bantuan->result() as $res)
	{
	$prosentase_penyerapan_nom = ($res->tot_nom_real/$res->tot_nom_saldo) * 100;
	$prosentase_penyerapan_jiwa = ($res->total_jml_realisasi/$res->total_jml_penerima) * 100;
	?>
		var series_rastra_nom_<?= $res->kode_bantuan?>= [{
		name: 'Speed',
		data: [<?=number_format($prosentase_penyerapan_nom,2,".",".")?>],
		tooltip: {
			valueSuffix: ' Rupiah'
		}
	    }];     
		  
	    var series2_<?= $res->kode_bantuan?>= [{
			name: 'Speed',
			data: [<?=number_format($prosentase_penyerapan_jiwa,2,".",".")?>],
			tooltip: {
				valueSuffix: ' Keluarga'
			}
	    }];  
		var title_bantuan1_<?= $res->kode_bantuan?> = {
		  text: 'Program <?= $res->nama_bantuan?> (Nominal)'   
	    };
		var title_bantuan2_<?= $res->kode_bantuan?> = {
		  text: 'Program <?= $res->nama_bantuan?>'   
	    }; 		
		var json_nominal_<?= $res->kode_bantuan?> = {};   
		json_nominal_<?= $res->kode_bantuan?>.chart = chart; 
		json_nominal_<?= $res->kode_bantuan?>.title = title_bantuan1_<?= $res->kode_bantuan?>;       
		json_nominal_<?= $res->kode_bantuan?>.pane = pane; 
		json_nominal_<?= $res->kode_bantuan?>.yAxis = yAxis; 
		json_nominal_<?= $res->kode_bantuan?>.series = series_rastra_nom_<?= $res->kode_bantuan?>;     

		var json_jumlah_<?= $res->kode_bantuan?> = {};   
		json_jumlah_<?= $res->kode_bantuan?>.chart = chart; 
		json_jumlah_<?= $res->kode_bantuan?>.title = title_bantuan2_<?= $res->kode_bantuan?>;       
		json_jumlah_<?= $res->kode_bantuan?>.pane = pane; 
		json_jumlah_<?= $res->kode_bantuan?>.yAxis = yAxis; 
		json_jumlah_<?= $res->kode_bantuan?>.series = series2_<?= $res->kode_bantuan?>;  

		$('#container_nom_<?= $res->kode_bantuan?>').highcharts(json_nominal_<?= $res->kode_bantuan?>);
		$('#container_jml_<?= $res->kode_bantuan?>').highcharts(json_jumlah_<?= $res->kode_bantuan?>);
    <?php 
	}?>
});


});
</script>
	</div>
</div>
		