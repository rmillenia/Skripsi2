<!DOCTYPE html>
<html>
<?php $this->load->view('elements/header') ?>
<body>
    <div class="wrapper sidebar_minimize">
        <?php $this->load->view('elements/header2') ?>
        <?php $this->load->view('elements/sidebar') ?>

        <div id="loading" class="loading" style="display: none;">Loading&#8230;</div>
        <div class="main-panel">
            <div class="content">
                <div class="page-inner">
                    <div class="page-header">
                        <h4 class="page-title">Upload and Summarize Documents (Nota Pembelaan)</h4>
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
                                <a href="#">Upload Documents</a>
                            </li>
                        </ul>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <form method="post" id="uploadPDF" action="<?= base_url('Documents/upload'); ?>" enctype="multipart/form-data">
                                        <?php if (!empty($error)){ echo $error;}?>
                                        <div class="page-title">Preview</div>
                                        <div class="row" style="padding-left: 10px">
                                            <canvas class="col-md-2" id="pdf-preview" style="border: 2px solid #E5E5E5;border-style: dashed; padding-left: 12px;"></canvas>
                                            <div class="col-md-10">

                                                <input type="file" name="file" class="dropify" id="pdf-file" accept="application/pdf" style="position: relative;height: 150px !important">
                                                <br>
                                                <input type="submit" class="btn btn-primary float-right" name="upload" value="Upload and Summarize">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="summary" style="display: none;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="page-title">Result</div>
                                        <div id="kompresi">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <h3>Tingkat Kompresi Ringkasan :</h3>
                                                </div>
                                                <div class="col-md-2">
                                                    <select name="kompresiSelect" id="kompresiSelect" class="form-control" style="padding: .3rem 1rem !important">
                                                      <option value="0.25">25 %</option>
                                                      <option value="0.5">50 %</option>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button id="download" class="btn btn-default" data-id="0" data-kompresi="0.5"> Download </button>
            </div>
        </div>
    </div>
    <div class="modal fade" id="detailUpload" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header no-bd">
                    <h5 class="modal-title">
                        <span class="fw-mediumbold">
                        Add New</span> 
                        <span class="fw-light">
                            User
                        </span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" id="updateUpload" action="<?= base_url('Documents/updateDocuments'); ?>" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <p class="small" style="color:#f25961">*Make Sure The Data Below is Correct</p>
                                <input name="id" id="id" type="hidden">
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group form-group-default">
                                    <label>No Perkara</label>
                                    <input name="no_perkara" id="no_perkara" type="text" class="form-control" placeholder="*required">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group form-group-default">
                                    <label>Terdakwa</label>
                                    <input name="terdakwa" id="terdakwa" type="text" class="form-control" placeholder="*required">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group form-group-default">
                                    <label>Pengadilan</label>
                                    <input name="pengadilan" id="pengadilan" type="text" class="form-control" placeholder="*required">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer no-bd">
                        <input type="submit" class="btn btn-primary float-right" name="submit" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
<?php $this->load->view('elements/footer') ?>

<script type="text/javascript">

    var kompresi  = 0;
    var id = 0;

    var _PDF_DOC;
    var link;

    // PDF.JS renders PDF in a <canvas> element
    var _CANVAS = document.querySelector('#pdf-preview');

    // will hold object url of chosen PDF
    var _OBJECT_URL;

    (function(a){a.createModal=function(b){defaults={title:"",message:"Your Message Goes Here!",closeButton:false,scrollable:false};var b=a.extend({},defaults,b);var c=(b.scrollable===true)?'style="max-height: 420px;overflow-y: auto;"':"";html='<div class="modal fade" id="myModal">';html+='<div class="modal-dialog">';html+='<div class="modal-content">';html+='<div class="modal-header">';if(b.title.length>0){html+='<h4 class="modal-title">'+b.title+"</h4>"}html+="</div>";html+='<div class="modal-body" '+c+">";html+=b.message;html+="</div>";html+='<div class="modal-footer">';if(b.closeButton===true){html+='<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>'}html+="</div>";html+="</div>";html+="</div>";html+="</div>";a("body").prepend(html);a("#myModal").modal().on("hidden.bs.modal",function(){a(this).remove()})}})(jQuery);


    $(document).ready(function(){
        $('.dropify').dropify({
            messages : {
                default : "Drag or Drop Your Pdf to Upload",
                replace : 'Replace',
                remove : 'Remove',
                error : 'error'
            }
        });
        $('form#uploadPDF').submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
            var url = $(this).attr('action');
            if ($('#pdf-file')[0].files[0] != null){
                swal({
                    title: 'Pilih Tingkat Kompresi Panjang Ringkasan',
                    closeOnClickOutside: false,
                    buttons: {
                        one: {
                          text: "25 %",
                          value: 1
                      },
                      two: {
                          text: "50 %",
                          value: 2
                      },
                      three: {
                          text: "75 %",
                          value: 3
                      }
                  }
              }).then( value => {
                switch (value) {                  
                    case 1:
                    kompresi = 0.25;
                    break;
                    case 2:
                    kompresi = 0.50;
                    break;
                    case 3:
                    kompresi = 0.75;
                    break;
                };
                formData.append("kompresi", kompresi);
            }).then( function () {     
                $.ajax({
                    url: url,
                    type: 'post',
                    data: formData,
                    beforeSend: function() {
                            // setting a timeout
                            $("#loading").css('display','block');
                        },
                        success: function(data) {
                            $("#loading").css('display','none');
                            $("#summary").css('display','inline');

                            var result = $.parseJSON(data);
                            id = result.data.id;
                            var no_perkara = result.data.no_perkara;
                            var terdakwa = result.data.terdakwa;
                            var pengadilan = result.data.pengadilan;

                            $('#detailUpload').modal('show');
                            $('#id').val(id);
                            $('#terdakwa').val(terdakwa);
                            $('#no_perkara').val(no_perkara);
                            $('#pengadilan').val(pengadilan);

                            $("#download").data("kompresi", kompresi);
                            $("#download").data("id", id);

                            showSummary(id,kompresi);
                            showDocuments(id);

                            $("#kompresiSelect").val(kompresi);
                            // $("html,#summary").animate({ scrollTop:$('#summary').prop("scrollHeight"))}, "slow");
                            // var $content = $("#summary");
                            // $content[0].scrollTop = $content[0].scrollHeight;
                            $('html, body').animate({
                                scrollTop: $("#summary").offset().top
                            }, 2000);

                        },
                        cache : false,
                        contentType : false,
                        processData : false,
                        error: function(data) {
                            swal(data.responseText);
                        }         
                    });
                });
            }else{
                swal({
                    icon: 'error',
                    title: 'Oops',
                    text: 'No File Uploaded',
                    timer: 2000,
                    buttons: false
                });
            };
        });

        $('form#updateUpload').submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
            var url = $(this).attr('action');
            $.ajax({
                url: url,
                type: 'post',
                data: formData,
                success: function(data) {
                    $('#detailUpload').modal('hide');

                    swal({
                        title: "Success",
                        type:"success",
                        text: "Your data has been added",
                        timer: 2000,
                        showConfirmButton: false
                    }); 
                },
                cache : false,
                contentType : false,
                processData : false,
                error: function(data) {
                    swal(data.responseText);
                }         
            });
        });

        $( '#kompresiSelect').on( 'change', function (i) {
            var kompresi = $(this).val();

            $("#download").data("kompresi", kompresi);
            $("#download").data("id", id);

            showSummary(id,kompresi);
        });

        $('.readmore').on( 'click', function (i) {
            $(this).next('.hidden').slideDown(750);
        });

        $('#download').on( 'click', function (i) {
            var id1 = $(this).data('id');
            var kompresi1 = $(this).data('kompresi');

            window.location.href = "<?= base_url('Documents/downloadSummary/')?>"+id1+'/'+kompresi1;

           // download(id1,kompresi1);

        });



        $('#pdf-preview').on('click',function(){
            var pdf_link = _OBJECT_URL;   
            var iframe = '<object type="application/pdf" data="'+pdf_link+'" width="100%" height="500"></object>'
            $.createModal({
                title:'Preview',
                message: iframe,
                closeButton:true,
                scrollable:false
            });
            return false;        
        });

    });

function showSummary(id,kompresi){
    $.ajax({
        url: "<?= base_url('Documents/getSummaryDocuments'); ?>",
        type: 'post',
        data: {id: id, kompresi: kompresi},
        success: function(data) {
                // alert(data.length);
                var result  = $.parseJSON(data);                    
                // alert(JSON.stringify(result));
                $('#afterSummary').empty();
                for (var i=0;i<result.data.length;++i)
                {
                    $('#afterSummary').append(result.data[i].sentence+".<br>");
                   // alert(data[i].sentence);
               }
           }
       })
}

function download(id,kompresi){
    $.ajax({
        url: "<?= base_url('Documents/downloadSummary'); ?>",
        type: 'post',
        data: {id: id, kompresi: kompresi}
       })
}

function showDocuments(id){
    $.ajax({
        url: "<?= base_url('Documents/getSentenceDocuments'); ?>",
        type: 'post',
        data: {id: id},
        success: function(data) {
                // var json = $.parseJSON(data);
                // alert(data);
                var result  = $.parseJSON(data);
                // alert(JSON.stringify(result));
                for (var i=0;i<result.data.length;++i)
                {
                    $('#beforeSummary').append(result.data[i].sentence+".<br>");
                    // alert(data[i].sentence);
                }
                // showReadMore();
            }
        })
}

    // function showReadMore(){
    //     var text = $('#beforeSummary').text();
    //     var max  = 250;
    //     if(text.length > max){
    //         var begin = text.substr(0,max);
    //         var end = text.substr(max);

    //         $('#beforeSummary').html(begin)
    //             .append($('<a class="readmore"/>').attr('href', '#').html('read more...'))
    //             .append($('<div class="hidden" />').html(end));       
    //     }
    //  }

    // load the PDF
    function showPDF(pdf_url) {
        PDFJS.getDocument({ url: pdf_url }).then(function(pdf_doc) {
            _PDF_DOC = pdf_doc;

            // alert(pdf_url);

            // show the first page of PDF
            showPage(1);

            // destroy previous object url
            // URL.revokeObjectURL(_OBJECT_URL);
        }).catch(function(error) {
            // error reason
            alert(error.message);
        });;
    }


    // show page of PDF
    function showPage(page_no) {
        _PDF_DOC.getPage(page_no).then(function(page) {
            // set the scale of viewport
            var scale_required = _CANVAS.width / page.getViewport(1).width;

            // get viewport of the page at required scale
            var viewport = page.getViewport(scale_required);

            // set canvas height
            _CANVAS.height = viewport.height;

            var renderContext = {
                canvasContext: _CANVAS.getContext('2d'),
                viewport: viewport
            };
            
            // render the page contents in the canvas
            page.render(renderContext).then(function() {
                document.querySelector("#pdf-preview").style.display = 'inline-block';
            });
        });
    }

    /* showing upload file dialog */
    // document.querySelector("#upload-dialog").addEventListener('click', function() {
    //     document.querySelector("#pdf-file").click();
    // });

    /* when users selects a file */
    document.querySelector("#pdf-file").addEventListener('change', function() {
        // user selected PDF
        var file = this.files[0];

        // allowed MIME types
        var mime_types = [ 'application/pdf' ];
        
        // validate whether PDF
        if(mime_types.indexOf(file.type) == -1) {
            alert('Error : Incorrect file type');
            return;
        }

        // validate file size
        if(file.size > 10*1024*1024) {
            alert('Error : Exceeded size 10MB');
            return;
        }
        if(_OBJECT_URL){
            URL.revokeObjectURL(_OBJECT_URL);
        }

        // validation is successful

        // hide upload dialog
        // document.querySelector("#upload-dialog").style.display = 'none';
        
        // show the PDF preview loader
        // object url of PDF 
        _OBJECT_URL = URL.createObjectURL(file)

        // send the object url of the pdf to the PDF preview function
        showPDF(_OBJECT_URL);
    });

    // function getURL(){
    //     document.querySelector("#pdf-file").addEventListener('change', function() {
    //     // user selected PDF
    //     var file = this.files[0];
    //     _OBJECT_URL = URL.createObjectURL(file);
    //     return(_OBJECT_URL);
    //     });
    // } 



</script>

</html>

