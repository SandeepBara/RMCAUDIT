<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url();?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">

                    <!--Page Title-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <div id="page-title">
                        <!--<h1 class="page-header text-overflow">Designation List</h1>//-->
                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End page title-->


                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
					<li><a href="#"><i class="demo-pli-home"></i></a></li>
					<li><a href="#">Water</a></li>
					<li class="active">Forward and Backward List</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h5 class="panel-title">Forward and Backward Search</h5>
						</div>
						<div class="panel-body">
                            <form class="form-horizontal" method="post" action="<?php echo base_url('water_da/forward_list2');?>">							
								<div class="form-group">


									<div class="col-md-12 text-center bg-mint ">
										<div class="radio">	

											<input type="radio" id="by_holding_dtl" class="magic-radio" name="by_holding_owner_dtl" value="by_application_no" checked onchange="div_show(this.value); $('#keyword_change_id').attr('data-original-title', 'Enter Application No');" <?=isset($by_holding_owner_dtl) && $by_holding_owner_dtl=='by_application_no'?'checked':''?>>
											<label for="by_holding_dtl">By Application No.</label>

											<input type="radio" id="by_owner_dtl" class="magic-radio" name="by_holding_owner_dtl" value="by_owner"  onchange="div_show(this.value); $('#keyword_change_id').attr('data-original-title', 'Enter Register Mobile No. Or Owner Name Or Father Name');" <?=isset($by_holding_owner_dtl) && $by_holding_owner_dtl=='by_owner'?'checked':''?>>
											<label for="by_owner_dtl">By Owner Details</label>
											<input type="hidden" id="selected_keys" value="<?=isset($by_holding_owner_dtl) ? $by_holding_owner_dtl:'';?>"/>
											
											<input type="radio" id="by_forward_date" class="magic-radio" name="by_holding_owner_dtl" value="by_forward_date"  onchange="div_show(this.value)" <?=isset($by_holding_owner_dtl) && $by_holding_owner_dtl=='by_forward_date'?'checked':''?>>
											<label for="by_forward_date">By Forward Date</label>
										</div>
									</div>

									<div id="forward_date_div" style="display:none;">
										<div class="col-md-3">
											<label class="control-label" for="from_date"><b>From Date</b> </label>
											<div class="input-group date">
												<input type="text" id="from_date" name="from_date" class="form-control mask_date" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" readonly>
												<span class="input-group-addon"><i class="demo-pli-calendar-4"></i></span>
											</div>
										</div>
										<div class="col-md-3">
											<label class="control-label" for="to_date"><b>To Date</b> </label>
											<div class="input-group date">
												<input type="text" id="to_date" name="to_date" class="form-control mask_date" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" readonly>
												<span class="input-group-addon"><i class="demo-pli-calendar-4"></i></span>
											</div>
										</div>
									</div>

									<div id="application_no_div" >
										<div class="col-md-6">
											<label for="keyword" class="control-label">
												Enter Keywords
												<i id="keyword_change_id" class="fa fa-info-circle" data-placement="bottom" data-toggle="tooltip" title="Enter Application No"></i>
											</label>
											<div class="input-group col-md-12">
												<input type="text" id="keyword" name="keyword" class="form-control " placeholder="Enter Keywords" value="<?=isset($keyword)?$keyword:'';?>">
												
											</div>
										</div>
										
									</div>
									
									 <div class="col-md-3">
										<label class="control-label" for="ward No"><b>Ward No</b><span class="text-danger">*</span> </label>
										<select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
										   <option value="">ALL</option> 
											<?php foreach($wardList as $value):?>
											<option value="<?=$value['ward_mstr_id']?>" <?=(isset($ward_mstr_id))?$ward_mstr_id==$value["ward_mstr_id"]?"SELECTED":"":"";?>><?=$value['ward_no'];?>
											</option>
											<?php endforeach;?>
										</select>
									</div>
									<div class="col-md-2">
										<label class="control-label" for="department_mstr_id">&nbsp;</label>
										<button class="btn btn-success btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
									</div>
								</div>
							</form>
						</div>
					</div>
                    <div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h5 class="panel-title">Forward and Backward List</h5>
						</div>                
						<div class="panel-body table-responsive">
							<table id="demo_dt_basic" class="table table-striped table-bordered " cellspacing="0" width="100%">
								<thead class="bg-trans-dark text-dark">
									<tr>
										<th>#</th>
										<th>Ward No.</th>
										<th>Holding No.</th>
										<th>Application No.</th>
										<th>Owner Name</th>
										<th>Mobile No.</th>
										<th>Forwarded To</th>
                                        <th>Forwad Date</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									//print_r($owner);
									if(isset($posts)):
									if(empty($posts)):
									?>
									<tr>
										<td colspan="7" style="text-align: center;">Data Not Available!!</td>
									</tr>
									<?php else:
									$i=$offset??0;
									foreach ($posts as $value):
									?>
									<tr>
										<td><?=++$i;?></td>
										<td><?=$value["ward_no"];?></td>
										<td><?=$value["holding_no"];?></td>
										<td><?=$value["application_no"];?></td>
										<td><?=$value["applicant_name"];?></td>
										<td><?=$value["mobile_no"];?></td>
										<td>
										<?php
												if($user_type==16)
												{
													echo "Final Approved";
												}
												elseif(in_array($user_type,[12,13,14,15]))
												{
													?>
														<?=$value["user_type"];?>

													<?php
												}
											?>
											
										</td>
                                        <td><?=$value["created_on"];?></td>
										<td>
											<?php
												if($user_type==16)
												{
													?>
													<a class="btn btn-primary" href="<?php echo base_url('Water_EO/eo_approved_view/'.md5($value['apply_connection_id']));?>" role="button">View</a>
													<?php
												}
												elseif(in_array($user_type,[12,13,14,15]))
												{
													?>
														<a class="btn btn-primary" href="<?php echo base_url('WaterApplyNewConnection/water_connection_view/'.md5($value['apply_connection_id']));?>" role="button">View</a>

													<?php
												}
											?>

										</td>
									</tr>
								<?php endforeach;?>
								<?php endif;  ?>
								<?php endif;  ?>
								</tbody>                                
							</table>
                            <?=pagination($count??0);?>
						</div>
					</div>
				</div>
					        
					    
                <!--===================================================-->
                <!--End page content-->
            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
<script>
    $(document).ready(function(){
        var search_by = $('#selected_keys').val();		
        if(search_by!='')
        {
            div_show(search_by);
        }
    });
    
    function div_show(val)
	{
		//alert(val);
		console.log(val);
		if(val=='by_forward_date')
		{
			$('#forward_date_div').show();
			$('#application_no_div').hide();
		}
		else 
		{
			$('#forward_date_div').hide();
			$('#application_no_div').show();
		}
	}
</script>
<?= $this->include('layout_vertical/footer');?>

<!--DataTables [ OPTIONAL ]-->
<script src="<?=base_url();?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script type="text/javascript">
    $('#from_date').datepicker({ 
    	format: "yyyy-mm-dd",
    	weekStart: 0,
    	autoclose:true,
    	todayHighlight:true,
    	todayBtn: "linked",
    	clearBtn:true,
    	daysOfWeekHighlighted:[0]
    });
    $('#to_date').datepicker({ 
    	format: "yyyy-mm-dd",
    	weekStart: 0,
    	autoclose:true,
    	todayHighlight:true,
    	todayBtn: "linked",
    	clearBtn:true,
    	daysOfWeekHighlighted:[0]
    });
	$(document).ready(function() {
        debugger;
        $("#from_date").change(function ()
        {
            var from_date= $("#from_date").val();
            var to_date= $("#to_date").val();

            var startDay = new Date(from_date);
            var endDay = new Date(to_date);

            if((startDay.getTime())>(endDay.getTime()))
            {
                alert("Please select valid To Date!!");
                $("#from_date").val('');
            }
        });
        $("#to_date").change(function ()
        {
            var from_date= $("#from_date").val();
            var to_date= $("#to_date").val();

            var startDay = new Date(from_date);
            var endDay = new Date(to_date);

            if((startDay.getTime())>(endDay.getTime()))
            {
                alert("Please select valid To Date!!");
                $("#to_date").val('');
            }
        });

        $("#btn_search").click(function(){
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            if(from_date=="")
			{
				alert("Please Select From Date");
				$('#from_date').focus();
				return false;
			}

			if(to_date=="")
			{
				alert("Please Select To date");
				$('#to_date').focus();
				return false;
			}
        });
		$('#demo_dt_basic1').DataTable({
			responsive: true,
			dom: 'Bfrtip',
            pagination:false,
	        lengthMenu: [
	            [ 10, 25, 50, -1 ],
	            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
	        ],
	        buttons: [
	        	'pageLength',
	          {
				text: 'excel',
				extend: "excel",
				title: "Report",
				footer: { text: '' },
				exportOptions: { columns: [ 0, 1,2,3,4,5] }
			}, {
				text: 'pdf',
				extend: "pdf",
				title: "Report",
				download: 'open',
				footer: { text: '' },
				exportOptions: { columns: [ 0, 1,2,3,4,5] }
			}]
		});
		
	});

	
 </script>

