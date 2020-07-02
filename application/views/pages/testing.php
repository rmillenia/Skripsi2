<!DOCTYPE html>
<html>
<?php $this->load->view('elements/header') ?>
<body>
	<div class="wrapper sidebar_minimize">
		<?php $this->load->view('elements/header2') ?>
		<?php $this->load->view('elements/sidebar') ?>

		<div class="main-panel">
			<div class="content">
				<div class="page-inner">
					<div class="page-header">
						<h4 class="page-title">Testing</h4>
						<ul class="breadcrumbs">
							<li class="nav-home">
								<a href="#">
									<i class="fas fa-layer-group"></i>
								</a>
							</li>
							<li class="separator">
								<i class="flaticon-right-arrow"></i>
							</li>
							<li class="nav-item">
								<a href="#">Testing</a>
							</li>
							<li class="separator">
								<i class="flaticon-right-arrow"></i>
							</li>
							<li class="nav-item">
								<a href="#">View All </a>
							</li>
						</ul>
					</div>
					<div class="row">
						<div class="col-md-8">
							<div class="card">
								<canvas id="multipleLineChart" width="1100px"></canvas>
							</div>
						</div>
						<div class="col-md-4">
							<div class="card card-stats card-round">
								<div class="card-body ">
									<div class="row">
										<div class="col-7">
											<canvas id="precisionDoughnut"  width="189px"></canvas>
										</div>
										<div class="col-5 col-stats">
											<div class="numbers">
												<p class="card-category">Precision</p>
												<h4 class="card-title" id="precisionText"></h4>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card card-stats card-round">
								<div class="card-body ">
									<div class="row">
										<div class="col-7">
											<canvas id="recallDoughnut" width="189px"></canvas>
										</div>
										<div class="col-5 col-stats">
											<div class="numbers">
												<p class="card-category">Recall</p>
												<h4 class="card-title" id="recallText"></h4>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card card-stats card-round">
								<div class="card-body ">
									<div class="row">
										<div class="col-7">
											<canvas id="fmeasureDoughnut" width="189px"></canvas>
										</div>
										<div class="col-5 col-stats">
											<div class="numbers">
												<p class="card-category" >F-measure</p>
												<h4 class="card-title" id="fmeasureText"></h4>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card card-stats card-round">
								<div class="card-body ">
									<div class="row">
										<div class="col-7">
											<canvas id="accuracyDoughnut" width="189px"></canvas>
										</div>
										<div class="col-5 col-stats">
											<div class="numbers">
												<p class="card-category" >Accuracy</p>
												<h4 class="card-title" id="accuracyText"></h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<div class="d-flex align-items-center">
										<h4 class="card-title">Testing List</h4>
										<button class="btn btn-icon btn-round btn-primary ml-auto" data-toggle="modal" data-target="#addRowModal">
											<i class="fa fa-plus"></i>
										</button>
									</div>
								</div>
								<div class="card-body">
									<div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header no-bd">
													<h5 class="modal-title">
														<span class="fw-mediumbold">
														Add New</span> 
														<span class="fw-light">
															Document
														</span>
													</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<form method="post" id="addTesting" action="<?= base_url('Testing/addTesting'); ?>" enctype="multipart/form-data">
													<div class="modal-body">
														<p class="small">Add document for testing</p>
														<div class="row">
															<div class="col-sm-12">
																<div class="form-group form-group-default">
																	<label>Choose Documents</label>
																	<select name="document" id="document" class="form-control" style="padding: .3rem 1rem !important">
																		<?php foreach ($this->db->get('documents')->result() as $key => $value): ?>
																			<option value="<?= $value->id;?>"><?= $value->no_perkara ?></option>
																		<?php endforeach?>
																	</select>
																</div>
																<div class="form-group form-group-default">
																	<label>Upload Summary (Manual)</label>
																	<input name="file" id="file" type="file">
																</div>
															</div>
														</div>
													</div>
													<div class="modal-footer no-bd">
														<input type="submit" class="btn btn-primary float-right" name="submit" value="Add">
														<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
													</div>
												</form>
											</div>
										</div>
									</div>

									<div class="table-responsive">
										<table id="get-testing" class="display table table-bordered table-hover" width="100%" style="width:100%" cellspacing="0">
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<?php $this->load->view('elements/footer') ?>


<script type="text/javascript">

	var table_testing;
	var multipleLineChart = document.getElementById('multipleLineChart').getContext('2d');
	var precisionDoughnut = document.getElementById('precisionDoughnut').getContext('2d');
	var recallDoughnut = document.getElementById('recallDoughnut').getContext('2d');
	var fmeasureDoughnut = document.getElementById('fmeasureDoughnut').getContext('2d');
	var accuracyDoughnut = document.getElementById('accuracyDoughnut').getContext('2d');

	var myPrecisionDoughnut,myRecallDoughnut,myFmeasureDoughnut,myAccuracyDoughnut;
	var myMultipleLineChart;

	grafik();
	AverageChart();

	$(document).ready(function() {
		table_testing = $('#get-testing').DataTable({
			"ajax": {
				'url': "<?= base_url("Testing/getList")?>",
			},
			"columns": [
			{
				"title" : "<div class='form-check'><label class='form-check-label'><input type='checkbox' class='form-check-input checkbox-selectall' id='selectall'><span class='form-check-sign'></span></label></div>",
				"class": "text-center",
				"orderable" : false,
				"data": (data, type, row, meta) => {
					let ret="";
					ret+= '<div class="form-check"><label class="form-check-label"><input type="checkbox" class="form-check-input sub_chk" id="sub_chk" name="sub_chk" value="' +data.id+ '" data-id="'+data.id+'"/><span class="form-check-sign"></span></label></div>';
					return ret;

				}
			},
			{
				"title": "No",
				"width": "15px",
				"data": null,
				"visible": true,
				"class": "text-center",
				render: (data, type, row, meta) => {
					return meta.row + meta.settings._iDisplayStart + 1;
				}
			},
			{
				'title': 'No Perkara',
				'data': 'no_perkara'
			},
			{
				'title': 'Terdakwa',
				'data': 'terdakwa'
			},
			{
				'title': 'Pengadilan',
				'data': 'pengadilan'
			},
			{
				'title': 'Kalimat Peringkasan Manual',
				'data': 'list_manual'
			},
			{
				'title': 'Kalimat Peringkasan Otomatis',
				'data': 'list_auto'
			},
			// {
			// 	'title': 'Precision',
			// 	'data': 'precision'
			// },
			// {
			// 	'title': 'Recall',
			// 	'data': 'recall'
			// },
			// {
			// 	'title': 'F-Measure',
			// 	'data': 'f_measure'
			// },
			{
				"title": "Actions",
				"width" : "120px",
				"visible":true,
				"class": "text-center noExport",
				"data": (data, type, row) => {
					let ret = "";
					ret += '<button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-id="'+data.id_document+'" onclick="deleteButton(this)" data-original-title="Remove"><i class="fa fa-lg fa-times"></i></button>';

					return ret;
				}
			}]
		});

		$('<label>&nbsp;&nbsp;&nbsp;&nbsp;Tingkat Kompresi :&nbsp;<select id="kompresi" name="kompresi" class="form-control form-control-sm" ><option value="0.25">25%</option><option value="0.5" selected>50%</option><option value="0.75">75%</option></select></label>').appendTo("#get-testing_length");
		
		$('form#addTesting').submit(function(e){
			e.preventDefault();
			var formData = new FormData(this);
			var url = $(this).attr('action');
			$.ajax({
				url: url,
				type: 'post',
				data: formData,
				success: function(data) {
					swal({
						title: "Success",
						type:"success",
						text: "Your data has been added",
						timer: 2000,
						showConfirmButton: false
					});	
					$('#addRowModal').modal('hide');
					table_testing.ajax.reload(null,false);
					grafik(null);
					AverageChart(null);
				},
				cache : false,
				contentType : false,
				processData : false,
				error: function(data) {
					swal(data.responseText);
				}         
			});
		});

		$('#kompresi').on('change',function(){
			var kompresi = $(this).val();
			$.ajax({
				url : "<?= base_url("Testing/getList")?>",
				type : "POST",
				data: {kompresi: kompresi},
				success : function (data){
							//alert(data);           
							table_testing.clear();
							let json = $.parseJSON(data);
							table_testing.rows.add(json.data);
							table_testing.draw(); 

							grafik(kompresi);
							AverageChart(kompresi);
						}
					});
		});
	});

	var deleteButton = (obj) => {
		swal({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			type: 'warning',
			buttons:{
				confirm: {
					text : 'Yes, delete it!',
					className : 'btn btn-success'
				},
				cancel: {
					visible: true,
					className: 'btn btn-danger'
				}
			}
		}).then((Delete) => {
			if (Delete) {
				$.ajax({
					url: "<?= base_url("Testing/deleteList")?>",
					type: "POST",
					data: {
						id: $(obj).data('id'),
					},
					success: function (data) {
						swal({
							title: "Success",
							type:"success",
							text: "Your data has been deleted",
							timer: 2000,
							showConfirmButton: false
						});

						table_testing.ajax.reload(null,false);
						grafik(null);
						AverageChart(null);
					},
					error: function (data) {
						swal(data.responseText);
					}                    
				})
			}else{
				swal.close();
			}
		});
	};

	function grafik(kompresi){
		$ .ajax ({
			url: "<?= base_url("Testing/grafik")?>",
			type: "POST",
			data: {
				kompresi: kompresi,
			},
			success: function (data) {
				var result  = $.parseJSON(data); 
				var label = [];
				var value1 = [];
				var value2 = [];
				var value3 = [];
				var value4 = [];

				if(myMultipleLineChart){
					myMultipleLineChart.destroy();
				}

				for (var i=0;i<result.data.length;++i)
				{
					label.push("Document "+(i+1));
					value1.push(result.data[i].precision*100);
					value2.push(result.data[i].recall*100);
					value3.push(result.data[i].f_measure*100);
					value4.push(result.data[i].accuracy*100);

				   // alert(data[i].sentence);
				}

				myMultipleLineChart = new Chart(multipleLineChart, {
					type: 'line',
					data: {
						labels: label,
						datasets: [{
							label: "Precision",
							borderColor: "#1d7af3",
							pointBorderColor: "#FFF",
							pointBackgroundColor: "#1d7af3",
							pointBorderWidth: 2,
							pointHoverRadius: 4,
							pointHoverBorderWidth: 1,
							pointRadius: 4,
							backgroundColor: 'transparent',
							fill: true,
							borderWidth: 2,
							data: value1
						},{
							label: "Recall",
							borderColor: "#59d05d",
							pointBorderColor: "#FFF",
							pointBackgroundColor: "#59d05d",
							pointBorderWidth: 2,
							pointHoverRadius: 4,
							pointHoverBorderWidth: 1,
							pointRadius: 4,
							backgroundColor: 'transparent',
							fill: true,
							borderWidth: 2,
							data: value2
						}, {
							label: "F-Measure",
							borderColor: "#f3545d",
							pointBorderColor: "#FFF",
							pointBackgroundColor: "#f3545d",
							pointBorderWidth: 2,
							pointHoverRadius: 4,
							pointHoverBorderWidth: 1,
							pointRadius: 4,
							backgroundColor: 'transparent',
							fill: true,
							borderWidth: 2,
							data: value3
						}, {
							label: "Accuracy",
							borderColor: "#ffad46",
							pointBorderColor: "#FFF",
							pointBackgroundColor: "#ffad46",
							pointBorderWidth: 2,
							pointHoverRadius: 4,
							pointHoverBorderWidth: 1,
							pointRadius: 4,
							backgroundColor: 'transparent',
							fill: true,
							borderWidth: 2,
							data: value4
						}]
					},
					options : {
						responsive: true, 
						maintainAspectRatio: false,
						legend: {
							position: 'top',
						},
						scales: {
							xAxes: [{
								time: {
									unit: 'date'
								},
								gridLines: {
									display: false,
									drawBorder: false
								},
								ticks: {
									maxTicksLimit: 7
								}
							}],
							yAxes: [{
								ticks: {
									max: 100,
									beginAtZero: true,
									maxTicksLimit: 5,
									stepSize: 25,
									padding: 10,
									// Include a dollar sign in the ticks
									callback: function(value, index, values) {
										return value + "%";
									}
								},
								gridLines: {
									color: "rgb(234, 236, 244)",
									zeroLineColor: "rgb(234, 236, 244)",
									drawBorder: false,
									borderDash: [2],
									zeroLineBorderDash: [2]
								}
							}],
						},
						tooltips: {
							backgroundColor: "rgb(255,255,255)",
							bodyFontColor: "#858796",
							titleMarginBottom: 10,
							titleFontColor: '#6e707e',
							titleFontSize: 14,
							borderColor: '#dddfeb',
							borderWidth: 1,
							xPadding: 15,
							yPadding: 15,
							displayColors: false,
							intersect: false,
							mode: 'index',
							caretPadding: 10,
							callbacks: {
								label: function(tooltipItem, chart) {
									var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
									return datasetLabel + ': '+tooltipItem.yLabel + "%";
								}
							}
						},
						layout:{
							padding:{left:15,right:15,top:15,bottom:15}
						}
					}
				});
			}
		});
	}

	function AverageChart(kompresi){
		$ .ajax ({
			url: "<?= base_url("Testing/grafikAverage")?>",
			type: "POST",
			data: {
				kompresi: kompresi,
			},
			success: function (data) {
				var result  = $.parseJSON(data); 
				var precision = [];
				var recall = [];
				var fmeasure = [];
				var accuracy = [];
				var empty1 = [];
				var empty2 = [];
				var empty3 = [];
				var empty4 = [];

				$('#precisionText').empty();
				$('#recallText').empty();
				$('#fmeasureText').empty();
				$('#accuracyText').empty();

				if(myPrecisionDoughnut && myRecallDoughnut && myFmeasureDoughnut  && myAccuracyDoughnut){
					myPrecisionDoughnut.destroy();
					myRecallDoughnut.destroy();
					myFmeasureDoughnut.destroy();
					myAccuracyDoughnut.destroy();
				}

				for (var i=0;i<result.data.length;++i)
				{
					precision.push(result.data[i].precisionAvg*100);
					recall.push(result.data[i].recallAvg*100);
					fmeasure.push(result.data[i].fmeasureAvg*100);
					accuracy.push(result.data[i].accuracyAvg*100);

					var empty1 = [100-result.data[i].precisionAvg*100];
					var empty2 = [100-result.data[i].recallAvg*100];
					var empty3 = [100-result.data[i].fmeasureAvg*100];
					var empty4 = [100-result.data[i].accuracyAvg*100];

					$('#precisionText').append(Math.ceil(result.data[i].precisionAvg*100)+"%");
					$('#recallText').append(Math.ceil(result.data[i].recallAvg*100)+"%");
					$('#fmeasureText').append(Math.ceil(result.data[i].fmeasureAvg*100)+"%");
					$('#accuracyText').append(Math.ceil(result.data[i].accuracyAvg*100)+"%");


				   // alert(data[i].sentence);
				}

				myPrecisionDoughnut = new Chart(precisionDoughnut, {
					type: 'doughnut',
					data: {
						datasets: [{
							data: [precision,empty1],
							backgroundColor: ['#1d7af3','#fff'],
							borderColor: ['#1d7af3','#1d7af3']
						}]
					},
					options: {
						responsive: true, 
						maintainAspectRatio: false,
						legend : {
							display: false
						},
						tooltips : {
							enabled: false
						},
						layout: {
							padding: {
								left: 20,
								right: 20,
								top: 20,
								bottom: 20
							}
						}
					}
				});

				myRecallDoughnut = new Chart(recallDoughnut, {
					type: 'doughnut',
					data: {
						datasets: [{
							data: [recall,empty2],
							backgroundColor: ['#59d05d','#fff'],
							borderColor: ['#59d05d','#59d05d']
						}]
					},
					options: {
						responsive: true, 
						maintainAspectRatio: false,
						legend : {
							display: false
						},
						tooltips : {
							enabled: false
						},
						layout: {
							padding: {
								left: 20,
								right: 20,
								top: 20,
								bottom: 20
							}
						}
					}
				});

				myFmeasureDoughnut = new Chart(fmeasureDoughnut, {
					type: 'doughnut',
					data: {
						datasets: [{
							data: [fmeasure,empty3],
							backgroundColor: ['#f3545d','#fff'],
							borderColor: ['#f3545d','#f3545d']
						}]
					},
					options: {
						responsive: true, 
						maintainAspectRatio: false,
						legend : {
							display: false
						},
						tooltips : {
							enabled: false
						},
						layout: {
							padding: {
								left: 20,
								right: 20,
								top: 20,
								bottom: 20
							}
						}
					}
				});

				myAccuracyDoughnut = new Chart(accuracyDoughnut, {
					type: 'doughnut',
					data: {
						datasets: [{
							data: [accuracy,empty4],
							backgroundColor: ['#ffad46','#fff'],
							borderColor: ['#ffad46','#ffad46']
						}]
					},
					options: {
						responsive: true, 
						maintainAspectRatio: false,
						legend : {
							display: false
						},
						tooltips : {
							enabled: false
						},
						layout: {
							padding: {
								left: 20,
								right: 20,
								top: 20,
								bottom: 20
							}
						}
					}
				});
			}
		});
	}

</script>
</html>