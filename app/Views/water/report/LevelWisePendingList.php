<?= $this->include('layout_vertical/header');?>
<style type="text/css">
	.error{
		color: red;
	}
</style>
<link href="<?=base_url('');?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">

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
    <li><a href="<?=base_url("WaterApplicationFormStatusDetailReport/LevelPendingReport");?>">Level Pending Report</a></li>
    <li class="active">Search Consumer</li>
    </ol>
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <!--End breadcrumb-->
    </div>
    <!--Page content-->
<!--===================================================-->
		<div id="page-content">
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h5 class="panel-title">Level Wise Pending List on <?=$user_type;?></h5>
				</div>
				<div class="panel-body">
                    <div class="">
                        <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead class="bg-trans-dark text-dark">
                                <tr>
                                    <th>S. No.</th>   
                                    <th>Ward No.</th>   
                                    <th>Application No.</th>
                                    <th>Category</th>
                                    <th>Applicant Name</th>
                                    <th>Mobile No.</th>
                                    <th>Apply Date</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if($list)
                                {
                                    $i=1;
                                    foreach($list as $val)
                                    {
                                        ?>
                                        <tr>  
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $val['ward_no'];?></td>
                                            <td><?php echo $val['application_no'];?></td>
                                            <td><?php echo $val['category'];?></td>
                                            <td><?php echo $val['applicant_name'];?></td>
                                            <td><?php echo $val['mobile_no'];?></td>
                                            <td><?php echo date('d-m-Y',strtotime($val['apply_date']));?></td>
                                            <td><a href="<?=base_url("WaterApplyNewConnection/water_connection_view/".md5($val["id"]));?>" class="btn btn-primary btn-sm" target="blank">View</a></td>
                                                
                                        </tr>
                                        <?php
                                            $i++;
                                    }
                                }
                                ?>
                            </tbody>  
                        </table>
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
});
/*
rules: {
            ward_id: {
                required: true,
               
            },
            keyword: {
                required: true,
                
            }
        }*/
$(document).ready(function(){
    $('#demo_dt_basic').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        lengthMenu: [
            [ 30, 50, 100, -1 ],
            [ '30 rows', '50 rows', '100 rows', 'Show all' ]
        ],
        buttons: [
            'pageLength',
          {
            text: 'excel',
            extend: "excel",
            title: "Report",
            footer: { text: '' },
            exportOptions: { columns: [ 0, 1,2,3, 4, 5, 6] }
        }, {
            text: 'pdf',
            extend: "pdf",
            title: "Report",
            download: 'open',
            footer: { text: '' },
            exportOptions: { columns: [ 0, 1,2,3, 4, 5, 6] }
        }]
    });
});
</script>

<script type="text/javascript">
/*$(document).ready(function(){
    $.fn.dataTable.ext.errMode = 'throw';
    var dataTable = $('#demo_dt_basic').DataTable({
        'responsive': true,
        'processing': true,
        'language': {
            'processing': '<div class="load8"><div class="loader"></div></div>...',
        },
        'serverSide': true,
        lengthMenu: [
            [10, 25, 50, -1],
            ['10 rows', '25 rows', '50 rows', 'Show all']
        ],
        "columnDefs": [
            { "orderable": false, "targets": [0, 4] }
        ],
        dom: 'Bfrtip',
        buttons: [
            'pageLength',
            {
                extend: "excel",
                footer: { text: '' },
                exportOptions: { columns: [0,1,2,3,4,5,6,7] }
            }],
        'ajax': {
            "type": "POST",
            'url':'<?=base_url('WaterSearchConsumer/getPagination');?>',
            "dataType": "text",
            'data': function(data){
                // Read values
                alert(data);
                var ward_id = $('#ward_id').val();
                var keyword = $('#keyword').val();
               // alert(keyword);

                // Append to data
                data.search_by_from_ward_id = ward_id;
                data.search_by_upto_ward_id = keyword;
            }
        },

         'columns': [
            { 'data': 's_no' },
            { 'data': 'id' },
            { 'data': 'ward_no' },
            { 'data': 'ulb_mstr_id' },
            { 'data': 'status' },
        ]
     
    });
    $('#btn_search').click(function(){
        dataTable.draw();
    });
});*/

</script>
