<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url();?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
            <!--===================================================-->
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
                    <li><a href="#">Trade </a></li>
                    <li class="active">Trade DA List</li>
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
                                    <h5 class="panel-title">Trade DA List</h5>
                                </div>
                                <?php //print_var($wardList);die(); ?>
                                <div class="panel-body">
                                    <div class ="row">
                                        <div class="col-md-12">
                                            <form class="form-horizontal" method="post" action="<?php echo base_url('trade_da/index');?>">
                                                <div class="form-group">
                                                    <!-- <div class="col-md-3">
                                                        <label class="control-label" for="from_date"><b>From Date</b> </label>
                                                        <input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?php //echo (isset($from_date))?$from_date:date('Y-m-d');?>">
                                                    </div> -->
                                                    <div class="col-md-6">
                                                        <label class="control-label" for="to_date">
                                                            <b>SEARCH WITH MOBILE / APPLICATION NUMBER</b>
                                                        </label>
                                                        <input type="TEXT" id="to_date" name="to_date" class="form-control" placeholder="eg: 1234567890 OR APPL123456789 OR RAN0123456789" value="<?php //echo (isset($to_date))?$to_date:date('Y-m-d');?>" autocomplete="on">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="control-label" for="ward No"><b>Ward No</b><span class="text-danger"></span> </label>
                                                        <select id="ward_mstr_id" name="ward_mstr_id" class="form-control" >
                                                           <option value="">All</option> 
                                                            <?php  foreach($wardList as $value):?>
                                                            <option value="<?= $value['id'];?>" <?php echo (isset($ward_mstr_id))?($ward_mstr_id==$value["id"])?"SELECTED":"":"";?>><?=$value['ward_no'];?>
                                                            </option>
                                                            <?php endforeach;?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="control-label" for="department_mstr_id">&nbsp;</label>
                                                        <button class="btn btn-success btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="table-responsive">
                                        <table id="" class="table table-striped table-hover table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Ward No.</th>
                                                    <th>Application No.</th>
                                                    <th>Firm Name</th>
                                                    <th>Mobile No.</th>
                                                    <th>Application Type</th>
                                                    <th>Applied Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                    // print_r($owner);die();
                                            if(isset($posts)):
                                                if(empty($posts)):
                                            ?>
                                                <tr>
                                                    <td colspan="7" style="text-align: center;">Data Not Available!!</td>
                                                </tr>
                                            <?php else:
                                                $i=$posts['offset'];
                                                $j=0;
                                                // print_var($posts['count']);
                                                foreach ($posts['result'] as $key=> $value):
                                                    // print_var($value[0]["ward_no"]);
                                            ?>
                                                <tr>
                                                    <td><?=++$i;?></td>
                                                    <td><?=$value["ward_no"];?></td>
                                                    <td><?=$value["application_no"];?></td>
                                                    <td><?=$value["firm_name"];?></td>
                                                    <td><?=$value["mobile_no"];?></td>
                                                    <td><?=$value["application_type"];?></td>
                                                    <?php if($value["pending_since"]==0):?>
                                                        <td><?=date('d-m-Y',strtotime($value["apply_date"]));?></td>
                                                    <?php else:?>
                                                    <td><?=date('d-m-Y',strtotime($value["apply_date"]));?>&nbsp;<span style="color:red;">(Pending since <?=$value["pending_since"];?> days)</span></td>
                                                    <?php endif;?>
                                                    <td><?=date('d-m-Y',strtotime($value["created_on"]));?></td>
                                                    <td>

                                                        <a class="btn btn-primary" href="<?php echo base_url('trade_da/view/'.md5($value['id']));?>" role="button">Verify</a>

                                                    </td>
                                                </tr>
                                            <?php endforeach;?>

                                             <?php endif;  ?>
                                        <?php endif;  ?>
                                            </tbody>
                                        </table>
                                        <?= pagination($posts['count']??0); ?>
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
<script src="<?=base_url();?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script type="text/javascript">
    
    $(document).ready(function() {
        
            $('#btn_search').attr('name','filter');
            $('#btn_search').html('Filter');
            
        // $("#from_date").change(function ()
        // {
        //     var from_date= $("#from_date").val();
        //     var to_date= $("#to_date").val();

        //     var startDay = new Date(from_date);
        //     var endDay = new Date(to_date);

        //     if((startDay.getTime())>(endDay.getTime()))
        //     {
        //         alert("Please select valid To Date!!");
        //         $("#from_date").val('');
        //     }
        // });
        // $("#to_date").change(function ()
        // {
        //     var from_date= $("#from_date").val();
        //     var to_date= $("#to_date").val();

        //     var startDay = new Date(from_date);
        //     var endDay = new Date(to_date);

        //     if((startDay.getTime())>(endDay.getTime()))
        //     {
        //         alert("Please select valid To Date!!");
        //         $("#to_date").val('');
        //     }
        // });

        $("#btn_search").click(function(){
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            if(from_date=="")
            {
                alert("Please Select From Date");
                $('#from_date').focus();
                return false;
            }

            if(to_date=="")
            {
                // alert("Please Enter a valid application no");
                // $('#to_date').focus();
                // return false;
            }
        });
        $('#demo_dt_basic').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            lengthMenu: [
                [ 10, 25, 50, -1 ],
                [ '10 rows', '25 rows', '50 rows', 'Show all' ]
            ],
            buttons: [
                'pageLength',
              {
                text: 'excel',
                extend: "excel",
                title: "Report",
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3,4,5] }
            }, {
                text: 'pdf',
                extend: "pdf",
                title: "Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3,4,5] }
            }]
        });
    });
    // function modelInfo(msg){
    //     $.niftyNoty({
    //         type: 'info',
    //         icon : 'pli-exclamation icon-2x',
    //         message : msg,
    //         container : 'floating',
    //         timer : 5000
    //     });
    // }
    <?php 
        // if($licence=flashToast('licence'))
        // {
        //     echo "modelInfo('".$licence."');";
        // }
    ?>

    $('#ward_mstr_id').change(function (){

        var whatever= $("#to_date").val();
        var wmid= $("#ward_mstr_id").val();
        if(whatever =="" && wmid !=""){
            $('#btn_search').html('Filter');
            $('#btn_search').attr('name','filter');
        }else{
            $('#btn_search').html('Search');
            $('#btn_search').attr('name','btn_search');
        }

        // var vals = this.val();
        console.log(wmid);

        $()
    });
    $('#to_date').change(function (){

        var whatever= $("#to_date").val();
        var wmid= $("#ward_mstr_id").val();
        if(whatever =="" && wmid !=""){
            $('#btn_search').html('Filter');
            $('#btn_search').attr('name','filter');
        }else{
            $('#btn_search').html('Search');
            $('#btn_search').attr('name','btn_search');
        }

        // var vals = this.val();
        console.log(wmid);

        $()
    });
   
 </script>

