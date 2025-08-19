<?php 
	if(isset($mobile) && !empty($mobile))
	{
		echo $this->include('layout_mobi/header');
		?>
		<script src="<?=base_url();?>/public/assets/js/jquery.min.js"></script>
		<?php

	}
	else
		echo $this->include('layout_vertical/header');
?>

<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">       
        <div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">Search Application</h3>
			</div>
			<div class="panel-body">
                <form class="form-horizontal" method="get">							
                    <div class="form-group">
                        <div class="col-md-12 text-center bg-mint ">
                            <div class="radio">
                                <input type="radio" id="by_forward_date" class="magic-radio" name="by_holding_owner_dtl" value="BY_FORWRD_DATE" checked onchange="div_show(this.value)" <?=isset($by_holding_owner_dtl) && $by_holding_owner_dtl=='BY_FORWRD_DATE'?'checked':''?>>
                                <label for="by_forward_date" >By Inspection Date</label>
                                <input type="radio" id="by_holding_dtl" class="magic-radio" name="by_holding_owner_dtl" value="BY_APPLICATION_NO"  onchange="div_show(this.value); $('#keyword_change_id').attr('data-original-title', 'Enter Application No');" <?=isset($by_holding_owner_dtl) && $by_holding_owner_dtl=='BY_APPLICATION_NO'?'checked':''?>>
                                <label for="by_holding_dtl">By Application No.</label>

                                <input type="radio" id="by_owner_dtl" class="magic-radio" name="by_holding_owner_dtl" value="BY_OWNER"  onchange="div_show(this.value); $('#keyword_change_id').attr('data-original-title', 'Enter Register Mobile No. Or Owner Name Or Father Name');" <?=isset($by_holding_owner_dtl) && $by_holding_owner_dtl=='BY_OWNER'?'checked':''?>>
                                <label for="by_owner_dtl">By Owner Details</label>
                                <input type="hidden" id="selected_keys" value="<?=isset($by_holding_owner_dtl) ? $by_holding_owner_dtl:'';?>"/>
                                
                            </div>
                        </div>

                        <div id="forward_date_div">
                            <div class=" col-md-3">
                                <label class="control-label" for="from_date"><b>From Date</b> </label>
                                <div class=" <?=!isset($mobile)?'input-group':'';?> date">
                                    <input type="date" id="from_date" name="from_date" class="form-control col-sm-12 mask_date" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" >
                                   
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label" for="to_date"><b>To Date</b> </label>
                                <div class="<?=!isset($mobile)?'input-group':'';?> date">
                                    <input type="date" id="to_date" name="to_date" class="form-control mask_date" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" >
                                   
                                </div>
                            </div>
                        </div>

                        <div id="application_no_div" style="display:none;">
                            <div class="col-md-6">
                                <label for="keyword" class="control-label">
                                    Enter Keywords
                                    <i id="keyword_change_id" class="fa fa-info-circle" data-placement="bottom" data-toggle="tooltip" title="Enter Application No"></i>
                                </label>
                                <div class="<?=!isset($mobile)?'input-group':'';?> col-md-12">
                                    <input type="text" id="keyword" name="keyword" class="form-control " placeholder="Enter Keywords" value="<?=isset($keyword)?$keyword:'';?>">
                                    
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="col-md-3">
                            <label class="control-label" for="ward No"><b>Ward No</b><span class="text-danger">*</span> </label>
                            <select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
                                <option value="">ALL</option> 
                                <?php foreach($ward_list as $value):?>
                                <option value="<?=$value['ward_mstr_id']?>" <?=(isset($ward_mstr_id))?($ward_mstr_id==$value["ward_mstr_id"]?"SELECTED":""):"";?>><?=$value['ward_no'];?>
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
				<h3 class="panel-title">APPLICATION DETAILS</h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="table-responsive">
						<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>#</th>
									<th>Ward No.</th>
									<th>Application No.</th>
									<th>Consumer Name</th>
									<th>Mobile No.</th>
									<th>Connection Type</th>
									<th>Apply Date</th>
                                    <th>Inspection Date</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php if(isset($site_insp_dtls)):
									if(empty($site_insp_dtls)):
								?>
								<tr>
									<td colspan="9" style="text-align: center;">Data Not Available!!</td>
								</tr>
								<?php else:
								$i= $offset??0;
								foreach ($site_insp_dtls as $val):
								?>
								<tr>
									<td><?=++$i;?></td>
									<td><?=$val['ward_no'];?></td>
									<td><?=$val['application_no'];?></td>
									<td><?=$val['applicant_name'];?></td>
									<td><?=$val['mobile_no'];?></td>
									<td><?=$val['connection_type'];?></td>
									<td><?=$val['apply_date'];?></td>
                                    <td><?=$val['inspection_date'];?></td>
									<td>
                                        <a href="<?php echo base_url().'/WaterApplyNewConnection/water_connection_view/'.md5($val['apply_connection_id']); ?>" class="btn btn-info">View</a>
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
		</div>
    </div>
</div>
    <!--End page content-->
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
		// alert(val);
		console.log(val);
		if(val=='BY_FORWRD_DATE')
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
<?php 
	if(isset($mobile) && !empty($mobile))
		echo $this->include('layout_mobi/footer');
	else
		echo$this->include('layout_vertical/footer');
?>
