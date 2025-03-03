<?php
include('header.php');
include('navbar.php');
include('sidebar.php');
include('includes/db_connect.php');

$document_number = $_GET['document_number'] ?? ''; 
$column_name = '';     
$created_at = '';      
$total_quantities = 0; 
$status_balance = '';

if (!empty($document_number)) {
    $sql = "SELECT column_name, status_balance, created_at, SUM(quantities) as total_quantities 
            FROM success WHERE document_number = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $document_number);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $column_name = $row['column_name'];
        $created_at = $row['created_at'];
        $total_quantities = $row['total_quantities'];
        $status_balance = $row['status_balance'];
    }
}
?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Manage Success</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item">Stock</li>
        <li class="breadcrumb-item active"><a href="manage_balance.php">Manage Balance</a></li>
        <li class="breadcrumb-item active"><a href="manage_success.php">Success</a></li>
      </ol>
    </nav>
  </div>
  <br>

<div class="rounded p-3 bg-white shadow-sm" style="border: 2px solid rgb(109, 109, 109) !important;">

<h4 class="fw-bold mb-3"><strong>Document ID</strong> : 
    <span class="badge bg-dark text-white"><?= htmlspecialchars($document_number) ?></span>
</h4>
<h5 class="fw-bold mb-3"><strong>Date</strong> : 
    <span class="fw-bold text-dark"><?= htmlspecialchars($created_at) ?></span>
</h5>
    <div id="selectedColumnContainer" class="alert text-center p-2 rounded shadow-sm"
           style="background:rgb(238, 246, 253); color: #212529; font-size: 1rem; ">
          <h3 class="fw-bold mb-0">
              <?php 
                  if (strtolower($status_balance) == "adjustment" || strtolower($status_balance) == "receive") {
                      echo "Status : <strong class='text-primary'>" . htmlspecialchars($status_balance) . "</strong>";
                  } else {
                      echo "Column Name : <strong class='text-primary'>" . htmlspecialchars($column_name) . "</strong>";
                  }
              ?>
          </h3>
      </div>

         <!-- Total Quantities Section -->
         <div class="d-flex align-items-center justify-content-center mb-3 p-2 rounded shadow-sm"
           style="background: #e9f7ef; border-left: 5px solid #e9f7ef; border-radius: 8px; display: inline-block; min-width: 200px;">
          <h4 class="fw-bold m-0" style="color:rgb(31, 102, 48);">
              <i class="fa-solid fa-box-archive"></i> Total : <?= htmlspecialchars($total_quantities) ?>
          </h4>
      </div>


      <div class="container1">
        <h4 class="mb-3"></h4>
        <div class="table-responsive">
          <table id="successTable" class="table table-striped table-bordered table-hover">
            <thead class="table-primary">
              <tr>
                <th>Part Name</th>
                <th>Barcode</th>
                <th>Quantities</th>
                <th>Remark</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>

      <br>
      <a href="manage_balance.php" class="btn btn-secondary">Back</a>
    </div>
  </div>
  </div>
</main>

<?php include('footer.php'); ?>

<!-- Include DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    let successTable = $("#successTable").DataTable({
        "columnDefs": [{
                    "targets": "_all",
                    "className": "text-center"
                } // จัดทุกคอลัมน์ตรงกลาง
            ],
        "destroy": true,
        "scrollY": "370px",  // ✅ กำหนดความสูงของตาราง
        "scrollX": false,     // ✅ ปิด scrollX ป้องกันตารางเบี้ยว
        "scrollCollapse": true,
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true,    // ✅ ให้ DataTables คำนวณความกว้างคอลัมน์ใหม่
        "responsive": false,
        "fixedHeader": true,
        "lengthMenu": [[10, 25, 50, 100, 500, -1], ["10", "25", "50", "100", "500", "All"]],
        "pageLength": 10,
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
            },
        "ajax": {
            "url": "includes/fetch_success_by_document.php",
            "type": "GET",
            "data": { document_number: "<?= $document_number ?>" },
            "dataSrc": function(json) {
                if (json.error || json.length === 0) {
                    Swal.fire({
                        title: "No Data",
                        text: "No information found for this document",
                        icon: "warning",
                        timer: 2500,
                        showConfirmButton: false
                    });
                    return [];
                }
                return json;
            }
        },
        "columns": [
            { "data": "part_name" },
            { "data": "barcode" },
            { "data": "total_quantities" },
            { "data": "remark", "defaultContent": "-" },
            { "data": "date" }
        ]
    });
    // ปรับ placeholder ของช่องค้นหา
    $('.dataTables_filter input')
            .attr("placeholder", " Search...")
            .addClass("search-placeholder");
    // ✅ แก้ปัญหาหัวตารางไม่ตรงกับข้อมูล
    setTimeout(function() {
        successTable.columns.adjust().draw();
    }, 500);
});

</script>

<style>/* ทำให้ขอบมุมแสดงผลได้ดีขึ้น */
.dataTables_wrapper {
    border: 2px solid #e7e7e7;
    border-radius: 8px;
    padding: 10px;
}


/* ✅ ปรับตารางให้อยู่ในกล่องที่สามารถเลื่อนได้ */


/* ✅ ปรับ ScrollBar */
.table-responsive::-webkit-scrollbar {
    width: 8px;
}
.table-responsive::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 5px;
}
.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* ✅ ตั้งค่าคอลัมน์ให้เหมาะสม */
/* ✅ ปรับขนาดคอลัมน์อัตโนมัติ */
#successTable {
    width: 100% !important;
    table-layout: auto; /* ✅ เปลี่ยนจาก fixed เป็น auto */
}

/* ✅ แก้ให้หัวตารางตรงกับข้อมูล */
#successTable thead th {
    text-align: center;
    white-space: nowrap;
}

/* ✅ ปรับขนาดให้ตารางตรง */
#successTable tbody td {
    text-align: center;
    vertical-align: middle;
    white-space: nowrap;
}

/* ✅ ป้องกันตารางเบี้ยว */
.dataTables_scrollHeadInner, .dataTables_scrollBody {
    width: 100% !important;
}


/* ✅ ปรับขนาดของปุ่ม Pagination */
.dataTables_paginate .paginate_button {
    padding: 5px 10px;
    font-size: 12px;
    border-radius: 5px;
}

.dataTables_filter {
    float: right;
    margin-bottom: 10px;
}

.dataTables_filter input {
    width: 150px;
    padding: 5px;
}

.dataTables_paginate {
    margin-top: 10px;
}

</style>
