
<?= $this->include('layout_vertical/header');?>
<div id="content-container">
    <div id="page-head">
        <ol class="breadcrumb">
		<li><a href="#"><i class="demo-pli-home"></i></a></li>
		<li><a href="active">Excel</a></li>
        </ol>
    </div>
	<div id="page-content">
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
                <span class="panel-control"><a href="<?=base_url("getImageLink.php?path=".$excelInfo["doc_path"])?>" target="_blank"> Export</a></span>
				<h3 class="panel-title">Search Application </h3>
			</div>
			<div class="panel-body">
				<div class="responsive">

                </div>
            </div>
        </div>
	</div>
</div>
<?= $this->include('layout_vertical/footer');?>
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">

<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script>
    $(document).ready(function(){
        showExcel("<?=$excelInfo["doc_path"]??'';?>");
        $('.excelTable').DataTable({
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
                
                title: "Report",
                footer: { text: '' },
                exportOptions: { columns: [ 0,1,2,3,4,5,6,7,8,9,10,11] }
            }, {
                text: 'pdf',
                extend: "pdfHtml5",
                title: "Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0,1,2,3,4,5,6,7,8,9,10,11] }
            }]
        });        
    });

    function showExcel(path){
        $.ajax({
            url: "<?= base_url('/ExcelFileReader.php') ?>"+"?path="+path,
            type: "get",
            dataType: "json",
            beforeSend: function () {
                $("#loadingDiv").show();
            },
            success: function (response) {
                $("#loadingDiv").hide();
                console.log(response);
                $(".responsive").html(response?.html);
                modelInfo(response?.message);
            },
            error: function (xhr, status, error) {
                $("#loadingDiv").hide();
                console.error("Error:", error);
            }
        });
    }

</script>
