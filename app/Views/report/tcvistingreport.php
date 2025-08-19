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
                                                          <select id="emp_dtl_id" name="emp_dtl_id" class="form-control" required="required">
                                                       <option value="">Select</option> 
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
                                               <th>Login Details</th>
                                               <th colspan="7" style="text-align: center;">Property</th> 
                                               <th colspan="3" style="text-align: center;">Water</th> 
                                               <th colspan="3" style="text-align: center;">Trade</th> 
                                               
                                            </tr>
                                            <tr>
                                             
                                                <th>Login Details</th>
                                                <th>Form Distribute</th>
                                                <th>SAF Done</th>
                                                <th>SAF Payment</th>
                                                <th>Property Payment</th>
                                                <th>Field Verification</th>
                                                <th>Geo Tagged</th>
                                                <th>Application Applied</th>
                                                <th>Application Payment</th>
                                                <th>Consumer Payment</th>
                                                <th>Application Applied</th>
                                                <th>Application Payment</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                  
                                        <tr>
                                            
                                            <td>
                                                <table class="table table-bordered">
                                                    <?php
                                                    if($userlogin){
                                                        foreach ($userlogin as $value) {
                                                    
                                                    ?>
                                                <tr>

                                                <td colspan="2" nowrap="nowrap"><?php echo "Date:".date('d-m-Y',strtotime($value['date'])).' / '.$value['time'] ?></td></tr>
                                           
                                           
                                                    <?php
                                                        }
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <td colspan="2" style="color: red;">No Data Found</td>
                                                        <?php
                                                    }
                                                    ?>
                                                   </table>
                                            </td>       
                                            

                                             <td>
                                                <table class="table table-bordered">
                                                    <?php
                                                    if($form_distribute){
                                                        foreach ($form_distribute as $value) {
                                                    
                                                    ?>
                                                <tr>
                                                <td nowrap="nowrap"><?php echo "Date:".date('d-m-Y',strtotime($value['date'])).' / '.$value['time'] ?></td></tr>
                                           
                                           
                                                    <?php
                                                        }
                                                   }
                                                    else
                                                    {
                                                    ?>
                                                     <td style="color: red;">No Data Found</td>
                                                    <?php
                                                    }
                                                    ?>
                                                   </table>
                                            </td>


                                             <td>
                                                <table class="table table-bordered">
                                                    <?php
                                                    if($saf_payment){
                                                        foreach ($saf_payment as $value) {
                                                    
                                                    ?>
                                                <tr>
                                                <td nowrap="nowrap"><?php echo "Date:".date('d-m-Y',strtotime($value['date'])).' / '.$value['time'] ?></td></tr>
                                           
                                           
                                                    <?php
                                                        }
                                                   }
                                                    else
                                                    {
                                                    ?>
                                                     <td style="color: red;">No Data Found</td>
                                                    <?php
                                                    }
                                                    ?>
                                                   </table>
                                            </td> 

                                               <td>
                                                <table class="table table-bordered">
                                                    <?php
                                                    if($prop_payment){
                                                        foreach ($prop_payment as $value) {
                                                    
                                                    ?>
                                                <tr>
                                                <td nowrap="nowrap"><?php echo "Date:".date('d-m-Y',strtotime($value['date'])).' / '.$value['time'] ?></td></tr>
                                           
                                            <?php
                                                        }
                                                   }
                                                    else
                                                    {
                                                    ?>
                                                     <td style="color: red;">No Data Found</td>
                                                    <?php
                                                    }
                                                    ?>
                                                   </table>
                                            </td> 

                                              <td>
                                                <table class="table table-bordered">
                                                    <?php
                                                    if($saf_done){
                                                        foreach ($saf_done as $value) {
                                                    
                                                    ?>
                                                <tr>
                                                <td nowrap="nowrap"><?php echo "Date:".date('d-m-Y',strtotime($value['date'])).' / '.$value['time'] ?></td></tr>
                                           
                                           
                                                     <?php
                                                        }
                                                   }
                                                    else
                                                    {
                                                    ?>
                                                     <td style="color: red;">No Data Found</td>
                                                    <?php
                                                    }
                                                    ?>
                                                   </table>
                                            </td>
                                             <td>
                                                <table class="table table-bordered">
                                                    <?php
                                                    if($field_verf){
                                                        foreach ($field_verf as $value) {
                                                    
                                                    ?>
                                                <tr>
                                                <td nowrap="nowrap"><?php echo "Date:".date('d-m-Y',strtotime($value['date'])).' / '.$value['time'] ?></td></tr>
                                           
                                            <?php
                                                        }
                                                   }
                                                    else
                                                    {
                                                    ?>
                                                     <td style="color: red;">No Data Found</td>
                                                    <?php
                                                    }
                                                    ?>
                                                   </table>
                                            </td>

                                          <td>
                                                <table class="table table-bordered">
                                                    <?php
                                                    if($geotagged){
                                                        foreach ($geotagged as $value) {
                                                    
                                                    ?>
                                                <tr>
                                                <td nowrap="nowrap"><?php echo "Date:".date('d-m-Y',strtotime($value['date'])).' / '.$value['time'] ?></td></tr>
                                           
                                            <?php
                                                        }
                                                   }
                                                    else
                                                    {
                                                    ?>
                                                   <td style="color: red;">No Data Found</td>
                                                    <?php
                                                    }
                                                    ?>
                                                   </table>
                                            </td>



                                            <td>
                                                <table class="table table-bordered">
                                                    <?php
                                                    if($water_application_applied){
                                                        foreach ($water_application_applied as $value) {
                                                    
                                                    ?>
                                                <tr>
                                                <td nowrap="nowrap"><?php echo "Date:".date('d-m-Y',strtotime($value['date'])).' / '.$value['time'] ?></td></tr>
                                           
                                                    
                                                    <?php
                                                        }
                                                   }
                                                    else
                                                    {
                                                    ?>
                                                     <td style="color: red;">No Data Found</td>
                                                    <?php
                                                    }
                                                    ?>
                                                   </table>
                                            </td>



                                            <td>
                                                <table class="table table-bordered">
                                                    <?php
                                                    if($water_application_payment){
                                                        foreach ($water_application_payment as $value) {
                                                    
                                                    ?>
                                                <tr>
                                                <td nowrap="nowrap"><?php echo "Date:".date('d-m-Y',strtotime($value['date'])).' / '.$value['time'] ?></td></tr>
                                           
                                                    
                                                    <?php
                                                        }
                                                   }
                                                    else
                                                    {
                                                    ?>
                                                     <td style="color: red;">No Data Found</td>
                                                    <?php
                                                    }
                                                    ?>
                                                   </table>
                                            </td>

                                             <td>
                                                <table class="table table-bordered">
                                                    <?php
                                                    if($consumer_payment){
                                                        foreach ($consumer_payment as $value) {
                                                    
                                                    ?>
                                                <tr>
                                                <td nowrap="nowrap"><?php echo "Date:".date('d-m-Y',strtotime($value['date'])).' / '.$value['time'] ?></td></tr>
                                           
                                                    
                                                    <?php
                                                        }
                                                   }
                                                    else
                                                    {
                                                    ?>
                                                     <td style="color: red;">No Data Found</td>
                                                    <?php
                                                    }
                                                    ?>
                                                   </table>
                                            </td>

                                             <td>
                                                <table class="table table-bordered">
                                                    <?php
                                                    if($trade_application_applied){
                                                        foreach ($trade_application_applied as $value) {
                                                    
                                                    ?>
                                                <tr>
                                                <td nowrap="nowrap"><?php echo "Date:".date('d-m-Y',strtotime($value['date'])).' / '.$value['time'] ?></td></tr>
                                           
                                                    
                                                    <?php
                                                        }
                                                   }
                                                    else
                                                    {
                                                    ?>
                                                     <td style="color: red;">No Data Found</td>
                                                    <?php
                                                    }
                                                    ?>
                                                   </table>
                                            </td>

                                             <td>
                                                <table class="table table-bordered">
                                                    <?php
                                                    if($trade_application_payment){
                                                        foreach ($trade_application_payment as $value) {
                                                    
                                                    ?>
                                                <tr>
                                                <td nowrap="nowrap"><?php echo "Date:".date('d-m-Y',strtotime($value['date'])).' / '.$value['time'] ?></td></tr>
                                           
                                                    
                                                    <?php
                                                        }
                                                   }
                                                    else
                                                    {
                                                    ?>
                                                     <td style="color: red;">No Data Found</td>
                                                    <?php
                                                    }
                                                    ?>
                                                   </table>
                                            </td>

                                        
                                                </table>
                                            </td>

                                        </tr>
                               
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
