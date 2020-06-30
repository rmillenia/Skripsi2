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
								<h2 class="text-white pb-2 fw-bold">Profile</h2>
							</div>
						</div>
					</div>
				</div>
				<div class="page-inner mt--5">
					<div class="card">
						<div class="card-body">
							<div class="row">
								<div class="col-md-2">
									<img src="<?= base_url('/assets/uploads/pic/')?><?= $pic;?>" alt="..." class="avatar-img rounded-circle">
								</div>
								<?php foreach ($this->db->get_where('users', array('id_user' => $id))->result() as $key => $value) { ?>
								<div class="col-md-6">
									<form method="post" id="updateProfile" action="<?= base_url('Profile/updateList'); ?>" enctype="multipart/form-data">
									<div class="form-group form-inline">
										<label for="fullname" class="col-md-3 col-form-label">Fullname</label>
										<div class="col-md-9 p-0">
											<input type="text" class="form-control input-full" id="fullname" name="fullname" value="<?= $value->fullname;?>" placeholder="Enter Fullname">
										</div>
									</div>
									<div class="form-group form-inline">
										<label for="username" class="col-md-3 col-form-label">Username</label>
										<div class="col-md-9 p-0">
											<input type="text" class="form-control input-full" id="username" name="username" value="<?= $value->username;?>" placeholder="Enter Username">
										</div>
									</div>
									<div class="form-group form-inline">
										<label for="password" class="col-md-3 col-form-label">Password</label>
										<div class="col-md-9 p-0">
											<input type="password" class="form-control input-full" id="password" placeholder="Enter Password">
										</div>
									</div>
									<div class="form-group form-inline">
										<label for="file" class="col-md-3 col-form-label">Pic</label>
										<div class="col-md-9 p-0">
											<input type="file" class="form-control input-full" id="file" name="file">
										</div>
									</div>
									<input type="reset" name="reset" class="btn btn-danger" style="float: right" value="Reset"> 
									<input type="submit" name="submit" id="submit" class="btn btn-primary" style="float: right; margin-right: 10px" value="Submit">
								</div>
								</form>
								<?php } ?>
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
<?php $this->load->view('elements/footer') ?>

<script type="text/javascript">
	$(document).ready(function() {
		$('form#updateProfile').submit(function(e){
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
				},
				cache : false,
				contentType : false,
				processData : false,
				error: function(data) {
					swal(data.responseText);
				}         
			});
		});
	}
</script>

</html>