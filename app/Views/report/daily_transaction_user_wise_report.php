<?= $this->include('layout_vertical/header');?>
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<!--DataTables [ OPTIONAL ]-->
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
                    <li><a href="#">Report</a></li>
                    <li class="active">All Module User Wise Collection List</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h5 class="panel-title">All Module User Wise Collection List</h5>
                                </div>
                                <div class="panel-body">
                                    <div class ="row">
                                        <div class="col-md-12">
                                            <form class="form-horizontal" method="post" action="<?=base_url('');?>/DailyTransactionUserWise/report">
                                                <div class="form-group">
                                                <div class="col-md-3">
                                                    <label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="control-label" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
                                                    <div class="input-group">
                                                        <input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                   
                                                    <label class="control-label" for="Ward"><b>Employee Name</b><span class="text-danger">*</span> </label>
                                                    <select id="tran_by_emp_details_id" name="tran_by_emp_details_id" class="form-control">
                                                       <option value="" <?php if($tran_by_emp_details_id==""){echo "selected";} ?>>ALL</option> 
                                                       <?php if($userList): ?> 
                                                        <?php foreach($userList as $value):?>
                                                        <option value="<?=$value['id']?>" <?=(isset($tran_by_emp_details_id))?$tran_by_emp_details_id==$value['id']?"SELECTED":"":"";?>><?=$value['emp_name']." ".$value['middle_name']." ".$value['last_name'];?>
                                                        </option>
                                                        <?php endforeach;?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="control-label" for="department_mstr_id">&nbsp;</label>
                                                    <button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
                                                </div>
                                            </div>
                                            <?php /*if(!isset($users_list)):?>
                                                 <div class ="row">
                                                    <div class="col-md-12 text-danger text-bold">
                                                        Record Does Not Exists!!!
                                                    </div>
                                                </div>
                                                <?php endif;*/ ?>
                                            </form>
                                        </div>
                                    </div>
                                <?php if(isset($users_list)): ?>
                                    <?php foreach ($users_list as $values):$i=0; 

                                        //echo $a[$values['id']]['emp_details']['emp_name'];
                                        if(round($a[$values['id']]['waterTotalAmount']+$a[$values['id']]['propertyTotalAmount']+$a[$values['id']]['tradeTotalAmount'])>0):
                                            
                                        ?>
                                    <div class="row">
                                        <div class="panel panel-bordered panel-dark">
											<div class="panel-heading">
												<div class="col-md-4">
													<h5 style="color:#fff;">Name : <?=$a[$values['id']]['emp_details']['emp_name']!=""?$a[$values['id']]['emp_details']['emp_name']." ".$a[$values['id']]['emp_details']['middle_name']." ".$a[$values['id']]['emp_details']['last_name']:"N/A";?></h5>
												</div>
												<div class="col-md-4">
													<h5  style="color:#fff;">Phone : <?=$a[$values['id']]['emp_details']['personal_phone_no']!=""?$a[$values['id']]['emp_details']['personal_phone_no']:"N/A";?></h5>
												</div>
												<div class="col-md-4">
													<h5  style="color:#fff;">Total Collection : <?=$a[$values['id']]['waterTotalAmount']+$a[$values['id']]['propertyTotalAmount']+$a[$values['id']]['tradeTotalAmount']!=0?round($a[$values['id']]['waterTotalAmount']+$a[$values['id']]['propertyTotalAmount']+$a[$values['id']]['tradeTotalAmount']).".00":"0";?></h5>
												</div>
											</div>
                                        
                                        <?php 



                                        if(isset($a[$values['id']]['watertrans'])):?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead class="bg-trans-dark text-dark">
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Module</th>
                                                                    <th>Transaction Date</th>
                                                                    <th>Payment Type</th>
                                                                    <th>Amount</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php foreach ($a[$values['id']]['watertrans'] as $value): ?>
                                                                <tr>
                                                                    <td><?=++$i;?></td>
                                                                    <td><?="Water";?></td>
                                                                    <td><?=$value['transaction_date']!=""?date('d-m-Y',strtotime($value['transaction_date'])):"";?></td>
                                                                    <td><?=$value['payment_mode']!=""?$value['payment_mode']:"";?></td>
                                                                    <td><?=$value['payable_amount']!=""?round($value['payable_amount']).".00":"";?></td>
                                                                </tr>
                                                            <?php endforeach;?>
                                                         
                                        <?php endif; ?>


                                           <?php if(isset($a[$values['id']]['tradetrans'])):?>
                                         
                                                            <?php foreach ($a[$values['id']]['tradetrans'] as $value): ?>
                                                                <tr>
                                                                    <td><?=++$i;?></td>
                                                                    <td><?="Trade";?></td>
                                                                    <td><?=$value['transaction_date']!=""?date('d-m-Y',strtotime($value['transaction_date'])):"";?></td>
                                                                 
                                                                    <td><?=$value['payment_mode']!=""?$value['payment_mode']:"";?></td>

                                                                    <td><?=$value['payable_amount']!=""?round($value['payable_amount']).".00":"";?></td>
                                                                </tr>
                                                            <?php endforeach;?>
                                                
                                        <?php endif; ?>


                                           <?php if(isset($a[$values['id']]['propertytrans'])):?>
                                          
                                                            <?php foreach ($a[$values['id']]['propertytrans'] as $value): ?>
                                                                <tr>
                                                                    <td><?=++$i;?></td>
                                                                    <td><?=$value['tran_type'];?></td>
                                                                    <td><?=$value['tran_date']!=""?date('d-m-Y',strtotime($value['tran_date'])):"";?></td>
                                                                    <td><?=$value['payment_mode']!=""?$value['payment_mode']:"";?></td>
                                                                    <td><?=$value['payable_amount']!=""?round($value['payable_amount']).".00":"";?></td>
                                                                </tr>
                                                            <?php endforeach;?>
                                                         
                                        <?php endif; ?>

                                                         </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

										</div>
                                    </div>
                                        <?php endif; ?>
                                    <?php endforeach;?>
                                <?php endif; ?>
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
    $(document).ready(function(){
        $('#demo_dt_basic').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        });
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
            }
        });
        $("#from_date").change(function(){$(this).css('border-color','');});
        $("#to_date").change(function(){$(this).css('border-color','');});
    });
</script>
<script type="text/javascript">
$(function () {
    var table = $('#example').DataTable();
    $("#btnExport").click(function(e) 
    {
        table.page.len( -1 ).draw();
        window.open('data:application/vnd.ms-excel,' + 
            encodeURIComponent($('#example').parent().html()));
      setTimeout(function(){
        table.page.len(10).draw();
      }, 1000)

    });
});
</script>>