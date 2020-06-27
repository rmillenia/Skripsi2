<!DOCTYPE html>
<html lang="en">
<head><meta charset=UTF-8">
	<style type="text/css">

		span.cls_002{font-family:Times,serif;font-size:12.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
		div.cls_002{font-family:Times,serif;font-size:12.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
		span.cls_003{font-family:Tahoma,serif;font-size:12.1px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
		div.cls_003{font-family:Tahoma,serif;font-size:12.1px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
		span.cls_024{font-family:Tahoma,serif;font-size:12.1px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: underline}
		div.cls_024{font-family:Tahoma,serif;font-size:12.1px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
		span.cls_004{font-family:Tahoma,serif;font-size:12.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
		div.cls_004{font-family:Tahoma,serif;font-size:12.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
		span.cls_006{font-family:Tahoma,serif;font-size:14.1px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
		div.cls_006{font-family:Tahoma,serif;font-size:14.1px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
<!-- div {
   line-height:1.5;
} -->
.top{
line-height: 4;
}


</style>
</head>
<body>
	<div style="padding-top:84.62px" >
		<center>
			<div class="cls_003"><span class="cls_003">P E M B E L A A N</span></div>
			<br>
			<div class="cls_003"><span class="cls_003">DALAM PERKARA PIDANA</span></div>
			<br>
			<div class="cls_003"><span class="cls_003">NOMOR: <?= $no_perkara;?></span></div>
			<br>
			<div class="cls_003"><span class="cls_003"><?= $pengadilan;?></span></div>
			<br>
		</center>
		<hr width="80%" style="border: 0; border-top: 4px double dark-grey; height: 3px" />
		<br>
		<br>
		<div style="padding-left:70.82px;padding-right:70.82px">
			<div class="cls_003"><span class="cls_003">I. </span><span class="cls_024">PENDAHULUAN</span></div>
			<br>
			<?php foreach ($kalimat as $key => $value) {?>
				<div class="cls_004"><span class="cls_004"><?= $value->sentence;?>.</span></div>
				<br>
			<?php } ?>
		</div>

	</div>

</body>
</html>
