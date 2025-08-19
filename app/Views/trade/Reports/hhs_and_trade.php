

<?= $this->include('layout_vertical/header');?>
<!--CSS Loaders [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-mint">
            <div class="panel-heading">
                <h5 class="panel-title">Summary of Non-Residential HHs and Trade Licence</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <form action="" method="Post">
                    <div class="col-md-12">
                        <div class="row">
                            <label class="col-md-2 text-bold">Fy Year</label>
                            <div class="col-md-3 has-success pad-btm">
                                <select  id="fy_year" class="form-control" name="fy_year">
                                    <?php
                                        foreach($fy_list as $val)
                                        {
                                            ?>
                                                <option value="<?=$val?>" <?=isset($_POST['fy_year']) && $_POST['fy_year']==$val?'selected':''?>><?=$val?></option>
                                            <?php
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4 text-right">
                                <input type="submit" id="btn_search" class="btn btn-primary" value="SEARCH" />                                
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-dark">
            <div class="panel-heading "> 
                <div class="panel-title text-center">
                    Summary of Non-Residential HHs and Trade Licence (<?=($priv_fy+1).'-'.($priv_fy+2)?>)
                </div>               
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        
                        <div class="table-responsive">
                            <table id="empTable" class="table table-striped table-bordered table-responsive text-sm">
                            <!-- <table class="table table-striped table-responsive table-bordered" id='empTable'> -->
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ULBs Name <br><span class="text-info">( ... )</span> </th>
                                        <th>Number of Mixed HH <br><span class="text-info">( 1 )</span></th>
                                        <th>Number of Non Residential HH <br><span class="text-info">( 2 )</span></th>
										<th>Total commercial HH <br><span class="text-info">( 3=1+2 )</span></th>
                                        <th>Number of Trade Licence New issued In <?=($priv_fy).'-'.($priv_fy+1)?>(PY) <br><span class="text-info">( 4 )</span></th>
                                        <th>Number of Trade Licence renew issued in <?=($priv_fy).'-'.($priv_fy+1)?>(PY)<br><span class="text-info">( 5 )</span></th>
                                        <th>Number of Trade Licence issued in <?=($priv_fy).'-'.($priv_fy+1)?>(PY)<br><span class="text-info">( 6=4+5 )</span></th>
                                        <th>Total Trade Licence Issued for more than one year <br><span class="text-info">( 7 )</span></th>
                                        <th>Number of Trade Licence to be renewed in <?=($priv_fy+1).'-'.($priv_fy+2)?>(CY) <br><span class="text-info">( 8=6-7 )</span></th>
                                        <th>No.of Surrendered Licence <br><span class="text-info">( 9 )</span> </th>
                                        <th>No.of Shop closed Licence <br><span class="text-info">( 10 )</span></th>
                                        <th>Number of Trade Licence Renewed till now <br><span class="text-info">( 11 )</span></th>
                                        <th>Trade Licence Denail Till Now <br><span class="text-info">( 12 )</span></th>
                                        <th>Balance Trade Licence not renewed till now <br><span class="text-info">( 13=8-(9+10+11+12))</span> </th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                        <!-- <tr style="background:#33b5e5;font-weight: bold; color: #fff;">
                                            <td></td>
                                            <td></td>
                                            <td>1</td>
                                            <td>2</td>
                                            <td>3=1+2</td>
                                            <td>4</td>
                                            <td>5</td>
                                            <td>6=4+5</td>
                                            <td>7</td>
                                            <td>8=6-7</td>
                                            <td>9</td>
                                            <td>10</td>
                                            <td>11</td>
                                            <td>12</td>                                            
                                            <td>13=8-(9+10+11+12)</td>
                                        </tr> -->
                                        <tr>
                                            <td>1</td>
                                            <td><?=$ulb_dtl['ulb_name']?></td>
                                            <td><?php echo $a=(isset($property_dtl['MIX_COMMERCIAL'])?$property_dtl['MIX_COMMERCIAL']['total']:0)+(isset($property_dtl['PURE_RESIDENCIAL'])?$property_dtl['PURE_RESIDENCIAL']['total']:0)?></td>
                                            <td><?php echo $b=(isset($property_dtl['PURE_COMMERCIAL'])?$property_dtl['PURE_COMMERCIAL']['total']:0)?></td>
                                            <td><?php echo $c=$a+$b?></td>
                                            <td><?php echo $d=(isset($count_app_type['NEW LICENSE'])?$count_app_type['NEW LICENSE']['total_app']:0)?></td>
                                            <td><?php echo $e=(isset($count_app_type['RENEWAL'])?$count_app_type['RENEWAL']['total_app']:0)?></td>
                                            <td><?php echo $f=$d+$e?></td>
                                            <td><?php echo $g=(isset($for_more_one_year['total'])?$for_more_one_year['total']:0)?></td>
                                            <td><?php echo $h=$f-$g?></td>
                                            <td><?php echo $i=(isset($surrended_trade['total'])?$surrended_trade['total']:0)?></td>
                                            <td><?php echo $j=(isset($close['total'])?$close['total']:0)?></td>
                                            <td><?php echo $k=(isset($renewal_till_now['total'])?$renewal_till_now['total']:0)?></td>
                                            <td><?php echo $l=(isset($denail_till_now['total'])?$denail_till_now['total']:0)?></td>
                                            <td><?php echo $m=($h-($i+$j+$k+$l))?></td>
                                        </tr>
                                </tbody>
                                
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
<?= $this->include('layout_vertical/footer');?>
<!--DataTables [ OPTIONAL ]-->
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>

<!--DataTables [ OPTIONAL ]-->
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>

<script type="text/javascript">
var collector_name = "";
<?php
if (isset($empDtlList)) 
{
    foreach ($empDtlList as $list) 
    {
        ?>
            collector_name += '<option value="<?=$list['id'];?>" <?=($list['status']==1)?"":"style='color:red'";?>><?=$list['emp_name']." ".$list['middle_name']." ".$list['last_name']." (".$list['user_type'].")";?></option>';
        <?php
    }
}
?>

$(document).ready(function(){
        //$.fn.dataTable.ext.errMode = 'throw';
        var dataTable = $('#empTable').DataTable({
            // 'responsive': true,
            // 'processing': true,
            'searching':false,
            //'pagination':false,
            "deferLoading": 0, // default ajax call prevent
            // 'serverSide': true,
            dom: 'Bfrtip',
            lengthMenu: [
                [10, 25, 50, 5000],
                ['10 rows', '25 rows', '50 rows', '5000 rows']
            ],
            buttons: [
                // {
                //     extend:    'copyHtml5',
                //     text:      '<i class="fa fa-files-o">Copy</i>',
                //     titleAttr: 'Copy'
                // },
                {
                    extend:    'excelHtml5',
                    // text:      '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Excel'
                },
                // {
                //     extend:    'csvHtml5',
                //     text:      '<i class="fa fa-file-text-o"></i>',
                //     titleAttr: 'CSV'
                // },
                // {
                //     extend:    'pdfHtml5',
                //     text:      '<i class="fa fa-file-pdf-o"></i>',
                //     titleAttr: 'PDF'
                // }
            ],
            
        });
        
    });
</script>
