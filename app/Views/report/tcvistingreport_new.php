
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
                    <li><a href="#">Report</a></li>
                    <li class="active">User Activity Details</li>
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
                                    <h5 class="panel-title">Activity Details</h5>
                                </div>
                                <div class="panel-body">
                                    <div class ="row">
                                        <div class="col-md-12">
                                            <form class="form-horizontal" method="post" >
                                            <div class="form-group">
                                                <div class="col-md-3">
                                                     <label class="control-label" for="ward No"><b>Date From</b><span class="text-danger">*</span> </label>
                                                     <input type="date" name="date_from" id="date_from" value="<?php echo $date_from;?>" class="form-control" max="<?=date('Y-m-d');?>">
                                                </div>
                                                 <div class="col-md-3">
                                                     <label class="control-label" for="ward No"><b>Date Upto</b><span class="text-danger">*</span> </label>
                                                     <input type="date" name="date_upto" id="date_upto" value="<?php echo $date_upto;?>" class="form-control" max="<?=date('Y-m-d');?>">
                                                </div>
                                            <input type="hidden" name="curr_date" id="curr_date" value="<?php echo $curr_date; ?>">
                                                <div class="col-md-3">
                                                      <label class="control-label" for="ward No"><b>User List</b><span class="text-danger">*</span> </label>
                                                          <select id="emp_dtl_id" name="emp_dtl_id" class="form-control" >
                                                       <option value="" selected="selected">All</option> 
                                                        <?php foreach($userlist as $value):?>
                                                        <option value="<?=$value['id']?>" <?=(isset($emp_dtl_id))?$emp_dtl_id==$value["id"]?"SELECTED":"":"";?>><?=$value['emp_name'].' / '.$value['employee_code'];?>
                                                        </option>
                                                        <?php endforeach;?>
                                                    </select>

                                                </div>
                                                <div class="col-md-2">
                                                    <label class="control-label" for="department_mstr_id">&nbsp;</label>
                                                    <button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="row">
                                    <div class="table-responsive">
                                     <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                               <th colspan="2" style="text-align: center; background-color: #d4d4d4;">Login Details</th>
                                               <th colspan="6" style="text-align: center; background-color: #d9eaf9;">Property</th> 
                                               <th colspan="3" style="text-align: center; background-color: #edd9f9;">Water</th> 
                                               <th colspan="3" style="text-align: center; background-color: #e4f9d9;">Trade</th> 
                                               
                                            </tr>
                                            <tr>
                                                <th style="text-align: center; background-color: #d4d4d4;">TC Name</th>
                                                <th style="text-align: center; background-color: #d4d4d4;">Login Details</th>
                                                <th style="text-align: center; background-color: #d9eaf9;">Form Distributed</th>
                                                <th style="text-align: center; background-color: #d9eaf9;">SAF Done</th>
                                                <th style="text-align: center; background-color: #d9eaf9;">SAF Payment</th>
                                                <th style="text-align: center; background-color: #d9eaf9;">Property Payment</th>
                                                <th style="text-align: center; background-color: #d9eaf9;">Field Verification</th>
                                                <th style="text-align: center; background-color: #d9eaf9;">Geo Tagged</th>
                                                <th style="text-align: center; background-color: #edd9f9;">Application Applied</th>
                                                <th style="text-align: center; background-color: #edd9f9;">Application Payment</th>
                                                <th style="text-align: center; background-color: #edd9f9;">Consumer Payment</th>
                                                <th style="text-align: center; background-color: #e4f9d9;">Application Applied</th>
                                                <th style="text-align: center; background-color: #e4f9d9;">Application Payment</th>

                                            </tr>
                                        </thead>
                              <tbody>
                                  
                                  <?php
                                  if($userlists):
                                    foreach($userlists as $val):
                                        //echo $val['id'];

                                  ?>
                                        <tr>
                                            
                                            <td nowrap="nowrap" style="width: 10px; font-weight: bold;"><?php echo $val['emp_name'].' / '.$val['employee_code']; ?></td>
                                            <td>
                                                <table class="table table-bordered">
                                                    <?php
                                                    if($a[$val['id']]['userlogin']){
                                                        foreach ($a[$val['id']]['userlogin'] as $value) {
                                                    
                                                    ?>
                                                <tr>

                                                <td colspan="2" nowrap="nowrap"><?php echo date('d-m-Y',strtotime($value['date'])).' / '.$value['time'] ?></td></tr>
                                           
                                           
                                                    <?php
                                                        }
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <td style="color: red;">N/A</td>
                                                        <?php
                                                    }
                                                    ?>
                                                   </table>
                                            </td>       
                                            

                                             <td>
                                                <table class="table table-bordered">
                                                    <?php
                                                    if($a[$val['id']]['form_distribute']){
                                                        foreach ($a[$val['id']]['form_distribute'] as $value) {
                                                    
                                                    ?>
                                                <tr>
                                                <td nowrap="nowrap"><?php echo date('d-m-Y',strtotime($value['date'])).' / '.$value['time'] ?></td></tr>
                                           
                                           
                                                    <?php
                                                        }
                                                   }
                                                    else
                                                    {
                                                    ?>
                                                     <td style="color: red;">N/A</td>
                                                    <?php
                                                    }
                                                    ?>
                                                   </table>
                                            </td>

                                              <td>
                                                <table class="table table-bordered">
                                                    <?php
                                                    if($a[$val['id']]['saf_done']){
                                                        foreach ($a[$val['id']]['saf_done'] as $value) {
                                                    
                                                    ?>
                                                <tr>
                                                <td nowrap="nowrap"><?php echo date('d-m-Y',strtotime($value['date'])).' / '.$value['time'] ?></td></tr>
                                           
                                           
                                                     <?php
                                                        }
                                                   }
                                                    else
                                                    {
                                                    ?>
                                                     <tr><td style="color: red;">N/A</td></tr>
                                                    <?php
                                                    }
                                                    ?>
                                                   </table>
                                            </td>

                                             <td>
                                                <table class="table table-bordered">
                                                    <?php
                                                    if($a[$val['id']]['saf_payment']){
                                                        foreach ($a[$val['id']]['saf_payment'] as $value) {
                                                    
                                                    ?>
                                                <tr>
                                                <td nowrap="nowrap"><?php echo date('d-m-Y',strtotime($value['date'])).' / '.$value['time'] ?></td></tr>
                                           
                                           
                                                    <?php
                                                        }
                                                   }
                                                    else
                                                    {
                                                    ?>
                                                     <td style="color: red;">N/A</td>
                                                    <?php
                                                    }
                                                    ?>
                                                   </table>
                                            </td> 

                                               <td>
                                                <table class="table table-bordered">
                                                    <?php
                                                    if($a[$val['id']]['prop_payment']){
                                                        foreach ($a[$val['id']]['prop_payment'] as $value) {
                                                    
                                                    ?>
                                                <tr>
                                                <td nowrap="nowrap"><?php echo date('d-m-Y',strtotime($value['date'])).' / '.$value['time'] ?></td></tr>
                                           
                                            <?php
                                                        }
                                                   }
                                                    else
                                                    {
                                                    ?>
                                                     <td style="color: red;">N/A</td>
                                                    <?php
                                                    }
                                                    ?>
                                                   </table>
                                            </td> 

                                            
                                             <td>
                                                <table class="table table-bordered">
                                                    <?php
                                                    if($a[$val['id']]['field_verf']){
                                                        foreach ($a[$val['id']]['field_verf'] as $value) {
                                                    
                                                    ?>
                                                <tr>
                                                <td nowrap="nowrap"><?php echo date('d-m-Y',strtotime($value['date'])).' / '.$value['time'] ?></td></tr>
                                           
                                            <?php
                                                        }
                                                   }
                                                    else
                                                    {
                                                    ?>
                                                     <td style="color: red;">N/A</td>
                                                    <?php
                                                    }
                                                    ?>
                                                   </table>
                                            </td>

                                          <td>
                                                <table class="table table-bordered">
                                                    <?php
                                                    if($a[$val['id']]['geotagged']){
                                                        foreach ($a[$val['id']]['geotagged'] as $value) {
                                                    
                                                    ?>
                                                <tr>
                                                <td nowrap="nowrap"><?php echo date('d-m-Y',strtotime($value['date'])).' / '.$value['time'] ?></td></tr>
                                           
                                            <?php
                                                        }
                                                   }
                                                    else
                                                    {
                                                    ?>
                                                   <td style="color: red;">N/A</td>
                                                    <?php
                                                    }
                                                    ?>
                                                   </table>
                                            </td>



                                            <td>
                                                <table class="table table-bordered">
                                                    <?php
                                                    if($a[$val['id']]['water_application_applied']){
                                                        foreach ($a[$val['id']]['water_application_applied'] as $value) {
                                                    
                                                    ?>
                                                <tr>
                                                <td nowrap="nowrap"><?php echo date('d-m-Y',strtotime($value['date'])).' / '.$value['time'] ?></td></tr>
                                           
                                                    
                                                    <?php
                                                        }
                                                   }
                                                    else
                                                    {
                                                    ?>
                                                     <td style="color: red;">N/A</td>
                                                    <?php
                                                    }
                                                    ?>
                                                   </table>
                                            </td>



                                            <td>
                                                <table class="table table-bordered">
                                                    <?php
                                                    if($a[$val['id']]['water_application_payment']){
                                                        foreach ($a[$val['id']]['water_application_payment'] as $value) {
                                                    
                                                    ?>
                                                <tr>
                                                <td nowrap="nowrap"><?php echo date('d-m-Y',strtotime($value['date'])).' / '.$value['time'] ?></td></tr>
                                           
                                                    
                                                    <?php
                                                        }
                                                   }
                                                    else
                                                    {
                                                    ?>
                                                     <td style="color: red;">N/A</td>
                                                    <?php
                                                    }
                                                    ?>
                                                   </table>
                                            </td>

                                             <td>
                                                <table class="table table-bordered">
                                                    <?php
                                                    if($a[$val['id']]['consumer_payment']){
                                                        foreach ($a[$val['id']]['consumer_payment'] as $value) {
                                                    
                                                    ?>
                                                <tr>
                                                <td nowrap="nowrap"><?php echo date('d-m-Y',strtotime($value['date'])).' / '.$value['time'] ?></td></tr>
                                           
                                                    
                                                    <?php
                                                        }
                                                   }
                                                    else
                                                    {
                                                    ?>
                                                     <td style="color: red;">N/A</td>
                                                    <?php
                                                    }
                                                    ?>
                                                   </table>
                                            </td>

                                             <td>
                                                <table class="table table-bordered">
                                                    <?php
                                                    if($a[$val['id']]['trade_application_applied']){
                                                        foreach ($a[$val['id']]['trade_application_applied'] as $value) {
                                                    
                                                    ?>
                                                <tr>
                                                <td nowrap="nowrap"><?php echo date('d-m-Y',strtotime($value['date'])).' / '.$value['time'] ?></td></tr>
                                           
                                                    
                                                    <?php
                                                        }
                                                   }
                                                    else
                                                    {
                                                    ?>
                                                     <td style="color: red;">N/A</td>
                                                    <?php
                                                    }
                                                    ?>
                                                   </table>
                                            </td>

                                             <td>
                                                <table class="table table-bordered">
                                                    <?php
                                                    if($a[$val['id']]['trade_application_payment']){
                                                        foreach ($a[$val['id']]['trade_application_payment'] as $value) {
                                                    
                                                    ?>
                                                <tr>
                                                <td nowrap="nowrap"><?php echo date('d-m-Y',strtotime($value['date'])).' / '.$value['time'] ?></td></tr>
                                           
                                                    
                                                    <?php
                                                        }
                                                   }
                                                    else
                                                    {
                                                    ?>
                                                     <td style="color: red;">N/A</td>
                                                    <?php
                                                    }
                                                    ?>
                                                   </table>
                                            </td>

                                             </tr>


                                              <?php
                                             endforeach;
                                                endif;

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
                <!--===================================================-->
                <!--End page content-->
            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->

  <script type="text/javascript">

  document.getElementById('date_from').onchange = function() {
    
        var date_from=document.getElementById('date_from').value;
        var date_upto=document.getElementById('date_upto').value;

        var curr_date=$("#curr_date").val();

        if(date_from>curr_date)
        {
            alert('Date From Should not be greater than Current Date');
            $("#date_from").val(curr_date);
        }
        else if(date_upto<date_from)
        {
             alert('Date From Should not be less than Date Upto');
            $("#date_from").val(curr_date);
        }

    }
    document.getElementById('date_upto').onchange = function() {
    
        var date_upto=document.getElementById('date_upto').value;
        var date_from=document.getElementById('date_from').value;

        var curr_date=$("#curr_date").val();
        
        if(date_upto>curr_date)
        {
            alert('Date Upto Should not be greater than Current Date');
            $("#date_upto").val(curr_date);
        }
        else if(date_upto<date_from)
        {
            alert('Date Upto Should not be greater than Date From');
            $("#date_upto").val(curr_date);
        }

    }
  </script>
<?= $this->include('layout_vertical/footer');?>
<!--DataTables [ OPTIONAL ]-->
