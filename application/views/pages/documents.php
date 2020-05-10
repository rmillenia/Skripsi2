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
						<h4 class="page-title">Nota Pembelaan</h4>
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
								<a href="#">Documents</a>
							</li>
							<li class="separator">
								<i class="flaticon-right-arrow"></i>
							</li>
							<li class="nav-item">
								<a href="#">View All Documents</a>
							</li>
						</ul>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-body">
									<button type="button" class="btn btn-outline-light delete_all" style="color: #575962"><span class="fa fa-lg fa-times-circle" style="color: #f25961;"></span> Delete</button>
									<br><br>
									<div class="table-responsive">
										<table id="get-documents" class="display table table-bordered table-hover" width="100%" style="width:100%" cellspacing="0">
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

	var table_documents;

	$(document).ready(function() {
		table_documents = $('#get-documents').DataTable({
			ajax: {
				url: '<?= base_url("Documents/getDocuments")?>',
			},
			'columns': [
			{
				"title" : "<input type='checkbox' class='checkbox-selectall' id='selectall'><label for='checkall'></label>",
				"class": "text-center",
				"orderable" : false,
				"data": (data, type, row, meta) => {
					let ret="";
					ret+= '<input type="checkbox" class="sub_chk" id="sub_chk" name="sub_chk" value="' +data.id+ '" data-id="'+data.id+'"/>';
					return ret;

				}
			},
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
				'title': 'No Perkara',
				'data': 'no_perkara'
			},
			{
				'title': 'Nama Dokumen',
				"visible":true,
				"class": "text-center noExport",
				"data": (data, type, row) => {
					let ret = "";
					ret += ' <a href="<?= base_url('assets/uploads/');?>'+data.file+'" class="btn btn-lg btn-rounded btn-primary">'+data.file+'</a>';

					return ret;
				}
			},
			{
				'title': 'Tanggal',
				'data': 'date_time'
			},
			// {
			// 	"title": "Actions",
			// 	"width" : "120px",
			// 	"visible":true,
			// 	"class": "text-center noExport",
			// 	"data": (data, type, row) => {
			// 		let ret = "";
			// 		ret += ' <a href="<?= base_url('Documents/deleteDocuments/');?>'+data.id+'"><span class="fa fa-trash" style="color: #f25961"></span></a>';

			// 		return ret;
			// 	}
			// }

			],
		});


		$('.checkbox-selectall').on('click',function(e){
       		$('.sub_chk').prop('checked',$(e.target).prop('checked'));
        });

		$('.delete_all').on('click', function(e) {

			var allVals = [];
			$(".sub_chk:checked").each(function() {
				allVals.push($(this).attr('data-id'));
				console.log($(this).attr('data-id'));
			});

			if(allVals.length <=0)
			{
				swal({
					title: "Warning",
					type:"warning",
					text: "Please Select a row",
					timer: 2000,
					showConfirmButton: false
				});
			}else{
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
						var join_selected_values = allVals.join(",");
						console.log(join_selected_values);

						$.ajax({
							url: "<?= base_url('Documents/deleteDocuments/');?>",
							type: 'POST',
							datatype:'JSON',
							data: 'ids='+join_selected_values,
							success: function (data) {
								console.log(data);
								$(".sub_chk:checked").each(function() {
									$(this).parents("tr").remove();
								});
								swal({
									title: 'Deleted!',
									text: 'Your file has been deleted.',
									type: 'success',
									buttons : {
										confirm: {
											className : 'btn btn-success'
										}
									}
								});
								table_documents.ajax.reload(null,false);
							},
							error: function (data) {
								swal(data.responseText);
							}                    
						});

						$.each(allVals, function( index, value ) {
							table_documents.row($(this).parents('tr')).remove();
						});
					} else {
						swal.close();
					}
				});
			}
		});
	});

</script>
</html>