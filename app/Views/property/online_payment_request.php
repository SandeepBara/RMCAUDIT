<?= $this->include('layout_vertical/header'); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content" id="divIdPDF">
        <form>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Property Online Request List
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3 pad-btm">Holding No</div>
                        <div class="col-md-3 pad-btm"><?=$holding_no;?></div>
                        <div class="col-md-3 pad-btm">New Holding No</div>
                        <div class="col-md-3 pad-btm"><?=$new_holding_no;?></div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 pad-btm">Address</div>
                        <div class="col-md-9 pad-btm"><?=$prop_address;?></div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 pad-btm">Owner Name</div>
                        <div class="col-md-3 pad-btm"><?=$owner_name;?></div>
                        <div class="col-md-3 pad-btm">Mobile No</div>
                        <div class="col-md-3 pad-btm"><?=$mobile_no;?></div>
                    </div>
                </div>
            </div>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Online Request List</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Payment datetime</th>
                                            <th>From FY/QTR</th>
                                            <th>Upto FY/QTR</th>
                                            <th>Payable Amount</th>
                                            <th>Order Id</th>
                                            <th>Verify Action</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($request_list)) {
                                            foreach ($request_list as $key=>$list) {
                                        ?>
                                                <tr>
                                                    <td><?= $key+1; ?></td>
                                                    <td><?= $list['created_on']; ?></td>
                                                    <td><?= $list['from_fy']."/".$list['from_qtr']; ?></td>
                                                    <td><?= $list['upto_fy']."/".$list['upto_qtr']; ?></td>
                                                    <td><?= $list['payable_amt']; ?></td>
                                                    <td><?= $list['order_id']; ?></td>
                                                    <td>
                                                    <?php if ($list['order_id']!="") { ?>
                                                        <button type="button" id="btn_verify_<?=$list['id'];?>" class="btn btn-primary btn-sm" value="<?=$list['id'];?>" onclick="verifyOrderId('<?=$list['id'];?>', <?=$prop_dtl_id;?>, this.id)">Verify with paymeny Gateway</button>
                                                    <?php } ?>
                                                    </td>
                                                    <td id="order_id_<?=$list['id'];?>"></td>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer'); ?>
<Script>
    const verifyOrderId = (order_id, prop_dtl_id, btn_id) => {
        //alert(order_id+ ", " +prop_dtl_id);
        $.ajax({
            type:"POST",
            url: '<?=base_url();?>/propOnlineRequest/VerifyPaymentIsDone',
            dataType: "json",
            data: {
                "order_id":order_id,
                "prop_dtl_id":prop_dtl_id,
            },
            beforeSend: function() {
                $("#"+btn_id).html("Please Wait...");
            },
            success:function(data){
                if(data.status=="captured"){
                    $("#order_id_"+order_id).addClass("text-success");
                    $("#order_id_"+order_id).html(data.msg);
                } else if(data.status=="failed"){
                    $("#order_id_"+order_id).addClass("text-danger");
                    $("#order_id_"+order_id).html(data.msg);
                }
                $("#"+btn_id).prop('disabled', true);
                $("#"+btn_id).html("Verify with paymeny Gateway");
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $("#"+btn_id).html("Try Again");
            }
        });
    }
</Script>