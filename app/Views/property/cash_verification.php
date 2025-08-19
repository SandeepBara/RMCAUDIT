<?= $this->include('layout_vertical/header');?>



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
        <li><a href="#">Accounts</a></li>
        <li class="active">Cash Verification</li>
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <!--===================================================-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Collection Details</h5>
            </div>
            <div class="panel-body">
                <div class ="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" method="post" action="<?=base_url('');?>/CashVerification/details">
                            <div class="form-group">
                                <div class="col-md-3">
	                                <label class="control-label" for="tran_date"><b>Date</b><span class="text-danger">*</span> </label>
									<input type="date" id="tran_date" name="tran_date" class="form-control" value="<?=(isset($tran_date))?$tran_date:date('Y-m-d');?>" max="<?=date("Y-m-d");?>">
                            	</div>
                           		<div class="col-md-3">
	                                <label class="control-label" for="Employee Nme"><b>Employee Name</b><span class="text-danger">*</span> </label>
	                                <select id="employee_id" name="employee_id" class="form-control">
	                                   <option value="">ALL</option>  
	                                    <?php foreach($emplist as $value):?>
	                                    <option value="<?=$value['id']?>" <?=(isset($employee_id))?$employee_id==$value["id"]?"SELECTED":"":"";?>><?=$value['emp_name']." ".$value['last_name'].'/'.$value['employee_code'];?>
	                                    </option>
	                                    <?php endforeach;?>
	                                </select>
                           		</div>
                           		<div class="col-md-3">
                                    <label class="control-label" for="collection">&nbsp;</label>
                                    <button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h5 class="panel-title">Employee Details</h5>
                    </div>
                    <div class="panel-body">
                        
                                <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>SI.No</th>
                                            <th>Employee Name</th>
                                            <th>Property</th>
                                            <th>GB SAF</th>
                                            <th>Water</th>
                                            <th>Trade</th>
                                            <th>Collected Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        
                                            $i=0;
                                            foreach ($cash_verification_detals as $value):
                                                ?>
                                                    <tr>
                                                        <?php if($value['total']>0){?>
                                                            <td><?=++$i;?></td>
                                                            <td><?=$value['emp_name'];?></td>
                                                            <td><?=$value['prop_saf'];?></td>
                                                            <td><?=$value['gsaf'];?></td>
                                                            <td><?=$value['water'];?></td>
                                                            <td><?=$value['trade'];?></td>
                                                            <td><?=$value['total'];?></td>
                                                            <td>
                                                                <a class="btn btn-primary" onclick="window.open('<?php echo base_url('CashVerification/verifyCash/'.md5($value['tran_by_emp_details_id']).'/'.$tran_date);?>', 'newwindow', 'width=1000, height=1000'); return false;" href="#">View</a>
                                                            </td>
                                                    <?php  } ?>
                                                    </tr>
                                                <?php 
                                            endforeach;
                                        
                                        ?>
                                    </tbody>
                                </table>
                            
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


<script type="text/javascript">

$('#btn_search').click(function(){
	var tran_date = $('#tran_date').val();
	//var employee_id = $('#employee_id').val();
	var process = true;
	if(tran_date==""){
		$("#tran_date").css({"border-color":"red"});
        $("#tran_date").focus();
        process = false;
	}
	/*if(employee_id==""){
		$("#employee_id").css({"border-color":"red"});
        $("#employee_id").focus();
        process = false;
	}*/
	return process;
});
$("#tran_date").change(function(){$(this).css('border-color','');});
/*$("#employee_id").change(function(){$(this).css('border-color','');});*/
