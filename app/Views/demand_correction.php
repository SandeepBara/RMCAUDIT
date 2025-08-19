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
														<input type="text" id="holding_no" name="holding_no" value="<?php echo isset($holding_no)?$holding_no:'';?>" class="form-control" >
													</div>
													<div class="col-md-3">
														<label class="control-label" for="">&nbsp;</label>
														<button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
													</div>
												</div>
                                            </form>
                                        </div>
                                    </div>
                                    <?php if(isset($prop_dtl_id)){?>
                                    <div class="row">
                                        <div class="text-center text-danger text-bold">Holding No: <?php echo isset($holding_no)?$holding_no:'NA';?> 
                                        </div>
										<div class="panel panel-dark panel-bordered">
                                            <div class="panel-heading">
                                                <h5 class="panel-title">List of floor</h5>
                                            </div>
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Floor No.</th>
                                                                <th>Usege Type</th>
                                                                <th>Occupancy Type</th>
                                                                <th>Construction Type</th>
                                                                <th>Built Up Area (in Sq. Ft)</th>
                                                                <th>From Date</th>
                                                                <th>Upto Date (Leave blank for current date)</th>
                                                            </tr>
                                                        </thead>
                                                        <form class="form-horizontal" method="post" action="">
                                                        <tbody>
                                                        
                                                        <?php
                                                        if(!isset($floorList)):
                                                        ?>
                                                                <tr>
                                                                    <td colspan="13" style="text-align: center;">Vacant land Property</td>
                                                                </tr>
                                                        <?php else:
                                                                $i=0;
                                                                $is_upto = false;
                                                                foreach ($floorList as $floor):
                                                                    if($floor['date_upto'] && $is_upto == false)
                                                                    {
                                                                        $is_upto = true;
                                                                        
                                                                    }
                                                        ?>
                                                                <tr>
                                                                    <td><input type="checkbox" name="verif_floor_id[]" value="<?=$floor['id']; ?>"/></td>
                                                                    <td><?php echo $floor['floor_name'];?></td>
                                                                    <td><?php echo $floor['usage_type'];?></td>
                                                                    <td><?php echo $floor['occupancy_name'];?></td>
                                                                    <td><?php echo @$floor['construction_type'];?></td>
                                                                    <td><?php echo @$floor['builtup_area'];?></td>
                                                                    <td><?php echo date('Y-m',strtotime($floor['date_from']));?></td>
                                                                    <td ><?php if($floor['date_upto']){ echo date('Y-m', strtotime($floor['date_upto']));} ?></td>
                                                                </tr>
                                                            <?php endforeach;?>
                                                        <?php endif;  ?>
                                                        
                                                        </tbody>
                                                        <?php if(isset($floorList) && $is_upto == true){ ?>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="8">
                                                                    <input type="submit" name="upto_update" id="upto_update" value="Update" class="btn btn-primary" />
                                                                    <input type="hidden" id="holding_no" name="holding_no" value="<?php echo isset($holding_no)?$holding_no:'NA';?> " class="form-control" >
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                        <?php } ?>
                                                        </form>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-dark panel-bordered">
                                            <div class="panel-heading">
                                                <h5 class="panel-title">Generated Tax details (According to ULB Tax)</h5>
                                            </div>
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Effect From</th>
                                                                <th>ARV</th>
                                                                <th>Holding Tax(Quarterly)</th>
                                                                <th>Water Tax(Quarterly)</th>
                                                                <th>Latrine/Conservancy Tax(Quarterly)</th>
                                                                <th>Education Cess(Quarterly)</th>
                                                                <th>Health Cess(Quarterly)</th>
                                                                <th>RWH</th>
                                                                <th>Quarterly Tax(Total)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        if(!isset($tax_details)):
                                                        ?>
                                                                <tr>
                                                                    <td colspan="13" style="text-align: center;">Data Not Available!!</td>
                                                                </tr>
                                                        <?php else:
                                                                $i=0;
                                                                foreach ($tax_details as $value):
                                                        ?>
                                                                <tr>
                                                                    <td><?=++$i;?></td>
                                                                    <td><?php echo $value['qtr'];?>/<?php echo $value['fyear'];?></td>
                                                                    <td><?php echo $value['arv'];?></td>
                                                                    <td><?php echo $value['holding_tax'];?></td>
                                                                    <td><?php echo @$value['water_tax'];?></td>
                                                                    <td><?php echo @$value['education_cess'];?></td>
                                                                    <td><?php echo @$value['health_cess'];?></td>
                                                                    <td ><?php echo @$value['latrine_tax'];?></td>
                                                                    <td><?php echo $value['additional_tax'];?></td>
                                                                    <td><?php echo $value['quarterly_tax'];?></td>
                                                                </tr>
                                                            <?php endforeach;?>
                                                        <?php endif;  ?>

                                                        </tbody>
                                                        
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-dark panel-bordered">
                                            <div class="panel-heading">
                                                <h5 class="panel-title">Property Current Tax details (According to assessment)</h5>
                                            </div>
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Effect From</th>
                                                                <th>ARV</th>
                                                                <th>Holding Tax(Quarterly)</th>
                                                                <th>Water Tax(Quarterly)</th>
                                                                <th>Latrine/Conservancy Tax(Quarterly)</th>
                                                                <th>Education Cess(Quarterly)</th>
                                                                <th>Health Cess(Quarterly)</th>
                                                                <th>RWH</th>
                                                                <th>Quarterly Tax(Total)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        if(!isset($prop_tax)):
                                                        ?>
                                                                <tr>
                                                                    <td colspan="13" style="text-align: center;">Data Not Available!!</td>
                                                                </tr>
                                                        <?php else:
                                                                $i=0;
                                                                foreach ($prop_tax as $value):
                                                        ?>
                                                                <tr>
                                                                    <td><?=++$i;?></td>
                                                                    <td><?php echo $value['qtr'];?>/<?php echo $value['fyear'];?></td>
                                                                    <td><?php echo $value['arv'];?></td>
                                                                    <td><?php echo $value['holding_tax'];?></td>
                                                                    <td><?php echo @$value['water_tax'];?></td>
                                                                    <td><?php echo @$value['education_cess'];?></td>
                                                                    <td><?php echo @$value['health_cess'];?></td>
                                                                    <td ><?php echo @$value['latrine_tax'];?></td>
                                                                    <td><?php echo $value['additional_tax'];?></td>
                                                                    <td><?php echo $value['quarterly_tax'];?></td>
                                                                </tr>
                                                            <?php endforeach;?>
                                                        <?php endif;  ?>

                                                        </tbody>
                                                        
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-dark panel-bordered">
                                            <div class="panel-heading">
                                                <h5 class="panel-title">Tax Differences</h5>
                                            </div>
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Effect From</th>
                                                                <th>ARV</th>
                                                                <th>Holding Tax(Quarterly)</th>
                                                                <th>Water Tax(Quarterly)</th>
                                                                <th>Latrine/Conservancy Tax(Quarterly)</th>
                                                                <th>Education Cess(Quarterly)</th>
                                                                <th>Health Cess(Quarterly)</th>
                                                                <th>RWH</th>
                                                                <th>Quarterly Tax(Total)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        if(!isset($tax_difference)):
                                                        ?>
                                                                <tr>
                                                                    <td colspan="13" style="text-align: center;">Data Not Available!!</td>
                                                                </tr>
                                                        <?php else:
                                                                $i=0;
                                                                foreach ($tax_difference as $value):
                                                        ?>
                                                                <tr>
                                                                    <td><?=++$i;?></td>
                                                                    <td><?php echo $value['qtr'];?>/<?php echo $value['fyear'];?></td>
                                                                    <td><?php echo $value['arv'];?></td>
                                                                    <td><?php echo $value['holding_tax'];?></td>
                                                                    <td><?php echo @$value['water_tax'];?></td>
                                                                    <td><?php echo @$value['education_cess'];?></td>
                                                                    <td><?php echo @$value['health_cess'];?></td>
                                                                    <td ><?php echo @$value['latrine_tax'];?></td>
                                                                    <td><?php echo $value['additional_tax'];?></td>
                                                                    <td><?php echo round($value['holding_tax']+$value['water_tax']+$value['education_cess']+$value['health_cess']+$value['latrine_tax']+$value['additional_tax'], 2);?></td>
                                                                </tr>
                                                            <?php endforeach;?>
                                                        <?php endif;  ?>

                                                        </tbody>
                                                        
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-dark panel-bordered">
                                            <div class="panel-heading">
                                                <h5 class="panel-title">Rest generate tax (For Generate Demand)</h5>
                                            </div>
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered text-sm">
                                                        <thead>
                                                            <?php if (isset($tax_to_be_generate) && count($tax_to_be_generate)>0) :
                                                                $i = 1;
                                                                $total_due = 0;
                                                            ?>
                                                                <?php foreach ($tax_to_be_generate as $tot_demand) :
                                                                    $i == 1 ? $first_qtr = $tot_demand['qtr'] : '';
                                                                    $i == 1 ? $first_fy = $tot_demand['fyear'] : '';

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
                                                                        Quarter <?php echo $tot_demand['qtr']; ?> / Year <?php echo $tot_demand['fyear']; ?> </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Total Quarter(s)</td>
                                                                    <td><strong>:</strong></td>
                                                                    <td ><?php 
                                                                                if ($total_quarter) {
                                                                                        echo $total_quarter;
                                                                                    } else {
                                                                                        echo "N/A";
                                                                                    } ?></td>
                                                                    <td>Advance Amount(Avg. calculation)</td>
                                                                    <td><strong>:</strong></td>
                                                                    <td><?php echo $advance_amount; ?></td>
                                                                </tr>
                                                            <?php endif; ?>
                                                            </tbody>
                                                    </table>
                                                    <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Effect From</th>
                                                                <th>Fy Master Id</th>
                                                                <th>Ward Master Id</th>
                                                                <th>Amount</th>
                                                                <th>Balance</th>
                                                                <th>Demand Amount</th>
                                                                <th>Additional(RWH) Amount</th>
                                                                <th>Adjust Amount</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        if(!isset($tax_to_be_generate)):
                                                        ?>
                                                                <tr>
                                                                    <td colspan="13" style="text-align: center;">Data Not Available!!</td>
                                                                </tr>
                                                        <?php else:
                                                                $i=0;
                                                                foreach ($tax_to_be_generate as $value):
                                                        ?>
                                                                <tr>
                                                                    <td><?=++$i;?></td>
                                                                    <td><?php echo $value['qtr'];?>/<?php echo $value['fyear'];?></td>
                                                                    <td><?php echo $value['fy_mstr_id'];?></td>
                                                                    <td><?php echo $value['ward_mstr_id'];?></td>
                                                                    <td><?php echo $value['amount'];?></td>
                                                                    <td><?php echo @$value['balance'];?></td>
                                                                    <td><?php echo @$value['demand_amount'];?></td>
                                                                    <td><?php echo @$value['additional_amount'];?></td>
                                                                    <td><?php echo @$value['adjust_amt'];?></td>
                                                                </tr>
                                                            <?php endforeach;?>
                                                        <?php endif;  ?>

                                                        </tbody>
                                                        
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if(isset($tax_details)){?>
                                        <form class="form-horizontal" method="post" action="">
                                            <div class="form-group">
                                                <div class="col-md-4">
                                                    <input type="hidden" id="encoded_data" name="encoded_data" value='<?=$encode_data;?>' class="form-control" >
                                                    <input type="hidden" id="holding_no" name="holding_no" value="<?php echo isset($holding_no)?$holding_no:'NA';?> " class="form-control" >
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="control-label" align="center" for="" style="color:#f00; font-weight:bold; text-align:center; font-size:20px;">* Please verify all the taxes before click on the button</label>
                                                    <button class="btn btn-primary btn-block" id="btn_verify" name="btn_verify" type="submit" >Verify & Generate</button>
                                                </div>
                                            </div>
                                        </form>
                                        <?php } ?>
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
<?= $this->include('layout_vertical/footer');?>
<script>
    function block_button(button)
    {
        button.disabled = true;
    }
    </script>
<!--DataTables [ OPTIONAL ]-->
