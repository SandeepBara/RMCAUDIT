<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/ladda.min.css" rel="stylesheet">
<style>
    .btn-glitch{
        /*background: #40ab1d;*/
        color: #fff;
        border-radius: 3px;
        text-wrap: nowrap;
        font-size: 12px !important;
        /*padding: 5px 4px;*/
        /*border: 1px solid #1adb17;*/
    }
</style>
<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">
                    <!--Page Title-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <div id="page-title">
                       <!-- <h1 class="page-header text-overflow">Department List</h1>//-->
                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End page title-->

                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
                    <li><a href="#"><i class="demo-pli-home"></i></a></li>
                    <li><a href="#">Property Correction</a></li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-dark">
                                <div class="panel-heading">
                                    <h5 class="panel-title">Property Correction Details</h5>
                                </div>
                                <div class="panel-body">
                                    <div class ="row">
                                        <div class="col-md-12">
                                            <form class="form-horizontal" method="post" action="">
												<div class="form-group">
                                                    <div class="col-md-3">
														<label class="control-label" for="from_date"><b>Holding No.</b> <span class="text-danger">*</span></label>
														<input type="text" id="holding_no" name="holding_no" value="<?php echo isset($holding_no)?$holding_no:'';?>" class="form-control" >
													</div>
													<div class="col-md-3">
														<label class="control-label" for="">&nbsp;</label>
														<button class="btn btn-primary btn-block" id="btn_search" value="true" name="btn_search" type="submit">Search</button>
													</div>
												</div>
                                            </form>
                                        </div>
                                    </div>
                                    <?php if(!empty($propertycorrection)){?>
                                    <div class="row">
                                        <div class="text-center text-danger text-bold">Holding No: <?php echo isset($holding_no)?$holding_no:'NA';?> 
                                        </div>
										<div class="panel panel-dark panel-bordered">
                                            <div class="panel-heading">
                                                <h5 class="panel-title">Correction Detail</h5>
                                            </div>
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table id="property_update" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                        <?php  include 'ulbdetail.php'  ?>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>

                                </div>
                                <div class="panel panel-dark panel-bordered">
                                    <div class="panel-heading">
                                        <h5 class="panel-title">Floor Correction Detail</h5>
                                    </div>
                                    <div class="panel-body">
                                    <div class="table-responsive">
                                        <table id="property_floorupdate" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <?php if(!empty($extrafloor)){
                                        include 'floors.php';
                                     } ?>

                                        </table>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--===================================================-->
                <!--End page content-->
            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/spin.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/ladda.min.js"></script>
<?= $this->include('layout_vertical/footer');?>
<script>

    function block_button(button)
    {
        button.disabled = true;
    }
    // var l = Ladda.create( document.querySelector( '.anime' ) );
    // l.start();
    </script>

<script>

    function correction(A,type){
        if(type=='FA') {
            var $l = Ladda.create( document.querySelector( '.anime' ) );
            dataitem = {'floorid': A.data('floorid'), 'vid': vid = A.data('vid')};
            propID = A.data('propid');
            $l.start();
            $.post("./glitch/correction", {propID: propID, dataitem: dataitem, 'type': type})
                .done(function (data) {
                    data = JSON.parse(data)
                    if (data.status) {
                        A.attr('disabled',true);
                        $l.stop();
                        modelInfo(data.message);
                        $('#btn_search').click();
                        A.remove();
                    }
                }).always(function (data) {
                    $l.stop();
                });
            }
        if(type=='WH') {
         //   var $l = Ladda.create( A[0] );
            //var $l = Ladda.bind($(A));
            propID = A.data('propid');
           // dataitem = {'floorid': A.data('floorid'), 'vid': vid = A.data('vid')};
            dataitem = {'propID': A.data('propid')};
         //  $l.Ladda('start');
            $.post("./glitch/correction", {propID: propID, dataitem: dataitem, 'type': type})
                .done(function (data) {
                    data = JSON.parse(data)
                    if (data.status) {
                        A.attr('disabled',true);
                       // $l.stop();
                        modelInfo(data.message);
                       // $('#btn_search').click();
                       // A.remove();
                    }
                }).always(function (data) {
                    $l.stop();
                });
            }
        if(type=='ST') {
         //   var $l = Ladda.create( A[0] );
            //var $l = Ladda.bind($(A));
            propID = A.data('propid');
           // dataitem = {'floorid': A.data('floorid'), 'vid': vid = A.data('vid')};
            dataitem = {'propID': A.data('propid')};
         //  $l.Ladda('start');
            $.post("./glitch/correction", {propID: propID, dataitem: dataitem, 'type': type})
                .done(function (data) {
                    data = JSON.parse(data)
                    if (data.status) {
                        A.attr('disabled',true);
                       // $l.stop();
                        modelInfo(data.message);
                       // $('#btn_search').click();
                       // A.remove();
                    }
                }).always(function (data) {
                    $l.stop();
                });
            }
    }
</script>