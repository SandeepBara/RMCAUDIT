<?= $this->include('layout_vertical/popup_header'); ?>

<style>
    #footer {
        display: none;
    }
</style>
<script src="<?= base_url(); ?>/public/assets/otherJs/validation.js"></script>

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

        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title"> <?=$heading??"";?></h3>
            </div>
            <div class="panel-body table-responsive">
                <table class="table table-bordered table-responsive">
                    
                    <tbody>
                        <tr>
                            <td>Sl</td>
                            <td>Particulars</td>
                            <td>New Assessment</td>
                            <td>Reassessment</td>
                            <td>Mutation</td>
                            <td>Total</td>
                        </tr>
                        <?php
                        if($section==1 && $row==4){
                            ?>
                            
                                <tr>
                                    <td>1</td>
                                    <td>Opening Assessed House Hold on Start Date</td>
                                    <td><?=$result["active_holding_new_assessment"]??0;?></td>
                                    <td><?=$result["active_holding_reassessment"]??0;?></td>
                                    <td><?=$result["active_holding_mutation"]??0;?></td>
                                    <td><?=$result["active_holding"]??0;?></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>New Assessment Done During the Period</td>
                                    <td><?=$result["approved_saf_new_assessment"]??0;?></td>
                                    <td><?=$result["approved_saf_reassessment"]??0;?></td>
                                    <td><?=$result["approved_saf_mutation"]??0;?></td>
                                    <td><?=$result["approved_saf"]??0;?></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Deactivated During the Period</td>
                                    <td><?=$result["deactivated_hh_new_assessment"]??0;?></td>
                                    <td><?=$result["deactivated_hh_reassessment"]??0;?></td>
                                    <td><?=$result["deactivated_hh_mutation"]??0;?></td>
                                    <td><?=$result["deactivated_hh"]??0;?></td>
                                </tr>
                                <tr>
                                    
                                    <td>4</td>
                                    <td>Total Assessed House Hold on End Date (1+2-3)</td>
                                    <td><?=($result["active_holding_new_assessment"]??0)+($result["approved_saf_new_assessment"]??0)-($result["deactivated_hh_new_assessment"]??0);?></td>
                                    <td><?=($result["active_holding_reassessment"]??0)+($result["approved_saf_reassessment"]??0)-($result["deactivated_hh_reassessment"]??0);?></td>
                                    <td><?=($result["active_holding_mutation"]??0)+($result["approved_saf_mutation"]??0)-($result["deactivated_hh_mutation"]??0);?></td>
                                    <td><?=($result["active_holding"]??0)+($result["approved_saf"]??0)-($result["deactivated_hh"]??0);?></td>
                                </tr>
                            <?php
                        }
                        else{
                            ?>
                                <tr>
                                    <td>1</td>
                                    <td><?=$heading?></td>
                                    <td><?=$result["new"]??0;?></td>
                                    <td><?=$result["re"]??0;?></td>
                                    <td><?=$result["mu"]??0;?></td>
                                    <td><?=$result["total"]??0;?></td>
                                </tr>
                            <?php
                        }
                        
                        ?>
                    </tbody>
                </table>
            </div>
        </div>



    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->