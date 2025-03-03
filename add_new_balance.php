<?php include 'header.php'; ?>
<?php include 'navbar.php'; ?>
<?php include 'sidebar.php'; ?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Add balance</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item">Stock</li>
                <li class="breadcrumb-item active"><a href="add_new_balances.php">Add balance</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <!-- Section for Stock Management -->
    <div class="container1">
        <div class="rounded p-3 bg-white shadow-lg" style="border: 2px solid rgb(109, 109, 109) !important;">
        
            <div class="icon-container d-flex justify-content-around flex-wrap">
                <!-- ปุ่มเลือกหัวข้อสต็อก -->
                <button class="btn btn-icon card-option" data-option="Waiting to Receive">
                    <i class="fas fa-box"></i> New Branch
                </button>
                <button class="btn btn-icon card-option" data-option="Process Adjust">
                    <i class="fas fa-cogs"></i> Replace
                </button>
                <button class="btn btn-icon card-option" data-option="Claim warranty">
                    <i class="fas fa-file-alt"></i> Additionnal
                </button>
                <button class="btn btn-icon card-option" data-option="Borrow">
                    <i class="fas fa-hand-holding-usd"></i> New DVR
                </button>
            </div>
        </div>
    </div>
    <br>
    
    <!-- Display selected option message -->
    
    <input type="hidden" id="selectedOptionMessage" class="alert alert-info" style="display:none; font-size: 18px; text-align: center; 
    background-color: #007bff; color: #fff; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); padding: 20px; 
    animation: slideIn 0.5s ease-out;">
    <input type="hidden" id="selectedColumnName">


    <!-- Form to manage stock, show when an option is selected -->
    <div class="container1" id="editStockContainer" style="display: none;">
        <div class="card rounded p-3 bg-white shadow-lg" style="border: 2px solid rgb(109, 109, 109) !important;">
            <div class="row align-items-end stock-row" style="margin-top: 20px;">
                <div class="col-md-3 position-relative">
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

            <!-- Add buttons inside the container -->
            <div class="d-flex justify-content-between mt-3">
                <button class="btn btn-success custom-btn-size1" id="addStockRow"><i class="fas fa-plus"></i></button>
                <div>
                    <button class="btn btn-danger btn-cancel"><i class="fas fa-times"></i> Cancel</button>
                    <button class="btn btn-success btn-savepending"><i class="fas fa-save"></i> Save</button>
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
        </div>
    </div>

</main><!-- End #main -->

<?php include 'footer.php'; ?>

<!-- Include DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    let formChanged = false;  // ติดตามว่ามีการเปลี่ยนแปลงในฟอร์มหรือไม่
    let currentOption = null; // เก็บหัวข้อที่เลือกอยู่ตอนนี้

    // ซ่อน Edit Form ไว้ก่อน
    $("#editStockContainer").hide();
    $("#selectedOptionMessage").hide();

    // ตรวจจับการกรอกข้อมูลในฟอร์ม
    $(document).on("input", "input[name='part_name'], input[name='barcode'], input[name='quantities'], input[name='remark']", function() {
        formChanged = true;  // เมื่อมีการกรอกข้อมูลในช่องฟอร์ม จะตั้งสถานะว่าเปลี่ยนแปลง
    });

    // เมื่อคลิกปุ่มใน icon-container ให้แสดงฟอร์มแก้ไข
    $(".card-option").click(function() {
        let selectedOption = $(this).data("option");

        // ถ้าหัวข้อที่เลือกซ้ำกับหัวข้อปัจจุบัน → ไม่ต้องทำอะไร
        if (selectedOption === currentOption) return;

        if (formChanged) {
            // ถ้ามีการเปลี่ยนแปลงข้อมูล จะแสดง SweetAlert ให้ยืนยัน
            Swal.fire({
                title: 'Are you sure?',
                text: "You have unsaved changes. Do you want to discard them?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, discard changes',
                cancelButtonText: 'No, stay here'
            }).then((result) => {
                if (result.isConfirmed) {
                    resetForm();  // รีเซ็ตค่าฟอร์ม
                    switchStockOption(selectedOption, $(this)); // เปลี่ยนหัวข้อ
                }
            });
        } else {
            switchStockOption(selectedOption, $(this)); // เปลี่ยนหัวข้อได้ทันทีถ้าไม่มีการเปลี่ยนแปลง
        }
    });

    function resetForm() {
        formChanged = false;  // รีเซ็ตสถานะการเปลี่ยนแปลง
        $("#editStockContainer input").val("");  // เคลียร์ค่าฟอร์ม
        
    }

    function switchStockOption(option, element) {
        currentOption = option;  // อัปเดตหัวข้อปัจจุบัน
        $("#selectedColumnName").text(option).attr("data-selected-option", option); // อัปเดตชื่อหัวข้อ
        console.log("Updated Column Name:", option);  // Debug เพื่อตรวจสอบว่าหัวข้อถูกอัปเดต

        $("#selectedOptionMessage").show();
        $("#editStockContainer").show();

        // ไฮไลต์หัวข้อที่เลือก
        $(".card-option").removeClass("selected").addClass("dimmed");
        element.removeClass("dimmed").addClass("selected");
    }


    // ฟังก์ชันที่ใช้สำหรับเพิ่มแถวใหม่ในฟอร์ม
    $("#addStockRow").click(function() {
        $("#addStockRow").closest(".d-flex").before(createRow());  // เพิ่มแถวใหม่
        formChanged = true;  // ตั้งสถานะการเปลี่ยนแปลงเป็น true
    });

    // การลบแถว
    $(document).on("click", ".remove-row", function() {
        $(this).closest(".stock-row").remove();
        formChanged = true;  // ตั้งสถานะการเปลี่ยนแปลงเป็น true
    });



    // เมื่อกดปุ่ม "Cancel"
    $(".btn-cancel").click(function() {
        formChanged = false;  // รีเซ็ตสถานะการเปลี่ยนแปลงเมื่อกดปุ่ม Cancel
        $("#editStockContainer input, #editStockContainer select").val("");  // เคลียร์ค่าฟอร์ม
        $(".stock-row").remove();  // ลบแถวที่เพิ่มมา
    });

    // เมื่อกดปุ่ม "Save"
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

    // ปิด Dropdown เมื่อคลิกข้างนอก
    $(document).click(function (e) {
        if (!$(e.target).closest(".part-name, .dropdown-list").length) {
            $(".dropdown-list").hide();
        }
    });

    function createRow() {
    return `
        <div class="row align-items-end stock-row" style="margin-top: 20px;">
            <div class="col-md-3 position-relative">
                <label><strong>Part Name</strong></label>
                <input type="text" class="form-control part-name" name="part_name" autocomplete="off">
                <ul class="dropdown-list list-unstyled"></ul>
            </div>
            <div class="col-md-2">
                <label><strong>Barcode</strong></label>
                <input type="text" class="form-control barcode" name="barcode" readonly>
            </div>
            <div class="col-md-2">
                <label><strong>Quantities</strong></label>
                <input type="number" class="form-control" name="quantities">
            </div>
            <div class="col-md-2">
                <label><strong>Remark</strong></label>
                <input type="text" class="form-control" name="remark">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button class="btn btn-danger remove-row">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        </div>
    `;
}

});

</script>

<style>
/* สไตล์การแสดงผลปุ่มที่เลือก */
.card-option.selected {
    background-color: #687079; /* ทำให้หัวข้อที่เลือกเด่น */
    color: white;
    border-color: #7d7d7d; /* เปลี่ยนกรอบให้ตรงกับสีของหัวข้อที่เลือก */
}

/* สไตล์การทำให้ปุ่มที่ไม่ได้เลือกจาง */
.card-option.dimmed {
    opacity: 0.5; /* ทำให้หัวข้อที่ไม่ได้เลือกจาง */
}

/* รูปแบบการแสดงข้อความที่เลือก */
#selectedOptionMessage {
    animation: slideIn 0.5s ease-out;
    background-color:rgb(66, 66, 66);
    color: white;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    padding: 20px;
    font-size: 18px;
    text-align: center;
}

#selectedOptionMessage i {
    font-size: 24px;
    margin-right: 10px;
}

/* เพิ่มการเคลื่อนไหว */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.custom-btn-size1 {
    height: 35px;
    font-size: 14px;
    width: 40px;
    min-width: 10px;
    padding: 5px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card-option {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    margin: 10px;
    padding: 25px; /* Increased padding for a larger card */
    background-color: #f7f7f7;
    border-radius: 8px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    width: 150px; /* Set width for consistent size */
    height: 150px; /* Set height for consistent size */
    text-align: center; /* Center align text inside the card */
    display: flex;
    flex-direction: column;
    justify-content: space-between; /* Ensures proper spacing */
    border: 2px solid rgb(185, 185, 185);
}

.card-option:hover {
    background-color:rgb(151, 151, 151);
    transform: translateY(-5px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    border-color:rgb(0, 0, 0);
}

.card-option i {
    font-size: 32px; /* Increase icon size for better visibility */
    margin-bottom: 10px; /* Increased margin for more space */
}

.card-option p {
    font-size: 14px;
    color: #333;
    margin: 0;
}

.card-option a {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.dropdown-list {
    width: 94%;
    position: absolute;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1050;
    top: 100%;
    display: none;
}

.dropdown-list li {
    padding: 10px;
    cursor: pointer;
    border-bottom: 1px solid #ddd;
    list-style-type: none;
}

.dropdown-list li:hover {
    background-color: #f8f9fa;
}

.stock-row {
    position: relative;
}

.card {
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}

.alert-info {
    font-size: 18px;
    text-align: center;
}

</style>
