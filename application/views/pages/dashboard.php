<!DOCTYPE html>
<html>
<?php $this->load->view('elements/header') ?>
<body>
	<div class="wrapper sidebar_minimize">
		<?php $this->load->view('elements/header2') ?>
		<?php $this->load->view('elements/sidebar') ?>

		<div class="main-panel">
			<div class="content">
				<div class="panel-header bg-primary-gradient">
					<div class="page-inner py-5">
						<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
							<div>
								<h2 class="text-white pb-2 fw-bold">Dashboard</h2>
							</div>
						</div>
					</div>
				</div>
				<div class="page-inner mt--5">
					<div class="row">
						<div class="col-md-6">
							<div class="row row-card-no-pd">
								<div class="col-sm-6 col-md-6">
									<div class="card card-stats card-round">
										<div class="card-body ">
											<div class="row">
												<div class="col-5">
													<div class="icon-big text-center">
														<i class="flaticon-file text-warning"></i>
													</div>
												</div>
												<div class="col-7 col-stats">
													<div class="numbers">
														<p class="card-category">Total Documents</p>
														<h4 class="card-title"><?= $count_documents?></h4>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-md-6">
									<div class="card card-stats card-round">
										<div class="card-body ">
											<div class="row">
												<div class="col-5">
													<div class="icon-big text-center">
														<i class="flaticon-users text-success"></i>
													</div>
												</div>
												<div class="col-7 col-stats">
													<div class="numbers">
														<p class="card-category">Total Users</p>
														<h4 class="card-title"><?= $count_users?></h4>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						<div class="card">
							<div class="card-header">
								<div class="card-title">Recent Activity</div>
							</div>
							<div class="card-body">
								<ol class="activity-feed">
									<?php
									if($history){
										foreach ($history as $key => $value) {?>
										<li class="feed-item <?php if($key == 0){ echo'feed-item-secondary';}else if($key == 1){ echo 'feed-item-success';}else if($key == 2){ echo 'feed-item-info';}else if($key == 3){ echo 'feed-item-warning';}else if($key == 4){ echo 'feed-item-danger';}?>">
											<time class="date"><?= date('d-M-Y', strtotime($value->date_time))?> <?= strtolower("at")?> <?= date('H:m:s', strtotime($value->date_time))?></time>
											<span class="text"><?= ucfirst($value->fullname);?> summarized documents with no perkara : <a href="#"><?= $value->no_perkara;?></a></span>
										</li>
										<?php }
									}else{?>
									<li class="feed-item feed-item-danger">
										<span class="text">No recent activity</span>
									</li>
									<?php }?>
								</ol>
							</div>
						</div>
					</div>

						<div class="col-md-6">
							<div class="card full-height">
								<div class="card-header">
									<div class="card-head-row">
										<div class="card-title">Total Documents</div>
										<div class="card-tools">
											<ul class="nav nav-pills nav-secondary nav-pills-no-bd nav-sm" id="pills-tab" role="tablist">
												<li class="nav-item">
													<a class="nav-link active" id="nav" data-id="1" data-toggle="pill" role="tab" aria-selected="true">Week</a>
												</li>
												<li class="nav-item">
													<a class="nav-link" id="nav" data-id="2" data-toggle="pill" role="tab" aria-selected="false">Month</a>
												</li>
												<li class="nav-item">
													<a class="nav-link" id="nav" data-id="3" data-toggle="pill" role="tab" aria-selected="false">Year</a>
												</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="card-body">
									<canvas id="reportChart"></canvas>
								</div>
							</div>
						</div>

					</div>
					
					
				</div>
			</div>
			<footer class="footer">
				<div class="container-fluid">
					<nav class="pull-left">
						<ul class="nav">
							<li class="nav-item">
								<a class="nav-link" href="https://www.themekita.com">
									ThemeKita
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">
									Help
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">
									Licenses
								</a>
							</li>
						</ul>
					</nav>
					<div class="copyright ml-auto">
						2018, made with <i class="fa fa-heart heart text-danger"></i> by <a href="https://www.themekita.com">ThemeKita</a>
					</div>				
				</div>
			</footer>
		</div>
	</div>
</body>
fetch_report
<?php $this->load->view('elements/footer') ?>

<script type="text/javascript">

	var barChart = document.getElementById('reportChart').getContext('2d');
	var myBarChart = null;

	grafik(1);

	$(document).ready(function(){

		$('.nav-link').on('click',function(){
			var id = $(this).data('id');

			if(myBarChart != null){
					myBarChart.destroy();
			}

			var label = [];
			var value = [];

			grafik(id);
		});


	});

	function grafik(id){
		$ .ajax ({
			url: "<?= base_url("Home/fetch_report")?>",
			type: "POST",
			data: {
				id: id,
			},
			success: function (data) {
				console.log (data);
				var result  = $.parseJSON(data); 
				var label = [];
				var value = [];

				for (var i=0;i<result.data.length;++i)
				{
					label.push(result.data[i].value);
					value.push(result.data[i].total);
				   // alert(data[i].sentence);
				}

				if(myBarChart){
					myBarChart.destroy();
				}

				myBarChart = new Chart(barChart, {
					type: 'bar',
					data: {
						labels: label,
						datasets : [{
							label: "Total Documents",
							backgroundColor: '#007bff',
							borderColor: '#007bff',
							data: value,
						}],
					},
					options: {
						responsive: true, 
						maintainAspectRatio: false,
						legend: {
							position : 'bottom'
						},
						title: {
							display: true,
							text: 'Traffic Stats'
						},
						tooltips: {
							mode: 'index',
							intersect: false
						},
						responsive: true,
						scales: {
							xAxes: [{
								stacked: true,
							}],
							yAxes: [{
								stacked: true
							}]
						}
					}		
				});
			}
		});
	}
</script>

</html>