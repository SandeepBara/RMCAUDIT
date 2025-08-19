<?= $this->include("layout_vertical/header"); ?>
<style type="text/css">
    .error {

        color: red;
    }
</style>
<link href="<?= base_url(); ?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">

<!--CONTENT CONTAINER-->
<div id="content-container">

    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Water</a></li>
            <li><a href="<?php echo base_url('WaterViewConsumerDetails/index/' . md5($consumer_dtls['id'])); ?>">Water
                    Connection Details</a></li>
            <li class="active">Payment</li>
        </ol>
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">

        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <div class="col-sm-6">
                    <h3 class="panel-title"> Water Connection Details View</h3>
                </div>
                <!-- <div class="col-sm-6" style="text-align: right; ">
                    <a href="<?php echo base_url('WaterViewConsumerDetails/index/' . md5($consumer_dtls['id'])); ?>" class="btn btn-info">Back</a>
                </div> -->
            </div>
            <div class="panel-body">

                <div class="row">
                    <label class="col-md-2 bolder">Consumer No.</label>
                    <div class="col-md-3 pad-btm">
                        <?php echo $consumer_dtls['consumer_no']; ?>
                    </div>

                    <label class="col-md-2 bolder">Category </label>
                    <div class="col-md-3 pad-btm">
                        <?php echo $consumer_dtls['category']; ?>
                    </div>

                </div>

                <div class="row">
                    <label class="col-md-2 bolder">Type of Connection </label>
                    <div class="col-md-3 pad-btm">
                        <?php echo $consumer_dtls['connection_type']; ?>
                    </div>
                    <label class="col-md-2 bolder">Connection Through </label>
                    <div class="col-md-3 pad-btm">
                        <?php echo $consumer_dtls['connection_through']; ?>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-2 bolder">Property Type </label>
                    <div class="col-md-3 pad-btm">
                        <?php echo $consumer_dtls['property_type']; ?>
                    </div>
                    <label class="col-md-2 bolder">Pipeline Type </label>
                    <div class="col-md-3 pad-btm">
                        <?php echo $consumer_dtls['pipeline_type']; ?>
                    </div>
                </div>


            </div>
        </div>

        <div style="clear: both;"></div>
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title"> Due Details</h3>
            </div>
            <div class="panel-body">
                <?php
                if ($demand_list) {
                    ?>
                    <table class="table table-responsive" id="demo_dt_basic">
                        <thead>
                            <tr>
                                <th>Demand From</th>
                                <th>Demand Upto</th>
                                <th>Connection Type</th>
                                <th>Amount</th>
                                <th>Penalty</th>
                                <th>Total Demand</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            foreach ($demand_list as $val) {
                                // echo $val['demand_upto'];
                                // echo  $month=date('F',strtotime($val['demand_upto']));
                                ?>
                                <tr>
                                     <td><?php echo $val['demand_from']; ?></td>
                                    <td><?php echo $val['demand_upto']; ?></td>
                                    <td><?php echo $val['connection_type'] ?></td>
                                    <td><?php echo $val['amount'] ?></td>
                                    <td><?php echo $val['penalty'] ?></td>
                                    <td><?php echo $val['balance_amount'] ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>

                    <?php

                } else {
                    ?>
                    <p style="color: green; font-size: 15px; text-align: center;">No Dues!!!</p>
                    <?php
                }
                ?>
            </div>
        </div>


        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title"> Proceed Payment </h3>
            </div>
            <div class="panel-body">
                <?php

                if (sizeof($demand_list) > 0) {
                    ?>
                    <form method="post" id="myform">
                        <table class="table table-responsive table-bordered">
                            <input type="hidden" name="consumer_id" id="consumer_id"
                                value="<?php echo $consumer_dtls['id']; ?>">
                            <!-- <input type="hidden" name="from_month" id="from_month" value="<?php echo $demand_list[0]["demand_from"]; ?>"> -->
                            <?php if (isset($connection_dtls['connection_type'])) {


                                if ($connection_dtls['connection_type'] != 3 && !isset($from_month)) {
                                    ?>
                                    <input type="hidden" name="from_month" id="from_month"
                                        value="<?php echo $demand_list[0]["demand_from"]; ?>">
                                    <?php
                                } else {
                                    ?>
                                    <?php
                                }

                            } elseif (!isset($connection_dtls['connection_type'])) {
                                ?>
                                <input type="hidden" name="from_month" id="from_month"
                                    value="<?php echo $demand_list[0]["demand_from"]; ?>">
                                <?php
                            }
                            ?>
                            <input type="hidden" name="ward_mstr_id" id="ward_mstr_id"
                                value="<?php echo $consumer_dtls['ward_mstr_id']; ?>">
                            <input type="hidden" name="consumer_no" id="consumer_no"
                                value="<?php echo $consumer_dtls['consumer_no']; ?>">
                            <input type="hidden" name="demand_id" id="demand_id" value="" />
                            <tr>
                                <?php
                                if (isset($connection_dtls['connection_type']) && $connection_dtls['connection_type'] == 3 || isset($from_month)) {
                                    ?>
                                    <td>Select Month From</td>

                                    <td>
                                        <select name="from_month" id="from_month" class="form-control"
                                            onchange="getAmountPayable()" required disabled>
                                            <?php
                                            $demand_list1 = $connection_dtls['connection_type'] == 3 ? (isset($from_month) && !empty($from_month) ? $from_month : $demand_list) : $from_month;
                                            $demandList = array_reverse($demand_list1);
                                            if ($demandList) {
                                                foreach ($demandList as $val) {
                                                    ?>
                                                    <option value="<?php echo $val['demand_from']; ?>">
                                                        <?php echo date("M/Y", strtotime($val['demand_from'])); ?>
                                                    </option>
                                                <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <?php
                                }
                                ?>
                                <td>Select
                                    Month<?= isset($connection_dtls['connection_type']) && ($connection_dtls['connection_type'] == 3 || isset($from_month)) ? " Upto" : '' ?>
                                </td>
                                <td>
                                    <!-- <select name="month" id="month" class="form-control" onchange="getAmountPayable()" required <?= isset($from_month) ? "disabled" : ''; ?>> -->
                                    <select name="month" id="month" class="form-control" onchange="getAmountPayable()"
                                        required <?= isset($from_month) ? "" : ''; ?>>
                                        <?php
                                        $demand_list1 = array_reverse($demand_list);
                                        // $demand_list1=$demand_list;
                                        if ($demand_list1) {
                                            foreach ($demand_list1 as $val) {
                                                ?>
                                                <option value="<?php echo $val['demand_from']; ?>">
                                                    <?php echo date("M/Y", strtotime($val['demand_upto'])); ?>
                                                </option>
                                            <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>Amount</td>
                                <td><input type="text" name="amount" id="amount" class="form-control" readonly="readonly"
                                        required /></td>

                            </tr>

                            <tr>
                                <td>Penalty</td>
                                <td><input type="text" name="penalty" id="penalty" class="form-control" value="" readonly
                                        required /></td>
                                <td>Other Penalty</td>
                                <td><input type="text" name="other_penalty" id="other_penalty" class="form-control" value=""
                                        readonly required /></td>
                            </tr>
                            <tr>

                                <td>Rebate</td>
                                <td><input type="text" name="rebate" id="rebate" class="form-control" value="0.00" readonly
                                        required /></td>
                                <td>Advance</td>
                                <td><input type="text" name="advance" id="advance" class="form-control" value="0.00"
                                        readonly required /></td>
                            </tr>
                            <tr>
                                <td><span class="remain_advance_block">Remain Advance( in Rs.)</span></td>
                                <td><span class="remain_advance_block text-warning" id="remain_advance"
                                        style="font-weight: bold; font-size:17px;"></span></td>
                                <td>Payable Amount( in Rs.)</td>
                                <td><span id="payable_amount"
                                        style="color: green; font-weight: bold; font-size:17px;"></span></td>
                            </tr>
                            <tr>
                                <td>Payment Mode</td>
                                <td>
                                    <select name="payment_mode" id="payment_mode" class="form-control"
                                        onchange="show_hide_cheque_details(this.value)" required>
                                        <option value="">Select</option>
                                        <option value="CASH">CASH</option>
                                        <option value="CHEQUE">CHEQUE</option>
                                        <option value="DD">DD</option>
                                        <option value="NEFT">NEFT</option>
                                        <option value="RTGS">RTGS</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="chq_dtls1" style="display: none;">
                                <input type="hidden" name="demand_id" id="demand_id">
                                <input type="hidden" name="penalty_installment_val" id="penalty_installment_val" value="0">
                                <td>Cheque/DD No.</td>
                                <td><input type="text" name="cheque_no" id="cheque_no" class="form-control"
                                        onkeypress="return isAlphaNum(event);" placeholder="Enter Cheque No."></td>

                                <td>Cheque/DD Date</td>
                                <td><input type="date" name="cheque_date" id="cheque_date" class="form-control"></td>

                            </tr>
                            <tr id="chq_dtls2" style="display: none;">

                            </tr>
                            <tr id="chq_dtls3" style="display: none;">
                                <td>Bank Name</td>
                                <td><input type="text" name="bank_name" id="bank_name" class="form-control"
                                        onkeypress="return isAlpha(event);" placeholder="Enter Bank Name"></td>
                                <td>Branch Name</td>
                                <td><input type="text" name="branch_name" id="branch_name" class="form-control"
                                        onkeypress="return isAlpha(event);" placeholder="Enter Branch Name"></td>

                            </tr>
                            <tr id="chq_dtls4" style="display: none;">
                            </tr>
                            <tr>
                                <td>Remarks</td>
                                <td><textarea class="form-control" name="remarks" id="remarks"></textarea></td>
                            </tr>
                            <tr>
                                <td align="center" colspan="4"><input type="submit" name="pay" id="pay" value="Pay Now"
                                        class="btn btn-primary">
                                </td>
                            </tr>
                        </table>
                    </form>

                <?php
                } else {
                    ?>
                    <p style="color: green; font-size: 15px; text-align: center;">No Dues!!!</p>
                    <?php

                }
                ?>
            </div>


        </div>
    </div>
    <!--End page content-->
    <!--END CONTENT CONTAINER-->
    <?= $this->include("layout_vertical/footer"); ?>
    <script src="<?= base_url(); ?>/public/assets/otherJs/validation.js"></script>
    <script src="<?= base_url(); ?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
    <script src="<?= base_url(); ?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
    <script src="<?= base_url(); ?>/public/assets/datatables/js/jszip.min.js"></script>
    <script src="<?= base_url(); ?>/public/assets/datatables/js/pdfmake.min.js"></script>
    <script src="<?= base_url(); ?>/public/assets/datatables/js/vfs_fonts.js"></script>
    <script src="<?= base_url(); ?>/public/assets/datatables/js/buttons.html5.min.js"></script>
    <script src="<?= base_url(); ?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            //alert("dadsasd");
            $('#demo_dt_basic').DataTable({

                responsive: true,
                ordering: false,
                dom: 'Bfrtip',
                lengthMenu: [
                    [10, 25, 50, -1],
                    ['10 rows', '25 rows', '50 rows', 'Show all']
                ],
                buttons: [
                    'pageLength',
                    {
                        text: 'excel',
                        extend: "excel",
                        title: "Report",
                        footer: { text: '' },
                        exportOptions: { columns: [0, 1, 2, 3, 4] }
                    }, {
                        text: 'pdf',
                        extend: "pdf",
                        title: "Report",
                        download: 'open',
                        footer: { text: '' },
                        exportOptions: { columns: [0, 1, 2, 3, 4] }
                    }]

            });
        });

    </script>

    <script src="<?= base_url(); ?>/public/assets/js/jquery.validate.min.js"></script>


    <script>
        $(document).ready(function () {
            $('#myform').validate({ // initialize the plugin

                rules: {
                    month: {
                        required: true,
                    },
                    amount: {
                        required: true,

                    },
                    payment_mode: {
                        required: true,

                    },
                    remarks: {
                        required: true,

                    },
                    cheque_no: {
                        required: true,

                    },
                    cheque_date: {
                        required: true,

                    },
                    bank_name: {
                        required: true,

                    },
                    branch_name: {
                        required: true,

                    }
                },

                submitHandler: function (form) {
                    if (confirm("Are sure want to make payment?")) {
                        $("#pay").hide();
                        water_pay_now();
                    }
                    else {
                        return false;
                    }
                }
            });
        });

        function water_pay_now() {

            var postdata = {
                demand_from: $("#from_month").val(),
                demand_upto: $("#month").val(),
                consumer_id: $("#consumer_id").val(),
                ward_mstr_id: '<?= $consumer_dtls["ward_mstr_id"]; ?>',
                ward_id: '<?= $consumer_dtls["ward_mstr_id"]; ?>',
                payment_mode: $("#payment_mode").val(),
                payment_from: 'JSK',
                demand_id: $("#demand_id").val(),
                remarks: $("#remarks").val(),

                cheque_no: $("#cheque_no").val(),
                cheque_date: $("#cheque_date").val(),
                bank_name: $("#bank_name").val(),
                branch_name: $("#branch_name").val(),
            };

            // console.log(postdata);
            $.ajax({
                url: "<?php echo base_url("WaterUserChargePayment/water_pay_now"); ?>",
                type: "post",    //request type,
                dataType: 'json',
                data: postdata,
                beforeSend: function () {
                    $("#loadingDiv").show();
                    $("#pay").attr('disabled', true);

                },
                success: function (result) {

                    $("#loadingDiv").hide();
                    $("#pay").attr('disabled', false);
                    console.log(result);
                    if (result.status == true) {
                        window.location.replace(result.url);
                    }
                }
            });
        }

        function getAmountPayable() {
            var demand_upto = $("#month").val();
            var consumer_id = $("#consumer_id").val();
            var from_month = $("#from_month").val();
            //alert(from_month);
            if (from_month > demand_upto) {
                alert("Can't be greater than from_month to uto_month");
                $("#pay").attr('disabled', true);
                $("#month").focus(function () {
                    $("#month").css('text-color', 'red');
                });
                $("#from_month").focus(function () {
                    $("#from_month").css('text-color', 'red');
                });
                return false;
            }

            $.ajax({
                url: "<?php echo base_url("WaterUserChargePayment/getAmountPayable"); ?>",
                type: "post",    //request type,
                dataType: 'json',
                data: { demand_upto: demand_upto, consumer_id: consumer_id <?= isset($connection_dtls['connection_type']) && ($connection_dtls['connection_type'] == 3 || isset($from_month)) ? ",demand_from:from_month" : '' ?> },
                beforeSend: function () {
                    $("#loadingDiv").show();
                    $("#pay").attr('disabled', true);

                },
                success: function (result) {
                    console.log(result);
                    $("#loadingDiv").hide();
                    $("#pay").attr('disabled', false);

                    if (result.status == true) {
                        var data = result.data;

                        $("#demand_id").val(data.demand_id);
                        $("#penalty").val(data.penalty);
                        $("#amount").val(data.amount);
                        $("#rebate").val(data.rebate);
                        $("#advance").val(data.balance);
                        $("#other_penalty").val(data.other_penalty);
                        var payable_amount = (parseInt(data.balance_amount) == 0) ? ((parseFloat(data.balance_amount) - (parseFloat(data.rebate) + parseFloat(data.advance)))) : (parseFloat(data.amount) + parseFloat(data.penalty) + parseFloat(data.other_penalty) - (parseFloat(data.rebate) + parseFloat(data.balance)));
                        if (data.advance == 0) {
                            $(".remain_advance_block").hide();
                        }

                        if (Math.round(payable_amount) >= 0) {
                            $("#payable_amount").text(payable_amount);
                            $(".remain_advance_block").show();
                            $("#remain_advance").text("0.00");
                        }
                        else if (Math.round(payable_amount) < 0) {
                            $("#payable_amount").text("0.00");
                            $(".remain_advance_block").show();
                            $("#remain_advance").text(-1 * parseFloat(payable_amount));
                        }

                        // $("#payable_amount").text(parseFloat(data.balance_amount) - (parseFloat(data.rebate)+parseFloat(data.advance)));
                        // if(parseInt(data.balance_amount)==0)
                        // { 
                        //     $("#payable_amount").text(parseFloat(data.amount) + parseFloat(data.penalty) + parseFloat(data.other_penalty)  - (parseFloat(data.rebate)+parparseFloatseInt(data.advance)));
                        // }
                    }
                }
            });
        }

        getAmountPayable();

        function show_hide_cheque_details(argument) {
            var payment_mode = argument;
            //alert(payment_mode);

            if (payment_mode != 'CASH' && argument != "") {
                $("#chq_dtls1").show();
                $("#chq_dtls2").show();
                $("#chq_dtls3").show();
                $("#chq_dtls4").show();
            }
            else {
                // alert('hhhhhhhhh');
                $("#chq_dtls1").hide();
                $("#chq_dtls2").hide();
                $("#chq_dtls3").hide();
                $("#chq_dtls4").hide();
            }

        }
    </script>