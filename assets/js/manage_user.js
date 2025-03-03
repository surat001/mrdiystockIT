// เปิด Modal สำหรับเพิ่มผู้ใช้
function Adduser() {
    const addUserModal = document.getElementById("addUserModal");
    const addUserForm = document.getElementById("addUserForm");

    if (addUserModal) {
        addUserModal.style.display = "block";
        addUserForm.reset();
        addUserForm.action = "Nice/process/add_user.php";
    }
}

// ปิด Modal
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = "none";
    }
}

// แก้ไขข้อมูลผู้ใช้
function handleEdituser(userId) {
    const editUserModal = document.getElementById("editUserModal");

    // ดึงข้อมูลผู้ใช้จากเซิร์ฟเวอร์
    fetch(`/Nice/process/get_user.php?id=${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // เติมข้อมูลในฟอร์มแก้ไข
                document.getElementById("editId").value = data.user.user_id;
                document.getElementById("editName").value = data.user.name;
                document.getElementById("editUsername").value = data.user.username;
                document.getElementById("editGroup").value = data.user.group_id;

                // แสดงฟอร์มแก้ไข
                if (editUserModal) editUserModal.style.display = "block";
            } else {
                alert('Failed to fetch user data.');
            }
        })
        .catch(error => {
            console.error('Error fetching user data:', error);
            alert('An error occurred while fetching user data.');
        });
}

// ลบผู้ใช้
function confirmDeleteuser(userId) {
    // ตรวจสอบว่า userId ถูกต้องหรือไม่
    if (!userId) {
        alert('User ID is missing');
        return;
    }

    // ใช้ SweetAlert2 เพื่อแสดงข้อความยืนยัน
    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'No, cancel!',
        confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
        // ถ้าผู้ใช้กดยืนยัน
        if (result.isConfirmed) {
            // ส่งคำขอผ่าน AJAX เพื่อทำการลบผู้ใช้
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `../Nice/process/delete_user.php?id=${userId}`, true);

            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log(xhr.responseText); // ตรวจสอบข้อความที่ได้รับจากเซิร์ฟเวอร์
                    try {
                        const response = JSON.parse(xhr.responseText); // แปลง JSON

                        if (response.success) {
                            // ถ้าลบสำเร็จแสดง SweetAlert2
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'User deleted successfully.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // รีโหลดหน้าเพื่อให้ข้อมูลอัพเดต
                                window.location.reload();
                            });
                        } else {
                            // ถ้าการลบไม่สำเร็จ แสดงข้อความแจ้งเตือน
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    } catch (e) {
                        console.error('Error parsing JSON response:', e);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Request failed with status ' + xhr.status,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            };

            xhr.onerror = function() {
                Swal.fire({
                    title: 'Error!',
                    text: 'Request failed. Please try again later.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            };

            xhr.send(); // ส่งคำขอไปยัง server
        }
    });
}

// Event Listeners
document.addEventListener("DOMContentLoaded", function () {
    // Add User button
    const addUserBtn = document.querySelector(".add-user-btn");
    if (addUserBtn) {
        addUserBtn.addEventListener("click", Adduser);
    }

    // Edit buttons
    const editButtons = document.querySelectorAll(".edit-btn");
    if (editButtons) {
        editButtons.forEach((btn) => {
            btn.addEventListener("click", function (event) {
                handleEdituser(this.getAttribute("data-userid"));
            });
        });
    }

    // Delete buttons
    const deleteButtons = document.querySelectorAll(".delete-btn");
    if (deleteButtons) {
        deleteButtons.forEach((btn) => {
            btn.addEventListener("click", function () {
                confirmDeleteuser(this.getAttribute("data-userid"));
            });
        });
    }

    // Close buttons
    const closeModalButtons = document.querySelectorAll(".close-modal-btn");
    if (closeModalButtons) {
        closeModalButtons.forEach((btn) => {
            btn.addEventListener("click", function() {
                closeModal(this.getAttribute("data-modal-id"));
            });
        });
    }
    
});

document.addEventListener("DOMContentLoaded", () => {
    const userForm = document.getElementById("addUserForm");
    if (userForm) {
        userForm.addEventListener("submit", function (e) {
            e.preventDefault(); // ป้องกันการ submit แบบปกติ

            const password = document.getElementById("password").value;
            const passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d).+$/; // ✅ ต้องมีทั้งตัวเลขและตัวอักษร

            if (!passwordRegex.test(password)) {
                Swal.fire({
                    icon: "error",
                    title: "Invalid Password",
                    text: "Password must contain at least one letter and one number.",
                });
                return;
            }

            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to save this user?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, save it!",
                cancelButtonText: "Cancel",
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData(this);

                    fetch("../Nice/process/add_user.php", {
                        method: "POST",
                        body: formData,
                    })
                        .then((response) => response.json())
                        .then((data) => {
                            if (data.status === "success") {
                                Swal.fire({
                                    icon: "success",
                                    title: "Success",
                                    text: data.message,
                                }).then(() => {
                                    window.location.href = "manage_user.php"; // เปลี่ยนเส้นทางไปยังหน้าแรก
                                });
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Error",
                                    text: data.message,
                                });
                            }
                        })
                        .catch((error) => {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "An unexpected error occurred.",
                            });
                            console.error("Error:", error);
                        });
                }
            });
        });
    }

    const editUserForm = document.getElementById("editUserForm");
    if (editUserForm) {
        editUserForm.addEventListener("submit", function (e) {
            e.preventDefault(); // ป้องกันการ submit แบบปกติ
    
            const password = document.getElementById("editPassword").value;
            const passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d).+$/; // ✅ ต้องมีทั้งตัวเลขและตัวอักษร
    
            if (password !== "" && !passwordRegex.test(password)) {
                Swal.fire({
                    icon: "error",
                    title: "Invalid Password",
                    text: "Password must contain at least one letter and one number.",
                });
                return;
            }
    
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to update this user?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, update it!",
                cancelButtonText: "Cancel",
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData(this);
    
                    fetch("../Nice/process/edit_user.php", {
                        method: "POST",
                        body: formData,
                    })
                        .then((response) => response.json())
                        .then((data) => {
                            if (data.status === "success") {
                                Swal.fire({
                                    icon: "success",
                                    title: "Success",
                                    text: data.message,
                                }).then(() => {
                                    window.location.href = "../Nice/manage_user.php"; // เปลี่ยนเส้นทางไปยังหน้าแรก
                                });
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Error",
                                    text: data.message,
                                });
                            }
                        })
                        .catch((error) => {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "An unexpected error occurred.",
                            });
                            console.error("Error:", error);
                        });
                }
            });
        });
    }
    
});