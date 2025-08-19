<?=$this->include('layout_vertical/popup_header');?>

<style>
    #footer{
        display: none;
    }
</style>
<link rel="icon" href="<?=base_url();?>/public/assets/img/favicon.ico">
<link href="<?=base_url();?>/public/assets/css/bootstrap.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/css/nifty.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/css/demo/nifty-demo-icons.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/bootstrap4-toggle/css/bootstrap4-toggle.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/css/common.css" rel="stylesheet">
<script src="<?=base_url();?>/public/assets/js/jquery.min.js"></script>

<!--CSS Loaders [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css" rel="stylesheet">
<!-- <script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script>  -->

<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Team Summary</a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
                <?php
                    if(!empty($reports))
                    {
                        ?>
                            <div class="panel panel-bordered panel-dark">
                                <div class="panel-heading">
                                    <h3 class="panel-title"> New connection Transaction</h3>
                                </div>
                                <div class="panel-body table-responsive">
                                    <table id ="empTable" class="table table-bordered table-responsive">
                                        <thead class="bg-trans-dark text-dark">
                                            <tr>
                                                <th>Sl. No.</th>
                                                <th>Ward No.</th>
                                                <th>Holding No.</th>
                                                <th>Address</th>
                                                <th>Owner Name</th>
                                                <th>Mobile No.</th>
                                                <th>Notice Date</th>
                                                <th>Notice No.</th>
                                                <th>Demand From/Upto</th>
                                                <th>Demand Amount</th>
                                                <?php
                                                    if(isset($noticeType) && $noticeType=="payment_done"){
                                                        ?>
                                                            <th>Tran Date</th>
                                                            <th>Tran No.</th>
                                                            <th>Tran Mode</th>
                                                            <th>Payment From/Upto</th>
                                                            <th>Paid Amount</th>
                                                        <?php
                                                    }
                                                ?>
                                            </tr>
                                        
                                        </thead>
                                        <tbody>
                                            <?php
                                                $i=0;
                                                $sum=0;
                                                foreach($reports as $key=>$val)
                                                {
                                                    // $sum+=$val['paid_amount'];
                                                    ?>
                                                    <tr>
                                                        <th><?=$key+1;?></th>
                                                        <th><?=$val["ward_no"];?></th>
                                                        <th><?=$val["holding_no"];?></th>
                                                        <th><?=$val["prop_address"];?></th>
                                                        <th><?=$val["owner_name"];?></th>
                                                        <th><?=$val["mobile_no"];?></th>
                                                        <th><?=$val["notice_date"];?></th>
                                                        <th><?=$val["notice_no"];?></th>
                                                        <th><?=$val["demand_from_upto_fy_qtr"];?></th>
                                                        <th><?=$val["demand_amount"];?></th>
                                                        <?php
                                                            if(isset($noticeType) && $noticeType=="payment_done"){
                                                                ?>
                                                                    <th><?=$val["tran_date"];?></th>
                                                                    <th><?=$val["tran_no"];?></th>
                                                                    <th><?=$val["tran_mode"];?></th>
                                                                    <th><?=$val["payment_from_upto_fy_qtr"];?></th>
                                                                    <th><?=$val["total_payable_amt"];?></th>
                                                                <?php
                                                            }
                                                        ?>
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



    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<!-- <?=$this->include('layout_vertical/footer');?> -->
<!-- <script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script> -->
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script>
$('#empTable').DataTable({
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
            customizeData: function(data) {
                    var namatabel = "empTable";
                    var colLength = $("#" + namatabel + " thead:first tr:last th").length;
                    var jmlheader = $('#'+namatabel+' thead:first tr').length;
                
                    if (jmlheader > 1) {
                        data.body.unshift(data.header);
                        data.header=[""];
                        var j=0,rspan=[];
                        for(j=0;j<jmlheader;j++){
                                rspan[j]=[];
                            for(var i=0;i<colLength;i++){
                                rspan[j][i]=0;
                            }
                        }
                        var colSpan=0,rowSpan=0;
                        var topHeader = [],thisHead=[],thiscol=0,thisrow=0,jspan=0;
                        for(j=1;j<=(jmlheader-1);j++){
                            thisHead=[],thiscol=0;jspan=0;
                            $('#'+namatabel).find("thead:first>tr:nth-child("+j+")>th").each(function (index, element) {
                                colSpan = parseInt(element.getAttribute("colSpan"));
                                rowSpan = parseInt(element.getAttribute("rowSpan"));
                                jspan=jspan+colSpan;
                                if(rspan[thisrow][thiscol]>0){
                                    for(var i=0;i<rspan[thisrow][thiscol];i++){
                                        thisHead.push("");    
                                    }
                                }
                                if(rowSpan>1){
                                    jspan=jspan-colSpan;
                                    for (var i=thisrow+1; i < jmlheader; i++) {
                                        rspan[i][jspan]=colSpan;   
                                    }
                                }
                                thisHead.push(element.innerHTML.toUpperCase());
                                for (var i = 0; i < colSpan - 1; i++) {
                                    thisHead.push("");
                                }
                                thiscol++;
                            });
                            thisrow++;
                            if(j==1){
                                data.header=thisHead;
                            }else{
                                topHeader.push(thisHead);
                            }
                            
                        };
                        thiscol=topHeader.length;
                        for(j=(thiscol-1);j>=0;j--){
                            data.body.unshift(topHeader[j]);
                        };    
                    }
                }
        }
    ]
});
</script>
