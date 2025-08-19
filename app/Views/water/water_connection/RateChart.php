<?php
@session_start();
if($user_type=="")
{   
    echo  $this->include('layout_home/header');
    
}
  # 4	Team Leader	
  # 5	Tax Collector
  # 7	ULB Tax Collector
  else if($user_type==4 || $user_type==5 || $user_type==7)
  {
     echo $this->include('layout_mobi/header');
  }
  else
  { 
     echo $this->include('layout_vertical/header');
  }
 

?>
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<style type="text/css">
.error
{
    color:red ;
}
</style>

<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <?php 
        if($user_type!="" && $user_type!=5)
        { 
            ?>
            <div id="page-head">
                <!--Breadcrumb-->
                <ol class="breadcrumb">
                    <li><a href="#"><i class="demo-pli-home"></i></a></li>
                    <li><a href="#">Water</a></li>
                    <li class="active"><a href="#">Apply Water Connection</a></li>
                </ol>
                <!--End breadcrumb-->
            </div>
            <?php 
        }
    ?>
    <!--Page content-->

    <div id="page-content">	
        <?php        
        if($Fixed)
        {
            ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Water Tariff(Fixed)</h3>                    
                </div>
                <div class="panel-body">

                <table id="demo_dt_basic_Fixed" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead class="bg-trans-dark text-dark">
                        <tr>
                            <th>#</th>
                            <th>Property Type</th>
                            <th>Range from</th>
                            <th>Range to</th>
                            <th>Rate (Rs.)</th>
                            <th>Effective date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i=0;
                            foreach($Fixed as $value)
                            {
                                ?>
                                    <tr>
                                        <td><?=++$i;?></td>
                                        <td><?=$value["property_type"];?></td>
                                        <td><?=$value["range_from"];?></td>
                                        <td><?=$value["range_to"];?></td>
                                        <td><?=$value["rate"];?></td>
                                        <td><?=date('d-m-Y',strtotime($value["effective_date"]));?></td>
                                        
                                    </tr>
                                <?php
                            }                        
                        ?>
                    </tbody>
                </table>
                </div>
            </div>
            <?php
        }
        ?>

        <?php        
        if($Metered)
        {
            ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Water Tariff(Metered)</h3>                    
                </div>
                <div class="panel-body">

                <table id="demo_dt_basic_Metered" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead class="bg-trans-dark text-dark">
                        <tr>
                            <th>#</th>
                            <th>Property Type</th>
                            <th>Category</th>
                            <th>Range from</th>
                            <th>Range to</th>
                            <th>Rate (Rs.)</th>
                            <th>Effective date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i=0;
                            foreach($Metered as $value)
                            {
                                ?>
                                    <tr>
                                        <td><?=++$i;?></td>
                                        <td><?=$value["property_type"];?></td>
                                        <td><?=$value["category"]?$value["category"]:"N/A";?></td>
                                        <td><?=$value["range_from"];?></td>
                                        <td><?=$value["range_to"];?></td>
                                        <td><?=$value["rate"];?></td>
                                        <td><?=date('d-m-Y',strtotime($value["effective_date"]));?></td>
                                        
                                    </tr>
                                <?php
                            }                        
                        ?>
                    </tbody>
                </table>
                </div>
            </div>
            <?php
        }
        ?>

        <?php        
        if($Old || $New)
        {
           if($Old)
            {
                ?>
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title">New Connection Olde Rate</h3>                    
                    </div>
                    <div class="panel-body">
    
                    <table id="demo_dt_basic_Olde_Rate" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead class="bg-trans-dark text-dark">
                            <tr>
                                <th>#</th>
                                <th>Property type</th>
                                <th>Connection through</th>
                                <th>Category</th>
                                <th>Connection type</th>
                                <th>Pipe type</th>
                                <th>Registration fee</th>
                                <th>Processing fee</th>
                                <th>application fee</th>
                                <th>Sec fee</th>
                                <th>Connection fee</th>
                                <th>Effective date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $i=0;
                                foreach($Old as $value)
                                {
                                    ?>
                                        <tr>
                                            <td><?=++$i;?></td>
                                            <td><?=$value["property_type"];?></td>
                                            <td><?=$value["connection_thru"];?></td>
                                            <td><?=$value["category"];?></td>
                                            <td><?=$value["connection_type"];?></td>
                                            <td><?=$value["pipe_type"];?></td>
                                            <td><?=$value["reg_fee"];?></td>
                                            <td><?=$value["proc_fee"];?></td>
                                            <td><?=$value["app_fee"];?></td>
                                            <td><?=$value["sec_fee"];?></td>
                                            <td><?=$value["conn_fee"];?></td>
                                            <td><?=date('d-m-Y',strtotime($value["effective_date"]));?></td>
                                            
                                        </tr>
                                    <?php
                                }                        
                            ?>
                        </tbody>
                    </table>
                    </div>
                </div>
                <?php
            }
            if($New)
            {
                ?>
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title">New Connection New Rate</h3>                    
                    </div>
                    <div class="panel-body">
    
                    <table id="demo_dt_basic_New_Rate" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead class="bg-trans-dark text-dark">
                            <tr>
                                <th>#</th>
                                <th>Property type</th>
                                <th>Category</th>
                                <th>Area From (Sqft)</th>
                                <th>Area To (Sqft)</th>
                                <th>Connection fee</th>                                
                                <th>Calculation type</th>
                                <th>Effective date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $i=0;
                                foreach($New as $value)
                                {
                                    ?>
                                        <tr>
                                            <td><?=++$i;?></td>
                                            <td><?=$value["property_type"];?></td>
                                            <td><?=$value["category"];?></td>
                                            <td><?=$value["area_from_sqft"]?$value["area_from_sqft"]:"N/A";?></td>
                                            <td><?=$value["area_upto_sqft"]?$value["area_upto_sqft"]:"N/A";?></td>
                                            <td><?=$value["conn_fee"];?></td>
                                            <td><?=$value["calculation_type"];?></td>
                                            <td><?=date('d-m-Y',strtotime($value["effective_date"]));?></td>
                                            
                                        </tr>
                                    <?php
                                }                        
                            ?>
                        </tbody>
                    </table>
                    </div>
                </div>
                <?php
            }
            
        }
        ?>
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->

<?php 
    if($user_type=='')
    {
        echo $this->include('layout_home/footer');
    }
	elseif($user_type==4 || $user_type==5 || $user_type==7)
	{

		echo $this->include('layout_mobi/footer');
	}
	else
	{
		echo $this->include('layout_vertical/footer');
        
	}
  
 ?>

<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#demo_dt_basic_Fixed').DataTable({
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
        $('#demo_dt_basic_Metered').DataTable({
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
				exportOptions: { columns: [ 0, 1,2,3,4,5,6] }
			}, {
				text: 'pdf',
				extend: "pdf",
				title: "Report",
				download: 'open',
				footer: { text: '' },
				exportOptions: { columns: [ 0, 1,2,3,4,5,6] }
			}]
		});
        $('#demo_dt_basic_Olde_Rate').DataTable({
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
				exportOptions: { columns: [ 0, 1,2,3,4,5,6,7,8,9,10,11] }
			}, {
				text: 'pdf',
				extend: "pdf",
				title: "Report",
				download: 'open',
				footer: { text: '' },
				exportOptions: { columns: [ 0, 1,2,3,4,5,6,7,8,9,10,11] }
			}]
		});
        $('#demo_dt_basic_New_Rate').DataTable({
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
				exportOptions: { columns: [ 0, 1,2,3,4,5,6] }
			}, {
				text: 'pdf',
				extend: "pdf",
				title: "Report",
				download: 'open',
				footer: { text: '' },
				exportOptions: { columns: [ 0, 1,2,3,4,5,6] }
			}]
		});
	});

</script>
