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
                    <li><a href="#">Trade</a></li>
                    <li class="active"><?= $designation; ?> OUTBOX</li>
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
                                    <h5 class="panel-title"><?= $designation; ?> outbox</h5>
                                </div>
                                <div class="panel-body">
                                    <div class ="row">
                                        <div class="col-md-12">
                                            <form class="form-horizontal" method="GET" action="<?php echo base_url('trade_SH/outbox');?>">
                                                <div class="form-group">
                                                    <div class="col-md-6">
                                                        <label class="control-label" for="search_param">
                                                            <b>SEARCH WITH MOBILE / APPLICATION NUMBER</b>
                                                        </label>
                                                        <input type="TEXT" id="search_param" name="search_param" class="form-control" placeholder="eg: 1234567890 OR APPL123456789 OR RAN0123456789" value="<?=$search_param??"";?>" autocomplete="on">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="control-label" for="ward No"><b>Ward No</b><span class="text-danger"></span> </label>
                                                        <select id="ward_mstr_id" name="ward_mstr_id" class="form-control" >
                                                           <option value="">All</option> 
                                                            <?php  foreach($wardList as $value):?>
                                                            <option value="<?= $value['ward_mstr_id'];?>" <?php echo (isset($ward_mstr_id))?($ward_mstr_id==$value["ward_mstr_id"])?"SELECTED":"":"";?>><?=$value['ward_no'];?>
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
                                        <form id="export_form" method="post" action="<?= base_url('trade_sh/collectionReportExcel') ?>">
                                            <input type="hidden" name="query_top" value="<?=$queryTop; ?>">
                                            <input type="hidden" name="query_middle" value="<?=$queryMiddle; ?>">
                                            <input type="hidden" name="query_bottom" value="<?=$queryRemaining; ?>">

                                            <input type="submit" name="submit" value="Export To Excel" class="btn btn-primary">
                                        </form>
                                        <!-- demo_dt_basic -->
                                        <div class="table-responsive">
                                    <table id="" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Ward No.</th>
                                                <th>Application No.</th>
                                                <th>Firm Name</th>
                                                <th>Application Type</th>
                                                <th>Applied Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            //print_r($owner);
                                        if(isset($result)):
                                            if(empty($result)):
                                        ?>
                                            <tr>
                                                <td colspan="7" style="text-align: center;">Data Not Available!!</td>
                                            </tr>
                                        <?php else:
                                            $i=$offset??0;
                                            foreach ($result as $value):
                                        ?>
                                            <tr>
                                                <td><?=++$i;?></td>
                                                <td><?=$value["ward_no"];?></td>
                                                <td><?=$value["application_no"];?></td>
                                                <td><?=$value["firm_name"];?></td>
                                                <td><?=$value["application_type"];?></td>
                                                <td><?=$value["apply_date"];?></td>
                                                <td>
                                                    <a class="btn btn-primary" href="<?php echo base_url('trade_da/view_application_details/'.md5($value['apply_licence_id']));?>" role="button">View</a>
                                                </td>
                                            </tr>
                                        <?php endforeach;?>
                                        <?php endif;  ?>
                                        <?php endif;  ?>
                                        </tbody>
                                    </table>
                                        <?= pagination($count??0); ?>
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
        //     var search_param= $("#search_param").val();

        //     var startDay = new Date(from_date);
        //     var endDay = new Date(search_param);

        //     if((startDay.getTime())>(endDay.getTime()))
        //     {
        //         alert("Please select valid To Date!!");
        //         $("#from_date").val('');
        //     }
        // });
        // $("#search_param").change(function ()
        // {
        //     var from_date= $("#from_date").val();
        //     var search_param= $("#search_param").val();

        //     var startDay = new Date(from_date);
        //     var endDay = new Date(search_param);

        //     if((startDay.getTime())>(endDay.getTime()))
        //     {
        //         alert("Please select valid To Date!!");
        //         $("#search_param").val('');
        //     }
        // });

        $("#btn_search").click(function(){
            var from_date = $("#from_date").val();
            var search_param = $("#search_param").val();
            if(from_date=="")
            {
                alert("Please Select From Date");
                $('#from_date').focus();
                return false;
            }

            if(search_param=="")
            {
                // alert("Please Select To date");
                // $('#search_param').focus();
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
     function modelInfo(msg){
        $.niftyNoty({
            type: 'info',
            icon : 'pli-exclamation icon-2x',
            message : msg,
            container : 'floating',
            timer : 5000
        });
    }
    <?php 
        if($licence=flashToast('licence'))
        {
            echo "modelInfo('".$licence."');";
        }
    ?>


    $('#ward_mstr_id').change(function (){

        var whatever= $("#search_param").val();
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

    });
    $('#search_param').change(function (){

        var whatever= $("#search_param").val();
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

    });
 </script>

