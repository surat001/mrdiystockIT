<?php include('header.php'); ?>
<?php include('navbar.php'); ?>
<?php include('sidebar.php'); ?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Add & Delete Product</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item">Stock</li>
                <li class="breadcrumb-item active"><a href="add_product.php">Add & Delete Product</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

     <!-- Section for Stock Management -->
     <div class="container1">
        <div class="rounded p-3 bg-white shadow-sm" style="border: 2px solid rgb(109, 109, 109) !important;">
            <div class="d-flex align-items-center">
                <!-- ปุ่ม Add Product -->
                <button class="btn btn-warning text-black me-2" id="showaddproduct">
                    <i class="fas fa-edit"></i> Add Product
                </button>
                <button class="btn btn-danger" id="showTableBtn">
                    <i class="fas fa-trash-alt"></i> Delete Product
                </button>
            </div>
        </div>
    </div>

    <br>
    <!-- Delete Stock Table -->
    <div class="container2" id="deleteStockTable" style="display: none;">
        <h4 class="mb-3"><strong>Delete Stock</strong></h4>
        <div class="table-responsive">
            <table id="stockTable" class="table table-striped table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>Barcode</th>
                        <th>Picture</th>
                        <th>Part Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody> <!-- ข้อมูลจะถูกโหลดผ่าน AJAX -->
            </table>
        </div>
    </div>

    <!-- ✅ ซ่อนฟอร์มไว้ก่อน -->
<div class="container-fluid mt-4" id="addProductContainer" style="display: none;">
    <div class="card shadow-sm p-4 mx-auto" style="max-width: 95%;">
        <form id="productForm" action="process/save_product.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="barcode" class="form-label"><strong>Barcode</strong></label>
                <input type="text" class="form-control" id="barcode" name="barcode" required>
            </div>

            <div class="mb-3">
                <label for="part_name" class="form-label"><strong>Part Name</strong></label>
                <input type="text" class="form-control" id="part_name" name="part_name" required>
            </div>

            <div class="mb-3">
                <label for="set_balance" class="form-label"><strong>Set Balance</strong></label>
                <input type="number" class="form-control" id="set_balance" name="set_balance" required>
            </div>

            <div class="mb-3">
                <label class="form-label"><strong>Picture</strong></label>
                <div class="dropzone dz-clickable text-center" id="imageDropzone">
                    <div class="dz-message">
                        <i class="bi bi-cloud-upload" style="font-size: 2rem;"></i>
                        <p>Click or drop the file to upload.</p>
                    </div>
                </div>
                <input type="hidden" name="image_path" id="image_path">
            </div>

            <div class="d-flex justify-content-center mt-4 gap-3">
                <button type="submit" class="btn btn-lg save-btn">Save</button>
                <a href="add_product.php" class="btn btn-lg cancel-btn">Cancel</a>
            </div>
        </form>
    </div>
</div>

</main><!-- End #main -->

<?php include('footer.php'); ?>

<!-- Dropzone.js -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>

<!-- Include DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
     // ✅ ซ่อน DataTable และฟอร์ม Add Product ไว้ก่อน
    $("#deleteStockTable").hide();
    $("#addProductContainer").hide();

    // ✅ ฟังก์ชันกดปุ่ม "Add Product"
    $("#showaddproduct").click(function () {
        $("#addProductContainer").fadeToggle(); // ✅ แสดง/ซ่อนฟอร์มเพิ่มสินค้า
        $("#deleteStockTable").hide(); // ✅ ซ่อนตาราง Delete Product ถ้ามีการแสดงอยู่
    });



    //---------------------------------------------------------------

    // แสดง DataTable เมื่อกดปุ่ม Delete Stock
    $("#showTableBtn").click(function() {
        $("#deleteStockTable").fadeToggle(); // ✅ แสดง/ซ่อนตาราง Delete Product
        $("#addProductContainer").hide(); // ✅ ซ่อนฟอร์ม Add Product ถ้ามีการแสดงอยู่
       


        if ($.fn.DataTable.isDataTable("#stockTable")) {
            $("#stockTable").DataTable().destroy(); // ลบ DataTable เดิมก่อนโหลดใหม่
        }

        $("#stockTable").DataTable({
            "ajax": {
                "url": "includes/fetch_products.php", // ดึงข้อมูลจากไฟล์ PHP
                "type": "GET",
                "dataSrc": "" // กำหนดว่า JSON ที่ส่งมาเป็น array
            },
            "columns": [{
                    "data": "barcode"
                },
                {
                    "data": "picture_url",
                    "render": function(data, type, row) {
                        return '<img src="' + data +
                            '" style="width:50px; height:auto; max-width:100%;">';
                    }
                },
                {
                    "data": "part_name"
                },
                {
                    "data": "barcode",
                    "render": function(data, type, row) {
                        return '<button class="btn btn-danger delete-btn" data-barcode="' +
                            data + '">Delete</button>';
                    }
                }
            ],
            "columnDefs": [{
                    "targets": "_all",
                    "className": "text-center"
                } // จัดทุกคอลัมน์ตรงกลาง
            ],
            "scrollY": "370px",
            "scrollX": true,
            "scrollCollapse": true,
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": false,
            "fixedHeader": true,
            "lengthMenu": [
                [10, 25, 50, 100, 500, -1],
                ["10", "25", "50", "100", "500", "All"]
            ],
            "pageLength": 25,
            "language": {
                "search": "🔍",
                "lengthMenu": " _MENU_ rows/page",
                "info": "Showing _START_ to _END_ of _TOTAL_ rows",
                "infoEmpty": "No data available",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Previous"
                }
            }
        });

        // ปรับ placeholder ของช่องค้นหา
        $('.dataTables_filter input')
            .attr("placeholder", " Search...")
            .addClass("search-placeholder");
    });

    // ตรวจจับเมื่อกดย่อหรือขยาย Sidebar
    $(document).on('click', '.toggle-sidebar-btn', function() {
        setTimeout(function() {
            if ($.fn.DataTable.isDataTable('#stockTable')) {
                var table = $('#stockTable').DataTable();
                if (table) {
                    table.columns.adjust().draw(); // ปรับขนาดคอลัมน์
                }
            }
        }, 300); // ให้เวลาหน้าเว็บปรับ Layout ก่อน
    });

    // ตรวจจับการเปลี่ยนขนาดหน้าจอ
    $(window).on('resize', function() {
        if ($.fn.DataTable.isDataTable('#stockTable')) {
            var table = $('#stockTable').DataTable();
            if (table) {
                table.columns.adjust().draw();
            }
        }
    });

    // ตรวจจับปุ่มลบข้อมูล
    $(document).ready(function() {
        // ตรวจจับปุ่มลบข้อมูล
        $(document).on("click", ".delete-btn", function() {
            var barcode = $(this).data("barcode");

            // แสดง SweetAlert เพื่อยืนยันการลบ
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    // ถ้าผู้ใช้กด "Yes, delete it!" ให้ส่ง AJAX ไปลบข้อมูล
                    $.ajax({
                        url: "process/delete_product.php",
                        type: "POST",
                        data: {
                            barcode: barcode
                        },
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "The item has been deleted.",
                                icon: "success",
                                timer: 2000, // ปิดอัตโนมัติใน 2 วินาที
                                showConfirmButton: false
                            });

                            $("#stockTable").DataTable().ajax
                        .reload(); // โหลดข้อมูลใหม่
                        },
                        error: function() {
                            Swal.fire({
                                title: "Error!",
                                text: "Something went wrong. Please try again.",
                                icon: "error",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        });
    });

});

Dropzone.autoDiscover = false;
var imageDropzone = new Dropzone("#imageDropzone", {
    url: "includes/upload_image.php",
    maxFiles: 1,
    acceptedFiles: "image/*",
    addRemoveLinks: true,
    success: function(file, response) {
        if (response.status === "success") {
            document.getElementById("image_path").value = response.filePath;
        } else {
            alert("Upload failed: " + response.message);
        }
    },
    error: function(file, response) {
        console.error("Dropzone error:", response);
    },
    removedfile: function(file) {
        var filePath = document.getElementById("image_path").value;

        if (!filePath) {
            alert("File path is empty. Cannot delete file.");
            return;
        }

        console.log("Deleting file:", filePath); // ตรวจสอบค่าก่อนส่งไปลบ

        fetch('includes/delete_image.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    filePath: filePath
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log("Server response:", data); // ตรวจสอบ response ที่ส่งกลับมา
                if (data.status === "success") {
                    document.getElementById("image_path").value = "";
                    var _ref;
                    return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file
                        .previewElement) : void 0;
                } else {
                    alert("Failed to delete image: " + data.message);
                }
            })
            .catch(error => {
                console.error("Error deleting image:", error);
            });
    }


});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById("productForm").addEventListener("submit", function(event) {
    event.preventDefault(); // ป้องกันการ submit แบบเดิม

    // ดึงค่าจากฟอร์ม
    var formData = new FormData();
    formData.append("barcode", document.getElementById("barcode").value);
    formData.append("part_name", document.getElementById("part_name").value);
    formData.append("set_balance", document.getElementById("set_balance").value);
    formData.append("image_path", document.getElementById("image_path").value);

    fetch("process/save_product.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json()) // แปลง response เป็น JSON
    .then(data => {
        console.log("Server Response:", data);

        if (data.status === "success") {
            Swal.fire({
                icon: "success",
                title: "Success",
                text: data.message,
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.location.href = "add_product.php";
            });
        } else {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: data.message,
                showConfirmButton: true
            });
        }
    })
    .catch(error => {
        console.error("Error:", error);
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Something went wrong!",
            showConfirmButton: true
        });
    });
});
</script>
<style>

/* ปรับให้ฟอร์มกว้างเต็มหน้าจอ */
.container-fluid {
    width: 100%;
   
}

.card {
    width: 100%;
    padding: 2rem;
}

/* ปรับเส้นประของ Dropzone */
#imageDropzone {
    border: 2px dashed #ccc !important;
    padding: 15px; /* 🔹 ลด padding เพื่อให้ dropzone ไม่สูงเกินไป */
    border-radius: 10px;
    text-align: center;
    background: #f9f9f9;
    max-height: 170px; /* 🔹 กำหนดความสูงสูงสุด */
    min-height: 80px; /* 🔹 ป้องกันไม่ให้เตี้ยเกินไป */
    display: flex;
    align-items: center; /* 🔹 จัดข้อความให้อยู่กึ่งกลางแนวตั้ง */
    justify-content: center; /* 🔹 จัดข้อความให้อยู่กึ่งกลางแนวนอน */
}


/* ปรับขนาดและสีของปุ่ม Save */
.save-btn {
    background-color: #42BD41 !important;
    color: white !important;
    font-size: 1rem;
    padding: 8px 24px;
    border-radius: 6px;
}

/* ปรับขนาดและสีของปุ่ม Cancel */
.cancel-btn {
    background-color: #F05858 !important;
    color: white !important;
    font-size: 1rem;
    padding: 8px 17px;
    border-radius: 6px;
}

/* เพิ่ม hover effect */
.save-btn:hover {
    background-color: #36A536 !important;
}

.cancel-btn:hover {
    background-color: #D94949 !important;
}

/* เพิ่มขอบให้กับ card */
.card {
    width: 100%;
    border: 2px solid #e7e7e7;
    border-radius: 10px;
    /* ทำให้ขอบโค้งเล็กน้อย */
    background: white;
}
</style>
<style>
/* ปรับระยะห่างของ Search Box */
.dataTables_filter {
    margin-bottom: 15px !important;
}

/* ปรับระยะห่างของ Length Menu */
.dataTables_length {
    margin-bottom: 15px !important;
}

/* จัดให้คอลัมน์ในตารางอยู่ตรงกลาง */
#stockTable th,
#stockTable td {
    text-align: center;
    vertical-align: middle;
}

/* ปรับขนาด Search Box */
.dataTables_filter input {
    width: 170px;
    font-size: 14px;
    padding: 4px;
}

/* ปรับขนาด Pagination */
.dataTables_paginate {
    font-size: 13px;
    margin-top: 10px;
}

.dataTables_paginate .paginate_button {
    padding: 5px 8px;
    font-size: 12px;
    border-radius: 5px;
}

.dataTables_paginate .paginate_button:hover {
    background-color: #007bff !important;
    color: white !important;
}

/* ปรับสีและสไตล์ของหัวตาราง */
#stockTable thead {
    background: #007bff;
    color: white;
}

#stockTable thead th {
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
}

/* ทำให้ DataTable มีมุมโค้ง */
.dataTables_wrapper {
    border: 2px solid #e7e7e7;
    border-radius: 8px;
    padding: 10px;
}
</style>