<?= $this->include('layout_home/header');?>
<?php $session = session(); ?>
<style>
.page_margin {
    margin-left: 150px; 
    margin-right: 150px
}
@media(max-width: 858px){
    .page_margin {
        margin-left: 0px; 
        margin-right: 0px
    }
}
</style>
<!--CONTENT CONTAINER-->
<div id="content-container" style="padding-top: 10px;">
<!--Page content-->
    <div class="page_margin">
        <div id="page-content">
            <div class="panel panel-bordered panel-dark">
                <div class="panel-body">
                    <div class="fixed-fluid">
                        <?= $this->include('citizen/SAF/SafCommonPage/saf_left_side');?>
                        <div class="fluid">
                            <?=$this->include('citizen/SAF/SafCommonPage/safBasicDtl');?>
                            <div class="panel panel-bordered panel-dark">
                                <?php $saf_dtl = $session->get('saf_dtl'); ?>
                                <div class="panel-heading">
                                    <h3 class="panel-title">Document Details</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead class="bg-gray">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Effect From</th>
                                                            <th>ARV</th>
                                                            <th>Quarterly Tax</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>s</td>
                                                            <td>s</td>
                                                            <td>s</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div><!--End page content-->
    </div>
</div><!--END CONTENT CONTAINER-->
<?= $this->include('layout_home/footer');?>
