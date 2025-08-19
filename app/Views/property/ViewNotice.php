<?=$this->include('layout_vertical/header');?>
<style>
.error{
    color: red;
}
#data_table_view, #data_table_view th, #data_table_view td {
        border: 1px solid black !important;
        border-collapse: collapse !important;
}
#data_table_view td{
    padding : 10px;
}
#data_table_view_first_row td{
    font-weight: 600;
}
</style>
<!--CONTENT CONTAINER-->
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
                <li><a href="#">Property</a></li>
                <li class="active">View Notice</li>
                </ol>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End breadcrumb-->
            </div>

            <div id="page-content">
                
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title">Property Details</h3>
                    </div>

                    
                    <div class="panel-body">

                        <div class="panel panel-bordered panel-dark" style="padding: 10px;">
                            <span class="text text-center" style="font-weight: bold; font-size: 23px; text-align: center; color: black; font-family: font-family Verdana, Arial, Helvetica, sans-serif Verdana, Arial, Helvetica, sans-serif;">
                                Your generated notice no. is 
                                    <span style="color: #ff6a00">NOTICE/<?=$notice["notice_no"];?></span>. 
                                    You can use this notice no. for future reference.
                            </span>
                            <br>
                        </div>
                        
                        <div class="row">
                            <label class="col-md-3">Hoding No </label>
                            <div class="col-md-3 text-bold pad-btm">
                            <?=!empty($property['new_holding_no'])?$property['new_holding_no']:$property['holding_no'];?>
                            </div>
                            
                            <label class="col-md-3">Ward No</label>
                            <div class="col-md-3 text-bold pad-btm">
                                <?=!empty($property['new_ward_no'])?$property['new_ward_no']:$property['ward_no'];?>
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-md-3">Notice No.</label>
                            <div class="col-md-3 text-bold pad-btm">
                            NOTICE/<?=$notice["notice_no"];?>
                            </div>
                            

                            <label class="col-md-3">Notice Date</label>
                            <div class="col-md-3 text-bold pad-btm">
                            <?=date('d-m-Y', strtotime($notice["notice_date"]));?>
                            </div>
                        </div>

                    
                        <div class="row">
                            <label class="col-md-3">Notice Type</label>
                            <div class="col-md-3 text-bold pad-btm">
                                <?=$notice['notice_type'];?>
                            </div>
                            
                            <label class="col-md-3">Generated Date</label>
                            <div class="col-md-3 text-bold pad-btm">
                                <?=date('d-m-Y', strtotime($notice["created_on"]));?>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-3">Owners Name</label>
                            <div class="col-md-3 text-bold pad-btm">
                                <?=$property['owner_name'];?>
                            </div>
                            
                            <label class="col-md-3">Owner Mobile No.</label>
                            <div class="col-md-3 text-bold pad-btm">
                                <?=$property['mobile_no'];?>
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-md-3">Address</label>
                            <div class="col-md-9 text-bold pad-btm">
                                <?=$property['prop_address'];?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if($notice['notice_type'] == 'Demand') {?>
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title">Demand Details
                        </h3>
                    </div>
                    <div class="panel-body">
                        <table id="data_table_view" style="width: 100%; font-weight: 300;margin-top:15px;">
                            <tr id="data_table_view_first_row">
                                <td width="20%">Demand From</td>
                                <td width="20%">Demand Upto</td>
                                <td width="20%">Demand (In Rs.)</td>
                                <td width="40%">Total (In Rs.)</td>
                            </tr>
                            <tr >
                                <td ><?=$notice["from_qtr"]." / ".$notice["from_fyear"];?></td>
                                <td ><?=$notice["upto_qtr"]." / ".$notice["upto_fyear"];?></td>
                                <td ><?=$notice["demand_amount"];?></td>
                                <td ><strong><?=$notice["demand_amount"];?></strong></td>
                            </tr>
                            <tr style="font-weight:600">
                                <td colspan="3" align="right">1% Interest</td>
                                <td ><?=$notice["penalty"];?></td>
                            </tr>
                            <tr style="font-weight:600">
                                <td colspan="3" align="right" >Total Payable</td>
                                <td><?=round(($notice["penalty"]+$notice["demand_amount"]));?>.00</td>
                            </tr>
                            <tr style="font-weight:600">
                                <td colspan="3" align="right" >Total Demand (in words)</td>
                                <td><?=ucwords(getIndianCurrency(round($notice["penalty"]+$notice["demand_amount"])));?> Only.</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <?php } ?>
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title">Notice Deactive </h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                        <form method="POST">
                            <div class="form-group">
                                <label class="col-md-2 text-bold">Remarks</label>
                                <div class="col-md-10">
                                    <textarea id="level1_remarks" name="level1_remarks" class="form-control" placeholder="Please Enter Remark" required minlength="5"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2">&nbsp;&nbsp;&nbsp;</label>
                                <div class="col-md-10" style="padding: 20px 20px 20px 10px;">
                                    <button type="submit" class="btn btn-danger" id="btn_deactive" name="btn_deactive">Deactivate</button>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--End page-content-->
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
