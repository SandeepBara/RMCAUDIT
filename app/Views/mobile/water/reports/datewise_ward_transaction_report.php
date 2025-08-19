<?=$this->include("layout_mobi/header");?>
<?php
$demand = array_filter($transaction['result'],function($val){
                    //print_var($val['transaction_type']);
                    return('Demand Collection'==trim($val['transaction_type']));
                    });
$new_connection=array_filter($transaction['result'],function($val){
    return(in_array(trim($val['transaction_type']),['New Connection','Site Inspection']));
    });

?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
           
			<div class="panel-heading flex" style="display: flex;">
				<div style="flex:1;">
					<h3 class="panel-title"><b style="color:white;">Search By</b></h3>
				</div>
				<div style="flex:1;text-align:right"><button onclick="history.go(-1)" class="btn btn-info btn_wait_load">Back</button></div>

			</div>
            <div class="panel-body">
				<form class="form-horizontal" method="post" >
					<div class="row">
						<div class="col-sm-6">
							<label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
						</div>
						<div class="col-sm-6 pad-btm">
							<input type="date" id="from_date" name="from_date" class="form-control"  value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<label class="control-label" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
						</div>
						<div class="col-sm-6 pad-btm">
							<input type="date" id="to_date" name="to_date" class="form-control" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<label class="control-label" for="Payment Mode"><b>Ward</b><span class="text-danger">*</span> </label>
						</div>
						<div class="col-sm-6 pad-btm">
							<select id="ward_id" name="ward_id" class="form-control">
							   <option value="">ALL</option> 
								<?php foreach($wardList as $value):?>
								<option value="<?=$value['id']?>" <?=(isset($ward_id))?($ward_id==$value["id"]?"SELECTED":""):"";?>><?=$value['ward_no'];?>
								</option>
								<?php endforeach;?>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<button class="btn btn-success" style="width:100%" id="btn_search" name="btn_search" type="submit">Submit</button>
						</div>
					</div>
				</form>
            </div>
        </div>
		<?php
                if(!empty($new_connection))
                {
                ?>
                <div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title"> New connection Transaction</h3>
					</div>
					<div class="panel-body table-responsive">
						<table class="table table-bordered table-responsive">
							<thead class="bg-trans-dark text-dark">
								<tr>
									<th>S. No.</th>
									<th>Collection Type</th>
									<th>Application No. </th>
                                    <th>Date</th>
									<th>Amount</th>
									<th>Payment Mode</th>                                    
								</tr>
							
							</thead>
							<tbody>
								<?php
                				//print_r($site_inspection_details);
								if($transaction)
								{
                                    $i=0;
                                    $sum=0;
                                    foreach($new_connection as $val)
                                    {
                                        $sum+=$val['paid_amount'];
                                        ?>
                                        <tr>
                                            <td><?=++$i?></td>
                                            <td class="bolder"><?=$val['transaction_type']?></td>
                                            <td class="bolder"><?=trim($val['transaction_type'])=='Demand Collection'? $val['consumer_no'] : $val['application_no']?></td>
                                            <td class="bolder"><?=$val['created_on']?></td>
                                            <td class="bolder"><?=$val['paid_amount']?></td>
                                            <td class="bolder"><?=$val['payment_mode']?></td>
                                        </tr>
                                        
                                        <?php
                                    }
                                    ?>
                                        <tr>
                                            <td colspan="4" class='text-center bolder'><b>Total</b></td>
                                            <td class="bolder"><?= sprintf("%.2f",$sum)//$sum?></td>
                                            
                                        </tr>
                                    <?php
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
                <?php
                }
                if(!empty($demand))
                {
                ?>
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title"> Demand Transaction Details</h3>
					</div>
					<div class="panel-body table-responsive">
						<table class="table table-bordered table-responsive">
							<thead class="bg-trans-dark text-dark">
								<tr>
									<th>S. No.</th>
									<th>Collection Type</th>
									<th>Consumer. No. </th>
                                    <th>Date</th>
									<th>Amount</th>
									<th>Payment Mode</th>                                    
								</tr>
							
							</thead>
							<tbody>
								<?php
                				//print_r($site_inspection_details);
								if($transaction)
								{
                                    $i=0;
                                    $sum=0;
                                    foreach($demand as $val)
                                    {
                                        $sum+=$val['paid_amount'];
                                        ?>
                                        <tr>
                                            <td><?=++$i?></td>
                                            <td class="bolder"><?=$val['transaction_type']?></td>
                                            <td class="bolder"><?=trim($val['transaction_type'])=='Demand Collection'? $val['consumer_no'] : $val['application_no']?></td>
                                            <td class="bolder"><?=$val['created_on']?></td>
                                            <td class="bolder"><?=$val['paid_amount']?></td>
                                            <td class="bolder"><?=$val['payment_mode']?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                        <tr>
                                            <td colspan="4" class='text-center bolder'><b>Total</b></td>
                                            <td class="bolder"><?=sprintf("%.2f",$sum)?></td>
                                            
                                        </tr>
                                    <?php
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
                <?php
                }
                ?>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>

<script type="text/javascript">
    $(document).ready(function(){

        $('#btn_search').click(function(){
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if(from_date=="")
            {
                $("#from_date").css({"border-color":"red"});
                $("#from_date").focus();
                return false;
            }
            if(to_date=="")
            {
                $("#to_date").css({"border-color":"red"});
                $("#to_date").focus();
                return false;
            }
            if(to_date<from_date)
            {
                alert("To Date Should Be Greater Than Or Equals To From Date");
                $("#to_date").css({"border-color":"red"});
                $("#to_date").focus();
                return false;
            }else{
            	$('#btn_search').html('Please Wait...');
            }
        });
        $("#from_date").change(function(){$(this).css('border-color','');});
        $("#to_date").change(function(){$(this).css('border-color','');});
    });

</script>                