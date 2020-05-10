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
						<h4 class="page-title">Stopword List</h4>
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
								<a href="#">Stopword</a>
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
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<div class="d-flex align-items-center">
										<h4 class="card-title">Add Stopword</h4>
										<button class="btn btn-primary btn-round ml-auto" data-toggle="modal" data-target="#addRowModal">
											<i class="fa fa-plus"></i>
											Add Stopword
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
															Stopword
														</span>
													</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<form method="post" id="addStopword" action="<?= base_url('Stopword/insertList'); ?>" enctype="multipart/form-data">
													<div class="modal-body">
														<p class="small">Make sure you fill them all</p>
														<div class="row">
															<div class="col-sm-12">
																<div class="form-group form-group-default">
																	<label>Stopword</label>
																	<input name="stopword" id="stopword" type="text" class="form-control" placeholder="*required">
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
										<table id="get-stopword" class="display table table-bordered table-hover" width="100%" style="width:100%" cellspacing="0">
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

<script>

	var table_stopword;

	$(document).ready(function() {
		table_stopword = $('#get-stopword').DataTable({
			"ajax": {
				'url': "<?= base_url("Stopword/getList")?>",
			},
			"columns": [
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
				'title': 'Stopword',
				'data': 'stopword'
			},
			{
				"title": "Actions",
				"width" : "120px",
				"visible":true,
				"class": "text-center noExport",
				"data": (data, type, row) => {
					let ret = "";
					ret += '<button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-id="'+data.id+'" onclick="deleteButton(this)" data-original-title="Remove"><i class="fa fa-lg fa-times-circle"></i></button>';

					return ret;
				}
			}
			],
		});
		$('form#addStopword').submit(function(e){
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
					table_stopword.ajax.reload(null,false);
				},
				cache : false,
				contentType : false,
				processData : false,
				error: function(data) {
					swal(data.responseText);
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
					url: "<?= base_url("Stopword/deleteList")?>",
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
						table_stopword.ajax.reload(null,false);
					},
					error: function (data) {
						swal(data.responseText);
					}                    
				})
			}else{
				swal.close();
			}
		});
	}

</script>
</html>