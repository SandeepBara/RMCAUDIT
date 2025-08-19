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
            <li class="active">Ward Wise Demand Summary </li>
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
                            <h5 class="panel-title">Ward Wise Demand Summary</h5>
                        </div>
                        <div class="panel-body">
                            <div class ="row">
                                <div class="col-md-12">
                                    <form class="form-horizontal" method="get" action="<?=base_url('water_report/ward_wise_demand')?>">
                                        <div class="form-group">
                                            <div class="col-md-3">
                                                <label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
                                                <input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control-label" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
                                                <input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="control-label" for="team_leader"><b>Emplooye List</b></label>
                                                <select id="emp_details_id" name="emp_details_id" class="form-control" onchange=" get_pemited_ward(this.value);">
                                                    <option value="">ALL</option> 
                                                    <?php
                                                    foreach($tc_list as $val)
                                                    {
                                                        ?>
                                                        <option value="<?=$val['id']?>" <?=isset($emp_details_id) && $emp_details_id ==$val['id']?"selected":""; ?>><?=$val['emp_name']?> (<?=$val['employee_code']?>)</option>
                                                        <?php
                                                    }
                                                    ?>															
                                                </select> 
                                            </div>
                                            <div class="col-md-4">
                                                <label class="control-label" for="tc_id"><b>Ward No</b></label>
                                                <select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
                                                    <option value="">ALL</option> 
                                                    <?php
                                                    foreach($ward_list as $value)
                                                    {
                                                        ?>
                                                        <option value="<?=$value['id']?>" <?=isset($ward_mstr_id) && $ward_mstr_id==$value['id'] ? 'selected':''?>><?=$value['ward_no']?></option>
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
                        </div>                               
                        

                    </div>
                    <div class="panel panel-bordered panel-dark">
                        
                        <div class="panel-body">   
                            <div class="row">
                            <?php
                                if(isset($demands))
                                {
                                    ?>
                                        <button class="bg-success" onclick="ExportToExcel('xlsx')" >Export Excel</button> 
                                    <?php
                                }
                            ?>
                            </div> 
                            <div class="row">                                    
                                <div class="" >
                                    <table id="demo_dt_basic" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Ward No</th>
                                                <th>Total Consumer</th>
                                                <th>Demand Genereted</th>
                                                <th>Demand Not Genereted</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        
                                        if(!isset($summary))
                                        {

                                        ?>
                                                <tr>
                                                    <td colspan="4" style="text-align: center;">Data Not Available!!</td>
                                                </tr>
                                        <?php 
                                        }
                                        else
                                        {
                                            $i=0;
                                            foreach ($summary as $value)
                                            {
                                                ?>
                                                <tr>
                                                    <td><?=++$i;?></td>
                                                    
                                                    <td><?=$value['ward_no'];?></td>
                                                    <td><?=$value['total_consumer'];?></td>
                                                    <td>
                                                        <a onClick="myPopup('<?=base_url('water_report/ward_wise_demand_dtl/generate/'.$value['ward_mstr_id']."/".$from_date.'/'.$to_date.(isset($emp_details_id) && $emp_details_id!=''? ("/".$emp_details_id):""));?>','xtf','900','700');">
                                                            <?=$value['generate'];?>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a onClick="myPopup('<?=base_url('water_report/ward_wise_demand_dtl/not_generate/'.$value['ward_mstr_id']."/".$from_date.'/'.$to_date.(isset($emp_details_id) && $emp_details_id!=''? ("/".$emp_details_id):""));?>','xtf','900','700');">
                                                            <?=$value['not_generate'];?>
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

    function ExportToExcel(type, fn, dl) 
    {
        //alert();       
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var tc_id = $('#tc_id').val();
        if (tc_id=='') 
        {
            tc_id = "ALL";
        }                           
        var gerUrl = from_date+'/'+to_date+'/'+tc_id;
        window.open('<?=base_url();?>/water_report/demand_summaryExcel/'+gerUrl).opener = null;
    }

    function get_pemited_ward(emp_id)
    { 
        if(emp_id!="")
        {
            try
            {
                $.ajax({
                    type:"POST",
                    url: "<?=base_url()?>/water_report/get_permited_ward",
                    dataType: "json",
                    data: {
                        "emp_details_id":emp_id,
                    },
                    beforeSend: function() {
                        $("#loadingDiv").show();
                    },
                    success:function(data){
                        console.log(data);
                        if(data.response==true){
                            
                            $("#ward_mstr_id").html('');
                            $("#ward_mstr_id").html(data.data);
                        }
                        $("#loadingDiv").hide();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $("#loadingDiv").hide();
                    }
                });
            }
            catch (err) 
            {
                alert(err.message);
            }
        } else {
            var wardOption = '<option value="">ALL</option> ';
            <?php foreach($ward_list as $value) { ?>
                wardOption += '<option value="<?=$value['id']?>"><?=$value["ward_no"]?></option>';
            <?php } ?>
            $("#ward_mstr_id").html(wardOption); 
        }
    }
    $(document).ready(function(){
        $('#demo_dt_basic').DataTable({
            responsive: false,
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
                
                title: $("#from_date").val()+" To "+$("#from_date").val()+" Ward Wise Demand Genert Report",
                footer: { text: '' },
                exportOptions: { columns: [ 0,1,2,3,4] }
            }, {
                text: 'pdf',
                extend: "pdfHtml5",
                title: $("#from_date").val()+" To "+$("#from_date").val()+" Ward Wise Demand Genert Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0,1,2,3,4] }
            }]
        });
        
    });
</script>