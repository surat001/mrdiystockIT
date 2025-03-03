<?php include('header.php'); ?>
<?php include('navbar.php');?>
<?php include('sidebar.php'); ?>


<main id="main" class="main">
    <div class="pagetitle">
        <h1>All Product</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item active"><a href="all_product.php">All Product</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="table-responsive">
        <table id="productTable" class="table table-striped table-bordered table-hover">
            <thead class="table-primary">
                <tr>
                    <th>Barcode</th>
                    <th>Picture</th>
                    <th>Part Name</th>
                    <th>Qube <br>System</th>
                    <th>Waiting <br>to Receive</th>
                    <th>Quantities<br>(On Hand)</th>
                    <th>Process <br>Adjust</th>
                    <th>Claim <br>warranty	</th>
                    <th>Borrow</th>
                    <th>Damage</th>
                    <th>Lost <br>items</th>
                    <th>Remark</th>
                    <th>Min-Max <br>(calculated)</th>
                    <th>Min</th>
                    <th>Max</th>
                    <th>Next <br>Orders</th>
                    <th>Reserved</th>
                    <th>Used</th>
                </tr>
            </thead>
            <tbody>
                <!-- ใช้ AJAX ดึงข้อมูล -->
            </tbody>
        </table>
    </div>
</main><!-- End #main -->




<?php include('footer.php'); ?>

<!-- Include DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
      $('#productTable').DataTable({
    "columnDefs": [
        { "targets": "_all", "className": "text-center" } // จัดทุกคอลัมน์ตรงกลาง
    ],
    "ajax": {
        "url": "includes/fetch_products.php",
        "type": "GET",
        "dataSrc": function (json) {
            if (json.error) {
                console.error("AJAX Error:", json.error);
                alert("Error: " + json.error); // แจ้งเตือน Error
                return [];
            }
            return json; // ส่งข้อมูลไปแสดงผลในตาราง
        }
    },
    "columns": [
        { "data": "barcode" },
        { 
            "data": "picture_url",
            "render": function(data, type, row) {
                return '<img src="' + data + '" style="width:50px; height:auto; max-width:100%;">';
            }
        },
        { "data": "part_name" },
        { "data": "qube_system" },
        { "data": "waiting_to_receive" },
        { "data": "quantities_on_hand" },
        { "data": "process_adjust" },
        { "data": "claim_warranty" },
        { "data": "borrow" },
        { "data": "damage" },
        { "data": "lost_items" },
        { "data": "remark" },
        { "data": "min_calculated" },
        { "data": "min_manual" },
        { "data": "max_manual" },
        { "data": "next_orders" },
        { "data": "reserved" },
        { "data": "used" }
    ],
        "scrollY": "480px",
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
        "lengthMenu": [[10, 25, 50, 100, 500, -1], ["10", "25", "50", "100", "500", "All"]],
        "pageLength": 25,
        "rowCallback": function(row, data) {
            // ตรวจสอบค่า status แล้วเปลี่ยนสีพื้นหลังของ barcode
            if (data.status === "high") {
                $("td:eq(5)", row).css("background-color", "#b2ffb2"); // สีเขียวอ่อน
            } else if (data.status === "low") {
                $("td:eq(5)", row).css("background-color", "#ffb2b2"); // สีแดงอ่อน
            }
        },
        "language": {
            "search": "🔍",
            "lengthMenu": "_MENU_ row/page",
            "info": "Showing _START_ to _END_ of _TOTAL_ row",
            "infoEmpty": "No data available",
            "paginate": {
                "first": "First",
                "last": "Last",
            }
        },
    });

     // ใส่ placeholder หลังจาก DataTables โหลดเสร็จ
     $('.dataTables_filter input').attr("placeholder", " Search...").addClass("search-placeholder");
    // Add event listener for the specific toggle button class
    $(document).on('click', '.toggle-sidebar-btn', function() {
      setTimeout(function() {
          if ($.fn.DataTable.isDataTable('#productTable')) {
              var table = $('#productTable').DataTable();
              table.columns.adjust();
              table.fixedHeader.adjust();
          }
      }, 300);
    });


    // Handle window resize
    $(window).on('resize', function() {
        table.columns.adjust();
        table.fixedHeader.adjust();
    });
    
});

</script>

<style>


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

</style>

