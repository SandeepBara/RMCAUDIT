<?= $this->include('layout_vertical/header');?>
<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
	<div id="page-head">
		<ol class="breadcrumb">
		<li><a href="#"><i class="demo-pli-home"></i></a></li>
		<li><a href="#">SAF</a></li>
		<li class="active">SAF Document Verification</li>
		</ol>
	</div>
	<!--Page content-->
	<div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <div class="panel-control">
                    <a href="<?=base_url()?>/safdtl/full/<?=md5($saf_dtl_id);?>" target="_blank" class="btn btn-default">View full SAF details </a>
                    |
                    <a href="<?php echo base_url('documentverification/index');?>" class="btn btn-default">Back</a>
                </div>
                <h3 class="panel-title">Basic Details</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <label class="col-md-3">Application No.</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?=($saf_no!="")?$saf_no:"N/A";?>
                    </div>
                    <label class="col-md-3">Applied Date</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?=($created_on!="")?date("Y-m-d", strtotime($created_on)):"N/A";?>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-3">Assessment Type</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?=(isset($assessment_type))?$assessment_type:"N/A";?>
                    </div>
                    <div class="<?=(isset($has_previous_holding_no))?($has_previous_holding_no==0)?"hidden":"":"";?>">
                        <label class="col-md-3">Previous Holding No.</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=(isset($previous_holding_no))?$previous_holding_no:"N/A";?>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="<?=(isset($has_previous_holding_no))?($has_previous_holding_no==0)?"hidden":"":"";?>">
                    <div class="row">
                        <label class="col-md-3">Is the Applicant tax payer for the mentioned holding? If owner(s) have been changed click No.</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=(isset($is_owner_changed))?($is_owner_changed==1)?"YES":"NO":"N/A";?>
                        </div>
                        <div id="is_owner_changed_tran_property_hide_show" class="<?=(isset($is_owner_changed))?($is_owner_changed==0)?"hidden":"":"";?>">
                            <label class="col-md-3">Mode of transfer of property from previous Holding Owner</label>
                            <div class="col-md-3 text-bold pad-btm">
                                    <?=(isset($transfer_mode))?$transfer_mode:"N/A";?>
                            </div>
                        </div>
                    </div>
                    <hr />
                </div>
                <div class="row">
                    <label class="col-md-3">Ward No</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?=(isset($ward_no))?$ward_no:"N/A";?>
                    </div>
                    <label class="col-md-3">Ownership Type</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?=(isset($ownership_type))?$ownership_type:"N/A";?>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-3">Property Type</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?=(isset($property_type))?$property_type:"N/A";?>
                    </div>
                </div>
                <div class="<?=(isset($prop_type_mstr_id))?(($prop_type_mstr_id!=3))?"hidden":"":"";?>">
                    <div class="row">
                        <label class="col-md-3">Appartment Name</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=(isset($appartment_name))?$appartment_name:"N/A";?>
                        </div>
                        <label class="col-md-3">Registry Date</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=(isset($flat_registry_date))?$flat_registry_date:"N/A";?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-3">Road Type</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?=(isset($road_type))?$road_type:"N/A";?>
                    </div>
                </div>
            <?php
            $db_name = $session->get('ulb_dtl');
            if ($db_name['ulb_mstr_id']==1) {
            ?>
                <div class="row">
                    <label class="col-md-3">Zone</label>
                    <div class="col-md-3 text-bold">
                        <?=(isset($zone_mstr_id))?($zone_mstr_id==1)?"Zone 1":"Zone 2":"N/A";?>
                    </div>
                </div>
            <?php
            }
            ?>
            </div>
        </div>
        <?php
        if ($has_previous_holding_no==0 || ($has_previous_holding_no==1 && $is_owner_changed==1)) {
        ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Owner Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>Owner Name</th>
                                            <th>Guardian Name</th>
                                            <th>Relation</th>
                                            <th>Mobile No</th>
                                            <th>Aadhar No.</th>
                                            <th>PAN No.</th>
                                            <th>Email ID</th>
                                        </tr>
                                    </thead>
                                    <tbody id="owner_dtl_append">
                                <?php
                                if (isset($saf_owner_detail)) {
                                    foreach ($saf_owner_detail as $owner_detail) {
                                ?>
                                        <tr class="text-bold">
                                            <td>
                                                <?=$owner_detail['owner_name'];?>
                                            </td>
                                            <td>
                                                <?=($owner_detail['guardian_name']!="")?$owner_detail['guardian_name']:"N/A";?>
                                            </td>
                                            <td>
                                            <?php if($owner_detail['relation_type']!=""){?>
                                                <?=($owner_detail['relation_type']=="S/O")?"S/O":"";?>
                                                <?=($owner_detail['relation_type']=="D/O")?"D/O":"";?>
                                                <?=($owner_detail['relation_type']=="W/O")?"W/O":"";?>
                                                <?=($owner_detail['relation_type']=="C/O")?"C/O":"";?>
                                            <?php }else{?>
                                                N/A
                                            <?php }?>
                                            </td>
                                            <td>
                                                <?=$owner_detail['mobile_no'];?>
                                            </td>
                                            <td>
                                                <?=($owner_detail['aadhar_no']!="")?$owner_detail['aadhar_no']:"N/A";?>
                                            </td>
                                            <td>
                                                <?=($owner_detail['pan_no']!="")?$owner_detail['pan_no']:"N/A";?>
                                            </td>
                                            <td>
                                                <?=($owner_detail['email']!="")?$owner_detail['email']:"N/A";?>
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
                </div>
            </div>
        <?php
        }
        ?>
		<div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Electricity Details</h3>
            </div>
            <div class="panel-body" style="padding-bottom: 0px;">
                <div class="row <?=(isset($prop_type_mstr_id))?(($prop_type_mstr_id!=2))?"hidden":"":"";?>">
                    <label class="col-md-10">
                        <div class="checkbox">
                            <?php
                            if($no_electric_connection=='t'){
                                echo '<i class="fa fa-check-square-o" style="font-size: 22px;"></i>';
                            }else{
                                echo '<i class="fa fa-window-close-o" style="font-size: 22px;"></i>';
                            }
                            ?>
                            <label for="no_electric_connection"><span class="text-danger">Note:</span> In case, there is no Electric Connection. You have to upload Affidavit Form-I. (Please Tick)</label>
                        </div>
                    </label>
                </div>
                <div class="row">
                    <label class="col-md-3">Electricity K. No</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?=($elect_consumer_no!="")?$elect_consumer_no:"N/A";?>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-12 pad-btm text-center text-bold"><u>OR</u></label>
                </div>
                <div class="row">
                    <label class="col-md-3">ACC No.</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?=($elect_acc_no!="")?$elect_acc_no:"N/A";?>
                    </div>
                    <label class="col-md-3">BIND/BOOK No.</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?=($elect_bind_book_no!="")?$elect_bind_book_no:"N/A";?>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-3">Electricity Consumer Category</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?=$elec_cons_category ?? "N/A";?>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Building Plan/Water Connection Details</h3>
            </div>
            <div class="panel-body" style="padding-bottom: 0px;">
                <div class="row">
                    <label class="col-md-3">Building Plan Approval No. </label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?=($building_plan_approval_no!="")?$building_plan_approval_no:"N/A";?>
                    </div>
                    <label class="col-md-3">Building Plan Approval Date </label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?=($building_plan_approval_date!="")?$building_plan_approval_date:"N/A";?>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-3">Water Consumer No. </label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?=($water_conn_no!="")?$water_conn_no:"N/A";?>
                    </div>
                    <label class="col-md-3">Water Connection Date</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?=($water_conn_date!="")?$water_conn_date:"N/A";?>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Property Details</h3>
            </div>
            <div class="panel-body" style="padding-bottom: 0px;">
                <div class="row">
                    <label class="col-md-3">Khata No.</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?=(isset($khata_no))?$khata_no:"";?>
                    </div>
                    <label class="col-md-3">Plot No.</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?=(isset($plot_no))?$plot_no:"";?>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-3">Village/Mauja Name</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?=(isset($village_mauja_name))?$village_mauja_name:"";?>
                    </div>
                    <label class="col-md-3">Area of Plot (in Decimal)</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?=(isset($area_of_plot))?$area_of_plot:"";?>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Property Address</h3>
            </div>
            <div class="panel-body" style="padding-bottom: 0px;">
                <div class="row">
                    <label class="col-md-3">Property Address</label>
                    <div class="col-md-7 text-bold pad-btm">
                        <?=(isset($prop_address))?$prop_address:"";?>
                    </div>
                    
                </div>
                <div class="row">
                    <label class="col-md-3">City</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?=(isset($prop_city))?$prop_city:"";?>
                    </div>
                    <label class="col-md-3">District</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?=(isset($prop_dist))?$prop_dist:"";?>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-3">Pin</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?=(isset($prop_pin_code))?$prop_pin_code:"";?>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-5">
                        <div class="checkbox">
                            <?php
                            if(isset($is_corr_add_differ)){
                                echo '<i class="fa fa-check-square-o" style="font-size: 22px;"></i>';
                            }else{
                                echo '<i class="fa fa-window-close-o" style="font-size: 22px;"></i>';
                            }
                            ?>
                            <label>If Corresponding Address Different from Property Address</label>
                            
                        </div>
                    </label>
                </div>
            </div>
        </div>
        
        
        <div id="floor_dtl_hide_show" class="<?=(isset($prop_type_mstr_id))?(($prop_type_mstr_id==4))?"hidden":"":"";?>">
            <div class="panel panel-bordered panel-dark">
                <a data-toggle="collapse" href="#floorDetailsCollapse">
                    <div class="panel-heading">
                        <h3 class="panel-title">Floor Details <i class="fa fa-arrows-v"></i></h3>
                    </div>
                </a>
                <div id="floorDetailsCollapse" class="panel-collapse collapse">
                    <div class="panel-body" style="padding-bottom: 0px;">
                        <div class="row">
                            <div class="col-md-12 pad-btm">
                                <span class="text-bold text-dark">Built Up :</span>
                                <span class="text-thin">It refers to the entire carpet area along with the thickness of the external walls of the apartment. It includes the thickness of the internal walls and the columns.</span>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-sm">
                                        <thead class="bg-trans-dark text-dark">
                                            <tr>
                                                <th>Floor No</th>
                                                <th>Usege Type</th>
                                                <th>Occupancy Type</th>
                                                <th>Construction Type</th>
                                                <th>Built Up Area  (in Sq. Ft)</th>
                                                <th>From Date</th>
                                                <th>Upto Date <span class="text-xs">(Leave blank for current date)</span></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-bold">
                                    <?php
                                    if(isset($saf_floor_details)) {
                                        foreach ($saf_floor_details as $floor_details) {
                                    ?>
                                            <tr>
                                                <td>
                                                    <?=(isset($floor_details['floor_name']))?$floor_details['floor_name']:"N/A";?>
                                                </td>
                                                <td>
                                                    <?=(isset($floor_details['usage_type']))?$floor_details['usage_type']:"N/A";?>
                                                </td>
                                                <td>
                                                    <?=(isset($floor_details['occupancy_name']))?$floor_details['occupancy_name']:"N/A";?>
                                                </td>
                                                <td>
                                                    <?=(isset($floor_details['construction_type']))?$floor_details['construction_type']:"N/A";?>
                                                </td>
                                                <td>
                                                    <?=(isset($floor_details['builtup_area']))?$floor_details['builtup_area']:"N/A";?>
                                                </td>
                                                <td>
                                                    <?=(isset($floor_details['date_from']))?date('Y-m', strtotime($floor_details['date_from'])):"N/A";?>
                                                </td>
                                                <td>
                                                    <?=(isset($floor_details['date_upto']))?date('Y-m', strtotime($floor_details['date_upto'])):"N/A";?>
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
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-bordered panel-dark">
            <div class="panel-body" style="padding-bottom: 0px;">
                <div class="row">
                    <label class="col-md-3">Does Property Have Mobile Tower(s) ?</label>
                    <div class="col-md-3 text-bold pad-btm">
                            <?=(isset($is_mobile_tower))?($is_mobile_tower=="t")?"YES":"NO":"N/A";?>
                    </div>
                </div>
                <div class="<?=(isset($is_mobile_tower))?($is_mobile_tower=="f")?"hidden":"":"";?>">
                    <div class="row">
                        <label class="col-md-3">Total Area Covered by Mobile Tower & its
Supporting Equipments & Accessories (in Sq. Ft.)</label>
                        <div class="col-md-3 text-bold">
                            <?=(isset($tower_area))?$tower_area:"";?>
                        </div>
                        <label class="col-md-3">Date of Installation of Mobile Tower</label>
                        <div class="col-md-3 text-bold">
                            <?=(isset($tower_installation_date))?$tower_installation_date:"";?>
                        </div>
                    </div>
                    <hr />
                </div>

                <div class="row">
                    <label class="col-md-3">Does Property Have Hoarding Board(s) ?</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?=(isset($is_hoarding_board))?($is_hoarding_board=="t")?"YES":"NO":"N/A";?>
                    </div>
                </div>
                <div class="<?=(isset($is_hoarding_board))?($is_hoarding_board=="f")?"hidden":"":"";?>">
                    <div class="row">
                        <label class="col-md-3">Total Area of Wall / Roof / Land (in Sq. Ft.)</label>
                        <div class="col-md-3 text-bold">
                            <?=(isset($hoarding_area))?$hoarding_area:"";?>
                        </div>
                        <label class="col-md-3">Date of Installation of Hoarding Board(s)</label>
                        <div class="col-md-3 text-bold">
                            <?=(isset($hoarding_installation_date))?$hoarding_installation_date:"";?>
                        </div>
                    </div>
                    <hr />
                </div>

                <div class="row">
                    <label class="col-md-3">Is property a Petrol Pump ?</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?=(isset($is_petrol_pump))?($is_petrol_pump=="t")?"YES":"NO":"N/A";?>
                    </div>
                </div>
                <div class="<?=(isset($is_petrol_pump))?($is_petrol_pump=="f")?"hidden":"":"";?>">
                    <div class="row">
                        <label class="col-md-3">    
Underground Storage Area (in Sq. Ft.)</label>
                        <div class="col-md-3 text-bold">
                            <?=(isset($under_ground_area))?$under_ground_area:"";?>
                        </div>
                        <label class="col-md-3">Completion Date of Petrol Pump</label>
                        <div class="col-md-3 text-bold">
                            <?=(isset($petrol_pump_completion_date))?$petrol_pump_completion_date:"";?>
                        </div>
                    </div>
                    <hr />
                </div>

                <div class="row">
                    <label class="col-md-3">Rainwater harvesting provision ?</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?=(isset($is_water_harvesting))?($is_water_harvesting=="t")?"YES":"NO":"N/A";?>
                    </div>
                </div>
                <div class="<?=(isset($prop_type_mstr_id))?($prop_type_mstr_id!=4)?"hidden":"":"";?>">
                    <div class="row">
                        <label class="col-md-3">Date of Possession / Purchase / Acquisition (Whichever is earlier)</label>
                        <div class="col-md-3 text-bold">
                            <?=(isset($land_occupation_date))?$land_occupation_date:"";?>
                        </div>
                    </div>
                    <hr />
                </div>

            </div>
        </div>

        <div class="panel panel-bordered panel-dark">
            <a data-toggle="collapse" href="#taxDetailsCollapse">
                <div class="panel-heading">
                    <h3 class="panel-title">Tax Details <i class="fa fa-arrows-v"></i></h3>
                </div>
            </a>
            <div id="taxDetailsCollapse" class="panel-collapse collapse">
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
                        <tbody class="text-bold">
                        <?php if($saf_tax_list):
                            $i=1; $qtr_tax=0; $lenght= sizeOf($saf_tax_list);?>
                        <?php foreach($saf_tax_list as $tax_list): 
                            $qtr_tax=$tax_list['holding_tax']+$tax_list['water_tax']+$tax_list['latrine_tax']+$tax_list['education_cess']+$tax_list['health_cess'] + $tax_list['additional_tax'];
                        ?>
                            <tr>
                                <td><?=$i++;?></td>
                                <td><?=round($tax_list['arv'], 2);?></td>
                                <td><?=$tax_list['qtr'];?> / <?=$tax_list['fy'];?></td>
                                <td><?=round($tax_list['holding_tax'], 2);?></td>
                                <td><?=round($tax_list['water_tax'], 2);?></td>
                                <td><?=round($tax_list['latrine_tax'], 2);?></td>
                                <td><?=round($tax_list['education_cess'], 2);?></td>
                                <td><?=round($tax_list['health_cess'], 2);?></td>
                                <td><?=round($tax_list['additional_tax'], 2);?></td>
                                <td><?=round($qtr_tax, 2); ?></td>
                            <?php if($i>$lenght){ ?>
                                <td class="text-danger">Current</td>
                            <?php } else { ?>
                                <td>Old</td>
                            <?php } ?>
                            </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11" style="text-align:center;color:red;">Data Are Not Available!!</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <a data-toggle="collapse" href="#paymentDetailsCollapse">
                    <h3 class="panel-title">Payment Details <i class="fa fa-arrows-v"></i></h3>
                </a>
            </div>
            <div id="paymentDetailsCollapse" class="panel-collapse collapse">
                <div class="table-responsive">
                    <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                        <thead class="bg-trans-dark text-dark">
                            <th>Sl No.</th>
                            <th>Transaction No</th>
                            <th>Payment Mode</th>
                            <th>Date</th>
                            <th>From Quarter / Year</th>
                            <th>Upto Quarter / Year</th>
                            <th>Amount</th>
                            <?php
                            $arr_user_type_mstr_id = [1];
                            if (isset($emp_details['user_type_mstr_id']) && in_array($emp_details['user_type_mstr_id'], $arr_user_type_mstr_id))
                            {
                                ?>
                                <th>View</th>
                                <?php
                            }
                            ?>
                            
                        </thead>
                        <tbody>
                            <?php 
                            if(isset($payment_detail)) {
                            $i=1;
                            ?>
                            <?php foreach($payment_detail as $payment_detail) {
                            ?>
                            <tr>
                                <td><?=$i++;?></td>
                                <td class="text-bold"><?=$payment_detail['tran_no'];?></td>
                                <td><?=$payment_detail['transaction_mode'] ?></td>
                                <td><?=$payment_detail['tran_date'];?></td>
                                <td><?=$payment_detail['from_qtr']." / ".$payment_detail['fy'];?></td>
                                <td><?=$payment_detail['upto_qtr']." / ".$payment_detail['upto_fy'];?></td>
                                <td><?=$payment_detail['payable_amt'];?></td>
                                <?php
                                if (isset($emp_details['user_type_mstr_id']) && in_array($emp_details['user_type_mstr_id'], $arr_user_type_mstr_id)) {
                                ?>
                                <td><a href="<?php echo base_url('safDemandPayment/saf_payment_receipt/'.md5($payment_detail['id']));?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View</a></td>
                                <?php
                                }
                                ?>
                            </tr>
                            <?php } ?>
                            <?php 
                            } else {?>
                            <tr>
                                <td colspan="9" class="text-danger text-bold text-center"> No Any Transaction ...</td>
                            </tr>
                            <?php 
                            }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>







        <form method="post" class="form-horizontal" action="<?=base_url('documentverification/byda').'/'.md5($level_dtl['id']);?>">            
            <!--------SAF Form------------>
			<?php 
            if(isset($saf_form_doc))
            {
                //print_var($saf_form_doc);exit;
                ?>
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title">SAF Form</h3>
					</div>
					<div class="panel-body" style="padding-bottom: 0px;">
						<div class="table-responsive">
							<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
								<thead class="bg-trans-dark text-dark">
									<tr>
										<th>Document Name</th>
										<th>Document Image</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="text-bold text-danger">SAF FORM</td>
										<td>
											<img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px; cursor: pointer;"
												onclick="window.open('<?=base_url();?>/writable/uploads/<?=$saf_form_doc['doc_path'];?>', 'popUpWindow', 'scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no, width=0,height=0,left=-1000,top=-1000');" />
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			    <?php 
            }
            ?>
			
            <!--------prop doc------------>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Property Document</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                            <thead class="bg-trans-dark text-dark">
                                <tr>
                                    <th>Document Name</th>
                                    <th>Document Image</th>
                                    <th>Upload Date | Time</th>
                                    <th>Action</th>
                                    <th style="width:200px;">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $i = 0;
                                
                                foreach($saf_owner_detail AS $list)
                                {
                                    ?>
                                    <tr>
                                        <td class="text-bold">Owner Photo (<span class="text-danger"><?=$list['owner_name'];?></span>)</td>
                                        <td>
                                            <img src="<?=base_url();?>/writable/uploads/<?=$list['applicant_img'];?>" style="width: 40px; height: 40px; cursor: pointer;"
                                            onclick="window.open('<?=base_url();?>/writable/uploads/<?=$list['applicant_img'];?>', 'popUpWindow', 'scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no, width=0,height=0,left=-1000,top=-1000');" />
                                        </td>
                                        <td><?=date("Y-m-d", strtotime($list['applicant_img_created_on']));?> | <?=date("H:i A", strtotime($list['applicant_img_created_on']));?></td>
                                        <td>
                                        <?php if ($list['applicant_img_verify_status']==0) { $i++; ?>
                                            <input type="hidden" id="saf_doc_dtl_id<?=$i;?>" name="saf_doc_dtl_id[]" value="<?=$list['applicant_img_id'];?>" />
                                            <select id="saf_doc_verify_status<?=$i;?>" name="saf_doc_verify_status[]" class="form-control saf_doc_verify_status" onchange="change_saf_doc_verify_status(this.id);">
                                                <option value="">== SELECT ==</option>
                                                <option value="1">VERIFY</option>
                                                <option value="2">REJECT</option>
                                            </select>
                                        <?php } elseif ($list['applicant_img_verify_status']==1) {
                                            echo "<span class='text-danger'>VERIFIED</span>";
                                        } elseif ($list['applicant_img_verify_status']==2) {
                                            echo "<span class='text-danger'>REJECTED</span>";
                                        } ?>
                                        </td>
                                        <td>
                                        <?php if ($list['applicant_img_verify_status']==0) {?>
                                            <textarea id="remarks<?=$i;?>" name="remarks[]" class="form-control hidden" placeholder="Enter Remarks" onkeypress="remarksOnKeyPress(this.id);"></textarea>
                                        <?php } else { echo $list['applicant_img_remarks']; } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold"><?=$list['applicant_doc_name'];?> (<span class="text-danger"><?=$list['owner_name'];?></span>)</td>
                                        <td>
                                            <img src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px; cursor: pointer;"
                                            onclick="window.open('<?=base_url();?>/writable/uploads/<?=$list['applicant_doc'];?>', 'popUpWindow', 'scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no, width=0,height=0,left=-1000,top=-1000');" />
                                        </td>
                                        <td><?=date("Y-m-d", strtotime($list['applicant_doc_created_on']));?> | <?=date("H:i A", strtotime($list['applicant_doc_created_on']));?></td>
                                        <td>
                                        <?php if ($list['applicant_doc_verify_status']==0) { $i++; ?>
                                            <input type="hidden" id="saf_doc_dtl_id<?=$i;?>" name="saf_doc_dtl_id[]" value="<?=$list['applicant_doc_id'];?>" />
                                            <select id="saf_doc_verify_status<?=$i;?>" name="saf_doc_verify_status[]" class="form-control saf_doc_verify_status" onchange="change_saf_doc_verify_status(this.id);">
                                                <option value="">== SELECT ==</option>
                                                <option value="1">VERIFY</option>
                                                <option value="2">REJECT</option>
                                            </select>
                                        <?php } elseif ($list['applicant_doc_verify_status']==1) {
                                            echo "<span class='text-danger'>VERIFIED</span>";
                                        } elseif ($list['applicant_doc_verify_status']==2) {
                                            echo "<span class='text-danger'>REJECTED</span>";
                                        } ?>
                                        </td>
                                        <td>
                                        <?php if ($list['applicant_doc_verify_status']==0) {?>
                                            <textarea id="remarks<?=$i;?>" name="remarks[]" class="form-control hidden" placeholder="Enter Remarks" onkeypress="remarksOnKeyPress(this.id);"></textarea>
                                        <?php } else { echo $list['applicant_doc_remarks']; } ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            
                                foreach($saf_doc_dtl AS $list)
                                {
                                    ?>
                                    <tr>
                                        <td class="text-bold"><?=$list['doc_name'];?></td>
                                        <td>
                                            <img src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px; cursor: pointer;"
                                            onclick="window.open('<?=base_url();?>/writable/uploads/<?=$list['doc_path'];?>', 'popUpWindow', 'scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no, width=0,height=0,left=-1000,top=-1000');" />
                                        </td>
                                        <td><?=date("Y-m-d", strtotime($list['created_on']));?> | <?=date("H:i A", strtotime($list['created_on']));?></td>
                                        <td>
                                        <?php if ($list['verify_status']==0) { $i++; ?>
                                            <input type="hidden" id="saf_doc_dtl_id<?=$i;?>" name="saf_doc_dtl_id[]" value="<?=$list['id'];?>" />
                                            <select id="saf_doc_verify_status<?=$i;?>" name="saf_doc_verify_status[]" class="form-control saf_doc_verify_status" onchange="change_saf_doc_verify_status(this.id);">
                                                <option value="">== SELECT ==</option>
                                                <option value="1">VERIFY</option>
                                                <option value="2">REJECT</option>
                                            </select>
                                        <?php } elseif ($list['verify_status']==1) {
                                            echo "<span class='text-danger'>VERIFIED</span>";
                                        } elseif ($list['verify_status']==2) {
                                            echo "<span class='text-danger'>REJECTED</span>";
                                        } ?>
                                        </td>
                                        <td>
                                        <?php if ($list['verify_status']==0) {?>
                                            <textarea id="remarks<?=$i;?>" name="remarks[]" class="form-control hidden" placeholder="Enter Remarks" onkeypress="remarksOnKeyPress(this.id);"></textarea>
                                        <?php } else { echo $list['remarks']; } ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            ?>								
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="form-group">
                        <label class="col-md-2">Final Remarks</label>
                        <div class="col-md-10">
                        <?php if ($level_dtl['verification_status']==0) { ?>
                            <textarea id="super_remark" name="super_remark" class="form-control" placeholder="Enter Remark"></textarea>
                        <?php } else { echo ($level_dtl['remarks']!="")?$level_dtl['remarks']:"N/A"; } ?>
                        </div>
                    </div>
                    <?php 
                    if ($level_dtl['verification_status']==0)
                    {
                        ?>
                        <div class="form-group">
                            <label class="col-md-2"></label>
                            <div class="col-md-10">
                                <button type="submit" class="btn btn-danger" id="btn_back_to_citizen_submit" name="btn_back_to_citizen_submit">Back To Citizen</button>
                                
                                <?php
                                if(empty($memo))
                                {
                                    ?>
                                    <button type="submit" class="btn btn-success hidden" id="btn_generate_memo_submit" name="btn_generate_memo_submit">Generate Memo</button>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <button type="submit" class="btn btn-success hidden" id="btn_forward" name="btn_forward">Forward</button>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php 
                    }
                    ?>
                </div>
            </div>
        </form>
    <!-- End page content-->
</div>
<?= $this->include('layout_vertical/footer');?>
<script>
var change_saf_doc_verify_status = (ID) => {
    ID = ID.split("saf_doc_verify_status")[1];
    if ($("#saf_doc_verify_status"+ID).val()==2) {
        $("#remarks"+ID).removeClass("hidden");
    } else {
        $("#remarks"+ID).addClass("hidden");
    }
    $("#saf_doc_verify_status"+ID).css('border-color', '');
    check_enable_generate_btn();
}
var check_enable_generate_btn = () => {
    var process = true;
    $(".saf_doc_verify_status").each(function() {
        var ID = this.id.split('saf_doc_verify_status')[1];
        if (this.value!=1) {
            process = false;
        }
    });
    if (process) {
        $("#btn_generate_memo_submit").removeClass("hidden");
    } else {
        $("#btn_generate_memo_submit").addClass("hidden");
    }
}
var validation = () => {
    var process = true;
    $(".saf_doc_verify_status").each(function() {
        var ID = this.id.split('saf_doc_verify_status')[1];
        if(this.value=="") {
            $("#saf_doc_verify_status"+ID).css('border-color', 'red'); process = false;
        } else {
            if (this.value==2) {
                if($("#remarks"+ID).val().trim()=="") {
                    $("#remarks"+ID).css('border-color', 'red'); process = false;
                }
            }
        }
    });
    return process;
}
$("#btn_back_to_citizen_submit").click(function() {  return validation(); });
$("#btn_generate_memo_submit").click(function() { return validation(); });
var remarksOnKeyPress = (ID) => {
    $("#"+ID).keyup(function(){ $(this).css('border-color', ''); });
}


</script>