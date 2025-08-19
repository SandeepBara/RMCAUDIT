<?=$this->include("layout_mobi/header");?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url();?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">

<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
            <div id="page-head">

<!--Page Title-->
<div id="page-title">
</div>
<ol class="breadcrumb">
<li><a href="#"><i class="demo-pli-home"></i></a></li>
<li><a href="#">Trade</a></li>
<li class="active">Report</li>
<li class="active">Water Connection Details</li>
</ol>
</div>

<div id="page-content">
            
        <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title"> Water Application Status</h3>
                </div>
         <div class="panel-body">
					<div class="row">
						<div class="col-md-12" style="text-align: center;">
							<div class="col-md-6">
								<span style="font-weight: bold; color: #bb4b0a;"> Your Application No. is</span> &nbsp;&nbsp;&nbsp;<span style="color: #179a07;"><?php echo $consumer_details['application_no'];?></span>
							</div>
							<div class="col-md-6">
								<span style="font-weight: bold; color: #bb4b0a;">Application Status: </span>&nbsp;&nbsp;&nbsp;<span style="color: #179a07;"><?php echo $application_status;?></span>
							</div>
						</div>
					</div>
                </div>
            </div>

              
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title"> Water Connection Details View</h3>
                </div>
                <div class="panel-body">                      
                    <div class="row">
                        <label class="col-md-2 bolder">Type of Connection </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['connection_type']; ?>
                        </div>
                        <label class="col-md-2 bolder">Connection Through </label>
                        <div class="col-md-3 pad-btm">
            							<?php echo $consumer_details['connection_through']; ?> 
            						</div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 bolder">Property Type </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['property_type']; ?> 
                        </div>
                            <label class="col-md-2 bolder">Pipeline Type </label>
                        <div class="col-md-3 pad-btm">
							<?php echo $consumer_details['pipeline_type']; ?> 
                        </div>
                    </div>
                
					<div class="row">
                        <label class="col-md-2 bolder">Category </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['category']; ?> 
                        </div>
                            <label class="col-md-2 bolder">Owner Type </label>
                        <div class="col-md-3 pad-btm">
							<?php echo $consumer_details['owner_type']; ?> 
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title"> Property Details</h3>
                </div>
                <div class="panel-body">                     
                    <div class="row">
                        <label class="col-md-2 bolder">Ward No. </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['ward_no']; ?>
                        </div>
                        <?php
                             if($consumer_details['connection_through']!= 'Id Proof'){
							if($consumer_details['prop_dtl_id']!="" and $consumer_details['prop_dtl_id']!=0)
							{
                        ?>
                        <label class="col-md-2 bolder">Holding No. </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['holding_no']; ?> 
						</div>
                        <?php   
							}
							else
							{
						?>
                        <label class="col-md-2 bolder">SAF No. </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['saf_no']; ?> 
                        </div>
                        <?php
							} }
                        ?>
                        
                    </div>
                    <div class="row">
                        <label class="col-md-2 bolder">Area in Sqft.</label>
                        <div class="col-md-3 pad-btm">
                          <?php echo $consumer_details['area_sqft']; ?> 
                        </div>
                        <label class="col-md-2 bolder">Area in Sqmt.</label>
                        <div class="col-md-3 pad-btm">
                            <?php echo round($consumer_details['area_sqmt'],2); ?> 
                        </div>
                    </div>
                   <div class="row">
                        <label class="col-md-2 bolder">Address</label>
                        <div class="col-md-3 pad-btm">
                          <?php echo $consumer_details['address']; ?> 
                        </div>
                        <label class="col-md-2 bolder">Landmark </label>
                        <div class="col-md-3 pad-btm">
                           <?php echo $consumer_details['landmark']; ?> 
                        </div>
                    </div>
					<div class="row">
						<label class="col-md-2 bolder">Pin</label>
						<div class="col-md-3 pad-btm">
						  <?php echo $consumer_details['pin']; ?> 
						</div>
					</div>
                </div>
            </div>
            <div class="clear"></div>
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title"> Owner Details</h3>
					</div>
					<div class="panel-body" style="overflow: scroll;">
						<table class="table table-bordered table-responsive">
							<thead class="bg-trans-dark text-dark">
								<tr>
									<th class="bolder">Owner Name</th>
									<th class="bolder">Guardian Name</th>
									<th class="bolder">Mobile No.</th>
									<th class="bolder">Email ID</th>
									<th class="bolder">State</th>
									<th class="bolder">District</th>
									<th class="bolder">City</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if($owner_details)
								{
									foreach($owner_details as $val)
                                    {
                                        ?>
                                        <tr>
                                            <td><?php echo $val['applicant_name'];?></td>
                                            <td><?php echo $val['father_name'];?></td>
                                            <td><?php echo $val['mobile_no'];?></td>
                                            <td><?php echo $val['email_id'];?></td>
                                            <td><?php echo $val['state'];?></td>
                                            <td><?php echo $val['district'];?></td>
                                            <td><?php echo $val['city'];?></td>
                                        </tr>
                                        <?php
                                    }
								}
								?>
							</tbody>
						</table>
					</div>
				</div>

				<?php
				if($user_type!=5)
				{
				?>
                
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title">Electricity Connection Details</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<label class="col-md-2 bolder">K No. </label>
							<div class="col-md-3 pad-btm">
								<?php echo $consumer_details['elec_k_no']; ?>
							</div>
							<label class="col-md-2 bolder">Bind Book No.</label>
							<div class="col-md-3 pad-btm">
								<?php echo $consumer_details['elec_bind_book_no']; ?> 
						   </div>
						</div>
						<div class="row">
							<label class="col-md-2 bolder">Electricity Account No. </label>
							<div class="col-md-3 pad-btm">
								<?php echo $consumer_details['elec_account_no']; ?> 
							</div>
							<label class="col-md-2 bolder">Electricity Category </label>
							<div class="col-md-3 pad-btm">
								<?php echo $consumer_details['elec_category']; ?> 
							</div>
						</div>
					</div>
				</div>
            
            <?php
            }
            ?>

         
            <div class="panel">
				<div class="panel-body text-center">
                   <input type="hidden" name="water_conn_id" id="water_conn_id" value="<?php echo $water_conn_id; ?>">
                    <?php
                    if($dues and ($user_type==1 or $user_type==8 or $user_type==5 or $user_type==4))
                    {
                        ?>
                        <div class="col-md-3 col-xs-6">
                        <a href="<?php echo base_url('WaterPaymentConnectionMobile/payment/'.$water_conn_id);?>"><button class="btn btn-success"  value="proceed_payment">Proceed to Payment</button></a>
                        </div>
                        <?php 
                    }
                 
                    if($dues)
                    {
                        ?>
                        <div class="col-md-3 col-xs-6">
                        <a href="<?php echo base_url('WaterViewConnectionChargeMobile/fee_charge/'.md5($consumer_details['id']));?>"><button class="btn btn-info" value="view_connection_fee">View Connection Fee</button></a>
                        </div>
                        <?php
                    }

                    if($site_inspection_details)
                    {
                        ?>
                        <div class="col-md-3 col-xs-6">
                        <a onClick="myPopup('<?php echo base_url('WaterSiteInspection/index/'.md5($consumer_details['id']));?>','xtf','900','700');" style="font-weight: bold; float:right; margin-top:-5px; margin-right:5px ; cursor: pointer;" class="btn btn-primary">Site Inspection Details</a>
                        </div>
                        <?php  
                    }
                   ?>
               </div>
            </div>  
      
    </div><!--End page content-->
 <!--End page content-->
                <!--===================================================-->
                <!--End page content-->
            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>
<script src="<?=base_url();?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script type="text/javascript">
     
     
	$(document).ready(function() {
		$('#demo_dt_basic').DataTable({
			responsive: true,
			dom: 'Bfrtip',
	        lengthMenu: [
	            [ 10, 25, 50, -1 ],
	            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
	        ],
	        buttons: [
	        	'pageLength',
	          {
				text: 'excel',
				extend: "excel",
				title: "<?=$denial?>    <?=$from_date?>  -  <?=$to_date?>",
				footer: { text: '' },
				exportOptions: { columns: [ 0, 1,2,3] }
			}, {
				text: 'pdf',
				extend: "pdf",
				title: "<?=$denial?>       <?=$from_date?>  -  <?=$to_date?>",
				download: 'open',
				footer: { text: '' },
				exportOptions: { columns: [ 0, 1,2,3] }
			}]
		});
	});
 </script>

