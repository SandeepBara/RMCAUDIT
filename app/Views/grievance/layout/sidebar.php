<style>
    .left_sidebar_container {
        width: 200px;
        height: 100vh;
        background-color: #5c789f;
        padding: 10px;
        margin-right: 15px;
    }

    .left_sidebar_container ul {
        padding-left: 10px;
    }

    .left_sidebar_container ul li {
        margin-top: 10px;
        list-style: none;
        padding: 10px;
        border-radius: 8px;
        cursor: pointer;
    }

    .left_sidebar_container ul li:hover {
        background-color: #000000;
        color: #ffffff;
    }

    .left_sidebar_container ul li a {
        font-family: verdana;
        font-size: 16px;
        font-weight: normal;
        color: #ffffff;
    }

    .left_sidebar_container ul li.active {
        background-color: #3b4d6d;
        /* Darker background color for the active menu item */
        color: #ffffff;
        /* Text color for the active menu item */
    }

    .left_sidebar_container ul li.active a {
        color: #ffffff;
        /* Ensures the link text stays white */
    }
</style>

<div class="link_main_container">
    <ul>
        <li class="<?= (current_url() == base_url('/grievance_new/welcome')) ? 'active' : ''; ?>">
            <a href="<?= base_url(); ?>/grievance_new/welcome">Dashboard</a>
        </li>
        <li class="<?= (current_url() == base_url('/grievance_new/applyGrievance')) ? 'active' : ''; ?>">
            <a href="<?= base_url(); ?>/grievance_new/applyGrievance">New Grievance</a>
        </li>
        <li class="<?= (strpos(current_url(), base_url('/grievance_new/citizenGrievanceList')) === 0) ? 'active' : ''; ?>">
            <a href="<?= base_url(); ?>/grievance_new/citizenGrievanceList">View Grievance</a>
        </li>
        <li>
            <a href="<?= base_url("/grievance_new/logout"); ?>">Logout</a>
        </li>
    </ul>
</div>
