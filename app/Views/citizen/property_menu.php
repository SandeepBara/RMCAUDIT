<?= $this->include('layout_home/header');?>
<style type="text/css">
    .menu_panel_hover:hover {
        background-color: #e0e5ea;
        cursor: pointer;
    }
</style>
<!--CONTENT CONTAINER-->
<div id="content-container" style="padding-top: 10px;">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">PROPERTY LIST</h3>
            </div>
            <div class="panel-body bg-gray-light pad-ver">
                <div class="row">
                    <div class="col-md-3">
                        <a href="<?=base_url();?>/Citizen/SelectMunicipal/<?=hashEncrypt('CitizenSaf/safmanual');?>">
                            <div class="panel media middle panel-bordered-purple menu_panel_hover">
                                <div class="media-left pad-all">
                                    <img src="<?=base_url();?>/public/assets/img/list/prop_assessment.png" width="48" height="48" />
                                </div>
                                <div class="media-body pad-btm">
                                    <h4>Self Assessment</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?=base_url();?>/Citizen/SelectMunicipal/<?=hashEncrypt('CitizenPropertySAF/index');?>">
                            <div class="panel media middle panel-bordered-purple menu_panel_hover">
                                <div class="media-left pad-all">
                                    <img src="<?=base_url();?>/public/assets/img/list/credit-card.png" width="48" height="48" />
                                </div>
                                <div class="media-body pad-btm">
                                    <h4>SAF Payment</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?=base_url();?>/Citizen/SelectMunicipal/<?=hashEncrypt('CitizenProperty/index');?>">
                            <div class="panel media middle panel-bordered-purple menu_panel_hover">
                                <div class="media-left pad-all">
                                    <img src="<?=base_url();?>/public/assets/img/list/credit-card.png" width="48" height="48" />
                                </div>
                                <div class="media-body pad-btm">
                                    <h4>Property Payment</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>                
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_home/footer');?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>
<script>
    $(document).ready(function(){
        $("#formValidate").validate({
            rules:{
                ulb_mstr_id:{
                    required:true
                }
            },
            messages:{
                ulb_mstr_id:{
                    required:"Please select ULB."
                }
            }
        });
    });
</script>