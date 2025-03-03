<?php
include('header.php');
include('navbar.php');
include('sidebar.php');
include('includes/db_connect.php');

$document_number = $_GET['document_number'] ?? ''; // รับค่า document_number จาก URL
$column_name = ''; // ตัวแปรเก็บ column_name
$created_at = ''; // ตัวแปรเก็บวันที่สร้าง
$total_quantities = 0; // ตัวแปรเก็บจำนวนรวมของสินค้าทั้งหมด

if (!empty($document_number)) {
    $sql = "SELECT column_name, created_at, SUM(quantities) as total_quantities 
            FROM pending WHERE document_number = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $document_number);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $column_name = $row['column_name'];
        $created_at = $row['created_at'];
        $total_quantities = $row['total_quantities'];
    }
}
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Manage Pending</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item">Stock</li>
                <li class="breadcrumb-item active"><a href="manage_balance.php">Manage Balance</a></li>
                <li class="breadcrumb-item active"><a href="manage_pending.php">Pending</a></li>
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
            <!-- ✅ แสดงชื่อ Column Name -->
            <div id="selectedColumnContainer" class="alert text-center p-2 rounded shadow-sm"
           style="background:rgb(238, 246, 253); color: #212529; font-size: 1rem; ">
          <h3 class="fw-bold mb-0">
                  Column : <strong class='text-primary'><?= htmlspecialchars($column_name) ?></strong>
                  </h3>
    </div>
           <!-- Total Cost Section -->
           <div class="d-flex align-items-center justify-content-center mb-3 p-2 rounded shadow-sm"
           style="background: #e9f7ef; border-left: 5px solid #e9f7ef; border-radius: 8px; display: inline-block; min-width: 200px;">
          <h4 class="fw-bold m-0" style="color:rgb(31, 102, 48);">
              <i class="fa-solid fa-box-archive"></i> Total : <?= htmlspecialchars($total_quantities) ?>
          </h4>
      </div>

            <!-- ✅ แสดงช่องกรอกวันที่เพียงช่องเดียว ถ้าตรงกับเงื่อนไข -->
            <?php if ($column_name === "Waiting to Receive" || $column_name === "Process Adjust") : ?>
                <div class="row">
                    <div class="col-md-3">
                        <label><strong>Status</strong></label>
                            <input type="text" class="form-control" name="selected_date" style="max-width: 200px !important;" 
                                value="<?= ($column_name === 'Waiting to Receive') ? 'Receive' : (($column_name === 'Process Adjust') ? 'Adjustment' : '') ?>" 
                                readonly>
                    </div>
                <div class="col-md-3">
                    <label><strong> 
                        <?php echo ($column_name === "Waiting to Receive") ? "Inbound Date" : "Outbound Date"; ?>
                    </strong></label>
                        <input type="date" class="form-control" id="selected-date" name="selected_date" style="max-width: 200px !important;" >
                </div>
            </div>
            <?php endif; ?>

<br>
            <div id="pendingItems">
                <!-- Data will be inserted here via JavaScript -->
            </div>

            <div class="d-flex justify-content-between mt-3">
                <h1></h1>
                <div>
                    <button class="btn btn-danger btn-cancel"><i class="fas fa-times"></i> Cancel</button>
                    <button class="btn btn-success btn-savepending"><i class="fas fa-save"></i> Save</button>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let documentNumber = "<?= $document_number ?>";
    let columnName = "<?= $column_name ?>"; // ✅ ดึงค่า column_name มาตรวจสอบ

    if (documentNumber) {
        loadPendingData(documentNumber);
    }

    function loadPendingData(docNumber) {
        $.ajax({
            url: "includes/fetch_pending_by_document.php",
            type: "GET",
            data: { document_number: docNumber },
            dataType: "json",
            success: function(response) {
                let pendingContainer = $("#pendingItems");
                pendingContainer.empty();

                response.forEach(row => {
                    let itemHtml = `
                        <div class="row align-items-end stock-row">
                            <div class="col-md-3">
                                <label><strong>Part Name</strong></label>
                                <input type="text" class="form-control" name="part_name" value="${row.part_name}" readonly>
                            </div>
                            <div class="col-md-2">
                                <label><strong>Barcode</strong></label>
                                <input type="text" class="form-control" name="barcode" value="${row.barcode}" readonly>
                            </div>
                            <div class="col-md-2">
                                <label><strong>Quantities</strong></label>
                                <input type="number" class="form-control" name="quantities" value="${row.total_quantities}" data-original-quantities="${row.total_quantities}">
                            </div>
                            <div class="col-md-2">
                                <label><strong>Remark</strong></label>
                                <input type="text" class="form-control" name="remark" value="${row.remark}">
                            </div>
                        </div>`;
                    pendingContainer.append(itemHtml);
                });
            },
            error: function(xhr, status, error) {
                console.error("Error fetching pending data:", error);
            }
        });
    }

    $(".btn-cancel").click(function() {
        window.history.back();
    });

    $(".btn-savepending").click(function () {
        let formData = [];
        let documentNumber = "<?= $document_number ?>";
        let columnName = "<?= $column_name ?>";

        // ✅ เช็คว่ามี `#selected-date` ใน DOM หรือไม่
        let selectedDate = $("#selected-date").length ? $("#selected-date").val().trim() : "";

        // ✅ ถ้าเป็น Waiting to Receive หรือ Process Adjust → ต้องกรอกวันที่
        if ((columnName === "Waiting to Receive" || columnName === "Process Adjust") && selectedDate === "") {
            Swal.fire({
                title: "Warning!",
                text: "Please specify the date before saving the data",
                icon: "warning",
                timer: 2500,
                showConfirmButton: false
            });
            return;
        }
        let allValid = true;
        $(".stock-row").each(function () {
            let part_name = $(this).find('input[name="part_name"]').val().trim();
            let barcode = $(this).find('input[name="barcode"]').val().trim();
            let quantities = $(this).find('input[name="quantities"]').val().trim();
            let originalQuantities = $(this).find('input[name="quantities"]').data('original-quantities');
            let remark = $(this).find('input[name="remark"]').val().trim();

            if (!part_name || !barcode || !quantities) {
                Swal.fire({
                    title: "Warning!",
                    text: "Please fill in all the fields.",
                    icon: "warning",
                    timer: 2500,
                    showConfirmButton: false
                });
                return false;
            }
            
            if (parseInt(quantities) > parseInt(originalQuantities)) {
                Swal.fire({
                    title: "Warning!",
                    text: "The value of Quantities must be less than or equal to the original value.",
                    icon: "warning",
                    timer: 2500,
                    showConfirmButton: false
                }).then(() => {
                });
                allValid = false;
                return false;
            }

            formData.push({
                part_name: part_name,
                barcode: barcode,
                quantities: quantities,
                document_number: documentNumber,
                column_name: columnName,
                selected_date: selectedDate, // ✅ เพิ่มค่าวันที่ (ถ้ามี)
                remark: remark
            });
        });

        if (!allValid || formData.length === 0) {
            return;
        }

        // ✅ แสดง SweetAlert ยืนยันก่อนบันทึก
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
                console.log("📌 Sending Data:", JSON.stringify({ stockData: formData }));

                $.ajax({
                    url: "process/save_success.php",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify({ stockData: formData }),
                    success: function (response) {
                        console.log("📌 Server Response:", response);
                        if (response.status === "success") {
                            Swal.fire({
                                title: "Save successful!",
                                text: "Data has been saved to Success. Document number: " + response.document_number,
                                icon: "success",
                                timer: 200000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = "manage_balance.php"; // ✅ Redirect ไปหน้า manage_balance.php
                            });
                        } else {
                            Swal.fire({
                                title: "An error occurred!",
                                text: response.message,
                                icon: "error",
                                timer: 2500,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("❌ Error:", error);
                        Swal.fire({
                            title: "An error occurred!",
                            text: "Unable to save the data. Please try again.",
                            icon: "error",
                            timer: 2500,
                            showConfirmButton: false
                        });
                    }
                });
            }
        });
    });



});

</script>

<?php include('footer.php'); ?>
