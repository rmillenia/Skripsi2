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
                    'title': 'File',
                    'data': 'file'
                },
                {
                    'title': 'Tanggal',
                    'data': 'date_time'
                },
                {
                    'title': 'Actions',
                    'data': 'date_time'
                }

            ],
        });
    });
</script>
</html>