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
            <li><a href="#">Water</a></li>
            <li class="active"> Online Payment </li>
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
                            <h5 class="panel-title">Water Online Payment Clear</h5>
                        </div>
                        <div class="panel-body">
                            <div class ="row">
                                <div class="col-md-12">
                                    <form class="form-horizontal" method="get">
                                        <div class="form-group">
                                            <div class="col-md-3">
                                                <label class="control-label" for="to_date"><b>Date</b><span class="text-danger">*</span> </label>
                                                <input type="date" id="date" name="date" class="form-control" value="<?=(isset($date))?$date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control-label" for="type"><b>Type</b><span class="text-danger">*</span> </label>
                                                <div >
                                                    <input type="radio" id="type_c" name="type" value="type_c" checked > Consumer
                                                    <input type="radio" id="type_a" name="type" value="type_a" <?=isset($type) && $type =='type_a'?"checked":"";?>> Application

                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control-label" for="team_leader"><b>Application/Consumer No</b> </label>
                                                <input type="text" id="application_no" name="application_no" class="form-control" value="<?=(isset($application_no))?$application_no:'';?>" >
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control-label" for="tc_id"><b>Order Id</b> </label>
                                                <input type="text" id="order_id" name="order_id" class="form-control" value="<?=(isset($order_id))?$order_id:'';?>">
                                                    
                                            </div>                                            
                                            <div class="col-md-2 ">
                                                <label class="control-label" for="department_mstr_id">&nbsp;</label>
                                                <button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>                               
                        

                    </div>
                    <div class="panel panel-bordered panel-dark">
                        <div class="panel-heading">
                            <h5 class="panel-title text-center">List</h5>
                        </div>
                        <div class="panel-body">    
                            <div class="row">
                                <div class="" >
                                    <table id="demo_dt_basic" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>ID</th>
                                                <th><?=isset($type) && $type =='type_a'?"Application No":"Consumer No";?></th>
                                                <th>Payment For</th>
                                                <th>Payable Amount</th>
                                                <th>Order Id</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        
                                        if(!isset($result))
                                        {
                                            ?>
                                                <tr>
                                                    <td colspan="10" style="text-align: center;">Data Not Available!!</td>
                                                </tr>
                                            <?php
                                        } 
                                        else
                                        {
                                            $i=$collection['offset']??0;
                                            //$i=0;
                                            foreach ($result as $value)
                                            {
                                                ?>
                                                <tr>
                                                    <td><?=++$i;?></td>                                                
                                                    <td><?=$value['id']?></td>
                                                    <td><?=$value['application_no'];?></td>
                                                    <td><?=$value['payment_from'];?></td>
                                                    <td><?=$value['amount'];?></td>
                                                    <td><?=$value['razorpay_order_id'];?></td>
                                                    <td>
                                                        <a onClick="myPopup('<?=base_url('OnlineRequest/watertestApi/'.$value['id']."/".$type);?>','xtf','900','700');" class='btn btn-primary'>
                                                            Clear
                                                        </a>
                                                    </td>
                                                    
                                                </tr>

                                                <?php 
                                            }
                                        }  
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
    function myPopup(myURL, title, myWidth, myHeight)
    {
        var left = (screen.width - myWidth) / 2;
        var top = (screen.height - myHeight) / 4;
        var myWindow = window.open(myURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + myWidth + ', height=' + myHeight + ', top=' + top + ', left=' + left);
    }
</script>