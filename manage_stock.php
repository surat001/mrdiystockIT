<?php include 'header.php'; ?>
<?php include 'navbar.php'; ?>
<?php include 'sidebar.php'; ?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Manage Stock</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item">Stock</li>
                <li class="breadcrumb-item active"><a href="manage_stock.php">Manage Stock</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <!-- Section for Stock Management -->
    <div class="container1">
        <div class="rounded p-3 bg-white shadow-lg" style="border: 2px solid rgb(109, 109, 109) !important;">
        
            <div class="icon-container d-flex justify-content-around flex-wrap">
                <!-- ปุ่มเลือกหัวข้อสต็อก -->
                <button class="btn btn-icon card-option" data-option="Waiting to Receive">
                    <i class="fas fa-box"></i> Waiting to Receive
                </button>
                <button class="btn btn-icon card-option" data-option="Process Adjust">
                    <i class="fas fa-cogs"></i> Process Adjust
                </button>
                <button class="btn btn-icon card-option" data-option="Claim warranty">
                    <i class="fas fa-file-alt"></i> Claim warranty
                </button>
                <button class="btn btn-icon card-option" data-option="Borrow">
                    <i class="fas fa-hand-holding-usd"></i> Borrow
                </button>
                <button class="btn btn-icon card-option" data-option="Damage">
                    <i class="fas fa-exclamation-circle"></i> Damage
                </button>
                <button class="btn btn-icon card-option" data-option="Remark">
                    <i class="fas fa-pencil-alt"></i> Remark
                </button>
                <button class="btn btn-icon card-option" data-option="Next Orders">
                    <i class="fas fa-boxes"></i> Next Orders
                </button>
                <button class="btn btn-icon card-option" data-option="Reserved">
                    <i class="fas fa-calendar-check"></i> Reserved
                </button>
                <button class="btn btn-icon card-option" data-option="Used">
                    <i class="fas fa-history"></i> Used
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

            <!-- Add buttons inside the container -->
            <div class="d-flex justify-content-between mt-3">
                <button class="btn btn-success custom-btn-size1" id="addStockRow"><i class="fas fa-plus"></i></button>
                <div>
                    <button class="btn btn-danger btn-cancel"><i class="fas fa-times"></i> Cancel</button>
                    <button class="btn btn-success btn-savepending"><i class="fas fa-save"></i> Save</button>
                </div>
            </div>
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
    $(".btn-savepending").click(function () {
        formChanged = false;  // รีเซ็ตสถานะการเปลี่ยนแปลงเมื่อกดปุ่ม Save
        let allFilled = true;
        let formData = [];
        let barcodeSet = new Set();

        $(".stock-row").each(function () {
            let part_name = $(this).find('input[name="part_name"]').val().trim();
            let barcode = $(this).find('input[name="barcode"]').val().trim();
            let quantities = $(this).find('input[name="quantities"]').val().trim();
            let remark = $(this).find('input[name="remark"]').val().trim();
            let column_name = $("#selectedColumnName").text();

            if (!part_name || !barcode || !quantities || column_name === "") {
                allFilled = false;
            }

            if (barcodeSet.has(barcode)) {
                Swal.fire({
                    title: "Warning!",
                    text: "Barcode '" + barcode + "' Already used in this list.",
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
                column_name: column_name
            });
        });

        if (!allFilled) {
            Swal.fire({
                title: "Warning!",
                text: "Please fill in all the fields or check for duplicate Barcode.",
                icon: "warning",
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }

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
                    url: "process/save_pending.php",
                    type: "POST",
                    data: { stockData: JSON.stringify(formData) },
                    success: function (response) {
                        console.log(response);  // ตรวจสอบ response ใน console

                        if (response.status === "success") {
                            // แสดงข้อความ Save สำเร็จและตัวเลือกให้ไปหน้า Manage Balance
                            Swal.fire({
                                title: "Save successful!",
                                text: "Data has been saved to Pending. Document number: " + response.document_number,
                                icon: "success",
                                showCancelButton: true,
                                confirmButtonText: 'Go to Manage Balance',
                                cancelButtonText: 'Stay here'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // ไปที่หน้า manage_balance.php
                                    window.location.href = "manage_balance.php";  // เปลี่ยนเส้นทางไปยัง manage_balance.php
                                } else {
                                    location.reload(); // รีเฟรชหน้าเว็บ
                                }

                            });
                        } else {
                            Swal.fire({
                                title: "An error occurred!",
                                text: response.message,
                                icon: "error",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            title: "An error occurred!",
                            text: "Unable to save the data. Please try again.",
                            icon: "error",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                });
            }
        });
    });

    // เรียกฟังก์ชัน fetchDropdown เมื่อพิมพ์ในช่อง Part_Name
    $(document).on("input", ".part-name", function () {
        let input = $(this);
        let query = input.val().trim();  // ดึงค่าจากช่อง Part Name
        let dropdown = input.siblings(".dropdown-list");  // เลือก dropdown ที่อยู่ใกล้ที่สุด

        if (query.length >= 1) {
            fetchDropdown(query, dropdown, input);  // เรียกฟังก์ชันดึงข้อมูลจากฐานข้อมูล
        } else {
            dropdown.hide();  // ถ้าไม่มีการพิมพ์คำค้นหาก็ซ่อน dropdown
        }
    });

    function fetchDropdown(query, dropdown, input) {
        fetch("includes/fetch_part_name.php?query=" + encodeURIComponent(query))
            .then(response => response.json())  // ดึงข้อมูลจากฐานข้อมูล
            .then(data => {
                dropdown.html('');  // ล้างข้อมูลใน dropdown ก่อน

                if (data.success && data.data.length > 0) {
                    data.data.forEach(item => {
                        let li = $("<li>").text(item.part_name)  // เพิ่มชื่อ part_name ลงใน li
                            .css({
                                "padding": "10px", "cursor": "pointer", "border-bottom": "1px solid #ddd"
                            })
                            .hover(function() {
                                $(this).css("background-color", "#f8f9fa");  // เมื่อ hover ให้เปลี่ยนสีพื้น
                            }, function() {
                                $(this).css("background-color", "#fff");
                            })
                            .click(function() {
                                input.val(item.part_name);  // เมื่อคลิกเติมข้อมูลลงในฟอร์ม
                                
                                // ตรวจสอบว่า `barcode` มีค่าหรือไม่
                                if(item.barcode) {
                                    input.closest(".stock-row").find(".barcode").val(item.barcode);  
                                }

                                dropdown.hide();  // ซ่อน dropdown หลังจากเลือก
                            });

                        dropdown.append(li);  // เพิ่มข้อมูลเข้า dropdown
                    });
                    dropdown.show();  // แสดง dropdown
                } else {
                    dropdown.append('<li class="text-muted text-center">No results found</li>').show();  // ถ้าไม่มีข้อมูล
                }
            })
            .catch(error => {
                console.error('Error fetching part names:', error);
                dropdown.append('<li class="text-muted text-center">Error fetching data</li>').show();  // หากเกิดข้อผิดพลาด
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
