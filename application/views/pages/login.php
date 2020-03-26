<!DOCTYPE html>
<html>
<?php $this->load->view('elements/header') ?>

<body id="login-bg">
	<div class="banner" id="home" style="padding-top: 15%;">
		<div class="layer">
			<div class="container-fluid">
				<div class="row" style="padding-left: 1%; padding-right: 1%">
					<div class="col-md-6 banner-text-w3ls"></div>
					<!-- banner slider-->
					<div class="col-md-6 px-lg-3 px-0">
						<div class="banner-form-w3 ml-lg-5">
							<div class="card shadow">
								<div class="card-title text-center" style="padding-top: 10px;">Welcome User</h4></div>
								<div class="card-body">
									<form method="post" id="inputReservation" accept-charset="utf-8" action="<?= site_url() ?>/User/addReservation">						
										<div class="form-group">
												<div class="input-icon">
													<span class="input-icon-addon">
														<i class="fa fa-user"></i>
													</span>
													<input type="text" class="form-control" placeholder="Username">
												</div>
											</div>
										<div class="form-group">
												<div class="input-icon">
													<span class="input-icon-addon">
														<i class="fa fa-lock"></i>
													</span>
													<input type="password" class="form-control" placeholder="Password">
												</div>
											</div>
										<div class="form-check">
											<label class="form-check-label">
												<input class="form-check-input" type="checkbox" value="">
												<span class="form-check-sign">Show password</span>
											</label>
										</div>

										<button type="submit" Class="btn btn-primary btn-block" style="border-radius: 30px;">Login</button>
										<hr>
										<div class="text-center">Don't have any account? <a href="">Create an Account</a></div>
									</div>
								</form>
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
</html>