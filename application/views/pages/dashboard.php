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
						<h4 class="page-title">Forms</h4>
						<ul class="breadcrumbs">
							<li class="nav-home">
								<a href="#">
									<i class="flaticon-home"></i>
								</a>
							</li>
							<li class="separator">
								<i class="flaticon-right-arrow"></i>
							</li>
							<li class="nav-item">
								<a href="#">Forms</a>
							</li>
							<li class="separator">
								<i class="flaticon-right-arrow"></i>
							</li>
							<li class="nav-item">
								<a href="#">Basic Form</a>
							</li>
						</ul>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-body">
									<form method="post" action="<?php echo base_url("index.php/MasterIn/form"); ?>" enctype="multipart/form-data">

										<input type="file" name="file" class="dropify">

                        				<input type="submit"   class="btn btn-primary float-right" name="preview" value="Preview">
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
		$('.dropify').dropify({
			messages : {
				default : "Drag or Drop Your Pdf to Upload",
				replace : 'Replace',
				remove : 'Remove',
				error : 'error'
			}
		});
	});
</script>

</html>