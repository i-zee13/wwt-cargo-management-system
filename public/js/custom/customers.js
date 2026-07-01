var segments = location.href.split("/");
var customerTable = "";
$(document).ready(function () {
  if (segments[3] == "customers-list") {
    fetchCustomers();
  } else if (segments[3] == "create-customer") {
    if (
      $("#customer_updating_id").val() &&
      $("#customer_updating_id").val().trim()
    ) {
      $(".passwordLabel").text("Password");
      $("#password").removeClass("required-customer");
    } else {
      $(".passwordLabel").text("Password*");
      $("#password").addClass("required-customer");
    }
  }
});
function fetchCustomers() {
  $("#customers_table_body").fadeOut();
  $("#tblLoader").show();
  $.ajax({
    type: "GET",
    url: `/get-customers`,
    success: function (response) {
      loadCustomers(response.customers);
    },
  });
}
function loadCustomers(customers) {
  $(".customers_table_body").append(`
         <table class="table table-hover dt-responsive nowrap" id="customerTable" style="width:100%;">
    <thead>
    <tr>
    <th>Id</th> 
  
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th> 
    <th>Status</th>
    <th>Action</th>
    </tr>
</thead>
<tbody></tbody>
    </table>
        `);
  if (customers && customers.length > 0) {
    console.log(customers);
    customers.forEach((customer) => {
      var status = customer.status == 1 ? "Active" : "InActive";
      var actionStatusText = customer.status == 1 ? "InActive" : "Active";
      $("#customerTable tbody").append(`
        <tr>
                <td>${customer.id ?? ""}</td>
              
                <td>${
                  (customer.first_name ?? "") + " " + (customer.last_name ?? "")
                }</td>
                <td>${customer.email ?? ""}</td>
                <td>${customer.phone_number ?? ""}</td> 
                <td class="text-status-${customer.id}">${status ?? ""}</td>
                <td>
                   <a href="create-customer/${customer.id}" title="Edit ${customer.first_name} Details" class="btn btn-outline-primary  ">
                    <svg width="10" height="13" viewBox="0 0 10 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.68745 11.18L4.5055 10.7143L4.68745 11.18ZM1.98319 11.6993L1.73319 12.1323H1.73319L1.98319 11.6993ZM1.08078 9.09767L0.586477 9.17295L1.08078 9.09767ZM1.40764 7.36282L1.84065 7.61282L1.40764 7.36282ZM1.01821 8.18767L0.526854 8.09509H0.526854L1.01821 8.18767ZM6.02644 10.0295L6.45946 10.2795L6.02644 10.0295ZM5.50683 10.7792L5.83268 11.1584L5.50683 10.7792ZM3.93993 2.97677L3.50692 2.72677L3.93993 2.97677ZM8.12572 5.39343L5.59343 9.77949L6.45946 10.2795L8.99175 5.89343L8.12572 5.39343ZM1.84065 7.61282L4.37295 3.22677L3.50692 2.72677L0.974629 7.11282L1.84065 7.61282ZM4.5055 10.7143C3.77452 10.9999 3.27668 11.193 2.89236 11.2828C2.52111 11.3696 2.35265 11.3352 2.23319 11.2663L1.73319 12.1323C2.1687 12.3837 2.63595 12.3697 3.11987 12.2566C3.59073 12.1466 4.16753 11.9199 4.86941 11.6457L4.5055 10.7143ZM0.586477 9.17295C0.699936 9.91791 0.792032 10.5308 0.93219 10.9935C1.07624 11.4692 1.29768 11.8808 1.73319 12.1323L2.23319 11.2663C2.11373 11.1973 1.99977 11.0686 1.88926 10.7037C1.77486 10.326 1.69324 9.79823 1.57508 9.02239L0.586477 9.17295ZM0.974629 7.11282C0.760081 7.48443 0.588305 7.76894 0.526854 8.09509L1.50956 8.28024C1.5322 8.16011 1.59202 8.04348 1.84065 7.61282L0.974629 7.11282ZM1.57508 9.02239C1.5002 8.53078 1.48693 8.40038 1.50956 8.28024L0.526854 8.09509C0.465403 8.42124 0.521869 8.74875 0.586477 9.17295L1.57508 9.02239ZM5.59343 9.77949C5.34479 10.2101 5.2737 10.3203 5.18097 10.3999L5.83268 11.1584C6.08441 10.9421 6.24491 10.6511 6.45946 10.2795L5.59343 9.77949ZM4.86941 11.6457C5.26908 11.4896 5.58095 11.3747 5.83268 11.1584L5.18097 10.3999C5.08825 10.4796 4.96869 10.5333 4.5055 10.7143L4.86941 11.6457ZM7.33267 2.43371C7.88924 2.75505 8.26258 2.97182 8.51981 3.17033C8.76556 3.35997 8.84384 3.48771 8.87564 3.60638L9.84157 3.34756C9.73042 2.93278 9.46507 2.63665 9.13075 2.37865C8.80792 2.12952 8.36476 1.87489 7.83267 1.56769L7.33267 2.43371ZM8.99175 5.89343C9.29895 5.36134 9.55542 4.91925 9.71044 4.54208C9.87097 4.15149 9.95271 3.76235 9.84157 3.34756L8.87564 3.60638C8.90744 3.72506 8.90351 3.87483 8.78551 4.16193C8.66199 4.46246 8.44706 4.83686 8.12572 5.39343L8.99175 5.89343ZM7.83267 1.56769C7.30058 1.26048 6.85848 1.00401 6.48131 0.848997C6.09072 0.688463 5.70159 0.606727 5.2868 0.717869L5.54562 1.68379C5.66429 1.652 5.81406 1.65592 6.10117 1.77392C6.4017 1.89744 6.77609 2.11237 7.33267 2.43371L7.83267 1.56769ZM4.37295 3.22677C4.69428 2.67019 4.91106 2.29686 5.10956 2.03962C5.2992 1.79388 5.42694 1.71559 5.54562 1.68379L5.2868 0.717869C4.87201 0.829011 4.57588 1.09436 4.31788 1.42869C4.06875 1.75152 3.81412 2.19468 3.50692 2.72677L4.37295 3.22677ZM8.80873 5.21042L4.18993 2.54375L3.68993 3.40978L8.30873 6.07645L8.80873 5.21042Z" fill=""></path>
                    </svg>
                </a>
                <span class="statusBtn-${customer.id}">
                <button type="button" class=" btn btn-primary changeStatus" id="${
                  customer.id}">
                ${actionStatusText}
                </button>
                </span>
             
                </td>
                </tr>
            `);
    });
  }
  customerTable = $("#customerTable").DataTable({
    responsive: true,
    lengthChange: false,
    info: false,
    pagingType: "simple_numbers",
    pageLength: 10,
    dom: "lrtip",
  });
  $("#customSearchInput").on("keyup", function () {
    customerTable.search(this.value).draw();
  });
  $("#tblLoader").hide();
  $(".customers_table_body").fadeIn();
}
$(document).on("click", ".changeStatus", function () {
  var id = $(this).attr("id");
  var currentRef = $(this);
  currentRef.attr("disabled", true);
  if (id) {
    $.ajax({
      type: "Post",
      url: `/change-customer-status`,
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf_token"]').attr("content"),
      },
      data: {
        customerId: id,
      },
      success: function (response) {
        if (response.status == "success") {
          currentRef.attr("disabled", false);
          var statusAction = response.action;

          var status = statusAction == 1 ? "Active" : "InActive";
          var actionStatusText = statusAction == 1 ? "InActive" : "Active";
          $(`.text-status-${id}`).html(status);
          $(`.statusBtn-${id}`).html(`
                      <button type="button" class="btn btn-primary changeStatus" id="${id}"> 
                       ${actionStatusText}
                    </button> 
                    `);
          $("#notifDiv").fadeIn();
          $("#notifDiv").css("background", "green");
          $("#notifDiv").text("Status Updated Successfully");
          setTimeout(() => {
            $("#notifDiv").fadeOut();
          }, 3000);
        }
      },
      error: function (err) {
        currentRef.attr("disabled", false);
        $("#notifDiv").fadeIn();
        $("#notifDiv").css("background", "red");
        $("#notifDiv").text(
          "Unable to update status at this moment. Please try again later"
        );
        setTimeout(() => {
          $("#notifDiv").fadeOut();
        }, 3000);
      },
    });
  }
});

$(document).on("click", "#savecustomer", function () {
  var dirty = false;
  $(".required-customer").each(function () {
    if (!$(this).val() || $(this).val() == 0 || !$(this).val().trim()) {
      if (dirty == false) {
        $(this).focus();
      }
      dirty = true;
      input_name = $(this).attr("name");
    }
  });
 
  if (dirty) {
    $("#notifDiv").fadeIn();
    $("#notifDiv").css("background", "red");
    $("#notifDiv").text("Please provide all required information (*)");
    setTimeout(() => {
      $("#notifDiv").fadeOut();
    }, 3000);
    return;
  } else if (!emailValidate($("#email").val())) {
    $("#notifDiv").fadeIn();
    $("#notifDiv").css("background", "red");
    $("#notifDiv").text("Please enter valid email address");
    setTimeout(() => {
      $("#notifDiv").fadeOut();
    }, 3000);
    $("#email").focus();
    return;
  }else if($('#password').val() && $('#password').val().trim() && $('#password').val().length < 6){
    $("#notifDiv").fadeIn();
    $("#notifDiv").css("background", "red");
    $("#notifDiv").text("Password must be at least 6 characters long");
    setTimeout(() => {
      $("#notifDiv").fadeOut();
    }, 3000);
    $('#password').focus();
    return;
  }else if($('.phone_field').val() && !$('.phone_field').val().includes('+')){ 
      $("#notifDiv").fadeIn();
      $("#notifDiv").css("background", "red");
      $("#notifDiv").text("Invalid Phone number");
      setTimeout(() => {
        $("#notifDiv").fadeOut();
      }, 3000);
      $('.phone_field').focus();
      return; 
  }
  else {
    var currentRef = $(this);
    currentRef.attr("disabled", true);
    var processText = $('#customer_updating_id').val() ? 'Updating':'Saving';
    var oldBtnText  = $('#customer_updating_id').val() ? 'Update':'Save';
    currentRef.text(processText);
    $("#saveCustomerForm").ajaxSubmit({
      type: "Post",
      url: `/save-customer`,
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf_token"]').attr("content"),
      },
      success: function (response) {
        if (response.status == "success") { 
          currentRef.text(oldBtnText+'d');
          $("#notifDiv").fadeIn();
          $("#notifDiv").css("background", "green");
          $("#notifDiv").text(response.msg);
          setTimeout(() => {
            $("#notifDiv").fadeOut();
            window.location.href = "/customers-list";
          }, 3000);
        } else if (response.status == "alreadyExist" || response.status == 'error') {
          currentRef.attr("disabled", false);
          currentRef.text(oldBtnText);
          $("#notifDiv").fadeIn();
          $("#notifDiv").css("background", "red");
          $("#notifDiv").text(`${response.msg}`);
          setTimeout(() => {
            $("#notifDiv").fadeOut();
          }, 3000);
        }
      },
      error: function (err) {
        currentRef.attr("disabled", false);
        currentRef.text(oldBtnText);
        $("#notifDiv").fadeIn();
        $("#notifDiv").css("background", "red");
        $("#notifDiv").text(`Unable to ${oldBtnText} Customer (*)`);
        setTimeout(() => {
          $("#notifDiv").fadeOut();
        }, 3000);
      },
    });
  }
});
