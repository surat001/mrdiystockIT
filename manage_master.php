<?php include('header.php'); ?>
<?php include('navbar.php');?>
<?php include('sidebar.php'); ?>
<?php include('includes/db_connect.php');?>
<?php include 'includes/fetch_master.php'; // ใช้ไฟล์ดึงข้อมูลของ master ?>


<main id="main" class="main">

    <div class="pagetitle">
        <h1>Manage Master</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item">User & Master</li>
                <li class="breadcrumb-item active">Manage Master</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->


        <div class="container">
            <div class="header-section2">
                <h1></h1>
                <button type="button" class="btn btn-light" onclick="Addgroup()"
                style = "padding: 10px 20px; font-size: 16px; font-weight: bold; text-align: center; border-radius: 15px; border: none; cursor: pointer; background-color: #42BD41; /* สีเขียว */ color: white; transition: background-color 0.3s ease;" 
                        onmouseover="this.style.backgroundColor='#218838'" onmouseout="this.style.backgroundColor='#28a745'">
                + Add Group</button>
            </div>

            <!-- Groups Table -->
            <table class="table table-bordered text-center mt-3">
                <thead class="table-primary">
                    <tr>
                        <th>Group Name</th>

                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="groupTableBody">
                    <?php if (!empty($groups_data)): ?>
                    <?php foreach ($groups_data as $index => $group): ?>
                    <tr>
                        <td id="groupName_<?= $group['id'] ?>"><?= htmlspecialchars($group['group_name']) ?></td>

                        <td>
                            <button type="button" class="btn btn-warning btn-sm"   style="background-color: #4C6085; border-color: #4C6085; color: white;"
                                onclick='Edituser(<?= $group["id"] ?>, "<?= htmlspecialchars($group["group_name"], ENT_QUOTES) ?>", <?= htmlspecialchars(json_encode($group["permissions"] ?? []), ENT_QUOTES, 'UTF-8') ?>)'>
                                Edit
                            </button>
                            <button class="btn btn-danger btn-sm" style=" background-color: #F05858";
                                onclick="deleteGroup(<?= $group['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="3">No groups found</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    

    <!-- Add Group Modal -->
    <div id="addGroupModal"
        style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 50%; background: white; border: 1px solid #ccc; box-shadow: 0px 4px 10px rgba(0,0,0,0.1); z-index: 1000; padding: 20px; border-radius: 10px;">
        <h2
            style="background: #01012F; padding: 10px; text-align: center; color: white; font-weight: bold; margin: -20px -20px 20px -20px; border-bottom: 2px solid #ccc; border-radius: 10px 10px 0 0; font-size: 24px;">
            Add Group</h2>
        <form id="addGroupForm" method="POST" action="process/add_group.php">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <!-- Group Name -->
                <div style="grid-column: span 2;">
                    <label for="groupName" style="display: block; margin-bottom: 5px; font-weight: bold;">Group
                        Name</label>
                    <input type="text" id="groupName" name="groupName"
                        style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
                </div>
            </div>

            <!-- Access Permissions -->
            <br>
            <h style="margin-top: 5px; font-weight: bold;">Permissions</h>
            <table class="permission-table">
                <thead>
                    <tr>
                        <th>PAGE</th>
                        <th>Access</th>
                    </tr>
                </thead>
                <tbody>
                <?php
$pages = ["dashboard.php", "all_product.php", "manage_stock.php", "manage_balance.php", "add_new_balance.php", "add_product.php", "report.php", "manage_user.php", "manage_master.php"];
foreach ($pages as $page) {
    $pageName = pathinfo($page, PATHINFO_FILENAME);
    echo "<tr>";
    echo "<td>$pageName</td>";
    echo "<td><input type='checkbox' class='access' name='access[$page]' value='1'></td>";
    echo "</tr>";
}
?>

                </tbody>
            </table>

            <div style="text-align: center; margin-top: 20px;">
                <button type="submit"
                    style="background: #FFC107; color: black; padding: 10px 20px; border: none; border-radius: 5px; margin-right: 10px;">Save</button>
                <button type="button" onclick="closeModal('addGroupModal')"
                    style="background: gray; color: white; padding: 10px 20px; border: none; border-radius: 5px;">Cancel</button>
            </div>
        </form>
    </div>


    <!-- Edit Group Modal -->
    <div id="editGroupModal"
        style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 50%; background: white; border: 1px solid #ccc; box-shadow: 0px 4px 10px rgba(0,0,0,0.1); z-index: 1000; padding: 20px; border-radius: 10px;">
        <h2
            style="background: #01012F; padding: 10px; text-align: center; color: white; font-weight: bold; margin: -20px -20px 20px -20px; border-bottom: 2px solid #ccc; border-radius: 10px 10px 0 0; font-size: 24px;">
            Edit Group</h2>
        <form id="editGroupForm" method="POST" action="process/edit_group.php">
            <input type="hidden" id="editGroupId" name="editGroupId">

            <!-- Group Name -->
            <div style="margin-bottom: 15px;">
                <label for="editGroupName" style="display: block; margin-bottom: 5px; font-weight: bold;">Group
                    Name</label>
                <input type="text" id="editGroupName" name="editGroupName"
                    style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
            </div>

            <!-- Access Permissions -->
            <h style="margin-top: 20px;font-weight: bold;">Permissions</h>
            <table class="permission-table">
                <thead>
                    <tr>
                        <th>PAGE</th>
                        <th>Access</th>
                    </tr>
                </thead>
                <tbody id="editPermissionsTable">
                    <!-- 🛠 ค่าจะถูกโหลดจาก JavaScript -->
                </tbody>
            </table>

            <div style="text-align: center; margin-top: 20px;">
                <button type="submit"
                    style="background: #42BD41; color: white; padding: 10px 20px; border: none; border-radius: 5px; margin-right: 10px;">Save</button>
                <button type="button" onclick="closeModal('editGroupModal')"
                    style="background: gray; color: white; padding: 10px 20px; border: none; border-radius: 5px;">Cancel</button>
            </div>
        </form>
    </div>


</main><!-- End #main -->

<script>
document.addEventListener("DOMContentLoaded", function() {
    function toggleEdit(checkbox, page) {
        let viewCheckbox = document.querySelector('input[name="can_view[' + page + ']"]');
        if (checkbox.checked) {
            viewCheckbox.checked = true; // ถ้าเลือก Edit ให้เลือก View อัตโนมัติ
        }
    }

    // ทำให้ toggleEdit ใช้งานได้ใน onclick
    window.toggleEdit = toggleEdit;
});
</script>
            
            <link rel="stylesheet" href="assets/css/user.css">
            <link rel="stylesheet" href="assets/css/master.css">
            <script src="assets/js/master.js"></script>

    <style>

        .container {
            /* max-width: 100%; เพิ่มความกว้างของคอนเทนเนอร์ */
            padding: 0 20px; /* เพิ่ม padding ซ้ายขวา */
        }
        
        .table {
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            border: 2px solid #000;
            border-collapse: collapse;
            width: 100%;
            border-radius: 10px;
            overflow: hidden; /* ให้ขอบมน */
        }
        /* เพิ่มเส้นขอบให้กับทุกเซลล์ของตาราง */
        .table th, .table td {
            border: 1px solid #000; /* สีดำ */
            padding: 10px;
            text-align: center;
        }

        /* กำหนดสีพื้นหลังให้หัวตาราง */
        .table thead {
            background-color: #c8d6e5; /* สีฟ้าอ่อน */
            border-bottom: 2px solid #000;
        }

    </style>
            
<?php include('footer.php'); ?>