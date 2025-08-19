<?= $this->include('layout_vertical/header'); ?>
<!--CSS Loaders [ OPTIONAL ]-->
<link href="<?= base_url(''); ?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">
<!--DataTables [ OPTIONAL ]-->
<link href="<?= base_url(''); ?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
<style>
    .survey{
        color:white !important;
        background-color: #26a69a;
        border-color: mistyrose !important;        
    }
    .holding{
        background-color: #3c4552 !important;
        border-color: mistyrose !important;
    }
    .connection{
        color:white !important;
        background-color: #042024;
    }
    .discconection{
        background-color: #140f1f;
    }
    .bill{
        background-color: #3c4552;
    }
</style>
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
            <li><a href="#">Water</a></li>
            <li class="active">Water Demand </li>
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">       

        <div class="panel panel-dark">
            <div class="panel-heading">
                <div class="panel-control" >
                    <button class="btn btn-primary" onclick="ExportToExcel('xlsx')">Export table to excel</button>
                </div>
                <h5 class="panel-title">Result</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="empTable" class="table table-responsive table-striped table-bordered text-sm">
                                <thead>
                                    <tr >
                                        <th rowspan="3">#</th>
                                        <th rowspan="2" >Ward No.</th>
                                        <th rowspan="2" >Assessed HH</th>
                                        <th rowspan="2" >No of assessed HH in apartment</th>
                                        <th rowspan="2" >Potential HH for water connection</th>
                                        <th rowspan="2" >Holding Connection Mapped with Water Connection</th>
                                        <th rowspan="2" >No. of WC (Portal)</th>
                                        <th rowspan="2" >Dry Connection</th>
                                        <th rowspan="2" >APL</th>
                                        <th colspan="2">Metered (6.1.1.1)</th>
                                        <th colspan="2">Non metered (6.1.1.2)</th>
                                        <th rowspan="2">BPL</th>
                                        <th colspan="2">Metered (6.1.2.1)</th>
                                        <th colspan="2">Non metered (6.1.2.2)</th>
                                        <th rowspan="2">Balance Connection</th>
                                        <th rowspan="2">APL</th>
                                        <th colspan="2">Metered (6.2.1.1)</th>
                                        <th colspan="2">Non metered (6.2.1.1)</th>
                                        <th rowspan="2">BPL</th>
                                        <th colspan="2">Metered (6.2.2.1)</th>
                                        <th colspan="2">Non metered (6.2.2.2)</th>
                                        <th rowspan="2">Total Metered</th>
                                        <th rowspan="2">Total Non metered</th>
                                        <th rowspan="2">Legacy Entry</th>
                                        <th rowspan="2">Total Paid Connection</th>
                                        <th rowspan="2">Total Unpaid Connection</th>                                        
                                    </tr>
                                    <tr>
                                        <th>Paid Connection</th>
                                        <th>Unpaid Connection</th>
                                        <th>Paid Connection</th>
                                        <th>Unpaid Connection</th>
                                        <th>Paid Connection</th>
                                        <th>Unpaid Connection</th>
                                        <th>Paid Connection</th>
                                        <th>Unpaid Connection</th>
                                        <th>Paid Connection</th>
                                        <th>Unpaid Connection</th>
                                        <th>Paid Connection</th>
                                        <th>Unpaid Connection</th>
                                        <th>Paid Connection</th>
                                        <th>Unpaid Connection</th>
                                        <th>Paid Connection</th>
                                        <th>Unpaid Connection</th>
                                    </tr>
                                    <tr >
                                        <th>[1]</th>
                                        <th>[2]</th>
                                        <th>[3]</th>
                                        <th>[4]=2-3</th>
                                        <th>[5]</th>
                                        <th>[6]</th>
                                        <th>[6.1]</th>
                                        <th>6.1.1</th>
                                        <th>A.1</th>
                                        <th>A.2</th>
                                        <th>B.1</th>
                                        <th>B.2</th>
                                        <th>6.1.2</th>
                                        <th>A.1</th>
                                        <th>A.2</th>
                                        <th>B.1</th>
                                        <th>B.2</th>
                                        <th>[6.2]</th>
                                        <th>6.2.1</th>
                                        <th>A.1</th>
                                        <th>A.2</th>
                                        <th>B.1</th>
                                        <th>B.2</th>
                                        <th>6.2.2</th> 

                                        <th>A.1</th>
                                        <th>A.2</th>
                                        <th>B.1</th>
                                        <th>B.2</th>
                                        <th>7=6.1.1.1+6.1.2.1 + 6.2.1.1 + 6.2.2.1</th>
                                        <th>8=6.1.1.2 + 6.1.2.2 + 6.2.1.2 + 6.2.2.2</th>
                                        <th>9</th>
                                        <th>10= Total of A1</th>
                                        <th>11= Total of B1</th>
                                    </tr>                                                                       
                                </thead>
                                <tbody>
                                    <?php
                                        $A= $B =$C =$D =$E =$F =$G =$H =$I =$J =$K =$L =$M 
                                        =$N =$O =$P =$Q =$R =$S =$T =$U =$V =$W =$X =$Y =$Z =$AA =$AB =$AC =$AD =$AE =$AF 
                                        =$AG =$AH=0;                                     
                                        $i = 0;
                                        $ad = 0;
                                        $ae = 0;
                                        $ta = 0;
                                        $tb = 0;
                                        $tpa = 0;
                                        if(isset($result))
                                        {                                             
                                            foreach($result as $val)
                                            {  
                                                $C += $val["total_prop"]??0;
                                                $D += ($val["total_appartment"]??0);
                                                  
                                                $F += $val["total_holding_consumer"]??0;
                                                $G += $val["total_consumer"]??0;
                                                $H += $val["total_dry_consumer"]??0;
                                                $I += $val["total_apl_dry_consumer"]??0;
                                                $J += $val["total_apl_meter_paid_dry_consumer"]??0;
                                                $K += $val["total_apl_meter_un_paid_dry_consumer"]??0;
                                                $L += $val["total_apl_non_meter_paid_dry_consumer"]??0;
                                                $M += $val["total_apl_non_meter_un_paid_dry_consumer"]??0;
                                                $N += $val["total_bpl_dry_consumer"]??0;
                                                $O += $val["total_bpl_meter_paid_dry_consumer"]??0;
                                                $P += $val["total_bpl_meter_un_paid_dry_consumer"]??0;
                                                $Q += $val["total_bpl_non_meter_paid_dry_consumer"]??0;
                                                $R += $val["total_bpl_non_meter_un_paid_dry_consumer"]??0;
                                                $S += $val["balance"]??0;
                                                $T += $val["balence_apl"]??0;
                                                $U += $val["balence_apl_paid_meter"]??0;
                                                $V += $val["balence_apl_unpaid_meter"]??0;
                                                $W += $val["balence_apl_paid_non_meter"]??0;
                                                $X += $val["balence_apl_unpaid_non_meter"]??0;
                                                $Y += $val["balence_bpl"]??0;
                                                $Z += $val["balence_bpl_paid_meter"]??0;
                                                $AA += $val["balence_bpl_unpaid_meter"]??0;
                                                $AB += $val["balence_bpl_paid_non_meter"]??0;
                                                $AC += $val["balence_bpl_unpaid_non_meter"]??0;

                                                $ad = (($val["total_apl_meter_paid_dry_consumer"]??0) + 
                                                        ($val["total_apl_meter_un_paid_dry_consumer"]??0) +
                                                        ($val["total_bpl_meter_paid_dry_consumer"]??0) +
                                                        ($val["total_bpl_meter_un_paid_dry_consumer"]??0) +
                                                        ($val["balence_apl_paid_meter"]??0) +
                                                        ($val["balence_bpl_paid_meter"]??0) + 
                                                        ($val["balence_bpl_unpaid_meter"]??0)
                                                        );
                                                $ae = (($val["total_apl_non_meter_paid_dry_consumer"]??0) +
                                                        ($val["total_apl_non_meter_un_paid_dry_consumer"]??0) +
                                                        ($val["total_bpl_non_meter_paid_dry_consumer"]??0) +
                                                        ($val["total_bpl_non_meter_un_paid_dry_consumer"]??0) +
                                                        ($val["balence_apl_paid_non_meter"]??0) +
                                                        ($val["balence_apl_unpaid_non_meter"]??0) +
                                                        ($val["balence_bpl_paid_non_meter"]??0) +
                                                        ($val["balence_bpl_unpaid_non_meter"]??0)
                                                        );
                                                $ta = (($val["total_apl_meter_paid_dry_consumer"]??0) +
                                                        ($val["total_bpl_meter_paid_dry_consumer"]??0) +
                                                        ($val["balence_apl_paid_meter"]??0) +
                                                        ($val["balence_bpl_paid_meter"]??0)
                                                        );
                                                $tb = (($val["total_apl_non_meter_paid_dry_consumer"]??0) +
                                                        ($val["total_bpl_non_meter_paid_dry_consumer"]??0) +
                                                        ($val["balence_apl_paid_non_meter"]??0) + 
                                                        ($val["balence_bpl_paid_non_meter"]??0)
                                                        );

                                                $tpa = ($val["total_prop"]??0)-($val["total_appartment"]??0);
                                                $E += $tpa;
                                                $AD += $ad;
                                                $AE += $ae;
                                                $AF  = 0;
                                                $AG += $ta;
                                                $AH += $tb;                                      
                                                ?>
                                                    
                                                <tr>
                                                    <td><?=++$i?></td>
                                                    <td><?=$val["ward_no2"]??"N/A";?></td>
                                                    <td><?=$val["total_prop"]??"N/A";?></td>
                                                    <td><?=$val["total_appartment"]??"N/A";?></td>
                                                    <td><?=$tpa;?></td>
                                                    <td><?=$val["total_holding_consumer"]??"N/A";?></td>
                                                    <td><?=$val["total_consumer"]??"N/A";?></td>
                                                    <td><?=$val["total_dry_consumer"]??"N/A";?></td>
                                                    <td><?=$val["total_apl_dry_consumer"]??"N/A";?></td>
                                                    <td><?=$val["total_apl_meter_paid_dry_consumer"]??"N/A";?></td>
                                                    <td><?=$val["total_apl_meter_un_paid_dry_consumer"]??"N/A";?></td>
                                                    <td><?=$val["total_apl_non_meter_paid_dry_consumer"]??"N/A";?></td>
                                                    <td><?=$val["total_apl_non_meter_un_paid_dry_consumer"]??"N/A";?></td>
                                                    <td><?=$val["total_bpl_dry_consumer"]??"N/A";?></td>
                                                    <td><?=$val["total_bpl_meter_paid_dry_consumer"]??"N/A";?></td>
                                                    <td><?=$val["total_bpl_meter_un_paid_dry_consumer"]??"N/A";?></td>
                                                    <td><?=$val["total_bpl_non_meter_paid_dry_consumer"]??"N/A";?></td>
                                                    <td><?=$val["total_bpl_non_meter_un_paid_dry_consumer"]??"N/A";?></td>
                                                    <td><?=$val["balance"]??"N/A";?></td>
                                                    <td><?=$val["balence_apl"]??"N/A";?></td>
                                                    <td><?=$val["balence_apl_paid_meter"]??"N/A";?></td>
                                                    <td><?=$val["balence_apl_unpaid_meter"]??"N/A";?></td>
                                                    <td><?=$val["balence_apl_paid_non_meter"]??"N/A";?></td>
                                                    <td><?=$val["balence_apl_unpaid_non_meter"]??"N/A";?></td>
                                                    <td><?=$val["balence_bpl"]??"N/A";?></td> 

                                                    <td><?=$val["balence_bpl_paid_meter"]??"N/A";?></td>
                                                    <td><?=$val["balence_bpl_unpaid_meter"]??"N/A";?></td>
                                                    <td><?=$val["balence_bpl_paid_non_meter"]??"N/A";?></td>
                                                    <td><?=$val["balence_bpl_unpaid_non_meter"]??"N/A";?></td>
                                                    <td><?=$ad;?></td>
                                                    <td><?=$ae;?></td>
                                                    <td>0</td>
                                                    <td><?=$ta;?></td>
                                                    <td><?=$tb;?></td>
                                                </tr>
                                                <?php                                            
                                            }
                                            ?>                                                
                                            <?php
                                        }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Total Forms</th>
                                        <th><?=$i;?></th>                                                    
                                        <th><?=$C;?></th>
                                        <th><?=$D;?></th>
                                        <th><?=$E;?></th>
                                        <th><?=$F;?></th>
                                        <th><?=$G;?></th>
                                        <th><?=$H;?></th>
                                        <th><?=$I;?></th>
                                        <th><?=$J;?></th>
                                        <th><?=$K;?></th>
                                        <th><?=$L;?></th>
                                        <th><?=$M;?></th>
                                        <th><?=$N;?></th>
                                        <th><?=$O;?></th>
                                        <th><?=$P;?></th>
                                        <th><?=$Q;?></th>
                                        <th><?=$R;?></th>
                                        <th><?=$S;?></th>
                                        <th><?=$T;?></th>
                                        <th><?=$U;?></th>
                                        <th><?=$V;?></th>
                                        <th><?=$W;?></th>
                                        <th><?=$X;?></th> 
                                        <th><?=$Y;?></th>
                                        <th><?=$Z;?></th>
                                        <th><?=$AA;?></th>
                                        <th><?=$AB;?></th>
                                        <th><?=$AC;?></th>
                                        <th><?=$AD;?></th>
                                        <th><?=$AE;?></th>
                                        <th><?=$AF;?></th>
                                        <th><?=$AG;?></th>
                                        <th><?=$AH;?></th>
                                    </tr>
                                </tfoot>
                            </table>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer'); ?>
<!--DataTables [ OPTIONAL ]-->

<script src="<?= base_url(''); ?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>

<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

<script type="text/javascript">

    function ExportToExcel(type, fn, dl) {
       var elt = document.getElementById('empTable');
       var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
       return dl ?
         XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
         XLSX.writeFile(wb, fn || ('MySheetName.' + (type || 'xlsx')));
    }
$(document).ready(function() {
    
    // $('#empTable').DataTable({
    //     responsive: false,
    //     dom: 'Bfrtip',
    //     "paging": false,
    //     "info": false,
    //     "searching":false,
    //     "aaSorting": [],
    //     "aoColumnDefs": [
    //         { "bSortable": false, "aTargets": [ 0, 1, 2 ] }, 
    //         { "bSearchable": false, "aTargets": [ 0, 1, 2 ] }
    //     ],
    //     buttons: [
    //         'pageLength',
    //         {
    //         text: 'Excel',
    //         extend: "excel",
    //         title: "(Water)",
    //         footer: { text: '' },
    //         exportOptions: { columns: [ 0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33] }
    //     }, 
    //     // {
    //     //     text: 'Print',
    //     //     extend: "print",
    //     //     title: "(Water)",
    //     //     download: 'open',
    //     //     footer: { text: '' },
    //     //     exportOptions: { columns: [ 0, 1,2, 3, 4, 5, 6, 7, 8, 9, 10, 11] }
    //     // }
    // ]
    // });

});
   
</script>

 <script>
    $('#survey_done').click(function() {
        $(".survey").show();
            // dataTable.draw();
        });
    $('#survey_no_done').click(function() {
            $(".survey").hide();
            // dataTable.draw();
        });
 </script>