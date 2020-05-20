<!DOCTYPE html>
<html>
<?php $this->load->view('elements/header') ?>
<body>
	<div class="wrapper sidebar_minimize">
		<?php $this->load->view('elements/header2') ?>
		<?php $this->load->view('elements/sidebar') ?>

		<div class="main-panel" id="main-panel">
			<div class="content">
				<div class="page-inner">
					<div class="page-header">
						<h4 class="page-title">Summary Result</h4>
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
								<a href="#">Result</a>
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
                                <h4 class="card-title">No Perkara : <?=$noPerkara[0]['no_perkara'];?></h4>
                            </div>
                            <div class="card-body">
                                <ul class="nav nav-pills nav-secondary" id="pills-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="pills-preprocess-tab" data-toggle="pill" href="#pills-preprocess" role="tab" aria-controls="pills-preprocess" aria-selected="true">Pre-processing</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-tfidf-tab" data-toggle="pill" href="#pills-tfidf" role="tab" aria-controls="pills-tfidf" aria-selected="false">TF-IDF</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-method-tab" data-toggle="pill" href="#pills-method" role="tab" aria-controls="pills-method" aria-selected="false">Method</a>
                                    </li>
                                </ul>
                                <div class="tab-content mt-2 mb-3" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-preprocess" role="tabpanel" aria-labelledby="pills-preprocess-tab">
                                        <ul class="nav nav-pills nav-secondary nav-pills-no-bd" id="pills-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link">Plain</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link">Filtering</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link">Stemming</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link">Tokenizing</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link">Stopword Removal</a>
                                            </li>
                                        </ul>
                                        <div class="table-responsive">
                                            <table id="get-preprocess" class="display table table-bordered table-striped table-hover" width="100%" style="width:100%" cellspacing="0">
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pills-tfidf" role="tabpanel" aria-labelledby="pills-tfidf-tab">
                                        <ul class="nav nav-pills nav-secondary nav-pills-no-bd" id="pills-tab-without-border" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="pills-tf-tab-nobd" data-toggle="pill" href="#pills-tf-nobd" role="tab" aria-controls="pills-tf-nobd" aria-selected="false">Table TF</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="pills-tfidf-tab-nobd" data-toggle="pill" href="#pills-tfidf-nobd" role="tab" aria-controls="pills-tfidf-nobd" aria-selected="false">Table TFIDF</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content mt-2 mb-3" id="pills-without-border-tabContent">
                                            <div class="tab-pane fade show active" id="pills-tf-nobd" role="tabpanel" aria-labelledby="pills-tf-tab-nobd">
                                                <div class="table-responsive">
                                                    <table id="get-tf" class="display table table-bordered table-striped table-hover" width="100%" style="width:100%" cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <th rowspan="2" class="text-center"> No </th>
                                                                <th rowspan="2" class="text-center"> Kata - Penting </th>
                                                                <th colspan="14" class="text-center"> TF </th>
                                                                <th rowspan="2" class="text-center">IDF + 1</th>
                                                            </tr>
                                                            <tr>
                                                                <?php
                                                                for ($i=1; $i <= $count ; $i++) { ?>
                                                                <th class="text-center">Kalimat ke - <?= $i; ?></th>
                                                                <?php } ?>
                                                            </tr>

                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($matrix_tf as $key => $value) {?>
                                                            <tr>
                                                                <td><?= $no = $key + 1; ?></td>
                                                                <td><?=$text_list_word[$key]?></td>

                                                                <?php foreach ($value as $k => $v) {?>
                                                                <td>
                                                                    <?= $matrix_tf[$key][$k]?>
                                                                </td>
                                                                <?php }?>
                                                                <td><?=$text_idfplus1[$key];?></td>
                                                            </tr>
                                                            <?php }?>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="pills-tfidf-nobd" role="tabpanel" aria-labelledby="pills-tfidf-tab-nobd">
                                             <div class="table-responsive">
                                                <table id="get-tfidf" class="display table table-bordered table-striped table-hover" width="100%" style="width:100%" cellspacing="0">
                                                    <thead>
                                                        <tr>
                                                            <th rowspan="2" class="text-center"> No </th>
                                                            <th rowspan="2" class="text-center"> Kata - Penting </th>
                                                            <th colspan="14" class="text-center"> TF-IDF </th>
                                                        </tr>
                                                        <tr>
                                                            <?php
                                                            for ($i=1; $i <= $count ; $i++) { ?>
                                                            <th class="text-center">Kalimat ke - <?= $i; ?></th>
                                                            <?php } ?>
                                                        </tr>

                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($matrix_tfidf as $key => $value) {?>
                                                        <tr>
                                                            <td><?= $no = $key + 1; ?></td>
                                                            <td><?=$text_list_word[$key]?></td>

                                                            <?php foreach ($value as $k => $v) {?>
                                                            <td>
                                                                <?= $matrix_tfidf[$key][$k]?>
                                                            </td>
                                                            <?php }?>
                                                        </tr>
                                                        <?php }?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-method" role="tabpanel" aria-labelledby="pills-method-tab">
                                 <div class="table-responsive">
                                    <table id="get-method" class="display table table-bordered table-striped table-hover" width="100%" style="width:100%" cellspacing="0">
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
</div>
</div>
</body>
<?php $this->load->view('elements/footer') ?>

<script>

	var table_preprocess;
    var table_tfidf;
    var table_method;
    var countColumn;
    var ids = "<?= $ids?>";


    $(document).ready(function() {

        table_preprocess = $('#get-preprocess').DataTable({
            "autoWidth": true,
            "responsive": true,
            "lengthChange": true,
            ajax: {
                url: "<?= base_url()?>/Documents/getPreprocess/"+ids,
                "dataSrc": function(data) {
                    result = data.data;
                    // console.log(result);
                    return data.data;
                }
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
                'title': 'Plain',
                'data' : 'plain'
            },
            {
                'title': 'Case Folding',
                'data' : 'casefolding',
                // render: (data, type, row, meta) => {
                //     return casefolding['0'];
                // }
            },
            {
                'title': 'Filtering',
                'data': 'filtering'
            },
            {
                'title': 'Stemming',
                'data': 'stemming'
            },
            {
                'title': 'Tokenizing',
                'data': 'tokenizing'
            },
            {
                'title': 'Stopword Removal',
                'data': 'stopwords',
                'autoWidth' : true
            }
            ],
            columnDefs:[
            {
                targets:[0,1,2,3,4,5,6],className:"truncate"
            }
            ],
            createdRow: function(row){
                $(row).find(".truncate").each(function(){
                    $(this).attr("title", this.innerText);
                });
            } 
        });

        // table_tfidf = $('#get-tfidf').DataTable({
        //     "autoWidth": true,
        //     "responsive": true,
        //     "lengthChange": true,
        //     ajax: {
        //         url: "<?= base_url()?>/Documents/getTfidf/"+ids,
        //         "dataSrc": function(data) {
        //             result = data.data;
        //             countColumn = data.count - 1;
        //             // result.forEach(entry => entry[0] += entry[0]);
        //             return Object.values(result);
        //         }
        //     },
        //     // data: result,
        //     // 'columns': [
        //     // {
        //     //     "title": "No",
        //     //     "width": "15px",
        //     //     "data": null,
        //     //     "visible": true,
        //     //     "class": "text-center",
        //     //     render: (data, type, row, meta) => {
        //     //         return meta.row + meta.settings._iDisplayStart + 1;
        //     //     }
        //     // },
        //     // // {
        //     // //     'title': 'Plain',
        //     // // },
        //     // // {
        //     // //     'title': 'Plain',
        //     // // },
        //     // // {
        //     // //     'title': 'Plain',
        //     // // },
        //     // // {
        //     // //     'title': 'Plain',
        //     // // },
        //     // // {
        //     // //     'title': 'Plain',
        //     // // },
        //     // // {
        //     // //     'title': 'Plain',
        //     // // },
        //     // // {
        //     // //     'title': 'Plain',
        //     // // },
        //     // // {
        //     // //     'title': 'Plain',
        //     // // },
        //     // // {
        //     // //     'title': 'Plain',
        //     // // },
        //     // // {
        //     // //     'title': 'Plain',
        //     // // },
        //     // // {
        //     // //     'title': 'Plain',
        //     // // },
        //     // // {
        //     // //     'title': 'Plain',
        //     // // },
        //     // // {
        //     // //     'title': 'Plain',
        //     // // },
        //     // // {
        //     // //     'title': 'Plain',
        //     // // },

        //     // ],
        //     // columnDefs: [
        //     // { targets: ['_all'], visible: true }
        //     // ]


        //     // ],
        // });

        table_method = $('#get-method').DataTable({
            "autoWidth": true,
            "responsive": true,
            "lengthChange": true,
            ajax: {
                url: "<?= base_url()?>/Documents/getMethod/"+ids,
                // "dataSrc": function(data) {
                //     result = data.data;
                //     return data.data;
                // }
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
                'title': 'F1',
                'data' : 'f1'
            },
            {
                'title': 'F2',
                'data' : 'f2',
                // render: (data, type, row, meta) => {
                //     return casefolding['0'];
                // }
            },
            {
                'title': 'Bobot',
                'data': 'bobot'
            },
            {
                'title': 'Kalimat',
                'data': 'sentence',
                'autoWidth' : true
            },
            ],
            columnDefs:[
            {
                targets:[0,1,2,3,4],className:"truncate"
            }
            ],
            createdRow: function(row){
                $(row).find(".truncate").each(function(){
                    $(this).attr("title", this.innerText);
                });
            } 
        });

        $('#get-preprocess tbody').on('click', 'tr', function() {
            $(this).find('td').each (function() {
                if($(this).hasClass('truncate')){
                    $(this).removeClass("truncate");
                }else{
                    $(this).addClass("truncate");
                }
            });   
        });

        $('#get-method tbody').on('click', 'tr', function() {
            $(this).find('td').each (function() {
                if($(this).hasClass('truncate')){
                    $(this).removeClass("truncate");
                }else{
                    $(this).addClass("truncate");
                }
            });   
        });

        // $('#pills-home-tfidf-nobd').on('click', function() {
        //     if($('#pills-tfidf-nobd').hasClass('active')){
        //         $('#pills-tf-nobd').removeClass("active");
        //     }else{
        //         $('#pills-tfidf-nobd').addClass("active");
        //         $('#pills-tf-nobd').removeClass("active");
        //     }
        // }); 

        // $('#pills-home-tf-nobd').on('click', function() {
        //     alert('a');
        //     if($('#pills-tfidf-nobd').hasClass('active')){
        //         $('#pills-tfidf-nobd').removeClass("active");
        //         $('#pills-tf-nobd').addClass("active");
        //     }else{
        //         $('#pills-tfidf-nobd').removeClass("active");
        //     }
        // });         
    });


// function readmore(){
//     readmore = 
//     $('#afterSummary').readmore({
//         speed: 75,
//         collapsedHeight: 10,
//         lessLink: '<a href="#">Read less</a>',
//         heightMargin: 16
//         });
// }


</script>
</html>