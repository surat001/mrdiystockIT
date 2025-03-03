function Addgroup() {
    document.getElementById('addGroupModal').style.display = 'block';
}

// ฟังก์ชัน Edit Group ที่รองรับการโหลด Access Permissions
function Edituser(groupId, groupName) {
    document.getElementById('editGroupId').value = groupId;
    document.getElementById('editGroupName').value = groupName;

    fetch(`../Nice/includes/fetch_group_permissions.php?group_id=${groupId}`)
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            let permissions = data.permissions;
            let tableBody = document.getElementById("editPermissionsTable");

            tableBody.innerHTML = "";

            let pages = ["dashboard.php", "all_product.php", "manage_stock.php", "manage_balance.php", "add_new_balance.php", "add_product.php", "report.php", "manage_user.php", "manage_master.php"];

            pages.forEach(page => {
                let row = document.createElement("tr");

                let cellPage = document.createElement("td");
                cellPage.textContent = page.replace('.php', '');

                let cellAccess = document.createElement("td");
                let accessCheckbox = document.createElement("input");
                accessCheckbox.type = "checkbox";
                accessCheckbox.className = "access";
                accessCheckbox.name = `access[${page}]`;
                accessCheckbox.value = "1";
                if (permissions[page] && permissions[page].access == 1) {
                    accessCheckbox.checked = true;
                }
                cellAccess.appendChild(accessCheckbox);

                row.appendChild(cellPage);
                row.appendChild(cellAccess);
                tableBody.appendChild(row);
            });

        } else {
            console.error("Failed to fetch permissions");
        }
    })
    .catch(error => console.error("Error fetching permissions:", error));

    document.getElementById('editGroupModal').style.display = 'block';
}



// ฟังก์ชันควบคุมให้เมื่อเลือก Edit จะเลือก View อัตโนมัติ
function toggleEdit(checkbox, page) {
    let viewCheckbox = document.querySelector(`input[name="can_view[${page}]"]`);
    if (checkbox.checked) {
        viewCheckbox.checked = true;
    }
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// ฟังก์ชันแสดง SweetAlert เมื่อสำเร็จ
function showSuccessAlert(message) {
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: message,
        confirmButtonText: 'OK'
    }).then(() => {
        location.reload();
    });
}

// ตรวจสอบการส่งฟอร์มและอัปเดตผ่าน fetch API
document.addEventListener('DOMContentLoaded', function () {
    const addGroupForm = document.getElementById('addGroupForm');
    if (addGroupForm) {
        addGroupForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(this);
            fetch(this.action, { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        closeModal('addGroupModal');
                        showSuccessAlert(data.message);
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonText: 'OK' });
                    }
                })
                .catch(error => Swal.fire({ icon: 'error', title: 'Error', text: 'An unexpected error occurred.', confirmButtonText: 'OK' }));
        });
    }

    const editGroupForm = document.getElementById('editGroupForm');
    if (editGroupForm) {
        editGroupForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(this);
            fetch(this.action, { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        closeModal('editGroupModal');
                        showSuccessAlert(data.message);
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonText: 'OK' });
                    }
                })
                .catch(error => Swal.fire({ icon: 'error', title: 'Error', text: 'An unexpected error occurred.', confirmButtonText: 'OK' }));
        });
    }
});

// ฟังก์ชันสำหรับลบกลุ่ม
function deleteGroup(groupId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`process/delete_group.php?id=${groupId}`, { method: 'GET' })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire('Deleted!', 'Your group has been deleted.', 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonText: 'OK' });
                    }
                })
                .catch(error => Swal.fire({ icon: 'error', title: 'Error', text: 'An unexpected error occurred.', confirmButtonText: 'OK' }));
        }
    });
}
