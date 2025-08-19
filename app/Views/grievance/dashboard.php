<?= $this->include('layout_home/header'); ?>

<script src="<?= base_url(); ?>/public/assets/otherJs/validation.js"></script>

<style>
    .container_wrapper {
        display: flex;
    }



    .content_container {
        padding: 20px;
        width: 100%;
    }

    .welcome-card {
        width: 500px;
        height: 300px;
        background-color: #ffffff;
        border: 1px solid #ddd;
        border-radius: 4px;
        /* box-shadow: 0 1px 1px rgba(0,0,0,.05); */
        /* box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.2); */
        box-shadow: 6px 6px 15px rgba(0, 0, 0, 0.3);
        padding: 20px;
        margin-top: 20px;
        text-align: center;
    }

    .welcome-card h2 {
        margin-top: 0;
        font-size: 24px;
        color: #333;
    }

    .welcome-card p {
        font-size: 16px;
        color: #666;
    }
</style>

<div class="main_div_container">
    <div class="container_wrapper">
        <!-- CITIZEN GRIEVANCE SIDEBAR START HERE -->
        <div class="left_sidebar_container">
            <?php
            $citizen = true;

            if ($citizen) {
                echo $this->include('grievance/layout/sidebar');
            } else {
                echo '<h1>Main User Sidebar</h1>';
            }
            ?>

        </div>
        <!-- CITIZEN GRIEVANCE SIDEBAR END HERE -->
        <!-- CONTENT CONTAINER -->
        <div class="content_container">
            <div class="welcome-card">
                <h2>Welcome to Grievance, <?= $user_name; ?>!</h2>
                <p>You are logged in as <?= $user_role; ?>. We hope you have a great experience managing your grievances.</p>
            </div>
        </div>
    </div>
</div>

<?= $this->include('layout_home/footer'); ?>
<script src="<?= base_url(); ?>/public/assets/js/jquery.validate.js"></script>