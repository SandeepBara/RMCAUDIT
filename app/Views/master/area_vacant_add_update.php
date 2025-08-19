<?= $this->include('layout_vertical/header');?>
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 
<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">

                    <!--Page Title-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <div id="page-title">
                        <!--<h1 class="page-header text-overflow">Add/Update Designation</h1>//-->
                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End page title-->


                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
					<li><a href="#"><i class="demo-pli-home"></i></a></li>
					<li><a href="#">Masters</a></li>
					<li class="active">Area Vacant Add/Update</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->

                </div>


                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="row">
					    <div class="col-sm-12">
					        <div class="panel">
					            <div class="panel-heading">
					                <h3 class="panel-title"><?php echo $title; ?>Area Vacant</h3>
					            </div>
					            <!--Horizontal Form-->
					            <!--===================================================-->
					                <div class="panel-body">
                              <div class="pad-btm">
                               <a href="<?php echo base_url('Area_Building/areaBuildingList');?>" class="btn btn-purple"><i class="fa fa-arrow-left"></i> Back </a>
                                </div>
                                  <form class="form-horizontal" method="post" action="<?php echo base_url('Area_Vacant/add_update/'.$id);?>">
                                  <input type="hidden" name="id" id="id" value="<?=(isset($id))?$id:"";?>">
					                    <div class="form-group">
					                   <label class="col-sm-2 " for="road_type_mstr_id">Road Type<span style="color:red">*</span></label>
					                   <div class="col-sm-4">
					                  <select id="road_type_mstr_id" name="road_type_mstr_id" class="form-control">
                              <option value="">--select--</option>
                                <?php foreach($roadTypeList as $value):?>
                                  <option value="<?=$value['id']?>" <?=(isset($road_type_mstr_id))?$road_type_mstr_id==$value["id"]?"SELECTED":"":"";?>><?=$value['road_type'];?>
                                </option>
                                <?php endforeach;?>
                            </select>
					                </div>
					                </div>
                          <div class="form-group">
                              <label class="col-sm-2 " for="rate">Rate <span style="color:red">*</span></label>
                                <div class="col-sm-4">
                                  <input type="text" maxlength="10" placeholder="Enter Rate" id="rate" name="rate" class="form-control" value="<?=(isset($rate))?$rate:"";?>" onkeypress="return isDecNum(this, event);">
                                </div>
                          </div>
                             <div class="form-group">
					            <label class="col-sm-2 " for="area_building">&nbsp;</label>
					               <div class="col-sm-4">
					               <button class="btn btn-success" id="btn_area_vacant" name="btn_area_vacant" type="submit"><?=(isset($id))?"Edit Arr Vacant":"Add Arr Vacant";?></button>
					              </div>
					       </div>
                            <?php if(isset($validation)){ ?>
                                <?= $validation->listErrors(); ?>

                            <?php } ?>
                          </form>
                        </div>

					            <!--===================================================-->
					            <!--End Horizontal Form-->

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
<script type="text/javascript">
  $(document).ready(function(){
    $("#btn_area_vacant").click(function(){
        var road_type_mstr_id = $('#road_type_mstr_id').val();
        var rate = $('#rate').val();
        if (road_type_mstr_id =="") 
        {
          $("#road_type_mstr_id").css({"border-color":"red"});
          $("#road_type_mstr_id").focus();
          return false;
        }
        if (rate =="" || rate==".") 
        {
          $("#rate").css({"border-color":"red"});
          $("#rate").focus();
          return false;
        }
    });
    $("#road_type_mstr_id").change(function(){$(this).css('border-color','');});
    $("#rate").keyup(function(){$(this).css('border-color','');});
});
function isDecNum(txt, evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode == 46) {
            //Check if the text already contains the . character
            if (txt.value.indexOf('.') === -1) {
                return true;
            } else {
                return false;
            }
        } else {
            if (charCode > 31 &&
                (charCode < 48 || charCode > 57))
                return false;
        }
        return true;
 }
</script>>