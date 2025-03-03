<?php include('header.php'); ?>
<?php include('navbar.php');?>
<?php include('sidebar.php'); ?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Manage Balance</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
          <li class="breadcrumb-item">Stock</li>
          <li class="breadcrumb-item active"><a href="manage_balance.php">Manage Balance</a></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <!-- Section for Stock Management -->
    <div class="container1">
        <div class="rounded p-3 bg-white shadow-sm" style="border: 2px solid rgb(109, 109, 109) !important;">
            <div class="d-flex align-items-center">
                <!-- ปุ่ม Add Product -->
                <button class="btn btn-warning text-black me-2" id="showpendingproduct">
                    <i class="bi bi-hourglass-split text-black"></i> Pending
                </button>

                <button class="btn btn-success" id="showsuccessBtn">
                    <i class="bi bi-check-circle-fill text-white"></i> Success
                </button>
                
                <button class="btn btn-primary me-2" id="showaddproduct">
                    <i class="bi bi-plus-circle text-white"></i> Add Balance
                </button>
            </div>
        </div>
    </div>
    <br>

    <!--show pending Table -->
    <div class="container1" id="showpendingTable" style="display: none;">
        <h4 class="mb-3"><strong>Pending</strong></h4>
        <div class="table-responsive">
            <table id="pendingTable" class="table table-striped table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>Document ID</th>
                        <th>Column Name</th>
                        <th>Total Quantities</th>
                        <th>Balance</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody> <!-- ข้อมูลจะถูกโหลดผ่าน AJAX -->
            </table>
        </div>
    </div>

    <!--show success Table -->
    <div class="container1" id="showsuccessTable" style="display: none;">
        <h4 class="mb-3"><strong>Success</strong></h4>
        <div class="table-responsive">
            <table id="successTable" class="table table-striped table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>Document ID</th>
                        <th>DO_NO</th>
                        <th>Balance</th>
                        <th>Total Quantities</th>
                        <th>Column Name</th>  
                        <th>Date</th>
                        <th>Status</th>
                       
                    </tr>
                </thead>
                <tbody></tbody> <!-- ข้อมูลจะถูกโหลดผ่าน AJAX -->
            </table>
        </div>
    </div>

     <!-- Edit Stock Container (ซ่อนไว้ก่อน) -->
     <div class="container1" id="editStockContainer" style="display: none;">
        <h4 class="mb-3"><strong>Add Balance</strong></h4>
        <div class="rounded p-3 bg-white shadow-sm" style="border: 2px solid rgb(109, 109, 109) !important;">
            <div class="row align-items-end">
                <!-- Column Name Select -->
                <div class="col-md-2 ">
                    <label  class="mb-2"><strong>Select status Balance</strong></label>
                    <select class="form-control" id="select-status">
                        <option value="">-----choose-----</option>
                        <option value="New Branch">New Branch</option>
                        <option value="Replace">Replace</option>
                        <option value="Additionnal">Additionnal</option>
                        <option value="New DVR">New DVR</option>
                    </select>
                </div>
                
                <!-- Submit Button -->
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary custom-btn-size" id="showStockFields" disabled>Submit</button>
                </div>
            
               
            </div>
            <!-- ✅ ซ่อนฟิลด์แก้ไขข้อมูลไว้ก่อน -->
            <div id="stockFieldsContainer" style="display: none; margin-top: 50px;">
                <div class="container2">
                <div class="row align-items-end" style="margin-top: 10px; margin-left: 48px;">
                    <div class="col-auto">
                        <label><strong>DO-No.</strong></label>
                        <input type="text" class="form-control" name="addDO-No" id="addDO-No">
                    </div>
                    <div class="col-auto">
                        <label><strong>DOC-No.</strong></label>
                        <input type="text" class="form-control" name="addDOC-No" id="addDOC-No">
                    </div>
                    <div class="col-auto custom4-col">
                        <label><strong>Request Date</strong></label>
                        <input type="date" class="form-control" name="addRequestDate" id="addRequestDate">
                    </div>
                    <div class="col-auto">
                        <label><strong>INV-No.</strong></label>
                        <input type="text" class="form-control" name="addINV-No" id="addINV-No">
                    </div>
                    <div class="col-auto">
                        <label><strong>Store</strong></label>
                        <input type="text" class="form-control" name="addStore" id="addStore">
                    </div>
                    <div class="col-auto">
                        <label><strong>Outlets</strong></label>
                        <input type="text" class="form-control" name="addOutlets" id="addOutlets">
                    </div>
                </div>
                </div>
                <br>
                <!-- 🔹 แสดงค่าที่เลือกไว้ด้านบน -->
                <div class="row align-items-end stock-row">
                    <div class="col-md-1 custom1-col position-relative">
                        <label><strong>Part Name</strong></label>
                        <input type="text" class="form-control part-name" name="addpart_name" autocomplete="off">
                        <ul class="dropdown-list list-unstyled"></ul>
                    </div>
                    <div class="col-md-1 custom-col">
                        <label><strong>Barcode</strong></label>
                        <input type="text" class="form-control barcode" name="addbarcode" readonly>
                    </div>
                    <div class="col-md-1 custom-col">
                        <label><strong>Quantities</strong></label>
                        <input type="number" class="form-control" name="addquantities">
                    </div>  
                    <div class="col-md-1 custom1-col">
                        <label><strong>S/N#1</strong></label>
                        <input type="text" class="form-control" name="addS1">
                    </div>
                    <div class="col-md-1 custom1-col">
                        <label><strong>S/N#2</strong></label>
                        <input type="text" class="form-control" name="addS2">
                    </div>
                    <div class="col-md-1 custom1-col">
                        <label><strong>S/N#3</strong></label>
                        <input type="text" class="form-control" name="addS3">
                    </div>
                    <div class="col-md-1 custom3-col">
                        <label><strong>cost</strong></label>
                        <input type="number" class="form-control" name="addcost">
                    </div>
                    <div class="col-md-1 custom2-col">
                        <label><strong>Remark</strong></label>
                        <input type="text" class="form-control" name="addremark">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button class="btn btn-danger remove-row">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
                

                <div class="d-flex justify-content-between mt-3">
                    <button class="btn btn-success custom-btn-size1" id="addStockRow"><i
                            class="fas fa-plus"></i></button>
                    <div>
                        <button class="btn btn-success btn-saveaddbalance"><i class="fas fa-save"></i> Save</button>
                    </div>
                </div>
            </div> <!-- ✅ สิ้นสุดส่วนที่ถูกซ่อน -->
        </div>
    </div>


  </main><!-- End #main -->

<!-- Include DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    let pendingTable, successTable;

    // ✅ กดปุ่ม "Pending"
    $("#showpendingproduct").click(function() {
        $("#showpendingTable").show();  // แสดงตาราง Pending
        $("#showsuccessTable").hide();  // ซ่อนตาราง Success
        $("#editStockContainer").hide();  // ซ่อนฟอร์มแก้ไขสต็อก
        $("#stockFieldsContainer").hide();
        $("#select-status").val("");
        $("#showStockFields").prop("disabled", true);
        if (!pendingTable) {
            pendingTable = $("#pendingTable").DataTable({
                "destroy": true, // ลบ DataTable เก่าก่อนโหลดใหม่
                "columnDefs": [{ "targets": "_all", "className": "text-center" }],
                "ajax": {
                    "url": "includes/fetch_pending.php",
                    "type": "GET",
                    "dataSrc": ""
                },
                "columns": [
                    { 
                        "data": "document_number",
                        "render": function(data) {
                            return `<a href="manage_pending.php?document_number=${encodeURIComponent(data)}" class="text-primary fw-bold">${data}</a>`;
                        }
                    },
                    { "data": "column_names" },
                    { "data": "total_quantities" },
                    { "data": "status_balance" },
                    { 
                        "data": "date",
                        "render": function(data, type, row) {
                            return (row.status_balance === "New Branch" || row.status_balance === "Replace" || row.status_balance === "Additionnal" || row.status_balance === "New DVR") 
                                ? (data ? data : "-") 
                                : (row.created_at ? row.created_at : "-");
                        }
                    },
                    { 
                        "data": "status",
                        "render": function(data) {
                            return data === "pending" ? '<span class="badge bg-warning text-dark">Pending</span>' :
                                                        '<span class="badge bg-success text-white">Success</span>';
                        }
                    },
                    {
                        "data": "document_number",
                        "render": function(data) {
                            return `<button class="btn btn-danger delete-btn" data-document="${data}">Delete</button>`;
                        }
                    }
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
        } else {
            pendingTable.ajax.reload();  // รีโหลดข้อมูลใหม่
        }
    });

    // ✅ กดปุ่ม "Success"
    // ✅ กดปุ่ม "Success"
    $("#showsuccessBtn").click(function() {
        $("#showsuccessTable").show();  // แสดงตาราง Success
        $("#showpendingTable").hide();  // ซ่อนตาราง Pending
        $("#editStockContainer").hide();  // ซ่อนฟอร์มแก้ไขสต็อก
        $("#stockFieldsContainer").hide();
        $("#select-status").val("");
        $("#showStockFields").prop("disabled", true);
        if (!successTable) {
            successTable = $("#successTable").DataTable({
                "destroy": true, // ลบ DataTable เก่าก่อนโหลดใหม่
                "columnDefs": [{ "targets": "_all", "className": "text-center" }],
                "ajax": {
                    "url": "includes/fetch_success.php",
                    "type": "GET",
                    "dataSrc": ""
                },
                "columns": [
                { 
                    "data": "document_number",
                    "render": function(data, type, row) {
                        console.log("🔍 Status Balance (Raw):", row.status_balance); // ✅ ตรวจสอบค่าที่ได้รับจาก JSON
                        
                        let balance = row.status_balance ? row.status_balance.trim().toLowerCase() : "";
                        console.log("🔍 Cleaned Balance:", `"${balance}"`); // ✅ ดูค่าหลังจาก trim().toLowerCase()

                        let redirectToBalance = ["new branch", "replace", "additionnal", "new dvr"];

                        if (redirectToBalance.includes(balance)) {
                            console.log("✅ Redirecting to: manage_success_balance.php");
                            return `<a href="manage_success_balance.php?document_number=${encodeURIComponent(data)}" class="text-primary fw-bold">${data}</a>`;
                        } else {
                            console.log("❌ Redirecting to: manage_success.php");
                            return `<a href="manage_success.php?document_number=${encodeURIComponent(data)}" class="text-primary fw-bold">${data}</a>`;
                        }
                    }
                },
                { "data": "do_no" },
                { "data": "status_balance" },
                { "data": "total_quantities" },
                { "data": "column_name" },
                { 
                    "data": "date",
                    "render": function(data, type, row) {
                        return (row.column_name === "Process Adjust" || 
                                row.column_name === "Waiting to Receive" || 
                                row.status_balance === "New Branch" || 
                                row.status_balance === "Replace" || 
                                row.status_balance === "Additionnal" || 
                                row.status_balance === "New DVR") 
                            ? (data ? data : "-") 
                            : (row.created_at ? row.created_at : "-");
                    }
                },
                { 
                    "data": "status",
                    "render": function(data) {
                        return data === "pending" ? '<span class="badge bg-warning text-dark">Pending</span>' :
                                                    '<span class="badge bg-success text-white">Success</span>';
                    }
                },
                
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
        } else {
            successTable.ajax.reload();  // รีโหลดข้อมูลใหม่
        }
    });


    // ✅ ตรวจจับปุ่มลบข้อมูล
    $(document).on("click", ".delete-btn1", function() {
        var documentId = $(this).data("document");

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
                $.ajax({
                    url: "process/delete_success.php",
                    type: "POST",
                    data: { document_id: documentId },
                    success: function(response) {
                        if (response.status === "success") {
                            Swal.fire({
                                title: "Deleted!",
                                text: response.message,
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });

                            $("#successTable").DataTable().ajax.reload();
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: response.message,
                                icon: "error",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }   
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

    // ✅ ตรวจจับปุ่มลบข้อมูล
    $(document).on("click", ".delete-btn", function() {
        var documentId = $(this).data("document");

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
                $.ajax({
                    url: "process/delete_pending.php",
                    type: "POST",
                    data: { document_id: documentId },
                    success: function(response) {
                        if (response.status === "success") {
                            Swal.fire({
                                title: "Deleted!",
                                text: response.message,
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });

                            $("#pendingTable").DataTable().ajax.reload();
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: response.message,
                                icon: "error",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }   
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
    // ตรวจจับเมื่อกดย่อหรือขยาย Sidebar
    $(document).on('click', '.toggle-sidebar-btn', function() {
        setTimeout(function() {
            if ($.fn.DataTable.isDataTable('#pendingTable')) {
                var table = $('#pendingTable').DataTable();
                if (table) {
                    table.columns.adjust().draw(); // ปรับขนาดคอลัมน์
                }
            }
        }, 300); // ให้เวลาหน้าเว็บปรับ Layout ก่อน
    });

    // ตรวจจับการเปลี่ยนขนาดหน้าจอ
    $(window).on('resize', function() {
        if ($.fn.DataTable.isDataTable('#pendingTable')) {
            var table = $('#pendingTable').DataTable();
            if (table) {
                table.columns.adjust().draw();
            }
        }
    });

    // ตรวจจับเมื่อกดย่อหรือขยาย Sidebar
    $(document).on('click', '.toggle-sidebar-btn', function() {
        setTimeout(function() {
            if ($.fn.DataTable.isDataTable('#successTable')) {
                var table = $('#successTable').DataTable();
                if (table) {
                    table.columns.adjust().draw(); // ปรับขนาดคอลัมน์
                }
            }
        }, 300); // ให้เวลาหน้าเว็บปรับ Layout ก่อน
    });

    // ตรวจจับการเปลี่ยนขนาดหน้าจอ
    $(window).on('resize', function() {
        if ($.fn.DataTable.isDataTable('#successTable')) {
            var table = $('#successTable').DataTable();
            if (table) {
                table.columns.adjust().draw();
            }
        }
    });

    // ซ่อน DataTable และ Edit Form ไว้ก่อน
    $("#editStockContainer").hide();
    $("#stockFieldsContainer").hide();

    // ✅ ปิดปุ่ม Submit ไว้ก่อน
    $("#showStockFields").prop("disabled", true);

    // เมื่อเลือกค่าใน Select ให้แสดงผลด้านบน
    $("#select-status").change(function () {
        let selectedValue = $(this).val();
        if (selectedValue) {
            $("#selectedColumnName").text(selectedValue);
            $("#selectedColumnContainer").fadeIn();
        } else {
            $("#selectedColumnContainer").fadeOut();
        }
    });

     // เมื่อกดปุ่ม Cancel ให้ซ่อนค่า Column Name และเคลียร์ค่าฟอร์ม
     $(".btn-cancel").click(function () {
        $("#selectedColumnContainer").fadeOut();
        $("#selectedColumnName").text("");
        $("#select-status").val(""); // รีเซ็ต Select
    });

    // ✅ ตรวจจับการเปลี่ยนค่าใน <select> เพื่อเปิดใช้งานปุ่ม Submit
    $("#select-status").on("change", function() {
        if ($(this).val() !== "") {
            $("#showStockFields").prop("disabled", false); // ✅ เปิดปุ่ม
        } else {
            $("#showStockFields").prop("disabled", true); // ❌ ปิดปุ่ม
        }
    });

    // ✅ เมื่อกดปุ่ม "Edit Stock"
    $("#showaddproduct").click(function() {
        $("#editStockContainer").show();
        $("#showsuccessTable").hide();  // แสดงตาราง Success
        $("#showpendingTable").hide();  // ซ่อนตาราง Pending
    });

    // ✅ เมื่อกดปุ่ม "Submit" ให้แสดงฟอร์มแก้ไขสต็อก
    $("#showStockFields").click(function() {
        $("#stockFieldsContainer").slideToggle();
    });

    // ✅ เมื่อกดปุ่ม "+" เพื่อเพิ่มแถวใหม่ **ก่อนปุ่ม Add Row**
        $("#addStockRow").click(function() {
            var newRow = `
                <div class="row align-items-end stock-row">
                        <div class="col-md-1 custom1-col position-relative">
                            <label><strong>Part Name</strong></label>
                            <input type="text" class="form-control part-name" name="addpart_name" autocomplete="off">
                            <ul class="dropdown-list list-unstyled"></ul>
                        </div>
                        <div class="col-md-1 custom-col">
                            <label><strong>Barcode</strong></label>
                            <input type="text" class="form-control barcode" name="addbarcode" readonly>
                        </div>
                        <div class="col-md-1 custom-col">
                            <label><strong>Quantities</strong></label>
                            <input type="number" class="form-control" name="addquantities">
                        </div>  
                        <div class="col-md-1 custom1-col">
                            <label><strong>S/N#1</strong></label>
                            <input type="text" class="form-control" name="addS1">
                        </div>
                        <div class="col-md-1 custom1-col">
                            <label><strong>S/N#2</strong></label>
                            <input type="text" class="form-control" name="addS2">
                        </div>
                        <div class="col-md-1 custom1-col">
                            <label><strong>S/N#3</strong></label>
                            <input type="text" class="form-control" name="addS3">
                        </div>
                        <div class="col-md-1 custom3-col">
                            <label><strong>cost</strong></label>
                            <input type="number" class="form-control" name="addcost">
                        </div>
                        <div class="col-md-1 custom2-col">
                            <label><strong>Remark</strong></label>
                            <input type="text" class="form-control" name="addremark">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button class="btn btn-danger remove-row">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>`;

            // ✅ เพิ่มแถวใหม่ก่อนปุ่ม Add Row
            $("#addStockRow").closest(".d-flex").before(newRow);
        });

    // ✅ เมื่อกดปุ่ม "ลบ" เพื่อลบบรรทัดที่เพิ่มมา
    $(document).on("click", ".remove-row", function() {
        $(this).closest(".stock-row").remove();
    });

    // ✅ กดปุ่ม "Save" เพื่อบันทึกข้อมูลไปยัง Pending
   
        // ✅ เมื่อกดปุ่ม "Save" เพื่อบันทึกข้อมูลไปยัง Pending
        $(".btn-saveaddbalance").click(function () {
    let allFilled = true;
    let formData = [];
    let barcodeSet = new Set(); // ✅ ใช้ตรวจสอบ barcode ซ้ำกัน
    let rowCount = $(".stock-row").length; // ✅ นับจำนวนแถว

    // ❌ ถ้าไม่มีแถวเลย ให้หยุดทำงานและแจ้งเตือน
    if (rowCount === 0) {
        console.warn("⚠️ No data in the form!");
        Swal.fire({
            title: "Warning!",
            text: "Unable to save because there is no data.",
            icon: "warning",
            timer: 2500,
            showConfirmButton: false
        });
        return;
    }

    $(".stock-row").each(function (index) {
        let part_name = $(this).find('input[name="addpart_name"]').val()?.trim() || "";
        let barcode = $(this).find('input[name="addbarcode"]').val()?.trim() || "";
        let quantities = $(this).find('input[name="addquantities"]').val()?.trim() || "";
        let remark = $(this).find('input[name="addremark"]').val()?.trim() || "";
        let addS1 = $(this).find('input[name="addS1"]').val()?.trim() || "";
        let addS2 = $(this).find('input[name="addS2"]').val()?.trim() || "";
        let addS3 = $(this).find('input[name="addS3"]').val()?.trim() || "";
        let cost = $(this).find('input[name="addcost"]').val()?.trim() || "0";


        let column_name = $("#select-status").val()?.trim() || "";
        let do_no = $("#addDO-No").val()?.trim() || "";
        let doc_no = $("#addDOC-No").val()?.trim() || "";
        let rq_date = $("#addRequestDate").val()?.trim() || "";
        let inv_no = $("#addINV-No").val()?.trim() || "";
        let store = $("#addStore").val()?.trim() || "";
        let outlets = $("#addOutlets").val()?.trim() || "";
        

        console.log(`🔹 Row ${index + 1}:`, { part_name, barcode, quantities, rq_date, column_name });

        // ❌ เช็กค่าหลัก ถ้าว่างให้แจ้งเตือน
        if (!part_name || !barcode || !quantities || !rq_date || column_name === "") {
            console.error(`❌ Row ${index + 1} has incomplete data!`, {
                part_name, barcode, quantities, rq_date, column_name
            });

            Swal.fire({
                title: "Warning!",
                text: `Please fill in all the fields or the barcode is duplicate.`,
                icon: "warning",
                timer: 2500,
                showConfirmButton: false
            });
            allFilled = false;
            return false;
        }

        // ❌ ตรวจสอบว่า barcode ซ้ำในเอกสารเดียวกันหรือไม่
        if (barcodeSet.has(barcode)) {
            console.warn(`⚠️ Duplicate barcode at row ${index + 1}: ${barcode}`);
            Swal.fire({
                title: "Warning!",
                text: `Barcode '${barcode}' has already been used (Row ${index + 1})`,
                icon: "warning",
                timer: 2000,
                showConfirmButton: false
            });
            allFilled = false;
            return false;
        }

        barcodeSet.add(barcode);

        formData.push({
            part_name: part_name,
            barcode: barcode,
            quantities: quantities,
            remark: remark,
            column_name: column_name,
            do_no: do_no,
            doc_no: doc_no,
            rq_date: rq_date,
            inv_no: inv_no,
            store: store,
            outlets: outlets,
            addS1: addS1,
            addS2: addS2,
            addS3: addS3,
            cost: cost
        });
    });

    // ❌ ถ้ามีช่องว่าง ให้หยุดทำงาน
    if (!allFilled) {
        console.error("🚨 Stopped working due to incomplete data");
        return;
    }

    console.log("✅ JSON Data to be sent:", JSON.stringify(formData));

    Swal.fire({
        title: "Confirm save?",
        text: "Are you sure you want to save this data?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Save it!",
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "process/save_successForbalance.php",
                type: "POST",
                data: { stockData: JSON.stringify(formData) },
                success: function(response) {
                    try {
                        let res = typeof response === "string" ? JSON.parse(response) : response;
                        console.log("🔹 Response from Server:", res);

                        if (res.status === "success") {
                            Swal.fire({
                                title: "Save successful!",
                                text: "Data has been saved to Pending. Document number: " + res.document_number,
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });

                            $(".stock-row").remove();
                            $("#select-status").val("");
                            $("#addDO-No").val("");
                            $("#addDOC-No").val("");
                            $("#addRequestDate").val("");
                            $("#addINV-No").val("");
                            $("#addStore").val("");
                            $("#addOutlets").val("");
                            $("#selectedColumnName").text("");
                            
                        } else {
                            console.error("❌ Error from Server:", res.message);
                            Swal.fire({ title: "An error occurred!", text: res.message, icon: "error" });
                        }
                    } catch (error) {
                        console.error("❌ JSON Parse Error:", error);
                        Swal.fire({ title: "An error occurred!", text: "Invalid JSON. Please check the response.", icon: "error" });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("❌ AJAX Error:", status, error);
                    Swal.fire({ title: "An error occurred!", text: "Unable to save the data. Please try again.", icon: "error" });
                }
            });
        }
    });
});




    // ✅ เรียก `fetchDropdown()` เมื่อพิมพ์ในช่อง `Part_Name`
    $(document).on("input", ".part-name", function () {
        let input = $(this);
        let query = input.val().trim();
        let dropdown = input.siblings(".dropdown-list"); // หา dropdown ที่อยู่ในแถวเดียวกัน

        if (query.length >= 1) {
            fetchDropdown(query, dropdown, input);
        } else {
            dropdown.hide();
        }
    });

    // ✅ ฟังก์ชันดึงข้อมูลจากฐานข้อมูล
    function fetchDropdown(query, dropdown, input) {
        fetch("includes/fetch_part_name.php?query=" + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                dropdown.html(''); // ล้าง Dropdown ก่อนเติมข้อมูลใหม่

                if (data.success && data.data.length > 0) {
                    data.data.forEach(item => {
                        let li = $("<li>").text(item.part_name)
                            .css({ "padding": "10px", "cursor": "pointer", "border-bottom": "1px solid #ddd" })
                            .hover(
                                function () { $(this).css("background-color", "#f8f9fa"); },
                                function () { $(this).css("background-color", "#fff"); }
                            )
                            .click(function () {
                                input.val(item.part_name);
                                input.closest(".stock-row").find(".barcode").val(item.barcode);
                                dropdown.hide();
                            });
                        dropdown.append(li);
                    });
                    dropdown.show();
                } else {
                    dropdown.append('<li class="text-muted text-center">No results found</li>').show();
                }
            })
            .catch(error => {
                console.error('Error fetching part names:', error);
            });
    }

    // ✅ ปิด Dropdown เมื่อคลิกข้างนอก
    $(document).click(function (e) {
        if (!$(e.target).closest(".part-name, .dropdown-list").length) {
            $(".dropdown-list").hide();
        }
    });

});


</script>


<style>
/* กำหนดความกว้างให้กับ container2 */


#productTable td:nth-child(3) { 
    word-wrap: break-word;
    white-space: normal;
    max-width: 200px; /* ปรับความกว้างของคอลัมน์ part_name */
}
#productTable td:nth-child(12) { 
    word-wrap: break-word;
    white-space: normal;
    max-width: 200px; /* ปรับความกว้างของคอลัมน์ part_name */
}

/* จัดข้อความในตารางให้อยู่กึ่งกลาง */
#productTable th,
#productTable td {
    text-align: center; /* จัดให้อยู่กึ่งกลางแนวนอน */
    vertical-align: middle; /* จัดให้อยู่กึ่งกลางแนวตั้ง */
}

#productTable thead th {
    text-align: center; /* จัดหัวข้อคอลัมน์ให้อยู่กลาง */
}

#productTable tbody td {
    text-align: center; /* จัดข้อมูลในแถวให้อยู่กลาง */
    vertical-align: middle; /* จัดให้อยู่กึ่งกลางแนวตั้ง */
}


/* Add smooth transitions to table header cells */
#productTable thead th {
    transition: width 0.3s ease-in-out;
    white-space: nowrap;
}

/* Ensure the header container also has smooth transitions */
.dataTables_scrollHead {
    transition: width 0.3s ease-in-out;
}

/* Optional: Add transitions to the table body cells for consistency */
#productTable tbody td {
    transition: width 0.3s ease-in-out;
    white-space: nowrap;
}
/* ปรับขนาดตัวอักษรในข้อมูลของตาราง */
#productTable tbody td {
    font-size: 15px; /* ปรับขนาดตัวอักษรให้เล็กลง */
    padding: 5px; /* ปรับระยะห่างของเซลล์ */
}

  /* จัดระยะห่างของส่วนควบคุม DataTables */
.dataTables_wrapper {
    padding: 10px 15px; /* เพิ่ม padding รอบๆ */
}

/* ปรับช่องค้นหา (Search Box) */
.dataTables_filter {
    margin-bottom: 15px; /* เพิ่มระยะห่างด้านล่าง */
}

/* ปรับระยะห่างของ Pagination */
.dataTables_paginate {
    margin-top: 15px !important;
}

/* ปรับตำแหน่งของ "แสดง x รายการต่อหน้า" */
.dataTables_length {
    margin-bottom: 15px;
}

/* ปรับระยะห่างของ Info */
.dataTables_info {
    margin-top: 15px;
}

/* Custom Light Scrollbar */
.dataTables_wrapper ::-webkit-scrollbar {
    width: 8px; /* กำหนดความกว้างของ Scrollbar */
    height: 8px;
}

.dataTables_wrapper ::-webkit-scrollbar-track {
    background: #f8f9fa; /* สีพื้นหลังของ Track เป็นสีเทาอ่อน */
    border-radius: 10px;
}

.dataTables_wrapper ::-webkit-scrollbar-thumb {
    background: #d6d6d6; /* สีของ Scrollbar เป็นสีเทาอ่อน */
    border-radius: 10px;
    border: 2px solid #f8f9fa; /* ทำให้มีช่องว่างรอบๆ */
}

.dataTables_wrapper ::-webkit-scrollbar-thumb:hover {
    background: #bfbfbf; /* สีของ Scrollbar เมื่อ Hover */
}

/* ปรับ Scrollbar บน Firefox */
.dataTables_wrapper {
    scrollbar-color: #d6d6d6 #f8f9fa; /* thumb และ track */
    scrollbar-width: thin;
}


/* ลดขนาดของ Search Box */
.dataTables_filter input {
    width: 170px; /* ลดความกว้าง */
    font-size: 14px; /* ลดขนาดตัวอักษร */
    padding: 4px;
}

/* ลดขนาดของ Length Menu */
.dataTables_length select {
    width: 70px; /* ลดความกว้าง */
    font-size: 14px;
    padding: 2px;
}

/* ลดขนาดของ Info */
.dataTables_info {
    font-size: 13px;
    margin-top: 10px;
}

/* ลดขนาดของ Pagination */
.dataTables_paginate {
    font-size: 13px;
    margin-top: 10px;
}

.dataTables_paginate .paginate_button {
    padding: 5px 8px;
    font-size: 12px;
    border-radius: 5px; /* ทำปุ่มโค้ง */
}

/* ปรับขนาดปุ่ม Pagination เมื่อ Hover */
.dataTables_paginate .paginate_button:hover {
    background-color: #007bff !important;
    color: white !important;
}

/* ทำให้หัวตาราง (thead) มีมุมโค้ง */
#productTable thead {
    background: #007bff; /* สีพื้นหลังของ thead */
    color: white; /* สีตัวอักษร */
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

/* ทำให้มุมโค้งแสดงผลได้ดีขึ้น */
#productTable thead th:first-child {
    border-top-left-radius: 12px;
}

#productTable thead th:last-child {
    border-top-right-radius: 12px;
}



/* ใส่เส้นขอบภายในตาราง */
#productTable th,
#productTable td {
    border: 1px solid #ddd; /* สีเทาอ่อน */
}

/* ปรับหัวตาราง (thead) ให้ดูโดดเด่น */
#productTable thead {
    background:rgb(6, 12, 17);
    color: white;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
}

/* ทำให้ขอบมุมแสดงผลได้ดีขึ้น */
.dataTables_wrapper {
    border: 2px solid #e7e7e7;
    border-radius: 8px;
    padding: 10px;
}


.btn-primary{
    margin-left: 8px;   
}

/* ปรับ dropdown ให้มีขนาดเท่ากับช่อง Part Name */
.dropdown-list {
    width: 94%; /* ✅ ปรับให้กว้างเท่าช่อง input */
    position: absolute; /* ✅ ทำให้ dropdown ไม่ดันเนื้อหาอื่น */
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1050; /* ✅ ปรับ z-index ให้สูงขึ้นเพื่อแสดงผลถูกต้อง */
    top: 100%; /* ✅ ให้ dropdown อยู่ถัดลงมาจาก input */
    display: none; /* ✅ ซ่อนไว้ก่อนจนกว่าจะพิมพ์ */
}

/* ✅ ปรับรายการ dropdown */
.dropdown-list li {
    padding: 10px;
    cursor: pointer;
    border-bottom: 1px solid #ddd;
    list-style-type: none;
}

/* ✅ เปลี่ยนสีเมื่อ hover */
.dropdown-list li:hover {
    background-color: #f8f9fa;
}

/* ✅ ให้ .stock-row มี position: relative เพื่ออ้างอิงตำแหน่ง dropdown */
.stock-row {
    position: relative;
}

.custom-col{
    width: 7%;
}
.custom1-col{
    width: 13%;
}
.custom2-col{
    width: 11%;
}
.custom3-col{
    width: 8%;
}
.custom4-col{
    width: 11%;
}

.container2 {
    width: 82%;
    padding: 10px;
    margin-left: 100px;
    border-radius: 10px;
    margin-bottom: 20px;
    
}
</style>


  <?php include('footer.php'); ?>

