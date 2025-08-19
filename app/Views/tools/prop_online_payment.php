<?= $this->include('layout_vertical/header');?>
<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <form method="post" id="form_saf_property" name="form_saf_property" action="">

        <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Property Payment Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>Property Id</th>
                                            <th>Upto Financial Year</th>
                                            <th>Upto Quarter</th>
                                            <th>Total Payable Amount </th>
                                            <th>Payment Date</th>
                                        </tr>
                                    </thead>
                                    <tbody id="prop_online_payment">
                                        <tr>
                                            <td>
                                                <input type="text" name="prop_id" class="form-control" required="required" value="" />
                                            </td>
                                            <td>
                                                <input type="text" name="upto_fy" class="form-control" required="required" value="" />
                                            </td>
                                            <td>
                                                <input type="text" name="upto_qtr"  class="form-control" required="required" value="" />
                                            </td>
                                            <td>
                                                <input type="text" name="total_payable_amount" class="form-control" required="required" value="" />
                                            </td>
                                            <td>
                                                <input type="date" id="payment_date" name="payment_date" class="form-control" required="required" value="<?=date('Y-m-d');?>" />
                                            </td>
                                        </tr>
                                    </tbody>

                                </table>
                                <div class="">
                                    <input type="submit" name="payment_submit" class="btn btn-primary" value="Save" />
                                    <br /><br />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--End holding_owner_details Bootstrap Modal-->
        </form>
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<script type="text/javascript">
</script>