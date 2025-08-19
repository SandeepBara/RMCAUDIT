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
    .btn_damand{
        background-color: #36A9AE;
        background-image: linear-gradient(#37ADB2, #329CA0);
        border: 1px solid #2A8387;
        border-radius: 4px;
        box-shadow: rgba(0, 0, 0, 0.12) 0 1px 1px;
        color: #FFFFFF;
        cursor: pointer;
        display: block;
        font-family: -apple-system,".SFNSDisplay-Regular","Helvetica Neue",Helvetica,Arial,sans-serif;
        font-size: 14px;
        line-height: 100%;
        margin: 0;
        outline: 0;
        padding: 6px 10px 6px;
        text-align: center;
        transition: box-shadow .05s ease-in-out,opacity .05s ease-in-out;
        user-select: none;
        -webkit-user-select: none;
        touch-action: manipulation;
    }

        .btn_damand:hover {
            box-shadow: rgba(255, 255, 255, 0.3) 0 0 2px inset, rgba(0, 0, 0, 0.4) 0 1px 2px;
            text-decoration: none;
            transition-duration: .15s, .15s;
        }

        .btn_damand:active {
            box-shadow: rgba(0, 0, 0, 0.15) 0 2px 4px inset, rgba(0, 0, 0, 0.4) 0 1px 1px;
        }

        .btn_damand:disabled {
            cursor: not-allowed;
            opacity: .6;
        }

        .btn_damand:disabled:active {
            pointer-events: none;
        }

        .btn_damand:disabled:hover {
            box-shadow: none;
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
                    <li><a href="#">Demand Correction</a></li>
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
                                    <h5 class="panel-title">Demand Correction Details</h5>
                                </div>
                                <div class="panel-body">
                                    <div class ="row">
                                        <div class="col-md-12">
                                            <form class="form-horizontal" method="post" action="">
												<div class="form-group">
                                                    <div class="col-md-3">
														<label class="control-label" for="from_date"><b>Holding No.</b> <span class="text-danger">*</span></label>
														<input type="text" id="holding_no" name="holding_no" value="<?php echo isset($new_holding_no)?$new_holding_no:'';?>" class="form-control" >
													</div>
													<div class="col-md-3">
														<label class="control-label" for="">&nbsp;</label>
														<button class="btn btn-primary btn-block" id="btn_search" value="true" name="btn_search" type="submit">Search</button>
													</div>
												</div>
                                            </form>
                                        </div>
                                    </div>
                                    <?php if(isset($property) && $property){ ?>
                                    <div class="panel panel-bordered panel-dark">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Tax Details</h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered text-sm">
                                                    <thead class="bg-trans-dark text-dark">
                                                    <tr>
                                                        <th>Sl No.</th>
                                                        <th>ARV</th>
                                                        <th>Effect From</th>
                                                        <th>Holding Tax</th>
                                                        <th>Water Tax</th>
                                                        <th>Conservancy/Latrine Tax</th>
                                                        <th>Education Cess</th>
                                                        <th>Health Cess</th>
                                                        <th>RWH Penalty</th>
                                                        <th>Quarterly Tax</th>
                                                        <th>Status</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    if ($prop_tax_list) {
                                                        $i = 1;
                                                        $qtr_tax = 0;
                                                        $lenght = sizeOf($prop_tax_list);
                                                        foreach ($prop_tax_list as $tax_list) {
                                                            $qtr_tax = $tax_list['holding_tax'] + $tax_list['water_tax'] + $tax_list['latrine_tax'] + $tax_list['education_cess'] + $tax_list['health_cess'] + $tax_list['additional_tax'];
                                                            ?>
                                                            <tr>
                                                                <td><?= $i++; ?></td>
                                                                <td><?= round($tax_list['arv'], 2); ?></td>
                                                                <td><?= $tax_list['qtr']; ?> / <?= $tax_list['fy']; ?></td>
                                                                <td><?= round($tax_list['holding_tax'], 2); ?></td>
                                                                <td><?= round($tax_list['water_tax'], 2); ?></td>
                                                                <td><?= round($tax_list['latrine_tax'], 2); ?></td>
                                                                <td><?= round($tax_list['education_cess'], 2); ?></td>
                                                                <td><?= round($tax_list['health_cess'], 2); ?></td>
                                                                <td><?= round($tax_list['additional_tax'], 2); ?></td>
                                                                <td><?= round($qtr_tax, 2); ?></td>
                                                                <td>
                                                                    <?php
                                                                    if ($i > $lenght) {
                                                                        ?>
                                                                        <span class="text text-success text-bold">Current</span>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <span class="text text-danger text-bold">Old</span>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </td>
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

                                    <div class="panel panel-bordered panel-dark">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Due Detail List</h3>
                                        </div>
                                        <div class="panel-body">
                                            <table class="table table-bordered text-sm">
                                                <thead>
                                                <?php if ($demand_detail) :
                                                    $i = 1;
                                                    $total_due = 0;
                                                    ?>
                                                    <?php foreach ($demand_detail as $tot_demand) :
                                                    $i == 1 ? $first_qtr = $tot_demand['qtr'] : '';
                                                    $i == 1 ? $first_fy = $tot_demand['fy'] : '';

                                                    $total_demand = $tot_demand['balance'];
                                                    $total_due = $total_due + $total_demand;
                                                    $total_quarter = $i;
                                                    $i++;

                                                    ?>
                                                <?php endforeach; ?>
                                                    <tr>
                                                        <td><b style="color:#bf06fb;">Total Dues</b></td>
                                                        <td><strong style="color:#bf06fb;">:</strong></td>
                                                        <td colspan="4">
                                                            <b style="color:#bf06fb;"><?php if ($total_due) {
                                                                    echo $total_due;
                                                                } else {
                                                                    echo "N/A";
                                                                } ?></b>
                                                        </td>
                                                    <tr>
                                                        <td>Dues From</td>
                                                        <td><strong>:</strong></td>
                                                        <td>
                                                            Quarter <?php echo $first_qtr; ?> / Year <?php echo $first_fy; ?>
                                                        </td>
                                                        <td>Dues Upto</td>
                                                        <td><strong>:</strong></td>
                                                        <td>
                                                            Quarter <?php echo $tot_demand['qtr']; ?> / Year <?php echo $tot_demand['fy']; ?> </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total Quarter(s)</td>
                                                        <td><strong>:</strong></td>
                                                        <td colspan="1"><?php
                                                            if ($total_quarter) {
                                                                echo $total_quarter;
                                                            } else {
                                                                echo "N/A";
                                                            } ?></td>
                                                        <td>1% Penalty</td>
                                                        <td></td>
                                                    </tr>
                                                <?php endif; ?>
                                                </tbody>
                                            </table>
                                            <form method="post" action="<?= base_url('glitch/demandcheckerupdate') ?>">
                                                <input type="hidden" name="propid" value="<?= $prop_dtl_id ?>">
                                                <table class="table table-bordered table-sm" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                                                <thead class="bg-trans-dark text-dark">
                                                <th><input type="checkbox" class="form-check"></th>
                                                <th>Sl No.</th>
                                                <th>Quarter/Year</th>
                                                <th>Quarterly Tax</th>
                                                <th>RWH Penalty</th>
                                                <th>Balance Amount</th>
                                                <th>Demand Amount</th>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $j = 0;
                                                if ($demand_detail) {
                                                    foreach ($demand_detail as $demand) {
                                                        //$tax = $demand["holding_tax"] + $demand["water_tax"] + $demand["education_cess"] + $demand["health_cess"] + $demand["latrine_tax"];
                                                        $tax = $demand["amount"];
                                                        ?>
                                                        <tr>
                                                            <td><label for="<?= $l=++$j; ?>" style="width: 100%;cursor: pointer">
                                                                <input id="<?= $l; ?>" type="checkbox" class="" name="demandid[]" value="<?= $demand['id']; ?>">
                                                                </label>
                                                            </td>
                                                            <td><?= ++$l; ?></td>
                                                            <td><?= $demand['qtr']; ?> / <?= $demand['fy']; ?></td>
                                                            <td><?= $tax; ?></td>
                                                            <td><?= $demand['additional_amount'];?></td>
                                                            <td><?= $demand['balance'];?></td>
                                                            <td><?= $demand['demand_amount'];?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="5" class="text text-success text-bold text-center"> No Dues Are Available!! </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                                    <tr>
                                                        <td colspan="5"></td>
                                                        <td colspan="2" class="text-center">
                                                            <button class="btn_damand btn" name="demandupdate" value="true">Update Demand</button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </form>
                                        </div>
                                    </div>
                                    <div class="panel">
                                        <div class="panel-body text-center">
                                            <?php
                                            // 4 Team Leader
                                            // 8 jsk
                                            // 11 Back Office
                                            if ($demand_detail && in_array($user_type_id, [1, 2, 4])) {
                                                ?>
                                                <a href="<?= base_url('jsk/holding_demand_print/' .$prop_dtl_id); ?>" target="_blank" class="btn btn-primary">Demand Print</a>
                                                <?php
                                            }
                                            ?>

                                        </div>
                                    </div>
                                    <?php } ?>
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
    <?php

    ?>
</script>