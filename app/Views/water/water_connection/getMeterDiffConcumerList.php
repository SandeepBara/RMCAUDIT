<?= $this->include('layout_vertical/header');?>
<style type="text/css">
	.error{
		color: red;
	}
</style>
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
<li class="active">Bulk Meter Demand Generate Diff Amount Genration</li>
</ol>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End breadcrumb-->
</div>
<!--Page content-->
<!--===================================================-->
		<div id="page-content">
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h5 class="panel-title">Demand Generate</h5>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" id="myform" method="POST" action="<?=base_url();?>/WaterViewConsumerDetails/MeterDiffConcumerGenrate">
						<div class="form-group">
                            <div class="panel-body table-responsive">
                    
                                <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>S. No.</th>  
                                            <th onclick="selectAll()">SELECT
                                                <input type="hidden" name = "selectAll" id="selectAll" value="0"/>
                                            </th> 
                                            <th>Ward No.</th>   
                                            <th>Consumer No.</th>
                                            <th>Applicant Name</th>
                                            <th>Guardian Name</th>
                                            <th>Mobile No.</th>
                                            <th>Address.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php  
                                        if($result)
                                        {
                                            $i=$offset??0;
                                            foreach($result as $val)
                                            {  
                                                ?>
                                                <tr>  
                                                    <td><?php echo ++$i; ?></td>
                                                    <td class="text-center"><input type="checkbox" name="check[]" value="<?=$val['id'];?>"></td>
                                                    <td><?php echo $val['ward_no']??"";?></td>
                                                    <td><?php echo $val['consumer_no']??"";?></td>
                                                    <td><?php echo $val['owner_name']??"";?></td>
                                                    <td><?php echo $val['father_name']??"";?></td>
                                                    <td><?php echo $val['mobile_no']??"";?></td>
                                                    <td><?php echo(isset($val['address']) && !empty($val['address']) ? $val['address']:'N/A');?></td>
                                                    
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </tbody>  
                                </table>
                                <?= pagination($count??0,500); ?> 
                            </div>
							<div class="col-md-2 text-center">
								<button class="btn btn-primary btn-block" id="generatedemand" name="generatedemand" type="submit">Generate Demand</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			
				
		<!--===================================================-->
		<!--End page content-->
		</div>
		<!--===================================================-->
		<!--END CONTENT CONTAINER-->


<?= $this->include('layout_vertical/footer');?>
<!--DataTables [ OPTIONAL ]-->

<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>

<script>
$(document).ready(function () 
{
    $('#myform').validate({ // initialize the plugin
        rules: {
            ward_id: {
                required: "#keyword:blank",
            },
            keyword: {
                required: "#ward_id:blank",
            }
        }
    });
    $("#generatedemand").click("on",function(){        
        $("#generatedemand").prop("disabled",true);
        $("#myform").submit();
        $("#loadingDiv").show();
    });
});
</script>
<script>
$(document).ready(function(){
    $('#demo_dt_basic').DataTable({
        responsive: true,
        paging:false,
        ordering:false,
        info:false,
        dom: 'Bfrtip',
        // lengthMenu: [
        //     [ 50,  100,500, -1 ],
        //     [ '50 rows', '100 rows', '500 rows', 'Show all' ]
        // ],
        buttons: [
            'pageLength',
          {
            text: 'excel',
            extend: "excel",
            title: "Diff_Meter_Demand_consumer_List_To_Genrate"+('<?=date("Y-m-d H:i:s")?>'),
            footer: { text: '' },
            exportOptions: { columns: [ 0, 1,2,3, 4, 5,6,7] }
        }, {
            text: 'pdf',
            extend: "pdf",
            title: "Diff_Meter_Demand_consumer_List_To_Genrate"+('<?=date("Y-m-d H:i:s")?>'),
            download: 'open',
            footer: { text: '' },
            exportOptions: { columns: [ 0, 1,2,3, 4, 5,6,7] }
        }]
    });
});

function selectAll()
{
    var selectAll = $("#selectAll").val();
    // alert(selectAll);
    if(selectAll==1)
    {
        $('input[name="check[]"]').prop("checked",false);
        $("#selectAll").val("0");
    }
    else
    {
        $('input[name="check[]"]').prop("checked",true);
        $("#selectAll").val("1");
    }
}
</script>