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
                    <li><a href="#">Online Payment Correction</a></li>
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
                                    <h5 class="panel-title">Payment Details</h5>
                                </div>
                                <div class="panel-body">
                                    <div class ="row">
                                        <div class="col-md-12">
<!--                                            <form class="form-horizontal" method="post" action="">-->
												<div class="form-group">
                                                    <div class="col-md-3">
														<label class="control-label" for="from_date"><b>Order ID</b> <span class="text-danger">*</span></label>
														<input type="text" id="order_id" name="order_id" required value="<?php echo isset($order_id)?$order_id:'';?>" class="form-control" >
													</div>
                                                    <div class="col-md-3">
                                                        <label class="control-label" for="payment_type"><b>Payment Type</b> <span class="text-danger">*</span></label>
                                                        <select name="payment_type" id="payment_type" class="form-control">
                                                            <option value="Property" <?php (isset($payment_type)&& $payment_type)=="Property"?'selected':'';?>>Property</option>
                                                            <option value="Water" <?php (isset($payment_type)&& $payment_type)=="Water"?'selected':'';?>>Water</option>
                                                            <option value="Trade" <?php (isset($payment_type)&& $payment_type)=="Trade"?'selected':'';?>>Trade</option>
                                                        </select>
                                                    </div>
													<div class="col-md-3">
														<label class="control-label" for="">&nbsp;</label>
														<button class="btn btn-primary btn-block" id="btn_search" value="true" name="btn_search" type="submit" onclick="paymentdetails();">Search</button>
													</div>
												</div>
<!--                                            </form>-->
                                        </div>
                                    </div>

                                </div>
                                </div>
                                <div class="panel panel-dark panel-bordered" id="html">
                                    <div class="panel-heading">
                                    </div>
                                    <div class="panel-body">
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

    paymentdetails();
    function paymentdetails(){
        var order_id= $('#order_id').val();
        var payment_type=$('#payment_type').val();
        if(order_id!==''){
            $('#html').html('<div class="text-center">Loading...</div>');
            $.post("/glitch/paymentfix", {order_id: order_id, 'payment_type': payment_type})
                .done(function (data,status, xhr) {
                    console.log(xhr.getResponseHeader("content-type"));
                    console.log($.isEmptyObject(data));
                    data=JSON.parse(data);
                    if(data.status==false)
                    {
                        modelInfo(data.message);
                        $('#html').html('');
                        return true;
                    }else {
                        $('#html').html(data.data);
                    }
                }).always(function (data) {
            });
        }
    }
    function updatepaymentdetails(A) {
        var $l = Ladda.create( document.querySelector( '.btn-glitch' ) );
        var order_id = A.data('order_id');
        var amt = $('input[name=payable_amt]').val();
        var type = $('input[name=payable_type]').val();
        var dataitem = {'order_id':order_id,'total_payable_amount':amt};
        $l.start();
        $.post("/glitch/fixpayment", { dataitem: dataitem, 'type': type})
            .done(function (data) {
                data = JSON.parse(data)
                if (data.status) {
                    A.attr('disabled',true);
                    $l.stop();
                    modelInfo(data.message);
                    // $('#btn_search').click();
                    A.remove();
                    paymentdetails();
                }
            }).always(function (data) {
            $l.stop();
        });
    }
</script>