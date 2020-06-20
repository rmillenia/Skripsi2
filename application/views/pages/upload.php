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

    // PDF.JS renders PDF in a <canvas> element
    var _CANVAS = document.querySelector('#pdf-preview');

    // will hold object url of chosen PDF
    var _OBJECT_URL;

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
                            id = $.parseJSON(data);
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

        $( '#kompresiSelect').on( 'change', function (i) {
            var kompresi = $(this).val();

            showSummary(id,kompresi);
        });

        $('.readmore').on( 'click', function (i) {
            $(this).next('.hidden').slideDown(750);
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

            // show the first page of PDF
            showPage(1);

            // destroy previous object url
            URL.revokeObjectURL(_OBJECT_URL);
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

        // validation is successful

        // hide upload dialog
        // document.querySelector("#upload-dialog").style.display = 'none';
        
        // show the PDF preview loader
        // object url of PDF 
        _OBJECT_URL = URL.createObjectURL(file)

        // send the object url of the pdf to the PDF preview function
        showPDF(_OBJECT_URL);
    });


</script>

</html>

