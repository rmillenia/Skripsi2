<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Legal Documents Summarizer</title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<link rel="icon" href="<?= base_url('assets/img/icon.ico')?>" type="image/x-icon"/>

	<!-- Fonts and icons -->
	<script src="<?= base_url('assets/js/plugin/webfont/webfont.min.js')?>"></script>
	<script>
		WebFont.load({
			google: {"families":["Lato:300,400,700,900"]},
			custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['<?= base_url("assets/css/fonts.min.css")?>']},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>

	<!-- CSS Files -->
	<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css')?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/atlantis.min.css')?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/style.css')?>">
	<link href="<?= base_url('assets/dropify-master/dist/css/dropify.min.css')?>" rel="stylesheet"/>

	<style type="text/css">
		
		#login-bg {
			background-image: url('<?= base_url("assets/pic/login-bg.png");?>');
		}
	</style>

</head>

