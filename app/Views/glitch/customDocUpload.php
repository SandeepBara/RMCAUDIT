<?= $this->include('layout_vertical/header'); ?>
<link href="<?= base_url(); ?>/public/assets/plugins/select2/css/select2.min.css" rel="stylesheet">
<style type="text/css">
    .error {
        color: red;
    }
    .blink { 
        text-align: center; 
        animation: animate  
            3s linear infinite; 
    } 

    @keyframes animate { 
        0% { 
            opacity: 0; 
        } 
        50% { 
            opacity: 0.7; 
        } 
        100% { 
            opacity: 0; 
        } 
    } 
</style>

<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Self Assessment Form</h3>
            </div>
            <div class="panel-body">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#certificateModel">Certificate List</button>
                <div id="certificateModel" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: #25476a;">
                                <button type="button" class="close" style="color: white; font-size:30px;" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title" style="color: white;">Custom Document</h4>
                            </div>                                
                            <form action="" id="customDocUpload">
                                <div class="modal-body">
                                    <div class="row">
                                        <label class="col-md-3">Name <span class="text-danger">*</span></label>
                                        <div class="col-md-3 pad-btm">
                                            <input type="text" id="name" name="name" class="form-control" value="" />
                                        </div>
                                        <label class="col-md-3">Path <span class="text-danger">*</span></label>
                                        <div class="col-md-3 pad-btm">
                                            <input type="text" id="path" name="path" class="form-control" value="" />
                                        </div>
                                        <label class="col-md-3">File <span class="text-danger">*</span></label>
                                        <div class="col-md-3 pad-btm">
                                            <input type="file" id="file" name="file" class="form-control" />
                                        </div>
                                    </div>                                            
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="summit" class="btn btn-primary">Save</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer'); ?>
<script src="<?= base_url(); ?>/public/assets/js/jquery.validate.js"></script>
<script>
	$(document).ready(function () {
        $("#customDocUpload").validate({
            rules: {
                "name": {
                    required: true,
                },
                "module": {
                    required: true,
                },
                "path": {
                    required: true,
                },
                "file": {
                    required: true,
                },
            },
            submitHandler: function (form) {
                submitCustomDocForm();
            }
        });
    });

    function submitCustomDocForm() {
        let formData = new FormData(document.getElementById("customDocUpload")); // Correct way
        $("#loadingDiv").show();

        $.ajax({
            url: "<?= base_url('glitch/uploadCustomDoc') ?>",
            method: "POST",
            data: formData,
            dataType:"json",
            processData: false, // Important for FormData
            contentType: false, // Important for FormData
            success: function (response) {
                $("#loadingDiv").hide();
                console.log("response:", response);
                if (response?.status) {
                    modelInfo("success");
                    document.getElementById("customDocUpload").reset();
                } else {
                    modelInfo("error");
                }
            },
            error: function (error) {
                $("#loadingDiv").hide();
                console.log("error:", error);
            }
        });
    }

</script>