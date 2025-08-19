<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">

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
            <li><a href="#">Meter Reading Correction</a></li>
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
                        <h5 class="panel-title">Meter Reading Correction Details</h5>
                    </div>

                    <div class="panel-body">
                        <div class ="row">
                            <div class="col-md-12">
                                <form class="form-horizontal" method="post" action="">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label class="control-label" for="from_date"><b>From Date.</b> <span class="text-danger">*</span></label>
                                            <input type="date" id="from_date" name="from_date" value="" required class="form-control" >
                                        </div>
                                        <div class="col-md-3">
                                            <label class="control-label" for="consumer_no"><b>Consumer No.</b> <span class="text-danger">*</span></label>
                                            <input type="text" id="consumer_no" name="consumer_no" value="<?= $owner_details->consumer_no ??"";?>" class="form-control" >
                                        </div>
                                        <div class="col-md-3">
                                            <label class="control-label" for="">&nbsp;</label>
                                            <button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!--  OWNER BASIC DETAILS START HERE -->


                        <?php if(isset($owner_details)){
                            ?>

                            <div class="row">
                                <div class="text-center text-danger text-bold">Consumer No: <?= $owner_details->consumer_no ?? ""; ?>
                                </div>
                                <div class="panel panel-dark panel-bordered">
                                    <div class="panel-heading">
                                        <h5 class="panel-title">Details Of Consumer</h5>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                <thead>
                                                <tr>
                                                    <th>Category</th>
                                                    <th>Owner Name</th>
                                                    <th>Property Type</th>
                                                    <th>Mobile No</th>
                                                </tr>
                                                </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><?= $owner_details->category ?? 'NA' ?></td>
                                                            <td><?= $owner_details->applicant_name ?? 'NA' ?></td>
                                                            <td><?= $owner_details->property_type ?? 'NA' ?></td>
                                                            <td><?= $owner_details->mobile_no?? 'NA' ?></td>
                                                        </tr>

                                                    </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <!--  OWNER BASIC END START HERE -->

                        <!--DEMAND HISTORY START HERE-->
                        <?php if(isset($owner_details)){?>
                            <div class="row">
                                <div class="panel panel-dark panel-bordered">
                                    <div class="panel-heading">
                                        <h5 class="panel-title text-center">Demand History</h5>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>From Date</th>
                                                    <th>Upto Date</th>
                                                    <th>Connection Type</th>
                                                    <th>Amount</th>
                                                    <th>Penalty</th>
                                                    <th>Demands</th>
                                                    <th>Payment Status</th>
                                                    <th>Meter Reading</th>
                                                    <th>Meter No</th>
                                                </tr>
                                                </thead>
                                                <form class="form-horizontal" method="post" action="">
                                                    <tbody>

                                                    <?php foreach ($demand_history as $demand): ?>
                                                        <tr>
                                                            <td>
                                                                <?php if ($demand['paid_status'] == 1): ?>
                                                                    <input type="checkbox" name="demand_id[]" value="<?= $demand['id'] ?? 1; ?>" disabled />
                                                                <?php else: ?>
                                                                    <input type="checkbox" name="demand_id[]" value="<?= $demand['id'] ?? 1; ?>" />
                                                                <?php endif; ?>
                                                            </td>
                                                            <td><?= $demand['demand_from'] ?? 'NA'; ?></td>
                                                            <td><?= $demand['demand_upto'] ?? 'NA'; ?></td>
                                                            <td><?= $demand['connection_type'] ?? 'NA'; ?></td>
                                                            <td><?= $demand['amount'] ?? 'NA'; ?></td>
                                                            <td><?= $demand['penalty'] ?? 'NA'; ?></td>
                                                            <td><?= $demand['balance_amount'] ?? 'NA'; ?></td>
                                                            <td><?= $demand['paid_status'] ?? 'NA'; ?></td>
                                                            <td><?= $demand['current_meter_reading'] ?? 'NA'; ?></td>
                                                            <td><?= $demand['meter_no'] ?? 'NA' ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    </tbody>

                                                </form>



                                            </table>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-md-4">
                                                <label class="control-label" align="center" for="" style="color:#f00; font-weight:bold; text-align:center; font-size:16px;">* Please verify details before click on the button</label>
                                                <button class="btn btn-primary btn-block" id="btn_verify" name="btn_verify" type="submit" >Verify & Map</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        <?php } ?>

                        <!--DEMAND HISTORY END HERE-->
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
<?= $this->include('layout_vertical/footer');?>
<script>
    function block_button(button)
    {
        button.disabled = true;
    }
</script>
<!--DataTables [ OPTIONAL ]-->
