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
					<li class="active">Document List</li>
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
					                <h3 class="panel-title"><?= isset($title)?$title:''; ?> Document</h3>

					            </div>

					            <!--Horizontal Form-->
					            <!--===================================================-->
					            <div class="panel-body">
                                    <div class="pad-btm">
                                        <a href="<?php echo base_url('document/index') ?>"><button id="demo-foo-collapse" class="btn btn-purple"><i class="fa fa-arrow-left"></i> Back   </button></a>
                                    </div>
                                        <form class="form-horizontal" method="post" action="">
                                        <input type="hidden" name="id" id="id" value="<?=(isset($id))?$id:"";?>">
                                    <div class="form-group">
                                       <label class="col-sm-2 control-label" for="doc_type">Document Type<span style="color:red">*</span></label>
                                        <div class="col-sm-4">
                                        <select id="doc_type" name="doc_type" class="form-control">
                                        <option value="">--select--</option>
                                         <option value="transfer_mode" <?=(isset($doc_type))?($doc_type=="transfer_mode")?"selected":"":"";?>>Transfer Mode</option>
                                        <option value="property_type" <?=(isset($doc_type))?($doc_type=="property_type")?"selected":"":"";?>>Property Type</option>
                                        <option value="other" <?=(isset($doc_type))?($doc_type=="other")?"selected":"":"";?>>Other</option>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="form-group transfer">
                                       <label class="col-sm-2 control-label" for="transfer_mode">Transfer Mode<span style="color:red">*</span></label>
                                        <div class="col-sm-4">
                                        <select id="transfer_mode" name="transfer_mode" class="form-control">
                                            <option value="">--select--</option>
                                            <?php foreach($transferModeList as $value):?>
                                              <option value="<?=$value['id']?>" <?=(isset($doc_id))?$doc_id==$value["id"]?"SELECTED":"":"";?>><?=$value['transfer_mode'];?>
                                            </option>
                                            <?php endforeach;?>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="form-group property">
                                       <label class="col-sm-2 control-label" for="property type">Property Type<span style="color:red">*</span></label>
                                        <div class="col-sm-4">
                                        <select id="property_type" name="property_type" class="form-control">
                                        <option value="">--select--</option>
                                            <?php foreach($propTypeList as $value):?>
                                              <option value="<?=$value['id']?>" <?=(isset($doc_id))?$doc_id==$value["id"]?"SELECTED":"":"";?>><?=$value['property_type'];?>
                                            </option>
                                            <?php endforeach;?>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="doc_name">Document Name<span style="color:red">*</span></label>
                                        <div class="col-sm-4">
                                        <input type="text" maxlength="20" placeholder="Enter Document Name" id="doc_name" name="doc_name" class="form-control" value="<?=(isset($doc_name))?$doc_name:"";?>" onkeypress="return isAlpha(event);" >
                                        </div>
                                    </div>
                                    <div class="form-group">
					                    <label class="col-sm-2 control-label" for="Document">&nbsp;</label>
					                    <div class="col-sm-4">
					                        <button class="btn btn-success" id="btn_doc" name="btn_doc" type="submit"><?=(isset($id))?"Edit Document":"Add Document";?></button>
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
        $('.transfer,.property').hide();
        var transfer_mode = $('#transfer_mode').val();
        var property_type = $('#property_type').val();
        var doc_type = $("#doc_type").val();
        if(doc_type=="transfer_mode")
        {
            if(transfer_mode!="")
            {
                $(".transfer").show();
            }
            else
            {
                $(".transfer").hide();
                $(".transfer_mode").val('');
            } 
        }
       else if(doc_type=="property_type")
        {
          if(property_type!="")
          {
            $(".property").show();
          }
          else
          {
            $(".property").hide();
            $(".property_type").val('');
          }  
        }
        else
        {
            $(".property_type").val(''); 
            $(".transfer_mode").val('');
        }
        $("#btn_doc").click(function(){
            var doc_name = $("#doc_name").val();
                   doc_name = doc_name.trim();
            var doc_type = $("#doc_type").val();
            if(doc_name=="")
            {
                $("#doc_name").css({"border-color":"red"});
                $("#doc_name").focus();
                return false;
            }
            if(doc_type=="")
            {
                $("#doc_type").css({"border-color":"red"});
                $("#doc_type").focus();
                return false;
            }
            if(doc_type!="")
            {
                if(doc_type=="transfer_mode")
                {
                    var transfer_mode = $('#transfer_mode').val();
                    if(transfer_mode=="")
                    {
                        $("#transfer_mode").css({"border-color":"red"});
                        $("#transfer_mode").focus();
                        return false;
                    }
                }
                if(doc_type=="property_type")
                {
                    var property_type = $('#property_type').val();
                    if(property_type=="")
                    {
                        $("#property_type").css({"border-color":"red"});
                        $("#property_type").focus();
                        return false;
                    }
                }
            }
        });
        $("#doc_name").keyup(function(){$(this).css('border-color','');});
        $("#doc_type").change(function(){$(this).css('border-color','');});
        $("#transfer_mode").change(function(){$(this).css('border-color','');});
        $("#property_type").change(function(){$(this).css('border-color','');});
       $('#doc_type').change(function(){
            var doc_type = $('#doc_type').val();
            if(doc_type=="transfer_mode")
            {
                $('.transfer').show();
                $('#property_type').val('');
            }
            else
            {
               $('.transfer').hide(); 
               $('#transfer_mode').val('');
            }
            if(doc_type=="property_type")
            {
                $('.property').show();
                $('#transfer_mode').val('');
            }
            else
            {
                $('.property').hide();
                $('#property_type').val('');
            }
       });
});
</script>>