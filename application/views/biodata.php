<!DOCTYPE html>
<html lang="">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Home</title>
		
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>	
		<![endif]-->
	<style type="text/css">
		p,h3 {
    padding: 1px 10px;
}
	</style>	
	</head>
	<body>
	<!-- navbar-->
  <div class="container">
  <nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Millenia</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="<?php echo site_url();?>/home">Home<span class="sr-only">(current)</span></a></li>
        <li><a href="<?php echo site_url();?>/home/1">News</a></li>
        <li ><a href="<?php echo site_url();?>/home/2">About</a></li>
        <li><a href="<?php echo site_url();?>/home/3">Contact</a></li>
<!--        <li class="dropdown">
         <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Posts <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Health</a></li>
            <li><a href="#">Entertainment</a></li>
            <li><a href="#">Sport</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Travel</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">All</a></li>
          </ul>
        </li> -->
      </ul> 
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#">Logout</a></li></ul>
      <form class="navbar-form navbar-right">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form>
      
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
  

<!-- jumbotron hello world -->
<br>
		<div class="col-xs-12 col-sm-12">
		<br>
		<div class="row">
		<div class="col-xs-12 col-sm-7">
            <div class="jumbotron">
                <br><center>
                <h1 style="font-size:390%;">Hello, World!</h1></center>
            </div></div>

        <div class="col-xs-12 col-sm-5">
			<div class="jumbotron">
		<div class="row">
		<div class="col-8 col-sm-8">
          <br>
					<h3>Photo Profile</h3>
					</div>
		<div class="col-sm-2">
					<br>
					<img src="<?php echo base_url();?>assets/img/img.png" width="87" height="90">
		    </div></div></div></div></div>

	<div class="container">
			<h1>Biodata</h1>
			<div class="table-responsive">
				<?php 
				foreach ($biodata as $key ) {
					echo "Nama : ".$key->namaPegawai;
					echo "<br>";
					echo "Alamat : ".$key->alamatPegawai;
					echo "<br>";
			} ?>
		</div>
		<div>
			<?php 
			foreach ($biodataJumlah as $key) {
		echo "jumlah pendidikan : ".$key->jumlahPendidikan;
		echo "<br>";
	}
			 ?>
		</div>
			<div>
			<h1>Daftar Pendidikan</h1>
			<div class="table-responsive">
			<table class="table table-hover">
				<tbody>
				<tr>
				
				<td>Nama Sekolah</td>
				<td>Tahun lulus</td>
			</tr>
				<?php foreach ($biodataPendidikan as $key ) {?>
					<tr>
						<td><?php echo $key->namaSekolah; ?></td>
						<td><?php echo $key->tahunLulus; ?></td>
					</tr>
			<?php 	} ?>
					
				</tbody>
			</table>


		</div>
		</div>
	</div>
	
	 <!-- footer -->
    <div class="panel panel-default">
      <div class="panel-body">
        
      </div>
      <div class="panel-footer">
        <center>Tugas Code Igniter / Millenia Rusbandi </center>
      </div>
    </div></div>
		

		<!-- jQuery -->
		<script src="<?php echo base_url();?>assets/js/jquery.js"></script>
		<!-- Bootstrap JavaScript -->
		<script src="<?php echo base_url();?>assets/js/bootstrap.js"></script>
		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<!--    <script src="<?php //echo base_url();?>Hello World"></script> -->
	</body>

</html>