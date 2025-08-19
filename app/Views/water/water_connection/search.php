<?= $this->include('layout_vertical/header');?>
<style type="text/css">
	.error{
		color: red;
	}
    /* .form-control
    {
        line-height: 2.428571;
    } */
    
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
					<h5 class="panel-title">Search Consumer</h5>
				</div>
				<div class="panel-body">
					<div class="row">
                        <div class="panel-body ">
                            <div class="row text-center"><h3>Search</h3>
                                <div class="col-md-4 col-md-offset-4 panel-bordered panel-dark" style="box-shadow: 0px 0px 15px;">
                                   <div style="margin: 2px 0 ;padding:3rem 1rem">
                                    <form action="<?=base_url()?>/<?=$froword_url?>" method="post">
                                        <div class="form-group">
                                            <label for="consumer_no" style="font-weight: bold;">Consumer No <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="consumer_no" id='consumer_no'>
                                        </div>
                                        <div class="form-group">
                                            <span style="font-weight: bold;" class="text-danger"> OR</span></br>
                                            <label for="application_no" style="font-weight: bold;">Application No<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name='application_no' id="application_no">
                                        </div>
                                        <div class="form-group">
                                            <span style="font-weight: bold;" class="text-danger"> OR</span></br>
                                            <label for="consumer_name" style="font-weight: bold;">Consumer Name<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="consumer_name" id="consumer_name">
                                        </div>
                                        <div class="form-group">
                                            <span style="font-weight: bold;" class="text-danger"> OR</span></br>
                                            <label style="font-weight: bold;" for="mobile_no">Mobile No <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name='mobile_no' id='mobile_no'>
                                        </div>
                                        <div class="form-group">
                                            <span style="font-weight: bold;" class="text-danger"> OR</span></br>
                                            <label for="ward_id" style="font-weight: bold;">Ward No<span class="text-danger">*</span></label>
                                            <select name="ward_id" id='ward_id' class="form-control">
                                                <option value="">All</option>
                                               <?php
                                                foreach($ward_list as $ward)
                                                {
                                                    ?>
                                                    <option value="<?=$ward['id']?>"><?=$ward['ward_no']?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <span style="font-weight: bold;" class="text-danger"> OR</span></br>
                                            <label for="holding_no" style="font-weight: bold;">Holding No <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name='holding_no' id='holding_no'>
                                        </div>
                                        
                                            <button type="sumbit" class=" btn btn-primary" style="width: 30%;">Search</button>
                                        
                                    </form>
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
</script>
<script>
$(document).ready(function(){
    $('#demo_dt_basic').DataTable({
        responsive: false,
        dom: 'Bfrtip',
        lengthMenu: [
            [ 10, 25, 100, -1 ],
            [ '10 rows', '25 rows', '100 rows', 'Show all' ]
        ],
        buttons: [
            'pageLength',
          {
            text: 'excel',
            extend: "excel",
            title: "Report",
            footer: { text: '' },
            exportOptions: { columns: [ 0, 1,2,3, 4, 5] }
        }, {
            text: 'pdf',
            extend: "pdf",
            title: "Report",
            download: 'open',
            footer: { text: '' },
            exportOptions: { columns: [ 0, 1,2,3, 4, 5] }
        }]
    });
});
</script>