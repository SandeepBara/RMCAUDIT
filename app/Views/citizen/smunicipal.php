<?= $this->include('layout_home/header');?>
<style type="text/css">
    .error {
        color: red;
    }
</style>

 <!--CONTENT CONTAINER-->
<div id="content-container" style="padding-top: 10px;">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">SELECT ULB</h3>
            </div>
            <div class="panel-body">
                <form method="POST" id="formValidate">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row" style="padding-top: 10px;">
                                <div class="col-md-2"></div>
                                <label class="col-md-2 pad-btm text-right">SELECT ULB <span class="text-danger">*</span></label>
                                <div class="col-md-3 pad-btm">
                                    <input type="hidden" name="url" value="<?=(isset($url))?$url:"";?>" />
                                    <select id="ulb_mstr_id" name="ulb_mstr_id" class="form-control">
                                        <!-- <option value="">== PLEASE SELECT ==</option> -->
                                    <?php
                                    if(isset($ulb_list))
                                    {
                                        foreach ($ulb_list as $key => $ulb)
                                        {
                                            ?>
                                            <option value="<?=md5($ulb['id']);?>"><?=strtoupper($ulb['ulb_name']);?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                    </select>
                                </div>
                                <div class="col-md-2 pad-btm">
                                    <button type="SUBMIT" id="submit" name="submit" class="btn btn-block btn-mint">GO NOW</button>
                                </div>
                                <div class="col-md-3"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-4">
                                    <img src="<?=base_url();?>/public/assets/img/jharkhand_map3.jpg" style="height: 300px;" />
                                </div>
                                <div class="col-md-4"></div>
                            </div>
                        </div>
                    </div>
                </form>
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


        $("#ulb_mstr_id").val('<?=md5(1);?>');
    });

</script>