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
                    <div id="summary" class="more" style="display: none;overflow: hidden;">
                     <div class="row">
                      <div class="col-md-12">
                       <div class="card">
                        <div class="card-body" id="card1">
                            <div class="page-title">Result</div>
                            <div id="kompresi">
                                <div class="row">
                                    <div class="col-md-4">
                                        <h3>Tingkat Kompresi Ringkasan :</h3>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="kompresiSelect" id="kompresiSelect" class="form-control" style="padding: .3rem 1rem !important">
                                          <option value="0.25">25 %</option>
                                          <option value="0.5" >50 %</option>
                                          <option value="0.75">75 %</option>
                                      </select>
                                  </div>
                              </div>
                          </div>
                          <div class="row">
                            <div class="col-md-6">
                                <h4><b>Before</b></h4>
                                <div id="beforeSummary" class="text-justify">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4><b>After</b></h4>
                                <div id="afterSummary" class="text-justify">

                                </div>
                            </div>
                        </div>
                        <br>
                        <button type="button" value="Read More" class="btn btn-default" id="readMore" style="display: hidden">Read More</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                      <table id="get-result" class="display table table-bordered table-hover" width="100%" style="width:100%" cellspacing="0">
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

	var table_result;
    var result;
    var result_index;

    $(document).ready(function() {
        // $('#summary').readmore({
        // speed: 75,
        // collapsedHeight: 10,
        // lessLink: '<a href="#">Read less</a>',
        // heightMargin: 16
        // });

        table_result = $('#get-result').DataTable({
            ajax: {
                url: '<?= base_url("Result/getResult")?>',
                "dataSrc": function(data) {
                    result = data.data;
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
                'title': 'No Perkara',
                'data': 'no_perkara'
            },
            {
                'title': 'Nama Dokumen',
                "visible":true,
                "class": "text-center noExport",
                "data": (data, type, row) => {
                    let ret = "";
                    ret += ' <button type="button" class="btn btn-lg btn-rounded btn-primary">'+data.file+'</button>';

                    return ret;
                }
            },
            {
                'title': 'Tanggal',
                'data': 'date_time'
            },
            {
                "title": "Actions",
                "width" : "120px",
                "visible":true,
                "class": "text-center noExport",
                "data": (data, type, row) => {
                    let ret = "";
                    ret += ' <a href="<?= base_url('Documents/goProcess/');?>'+data.id+'" style=" text-decoration: none;color:black ">See Process&nbsp;&nbsp;</a><span class="fa fa-spinner" style="color: #1572e8"></span>';

                    return ret;
                }
            }

            ],
        });
        $('#get-result tbody').on('click', 'tr', function() {
            let index = table_result.row(this).index();
            result_index = index;
            $("#summary").css('display','inline');
            showSummary(result[result_index].id,0.5);
            showDocuments(result[result_index].id);
            $("#kompresiSelect").val('0.5');
            $('html, body').animate({ scrollTop: $("#main-panel").offset().top}, 2000);
        });
        $( '#kompresiSelect').on( 'change', function (i) {
            var kompresi = $(this).val();
            showSummary(result[result_index].id,kompresi);
        });

        $('#readMore').on('click', function () {
            if($('#hidden').css('display') == 'none' && $('#hidden1').css('display') == 'none'){
                $("#hidden").css('display','inline');
                $("#delete").css('display','none');
                $("#hidden1").css('display','inline');
                $("#delete1").css('display','none');
                $("#readMore").html('Read Less');
            }else if($('#hidden').css('display') != 'none' && $('#hidden1').css('display') != 'none'){
                $("#hidden").css('display','none');
                $("#delete").css('display','inline');
                $("#hidden1").css('display','none');
                $("#delete1").css('display','inline');
                $("#readMore").html('Read More');

            }
        });

    });

    var showSummary = (id,kompresi) => {
        $.ajax({
            url: "<?= base_url('Result/getSummaryResult'); ?>",
            type: 'post',
            data: {id: id, kompresi: kompresi},
            success: function(data) {
                var result  = $.parseJSON(data);                    
                    // alert(JSON.stringify(result));
                    $('#afterSummary').empty();
                    for (var i=0;i<result.data.length;++i)
                    {
                        $('#afterSummary').append(result.data[i].sentence+". ");
                       // alert(data[i].sentence);
                   }

                   var text = $('#afterSummary').text();
                   var maxL = 500;
                    // alert(text.length);
                    if(text.length > maxL && $('#readMore').text() == 'Read More') {

                        var begin = text.substr(0, maxL);
                        var end = text.substr(maxL);
                        var delimeter = '...';


                        $('#afterSummary').empty();
                        $('#afterSummary').append(begin).append($('<a id="delete1" style="display:inline" />').html(delimeter)).append($('<a id="hidden1" style="display:none" />').html(end));


                        $("#readMore").css('display','inline');
                    }
                    // alert(.text());
                    // if($('#summary').css('display') == 'inline')
                    // {
                    // readmore();
                    // }
                }
            })
    }
    var showDocuments = (id) => {
        $.ajax({
            url: "<?= base_url('Result/getSentence'); ?>",
            type: 'post',
            data: {id: id},
            success: function(data) {
                    // var json = $.parseJSON(data);
                    var result  = $.parseJSON(data);
                    // alert(JSON.stringify(result));
                    $('#beforeSummary').empty();
                    for (var i=0;i<result.data.length;++i)
                    {
                        $('#beforeSummary').append(result.data[i].sentence+". ");
                        // alert(data[i].sentence);
                    }

                    var text = $('#beforeSummary').text();
                    var maxL = 500;
                    var delimeter = '...';
                    // alert(text.length);
                    if(text.length > maxL) {

                        var begin = text.substr(0, maxL);
                        end = text.substr(maxL);

                        $('#beforeSummary').empty();
                        $('#beforeSummary').append(begin).append($('<a id="delete" style="display:inline" />').html(delimeter)).append($('<a id="hidden" style="display:none" />').html(end));
                    }

                }
            })
    }

    // $(document).on('click', '#btnRead', function () {
    //     $(this).next('.hidden').slideDown(750);
    // })   

    // var readMore = (idElement) => {
    //     var text = idElement.text();
    //     var maxL = 500;
    //                 // alert(text.length);
    //     if(text.length > maxL) {

    //         var begin = text.substr(0, maxL);
    //         var end = text.substr(maxL);

    //         $(idElement).empty();
    //         $(idElement).append(begin).append($('<a style="display:none" />').html(end));
    //     }
    // }


</script>
</html>