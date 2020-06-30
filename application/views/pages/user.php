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
						<h4 class="page-title">Users List</h4>
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
								<a href="#">Users</a>
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
										<h4 class="card-title">Add User</h4>
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
															User
														</span>
													</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<form method="post" id="addUser" action="<?= base_url('User/insertList'); ?>" enctype="multipart/form-data">
													<div class="modal-body">
														<p class="small">Make sure you fill them all</p>
														<div class="row">
															<div class="col-sm-12">
																<div class="form-group form-group-default">
																	<label>Fullname</label>
																	<input name="fullname" id="fullname" type="text" class="form-control" placeholder="*required">
																</div>
															</div>
															<div class="col-sm-12">
																<div class="form-group form-group-default">
																	<label>Username</label>
																	<input name="username" id="username" type="text" class="form-control" placeholder="*required">
																</div>
															</div>
															<div class="col-sm-12">
																<div class="form-group form-group-default">
																	<label>Type</label>
																	<select class="form-control" id="type" name="type">
																		<option value="admin">Admin</option>
																		<option value="pegawai">Pegawai</option>
																	</select>
																</div>
															</div>
															<div class="col-sm-12">
																<p class="small" style="color:#f25961">*default password is using username</p>
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
										<table id="get-user" class="display table table-bordered table-hover" width="100%" style="width:100%" cellspacing="0">
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

	var table_user;

	$(document).ready(function() {
		table_user = $('#get-user').DataTable({
			"ajax": {
				'url': "<?= base_url("User/getList")?>",
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
				'title': 'Full Name',
				'data': 'fullname'
			},
			{
				'title': 'Username',
				'data': 'username'
			},
			{
				'title': 'Type',
				'data': 'type'
			},
			{
				"title": "Permission",
				"visible":true,
				"class": "text-center",
				"data": (data, type, row) => {
					let ret = "";
					if(data.status==1){
						ret += '<span class"badge-warning">Off</span>';
					}else{
						ret += '<span class"badge-danger">On</span>';
					}
					return ret;
				}
			},
			{
				"title": "Actions",
				"visible":true,
				"class": "text-center noExport",
				"data": (data, type, row) => {
					let ret = "";
					ret += '<button type="button" data-toggle="tooltip" title="" class="btn btn-outline-primary" data-id="'+data.id_user+'" data-user="'+data.username+'" onclick="resetPassButton(this)" data-original-title="Reset"><i class="fa fa-lg fas fa-undo-alt" style="color:#1572e8"></i>&nbsp;Reset Password</button>&nbsp;&nbsp;';
					if(data.status==1){
						ret += '<button type="button" data-toggle="tooltip" title="" class="btn btn-outline-success" data-id="'+data.id_user+'" onclick="updateButton(this)" data-original-title="Update"><i class="fa fa-lg fa-check-circle" style="color:green"></i>&nbsp;Turn Permission On</button>';
					}else{
						ret += '<button type="button" data-toggle="tooltip" title="" class="btn btn-outline-danger" data-id="'+data.id_user+'" onclick="updateButton(this)" data-original-title="Update"><i class="fa fa-lg fa-times-circle" style="color:#f25961"></i>&nbsp;Turn Permission Off</button>';
					}
					

					return ret;
				}
			}
			],
		});
		$('form#addUser').submit(function(e){
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
					table_user.ajax.reload(null,false);
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

	var resetPassButton = (obj) => {
		swal({
			title: 'Are you sure?',
			text: "You won't to reset password of this user",
			type: 'warning',
			buttons:{
				confirm: {
					text : 'Yes',
					className : 'btn btn-success'
				},
				cancel: {
					visible: true,
					className: 'btn btn-danger'
				}
			}
		}).then((Reset) => {
			if (Reset) {
				$.ajax({
					url: "<?= base_url("User/resetPass")?>",
					type: "POST",
					data: {
						id: $(obj).data('id'),
						username: $(obj).data('user'),
					},
					success: function (data) {
						swal({
							title: "Success",
							type:"success",
							text: "Password has been updated",
							timer: 2000,
							showConfirmButton: false
						});
						table_user.ajax.reload(null,false);
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

	var updateButton = (obj) => {
		$.ajax({
			url: "<?= base_url("User/updateStatus")?>",
			type: "POST",
			data: {
				id: $(obj).data('id'),
			},
			success: function (data) {
				swal({
					title: "Success",
					type:"success",
					text: "Permission has been updated",
					timer: 2000,
					showConfirmButton: false
				});
				table_user.ajax.reload(null,false);
			},
			error: function (data) {
				swal(data.responseText);
			}                    
		})
	}

</script>
</html>