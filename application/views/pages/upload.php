
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
                                    <div class="page-title">Preview</div>
                                    <div class="row">
                                        <canvas class="col-md-2" id="pdf-preview" style="border: 2px solid #E5E5E5;border-style: dashed; padding-left: 12px"></canvas>
                                        <div class="col-md-10">

                                                <input type="file" name="file" class="dropify" id="pdf-file" accept="application/pdf"  data-min-width="700">
                                                <br>
                                                <input type="submit" class="btn btn-primary float-right" name="upload" value="Upload">
                                        </div>
                                    </div>
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

    var _PDF_DOC;

// PDF.JS renders PDF in a <canvas> element
var _CANVAS = document.querySelector('#pdf-preview');

// will hold object url of chosen PDF
var _OBJECT_URL;

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

