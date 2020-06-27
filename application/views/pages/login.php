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
									<?php if (validation_errors()){?>
										<div class="alert alert-warning" role="alert">
										<?= validation_errors();?>
										</div>
									<?php }?>
									<form method="post" id="login" accept-charset="utf-8" action="<?= base_url('Home/cekLogin');?>">						
										<div class="form-group">
											<div class="input-icon">
												<span class="input-icon-addon">
													<i class="fas fa-user-circle"></i>
												</span>
												<input type="text" id="username" name="username" class="form-control" placeholder="Username">
											</div>
										</div>
										<div class="form-group">
											<div class="input-icon">
												<span class="input-icon-addon">
													<i class="fas fa-lock"></i>
												</span>
												<input type="password" id="password" name="password" class="form-control form-password" placeholder="Password">
											</div>
										</div>
										<div class="form-check">
											<label class="form-check-label">
												<input class="form-check-input form-checkbox" type="checkbox" value="">
												<span class="form-check-sign">Show password</span>
											</label>
										</div>

										<button type="submit" Class="btn btn-primary btn-block" style="border-radius: 30px;">Login</button>
										<hr>
										<div class="text-center">Don't have any account? <a href="<?= base_url('Home/register')?>" style="text-decoration: none">Create an Account</a></div>
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

<script type="text/javascript">
	$(document).ready(function(){		
		$('.form-checkbox').click(function(){
			if($(this).is(':checked')){
				$('.form-password').attr('type','text');
			}else{
				$('.form-password').attr('type','password');
			}
		});
	});
</script>
</html>