<?php
include('header.php');
include('navbar.php');
include('sidebar.php');
include('includes/db_connect.php');

$document_number = $_GET['document_number'] ?? ''; 
$column_name = '';     
$created_at = (!empty($data) && isset($data[0]['created_at'])) ? $data[0]['created_at'] : 'N/A';      
$total_quantities = 0; 
$status_balance = (!empty($data) && isset($data[0]['status_balance'])) ? $data[0]['status_balance'] : 'N/A';
$total_cost = 0;
$Do_no = '';
$Doc_no = '';
$Request_date = '';
$INV_no = '';
$Store = '';
$Outlets = '';
$Part_name = '';
$Quantities = '';
$Barcode = '';
$SN1 = '';
$SN2 = '';
$SN3 = '';
$Cost = '';

if (!empty($document_number)) {
    // ✅ ดึงข้อมูลทุกแถว
    $sql = "SELECT  do_no,
                    doc_no,
                    date,
                    inv_no,
                    store,
                    outlets,
                    part_name,
                    quantities,
                    barcode,
                    addS1 AS serial_number_1,
                    addS2 AS serial_number_2,
                    addS3 AS serial_number_3,
                    cost,
                    status_balance, 
                    created_at
            FROM success WHERE document_number = ?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL Error: " . $conn->error); // ✅ ตรวจสอบ SQL Error
    }

    $stmt->bind_param("s", $document_number);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = []; // ✅ เก็บข้อมูลทุกแถวใน array
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // ✅ ตรวจสอบค่า `$status_balance`
    if (!empty($data)) {
        $status_balance = isset($data[0]['status_balance']) ? $data[0]['status_balance'] : 'N/A';
    }

    // ✅ ดึงค่าผลรวมแยก
    $sql_total = "SELECT SUM(quantities) AS total_quantities, SUM(cost) AS total_cost 
                  FROM success WHERE document_number = ?";
    
    $stmt_total = $conn->prepare($sql_total);
    $stmt_total->bind_param("s", $document_number);
    $stmt_total->execute();
    $result_total = $stmt_total->get_result();
    
    $total_quantities = 0;
    $total_cost = 0;
    
    if ($row_total = $result_total->fetch_assoc()) {
        $total_quantities = $row_total['total_quantities'];
        $total_cost = $row_total['total_cost'];
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

  <div id="selectedColumnContainer" class="alert text-center p-2 rounded shadow-sm"
           style="background:rgb(238, 246, 253); color: #212529; font-size: 1rem; ">
          <h3 class="fw-bold mb-0">
                  Status : <strong class='text-primary'><?= htmlspecialchars($status_balance) ?></strong>
                  </h3>
    </div>

         <!-- Total Quantities Section -->
         <div class="d-flex align-items-center justify-content-center mb-3 p-2 rounded shadow-sm"
           style="background: #e9f7ef; border-left: 5px solid #e9f7ef; border-radius: 8px; display: inline-block; min-width: 200px;">
          <h4 class="fw-bold m-0" style="color:rgb(31, 102, 48);">
              <i class="fa-solid fa-box-archive"></i> Total : <?= htmlspecialchars($total_quantities) ?>
          </h4>
      </div>

       <!-- Total Cost Section -->
       <div class="d-flex align-items-center justify-content-center mb-3 p-2 rounded shadow-sm"
           style="background:rgb(247, 245, 233); border-left: 5px solid #e9f7ef; border-radius: 8px; display: inline-block; min-width: 200px;">
          <h4 class="fw-bold m-0" style="color:rgb(143, 117, 0);">
          <i class="fa-solid fa-money-check-dollar"></i> Total Cost : <?= htmlspecialchars($total_cost) ?>
          </h4>
      </div>


      <div class="container1">
        <h4 class="mb-3"></h4>
        <div class="table-responsive">
          <table id="successTable" class="table table-striped table-bordered table-hover">
            <thead class="table-primary">
              <tr>
                <th>Do-No</th>
                <th>Doc-No</th>
                <th>Request Date</th>
                <th>INV-No</th>
                <th>Store</th>
                <th>Outlets</th>
                <th>Part Name</th>
                <th>Quantities</th>
                <th>Barcode</th>
                <th>S/N#1</th>
                <th>S/N#2</th>
                <th>S/N#3</th>
                <th>Cost</th>
              </tr>
            </thead>
            <tbody>
        <?php foreach ($data as $row) : ?>
        <tr>
            <td><?= htmlspecialchars($row['do_no']) ?></td>
            <td><?= htmlspecialchars($row['doc_no']) ?></td>
            <td><?= htmlspecialchars($row['date']) ?></td>
            <td><?= htmlspecialchars($row['inv_no']) ?></td>
            <td><?= htmlspecialchars($row['store']) ?></td>
            <td><?= htmlspecialchars($row['outlets']) ?></td>
            <td><?= htmlspecialchars($row['part_name']) ?></td>
            <td><?= htmlspecialchars($row['quantities']) ?></td>
            <td><?= htmlspecialchars($row['barcode']) ?></td>
            <td><?= htmlspecialchars($row['serial_number_1']) ?></td>
            <td><?= htmlspecialchars($row['serial_number_2']) ?></td>
            <td><?= htmlspecialchars($row['serial_number_3']) ?></td>
            <td><?= htmlspecialchars($row['cost']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
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
    if ($("#successTable").length) {
        let successTable = $("#successTable").DataTable({
            "columnDefs": [{
                        "targets": "_all",
                        "className": "text-center"
                    }],
            "destroy": true,
            "scrollY": "370px",
            "scrollX": false,
            "scrollCollapse": true,
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "responsive": false,
            "fixedHeader": true,
            "lengthMenu": [[10, 25, 50, 100, 500, -1], ["10", "25", "50", "100", "500", "All"]],
            "pageLength": 10,
            "ajax": {
                "url": "includes/fetch_success_doc_balance.php",
                "type": "GET",
                "data": { document_number: "<?= $document_number ?>" },
                "dataSrc": function(json) {
                    if (!json || json.length === 0) {
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
                { "data": "do_no" },
                { "data": "doc_no" },
                { "data": "date" },
                { "data": "inv_no" },
                { "data": "store" },
                { "data": "outlets" },
                { "data": "part_name" },
                { "data": "quantities" },
                { "data": "barcode" },
                { "data": "addS1" },
                { "data": "addS2" },
                { "data": "addS3" },
                { "data": "cost" }
            ]
        });

        setTimeout(function() {
            successTable.columns.adjust().draw();
        }, 500);
    } else {
        console.error("Error: Table #successTable not found in DOM!");
    }
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
