<?=$this->include("layout_mobi/header");?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-mint">
                <div class="panel-heading">
                    <h3 class="panel-title">Form Receive <a href="<?php echo base_url('safdistribution/receive_form_search') ?>" class="btn btn-danger" style="float:right;"> Back </a></h3>

                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <label class="col-sm-2">SAF No.:</label>
                        <div class="col-sm-4 pad-btm">
                             <b><?php echo $form['saf_no'] ?></b>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2">Ward No:</label>
                        <div class="col-sm-4 pad-btm">
                             <b><?php echo $ward['ward_no'] ?></b>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2">Owner Name:</label>
                        <div class="col-sm-4 pad-btm">
                             <b><?php echo $form['owner_name'] ?></b>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2">Phone No.:</label>
                        <div class="col-sm-4 pad-btm">
                             <b><?php echo $form['phone_no'] ?></b>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2">Owner Address:</label>
                        <div class="col-sm-4 pad-btm">
                             <b><?php echo $form['owner_address'] ?></b>
                        </div>
                    </div>
                </div>
            </div>
        <form method="POST" action="">
        <input type="hidden" name="saf_distributed_dtl_id" value="<?=(isset($form['id']))?$form['id']:'';?>">
        <!-------Transfer Mode-------->
            <div class="panel panel-bordered panel-mint">
                <div class="panel-heading">
                    <h3 class="panel-title">Transfer Mode</h3>

                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="col-xs-6">
                            <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Transfer Mode</th>

                                            </tr>
                                        </thead>
                                        <tbody id="tr_tbody">
                                    <?php
                                    if(isset($transfer_mode)):
                                          if(empty($transfer_mode)):
                                    ?>
                                            <tr>
                                                <td colspan="2" style="text-align: center;">Data Not Available!!</td>
                                            </tr>
                                    <?php else:
                                            $i=0;
                                            foreach ($transfer_mode as $value):
                                            $i++;

                                    ?>
                                            <tr>
                                                <td><input type="radio" class="tr_mode" id="transfer_mode<?=$i;?>" value="<?=$value["id"];?>" name="transfer_mode"> <?=$value["transfer_mode"];?></td>

                                            </tr>
                                        <?php endforeach;?>
                                         <?php endif;  ?>
                                    <?php endif;  ?>
 					                    </tbody>
					                </table>
                        </div>
                    <div class="col-xs-6">
                        <span id="tr_doc_name"></span>
                        <input type="hidden" id="tr_doc_len" name="tr_doc_len" value="" />
                        </div>
                </div>
            </div>
            <!-------Propery Type-------->
            <div class="panel panel-bordered panel-mint">
                <div class="panel-heading">
                    <h3 class="panel-title">Property Type</h3>

                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="col-xs-6">
                            <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Property Type</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php
                                    if(isset($property_type)):
                                          if(empty($property_type)):
                                    ?>
                                            <tr>
                                                <td colspan="3" style="text-align: center;">Data Not Available!!</td>
                                            </tr>
                                    <?php else:
                                            $i=0;
                                            foreach ($property_type as $value):
                                            $i++;

                                    ?>
                                            <tr>
                                                <td><input type="radio" class="pr_mode" id="property_type<?=$i;?>" value="<?=$value["id"];?>" name="property_type"> <?=$value["property_type"];?></td>

                                            </tr>
                                        <?php endforeach;?>
                                         <?php endif;  ?>
                                    <?php endif;  ?>
 					                    </tbody>
					                </table>
                                </div>
                                    <div class="col-xs-6">
                                        <span id="pr_doc_name"></span>
                                        <input type="hidden" id="pr_doc_len" name="pr_doc_len" value="" />
                                    </div>

                </div>
            </div>
        <!-------Others-------->
            <div class="panel panel-bordered panel-mint">
                <div class="panel-heading">
                    <h3 class="panel-title">Others</h3>

                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div>
                            <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Others Documents</th>
                                            </tr>
                                        </thead>
                                        <tbody >
                                    <?php
                                    if(isset($other_doc)):
                                          if(empty($other_doc)):
                                    ?>
                                            <tr>
                                                <td colspan="3" style="text-align: center;">Data Not Available!!</td>
                                            </tr>
                                    <?php else:
                                            $i=0;
                                            foreach ($other_doc as $value):
                                            $i++;
                                            //print_r($value['doc_name']);
                                    ?>
                                            <tr>
                                                <td><input type="checkbox" id="other_doc<?=$i;?>" value="<?=$value["id"];?>" name="doc_mstr_id[]"> <?=$value["doc_name"];?></td>

                                            </tr>
                                        <?php endforeach;?>
                                         <?php endif;  ?>
                                    <?php endif;  ?>
 					                    </tbody>
					                </table>
                        </div>
                </div>
            </div>
            <button class="btn btn-success" id="btndesign" name="btndesign" type="submit">Submit</button>
        </form>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>
<script type="text/javascript">
$(document).ready( function () {
    $("#tr_doc_name").hide();
    $("#pr_doc_name").hide();
    $('input[name="transfer_mode"]').click(function() {
        $('#tr_doc_name').show();
        $('#tr_doc_name').html('');
        var doc_type_val = $("input[name='transfer_mode']:checked").val();
        //alert(doc_type_val);
        try{
            $.ajax({
                type:"POST",
                url: "<?=base_url('safdistribution/getTrDocumentName');?>",
                dataType: "json",
                data: {"doc_type_val":doc_type_val},
                beforeSend: function() {
                    $("#loadingDiv").show();
                },
                success:function(data){
                    console.log(data);
                    if(data.response==true){
                        $('#tr_doc_name').html('<h4 class="text-danger">Documents: </h4>');
                        var max_length=(data.data).length;
                        $("#tr_doc_len").val(max_length);
                        var i;
                        if(max_length>0)
                        {
                            for(i = 0; i < max_length; i++){
                                var doc_id=data.data[i]['id'];
                                var doc_name=data.data[i]['doc_name'];

                                $('#tr_doc_name').append('<input type="checkbox" name="doc_mstr_id[]" class="tr_chk" id="tr_chk'+ i +'" value='+ doc_id +' /> <b>' + doc_name + '</b><br/>');
                            }
                        }
                        else
                        {
                            $('#tr_doc_name').html('<h4 class="text-danger">Documents not available!! </h4>');
                        }
                    }else{
                            //$('#chk_load').html('ffd');
                    }
                    $("#loadingDiv").hide();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $("#loadingDiv").hide();
                    alert(JSON.stringify(jqXHR));
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
        }catch (err) {
            alert(err.message);
        }

});
    ///Property Type 
    $('input[name="property_type"]').click(function() {
        $('#pr_doc_name').show();
        $('#pr_doc_name').html('');
        var doc_type_val = $("input[name='property_type']:checked").val();
       //alert(doc_type_val);
        try{
            $.ajax({
                type:"POST",
                url: "<?=base_url('safdistribution/getPrDocumentName');?>",
                dataType: "json",
                data: {"doc_type_val":doc_type_val},
                beforeSend: function() {
                    $("#loadingDiv").show();
                },
                success:function(data){
                    console.log(data);
                    if(data.response==true){
                        $('#pr_doc_name').html('<h4 class="text-danger">Documents: </h4>');
                        var max_length=(data.data).length;
                        $("#pr_doc_len").val(max_length);
                        var i;
                        if(max_length>0)
                        {
                            for(i = 0; i < max_length; i++){
                            var doc_id=data.data[i]['id'];
                            var doc_name=data.data[i]['doc_name'];
                                //alert(doc_name);
                            $('#pr_doc_name').append('<input type="checkbox" name="doc_mstr_id[]" class="pr_chk" id="pr_chk'+ i +'" value='+ doc_id +' /> <b>' + doc_name + '</b><br/>');
                            }
                        }
                        else
                         {
                            $('#pr_doc_name').html('<h4 class="text-danger">Documents not available!! </h4>');
                         }

                    }else{
                            //$('#pr_doc_name').html('Documentsb ');
                    }
                    $("#loadingDiv").hide();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $("#loadingDiv").hide();
                    alert(JSON.stringify(jqXHR));
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
        }catch (err) {
            alert(err.message);
        }
    });
    $("#btndesign").click(function() {
        var process = true;

        if (!$("input[name='transfer_mode']:checked").val()) {
       alert('Please Choose Atleast one option of transfer mode !!');
        process = false;
         }
        if (!$("input[name='property_type']:checked").val()) {
       alert('Please Choose Atleast one option of  property type!!');
        process = false;
         }
        if ($("input[name='transfer_mode']:checked").val()) {
            var tr_doc_len=$("#tr_doc_len").val();
            if(tr_doc_len>0)
            {
                if (!$(".tr_chk").is(':checked')) {
                    alert("Choose Atleast One Document of Transfer Mode!!!");
                    process = false;
                }
            }
            else
            {
                alert("Choose Another option of Transfer Mode!!");
                process = false;
            }

         }
        if ($("input[name='property_type']:checked").val()) {
            var pr_doc_len=$("#pr_doc_len").val();
            if(pr_doc_len>0)
            {
                if (!$(".pr_chk").is(':checked')) {
                    alert("Choose Atleast One Document of Property Type!!!");
                    process = false;
                }
            }
            else
            {
                alert("Choose Another option of Property Type!!");
                process = false;
            }

         }
        return process;
    });
});
</script>