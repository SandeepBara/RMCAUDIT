<?php
$app=array();
$consumer=array();
if(isset($collection))
{
    $app = array_filter($collection['result'],function($val){
            if($val['transaction_type']=='New Connection')
                return  $val;
    });

    $consumer = array_filter($collection['result'],function($val){
        if($val['transaction_type']=='Demand Collection')
            return  $val;
    });
}
?>
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
                    <li><a href="#">Tread</a></li>
                    <li class="active">Application Against Denial </li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-bordered panel-dark">
                                <div class="panel-heading">
                                    <h5 class="panel-title">Application Against Denial</h5>
                                </div>
                                <div class="panel-body">
                                    <div class ="row">
                                        <div class="col-md-12">
                                        <form class="form-horizontal" method="post" action="<?=base_url("Trade_report/application_against_denial?page=clr")?>" >
                                                <div class="form-group">
                                                    <div class="col-md-2">
														<label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
														<input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
													</div>
													<div class="col-md-2">
														<label class="control-label" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
														<input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
													</div>
													<div class="col-md-2">
														<label class="control-label" for="ward_id"><b>Ward No.</b><span class="text-danger">*</span> </label>
														<select id="ward_id" name="ward_id" class="form-control">
														   <option value="">ALL</option> 
                                                           <?php
                                                           foreach($ward as $value)
                                                           {
                                                                ?>
                                                                <option value="<?=$value['id']?>" <?=isset($ward_id)  && $ward_id==$value['id'] ? 'selected':''?>><?=$value['ward_no']?></option>
                                                                <?php
                                                           }
                                                           ?>														
														</select> 
													</div>
                                                    
													<div class="col-md-2 ">
														<label class="control-label" for="department_mstr_id">&nbsp;</label>
														<button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
													</div>
												</div>
                                            </form>
                                        </div>
                                    </div>
                                
                                    <div class="panel panel-bordered panel-dark">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Application List</h3>
                                        </div>
                                        <div class="panel-body table-responsive">
                                            <table id="demo_dt_basic" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Application No</th>
                                                    <th>Notice No</th>
                                                    <th>Firm Name</th>
                                                    <th>Address</th>
                                                    <th>Ward No.</th>
                                                    <th>Mobile No</th>
                                                    <th>Denial Approval Date</th>
                                                    <th>Application Applied Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            
                                            if(empty($report))
                                            {
                                            ?>
                                                    <tr>
                                                        <td colspan="10" style="text-align: center;">Data Not Available!!</td>
                                                    </tr>
                                            <?php 
                                            }
                                            else
                                            {
                                                    //$i=$collection['offset'];
                                                    $i=0;
                                                    foreach ($report as $value)
                                                    {
                                            ?>
                                                    <tr>
                                                        <td><?=++$i;?></td>
                                                        <td><?=!empty($value['application_no'])? $value['application_no'] :'N/A'?></td>
                                                        <td><?=!empty($value['notice_no']) ? $value['notice_no'] : 'N/A'?></td>
                                                        <td><?=!empty($value['firm_name']) ? $value['firm_name'] : 'N/A'?></td>
                                                        <td><?=!empty($value['address']) ? $value['address'] : 'N/A'?></td>
                                                        <td><?=!empty($value['ward_no']) ? $value['ward_no'] : 'N/A'?></td>
                                                        <td><?=!empty($value['mobile']) ? $value['mobile'] : 'N/A'?></td>
                                                        <td><?=!empty($value['verify_date']) ? $value['verify_date'] : 'N/A'?></td>
                                                        <td><?=!empty($value['apply_date']) ? $value['apply_date'] : 'N/A'?></td>
                                                                                                                
                                                    </tr>

                                                <?php }?>
                                            <?php }  ?>
                                            </tbody>
                                        </table>
                                        <?=pagination($count,$offset)?>
                                        </div>
                                    </div>

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
<!--DataTables [ OPTIONAL ]-->
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script type="text/javascript">
   

</script>
<script type="text/javascript">
    function myPopup(myURL, title, myWidth, myHeight)
    {
        var left = (screen.width - myWidth) / 2;
        var top = (screen.height - myHeight) / 4;
        var myWindow = window.open(myURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + myWidth + ', height=' + myHeight + ', top=' + top + ', left=' + left);
    }
</script>