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
                    <li class="active">TC Visiting Detail Report</li>
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
                                    <h5 class="panel-title">TC Visiting Detail Report</h5>
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
                                             
                                                <th>TC Name</th>
                                                <th>View</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            if($visiting_detail):
                                                foreach($visiting_detail as $val):
                                        ?>
                                        <tr>

                                            <td><?php echo $val['emp_name'];?></td>
                                                
                                            <td> <button type="button" class="btn btn-info btn-md" data-toggle="modal" data-target="#myModal<?php echo $val['user_id'];?>" onclick="getDetails(this.value)" value="<?php echo $val['user_id'];?>">Open Modal</button></td>

                                        </tr>
                                      

                                        </tbody>
               
            <!-- Modal -->
            <div id="myModal<?php echo $val['user_id'];?>" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modal Header</h4>
                  </div>
                  <div class="modal-body" >
                    
                   <!-- <table>
                        <thead>
                            <tr>
                            <th>Holding No.</th>
                            </tr>


                        </thead>
                        <tbody>
                             <?php
                            if($a[$val['user_id']]['tc_dtl_prop']):
                                foreach($a[$val['user_id']]['tc_dtl_prop'] as $val):
                            ?>
                            <tr>
                                <td><?php echo $val['holding_no']; ?></td>

                            </tr>

                            <?php
                                 endforeach;
                            endif;
                            ?>
                        </tbody>
                    </table>-->
                      <?php
                            if($a[$val['user_id']]['tc_dtl_prop']):
                                foreach($a[$val['user_id']]['tc_dtl_prop'] as $val):
                      ?>
                   <div class="row">
                       <div class="col-md-12">
                           <div class="col-md-3"><?php echo $val['holding_no'];?></div>
                           <div class="col-md-3"><?php echo date('d-m-Y',strtotime($val['visiting_date']));?></div>
                           <div class="col-md-3"><?php echo $val['message'];?></div>
                           
                               
                        </div>
                   </div>
                       <?php
                                 endforeach;
                            endif;
                        ?> 
                 
                  <?php
                            if($a[$val['user_id']]['tc_dtl_saf']):
                                foreach($a[$val['user_id']]['tc_dtl_saf'] as $val):
                      ?>
                   <div class="row">
                       <div class="col-md-12">
                           <div class="col-md-3"><?php echo $val['holding_no'];?></div>
                           <div class="col-md-3"><?php echo date('d-m-Y',strtotime($val['visiting_date']));?></div>
                           <div class="col-md-3"><?php echo $val['message'];?></div>
                           
                               
                        </div>
                   </div>
                       <?php
                                 endforeach;
                            endif;
                        ?> 
                 

                  <?php
                            if($a[$val['user_id']]['tc_dtl_water']):
                                foreach($a[$val['user_id']]['tc_dtl_water'] as $val):
                      ?>
                   <div class="row">
                       <div class="col-md-12">
                           <div class="col-md-3"><?php echo $val['holding_no'];?></div>
                           <div class="col-md-3"><?php echo date('d-m-Y',strtotime($val['visiting_date']));?></div>
                           <div class="col-md-3"><?php echo $val['message'];?></div>
                           
                               
                        </div>
                   </div>
                       <?php
                                 endforeach;
                            endif;
                        ?> 
                 

                  <?php
                            if($a[$val['user_id']]['tc_dtl_trade']):
                                foreach($a[$val['user_id']]['tc_dtl_trade'] as $val):
                      ?>
                   <div class="row">
                       <div class="col-md-12">
                           <div class="col-md-3"><?php echo $val['holding_no'];?></div>
                           <div class="col-md-3"><?php echo date('d-m-Y',strtotime($val['visiting_date']));?></div>
                           <div class="col-md-3"><?php echo $val['message'];?></div>
                           
                               
                        </div>
                   </div>
                       <?php
                                 endforeach;
                            endif;
                        ?> 

                    <?php
                            if($a[$val['user_id']]['tc_dtl_water_cons']):
                                foreach($a[$val['user_id']]['tc_dtl_water_cons'] as $val):
                      ?>
                   <div class="row">
                       <div class="col-md-12">
                           <div class="col-md-3"><?php echo $val['consumer_no'];?></div>
                           <div class="col-md-3"><?php echo date('d-m-Y',strtotime($val['visiting_date']));?></div>
                           <div class="col-md-3"><?php echo $val['message'];?></div>
                           
                               
                        </div>
                   </div>
                       <?php
                                 endforeach;
                            endif;
                        ?> 
                 
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>

              </div>
            </div>



                                        <?php
                                                endforeach;
                                            endif;
                                        ?>

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
