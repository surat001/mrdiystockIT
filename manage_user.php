<?php include('header.php'); 
 include('navbar.php'); 
 include('sidebar.php'); 
 include('includes/db_connect.php'); 

include 'includes/fetch_user.php'; // เชื่อมต่อกับไฟล์ที่มีการจัดการผู้ใช้

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "mrdiyit");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูลกลุ่มจากฐานข้อมูล
$groupQuery = "SELECT id, group_name FROM groups";
$groupResult = $conn->query($groupQuery);
$groups = [];
if ($groupResult->num_rows > 0) {
    while ($row = $groupResult->fetch_assoc()) {
        $groups[] = $row;
    }
}
$conn->close();
?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Manage Users</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item">User & Master</li>
                <li class="breadcrumb-item active"><a href="manage_user.php">Manage Users</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

   
        <!-- Main Content -->
        <div class="container">
            <div class="header-section2">
                <h1></h1>
                <button type="button" class="btn btn-light add-user-btn" style = "padding: 10px 20px; font-size: 16px; font-weight: bold; text-align: center; border-radius: 15px; border: none; cursor: pointer; background-color: #42BD41; /* สีเขียว */ color: white; transition: background-color 0.3s ease;" 
                onmouseover="this.style.backgroundColor='#218838'" onmouseout="this.style.backgroundColor='#28a745'">+ Add User</button>
            </div>
            <div class="user-table">
                <div class="header-section2">
                    <table>
                        <thead>
                            <tr>

                                <th>Name</th>
                                <th>UserName</th>
                                <th>Password</th>
                                <th>Group</th>
                                <th style="width: 200px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($user_it)): ?>
                            <?php foreach ($user_it as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['username']) ?></td>
                                <td>****</td>
                                <td><?= htmlspecialchars($row['group_name'] ?? 'No Group') ?></td>
                                <td class="action-buttons">
                                    <button type="button" class="btn btn-warning edit-btn"   style="background-color: #4C6085; border-color: #4C6085; color: white;  border-radius: 8px;"
                                        data-userid="<?= htmlspecialchars($row['user_id']) ?>">Edit</button>
                                    <button type="button" class="btn btn-danger delete-btn" style=" border-radius: 8px; background-color: #F05858";
                                        data-userid="<?= htmlspecialchars($row['user_id']) ?>">Delete</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No users found.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
</main>

                                <!-- Add User Modal -->
<div id="addUserModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 50%; background: white; border: 1px solid #ccc; box-shadow: 0px 4px 10px rgba(0,0,0,0.1); z-index: 1000; padding: 20px; border-radius: 10px;">
    <h2 style="background: #01012F; padding: 10px; text-align: center; color: white; font-weight: bold; margin: -20px -20px 20px -20px; border-bottom: 2px solid #ccc; border-radius: 10px 10px 0 0; font-size: 24px;">Add User</h2>
    <form id="addUserForm"  method="POST">
        <!-- Grid Container -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <!-- Name -->
            <div style="grid-column: span 2;">
                <label for="name" style="display: block; margin-bottom: 5px; font-weight: bold;">Name</label>
                <input type="text" id="name" name="name" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; " required>
            </div>

            <!-- Username -->
            <div>
                <label for="username" style="display: block; margin-bottom: 5px; font-weight: bold;">Username</label>
                <input type="text" id="username" name="username" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required minlength="6" maxlength="12">
            </div>

            <!-- Password -->
            <div>
                <label for="password" style="display: block; margin-bottom: 5px; font-weight: bold;">Password</label>
                <input type="password" id="password" name="password" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required minlength="6" maxlength="12">
            </div>

            <!-- Group -->
            <div>
                <label for="group_name" style="display: block; margin-bottom: 5px; font-weight: bold;">Group</label>
                <select id="group_name" name="group_name" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
                    <?php foreach ($groups as $group): ?>
                        <option value="<?= htmlspecialchars($group['id']) ?>"><?= htmlspecialchars($group['group_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <!-- Buttons -->
        <div style="text-align: center; margin-top: 20px;">
            <button type="submit" style="background: #FFC107; color: black; padding: 10px 20px; border: none; border-radius: 5px; margin-right: 10px;">Save</button>
            <button type="button" class="close-modal-btn" data-modal-id="addUserModal" style="background: gray; color: white; padding: 10px 20px; border: none; border-radius: 5px;">Cancel</button>
        </div>
    </form>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 50%; background: white; border: 1px solid #ccc; box-shadow: 0px 4px 10px rgba(0,0,0,0.1); z-index: 1000; padding: 20px; border-radius: 10px;">
    <h2 style="background: #01012F; padding: 10px; text-align: center; color:white; font-weight: bold; margin: -20px -20px 20px -20px; border-bottom: 2px solid #ccc; border-radius: 10px 10px 0 0;font-size: 24px;">Edit User</h2>
    <form id="editUserForm" style="border-radius: 10px;" method="POST" action="../process/edit_user.php">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <!-- Hidden Input -->
            <input type="hidden" id="editId" name="editId">

            <!-- Name (แถวแรก ครอบ 2 คอลัมน์) -->
            <div style="grid-column: span 2;">
                <label for="editName" style="display: block; font-weight: bold; margin-bottom: 5px;">Name</label>
                <input type="text" id="editName" name="editName" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <!-- Username -->
            <div>
                <label for="editUsername" style="display: block; font-weight: bold; margin-bottom: 5px;">Username</label>
                <input type="text" id="editUsername" name="editUsername" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" minlength="6" maxlength="12">
            </div>

            <!-- New Password -->
            <div>
                <label for="editPassword" style="display: block; font-weight: bold; margin-bottom: 5px;">New Password</label>
                <input type="password" id="editPassword" name="editPassword" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" minlength="6" maxlength="12">
            </div>

            <!-- Group -->
            <div>
                <label for="editGroup" style="display: block; font-weight: bold; margin-bottom: 5px;">Group</label>
                <select id="editGroup" name="editGroup" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <?php foreach ($groups as $group): ?>
                        <option value="<?= htmlspecialchars($group['id']) ?>"><?= htmlspecialchars($group['group_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Buttons -->
        <div style="text-align: center; margin-top: 20px;">
            <button type="submit" form="editUserForm"style="background: #42BD41; color: white; padding: 10px 20px; border: none; border-radius: 5px; margin-right: 10px;">Save</button>
            <button type="button" class="close-modal-btn" data-modal-id="editUserModal" style="background: #F05858; color: white; padding: 10px 20px; border: none; border-radius: 5px;">Cancel</button>
            
        </div>
    </form>
</div>

            <link rel="stylesheet" href="assets/css/table.css">
            <link rel="stylesheet" href="assets/css/user.css">
            <style>
                .container {
                    max-width: 80%; /* เพิ่มความกว้างของคอนเทนเนอร์ */
                    padding: 0 20px; /* เพิ่ม padding ซ้ายขวา */
                }
                

                .add-user-btn {
                    background-color: #28a745; /* สีเขียว */
                    color: white;
                    transition: background-color 0.3s ease;
                }

                .add-user-btn:hover {
                    background-color: #218838; /* สีเขียวเข้มเมื่อ hover */
                }
                
                </style>
            <script src="assets/js/manage_user.js"></script>
 <br>
 <?php include('footer.php'); ?>

