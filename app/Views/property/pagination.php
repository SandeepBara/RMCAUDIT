<?= $this->include('layout_vertical/header');?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">List</h5>
            </div>
            <div class="panel-body">
                <div class ="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Saf No</th>
                                        <th>assessment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if($users): ?>
                                <?php foreach($users as $user): ?>
                                <tr>
                                    <td><?php echo $user['id']; ?></td>
                                    <td><?php echo $user['saf_no']; ?></td>
                                    <td><?php echo $user['saf_no']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                            <?php
                            ?>
                            <?=pagination($pager);?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>

