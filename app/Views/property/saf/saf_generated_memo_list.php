<?= $this->include('layout_vertical/header'); ?>
<div id="content-container">
    <div id="page-head">
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Property</a></li>
            <li><a href="#">SAF</a></li>
            <li class="active">SAM Memo List</li>
        </ol>
    </div>
    <div id="page-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h5 class="panel-title">Assessment Memo List</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="encryptFrom" name="encryptFrom" value="<?=$sql??"";?>">
                                <form id="myForm" class="form-horizontal" method="get" action="<?php echo base_url('documentverification/list_of_saf_generated_memo'); ?>">
                                    <div class="form-group">
                                        <div class="col-md-2">
                                            <label class="control-label" for="ward No"><b>Ward No</b><span class="text-danger">*</span> </label>
                                            <select id="ward_mstr_id" name="ward_mstr_id" class="form-control" required>
                                                <option value="All">ALL</option>
                                                <?php foreach ($wardList as $value) : ?>
                                                    <option value="<?= $value['ward_mstr_id'] ?>" <?= (isset($ward_mstr_id)) ? $ward_mstr_id == $value["ward_mstr_id"] ? "SELECTED" : "" : ""; ?>><?= $value['ward_no']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label" for="search_by_holding_no"><b>Enter Holding No.</b> </label>
                                            <input type="text" id="search_by_holding_no" name="search_by_holding_no" class="form-control" placeholder="Enter Holding No." value="<?=$search_by_holding_no??"";?>" />
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label" for="search_by_saf_no"><b>Enter Saf No.</b> </label>
                                            <input type="text" id="search_by_saf_no" name="search_by_saf_no" class="form-control" placeholder="Enter Saf No." value="<?=$search_by_saf_no??"";?>" />
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label" for="search_by_memo_no"><b>Enter Memo No.</b> </label>
                                            <input type="text" id="search_by_memo_no" name="search_by_memo_no" class="form-control" placeholder="Enter Memo No." value="<?=$search_by_memo_no??"";?>" />
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label" for="daId"><b>Dealing List</b> </label>
                                            <select id="daId" name="daId" class="form-control">
                                                <option value="">All</option>
                                                <?php
                                                    foreach($daList??[] as $item){
                                                        ?>
                                                        <option value="<?=$item['id'];?>" <?=($daId??"")==$item['id']?"selected":"";?>><?=$item["emp_name"]?></option>
                                                        <?php
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12 text-center">
                                            <button class="btn btn-primary" id="btn_export" type="button">Export</button>
                                            <button class="btn btn-primary" id="btn_search" type="submit">Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="row">
                            <div class="table-responsive">
                                <table id="demo_dt_basic" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Ward No.</th>
                                            <th>Memo No.</th>
                                            <th>Holding No.</th>
                                            <th>SAF No.</th>
                                            <th>Owner Name</th>
                                            <th>Mobile No.</th>
                                            <th>Assessment Type</th>
                                            <th>Approval Date</th>
                                            <th>Approved By</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($memo_list)) {
                                            $i = $offset;
                                            foreach ($memo_list as $value) {
                                        ?>
                                                <tr>
                                                    <td><?= ++$i; ?></td>
                                                    <td><?= $value["ward_no"]; ?></td>
                                                    <td><?= $value["memo_no"]; ?></td>
                                                    <td><?= $value["holding_no"]; ?></td>
                                                    <td><?= $value["saf_no"]; ?></td>
                                                    <td><?= $value['owner_name']; ?></td>
                                                    <td><?= $value['mobile_no']; ?></td>
                                                    <td><?= $value['assessment_type']; ?></td>
                                                    <td><?= ($value['created_on']=="")?"":date("Y-m-d", strtotime($value['created_on'])); ?></td>
                                                    <td><?= $value['emp_name']??""; ?></td>
                                                    <td>
                                                        <a class="btn btn-primary" href="javascript: void(0)" onclick="window.open('<?= base_url(); ?>/citizenPaymentReceipt/da_eng_memo_receipt/<?= md5($ulb_mstr_id); ?>/<?= md5($value['id']); ?>', 'newwindow', 'width=1000, height=1000'); return false;">View</a>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="7" class="text text-center">
                                                    <span class="text text-danger"> Not Available </span>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <?= pagination($pager); ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->include('layout_vertical/footer'); ?>
<script>
    $("#btn_export").click(function() {
        try{
            let formData = new FormData(document.getElementById("myForm"));
            formData.append("encryptFrom", $("#encryptFrom").val());

            $.ajax({
                type:"POST",
                url: "<?=base_url();?>/DocumentVerification/list_of_saf_generated_memo_excel",
                dataType: "json",
                processData: false,
                contentType: false,
                // data: {
                //     "ward_mstr_id":$("#ward_mstr_id").val(),
                //     "search_by_holding_no":$("#search_by_holding_no").val(),
                //     "search_by_saf_no":$("#search_by_saf_no").val(),
                //     "search_by_memo_no":$("#search_by_memo_no").val(),
                // },
                data:formData,
                beforeSend: function() {
                    modelInfo("Please Wait, Report is generating...");
                    $("#excel_export_ajax").val("Please Wait...");
                    $("#loadingDiv").show();
                },
                success:function(data){
                    console.log(data);
                    var filename = data;
                    window.open('<?=base_url();?>/'+filename);
                    $("#loadingDiv").hide();
                    $("#excel_export_ajax").val("EXCEL EXPORT");
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    $("#loadingDiv").hide();
                    $("#excel_export_ajax").val("EXCEL EXPORT");
                }
            });
        }catch (err) {
            alert(err.message);
        }
    });
    
</script>