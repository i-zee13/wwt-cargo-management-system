// For arr.reduce/array.reduce algorithm
// orderData.contents.reduce((sum, val) => sum + val.net_weight, 0)
let dateTimeFormat = "yyyy-mm-dd hh:ii";
let dateFormat = "yyyy-mm-dd";
let timeFormat = "hh:ii";
var isSidebarOpen = false;
Dropzone.autoDiscover = false;
let subNavItems = [];
let parentModRef = null;
var item_id = "";
let editSubNavItem = null;
var allActivities = [];
var filtersArray = [
  "900",
  "901",
  "902",
  "903",
  "904",
  "905",
  "906",
  "907",
  "908",
  "909",
  "910",
];
var selected_customer_id = 0;
var filterStartDate = "";
var filterEndDate = "";
var filterDate = 0;
var searchQuery = null;
let allTasksCreated = [];
let files_url = "";
let all_files = []; 
$(document).ready(function () {
  $(document).ready(function () {
    
    
    var currentUrl = window.location.href;
    var segments = currentUrl.split("/");
    var segment3 = segments[4]; 
    var attrName = $("a[href*='" + segment3 + "']").attr("attr-name");
    if (attrName) {
      var parentLi = $("ul." + attrName.split(" ").join(".")).closest("li");
      if (!parentLi.hasClass("arrow")) {
        parentLi = $("ul." + attrName.split(" ").join(".")).parents("li.arrow");
      }
      if (parentLi) {
        $(".arrow").removeClass("showMenu");
        parentLi.addClass("showMenu");
      }
    }
  });

  $(".dropify").dropify({});
  $(".select_class").select2({});

  $(".sortable").sortable();
  var segments = location.href;
  segments = $.trim(segments.replace("#", ""));
  segments = segments.split("/");
  var logs_segment = segments[4];
  var bar = $(".bar");
  var percent = $(".percent");
  var status = $("#status");
  

  var segments = location.href.split("/");
  var action = segments[4];

  if (action == "view_all_activities") {
    fetchActivities(filtersArray, filterDate, filterStartDate, filterEndDate);
  }

  let activeTaskForComments = null;

  if (action == "Tasks" || action == "Correspondence") {
    fetchTaskFromMaster();
  }

  $(".centralizedTaskDp")
    .datepicker({
      format: "yyyy-mm-dd",
      startDate: "+0d",
    })
    .on("changeDate", function (e) {
      $(this).datepicker("hide");
    });

  $("#transaction_date_cash")
    .datepicker({
      format: "yyyy-mm-dd",
      startDate: "+0d",
    })
    .on("changeDate", function (e) {
      $(this).datepicker("hide");
    });

  $("#modalCloseTaskCentralized").on("click", function () {
    $("#taskCentralizedModal").removeClass("modalShow");
  });

  $(document).on("change", ".taskStatusChange", function () {
    $(this).parent().parent().find(".TS-Circle").removeClass("TS-InReview");
    $(this).parent().parent().find(".TS-Circle").removeClass("TS-InProgress");
    $(this).parent().parent().find(".TS-Circle").removeClass("TS-NotStarted");
    $(this).parent().parent().find(".TS-Circle").removeClass("TS-Completed");
    $(this).parent().parent().find(".TS-Circle").removeClass("TS-Cancelled");

    if ($(this).val() == "in-review")
      $(this).parent().parent().find(".TS-Circle").addClass("TS-InReview");
    else if ($(this).val() == "in-progress")
      $(this).parent().parent().find(".TS-Circle").addClass("TS-InProgress");
    else if ($(this).val() == "not-started")
      $(this).parent().parent().find(".TS-Circle").addClass("TS-NotStarted");
    else if ($(this).val() == "completed")
      $(this).parent().parent().find(".TS-Circle").addClass("TS-Completed");
    else if ($(this).val() == "cancelled")
      $(this).parent().parent().find(".TS-Circle").addClass("TS-Cancelled");

    ajaxer("/UpdateTaskStatus", "POST", {
      _token: $('[name="csrf_token"]').attr("content"),
      id: activeTaskForComments.id,
      status: $(this).val(),
    }).then((x) => {
      activeTaskForComments.task_status = $(this).val();
    });
  });

  $(document).on("click", ".deleteTaskFromModal", function () {
    if (!confirm("Are you sure you want to delete this task?")) return;
    $(this).attr("disabled", true);
    ajaxer("/Correspondence/Delete", "POST", {
      _token: $('[name="csrf_token"]').attr("content"),
      id: $(this).attr("task-id"),
    }).then((x) => {
      $(this).parent().parent().remove();
    });
  });
  // for text type inputs which are required to accept only numeric values
  $(document).on("keypress", ".only_numerics", function (e) {
    // this.value = this.value.replace(/[^0-9]/gi,'');
    var charCode = e.which ? e.which : event.keyCode;
    if (String.fromCharCode(charCode).match(/[^0-9]/g)) return false;
  });
  $(document).on("keypress", ".phone_number_field", function (e) {
    var charCode = e.which ? e.which : event.keyCode;
    if (String.fromCharCode(charCode).match(/[^0-9\s()+\-]/g)) return false;
  });

  $(document).on("input", ".only_decimals_no", function (e) {
    var inputValue = this.value;
    inputValue = inputValue.replace(/[^0-9.]/g, "");
    inputValue = inputValue.replace(/^0+/, "");
    this.value = inputValue;
    if ((inputValue.match(/\./g) || []).length > 1) {
      this.value = inputValue.slice(0, -1);
    }
  });

  $(document).on("keyup", ".postal_code_input", function (e) {
    var txtVal = $(this).val();
    txtVal = txtVal.replace(/ /g, "");
    $(".postal_code_input").val(txtVal);
    var txtVal = $(this).val();
    if (txtVal.length > 6) {
      $(".postal_code_input").val(txtVal.substring(0, 6));
    }
  });
  // for text type inputs which are required to accept only aplphabetic values
  $(document).on("input", ".only_alphabets", function () {
    this.value = this.value.replace(/[^a-z\s.]/gi, "");
  });
 

   
 
 

  $("#modalExpand-btn").click(function () {
    //Saif Work
    $("#taskCentralizedModal").toggleClass("modalExpand");
    $(".test_i").toggleClass("fa-expand fa-compress");
  });

  $("#saveCentralizedTask").click(function () {
    if (
      !$("#taskTitleTaskCentralized").val() ||
      !$("#momTaskMomCentralized").val() ||
      !$("#assigned_toTaskCentralized").val().length ||
      !$("#customerIdTaskCentralized").val()
    ) {
      $("#notifDiv").fadeIn();
      $("#notifDiv").css("background", "red");
      $("#notifDiv").text("Please provide all the required information");
      setTimeout(() => {
        $("#notifDiv").fadeOut();
      }, 3000);
      return;
    }
    $(this).attr("disabled", true);
    $(this).text("Saving");
    $(".tasksListTable tbody").html(
      '<tr><td colspan="8" style="text-align: center; line-height: 3; font-weight: bold; font-size: 14px">LOADING</td> </tr>'
    );
    $.ajax({
      type: "POST",
      url: "/admin/Correspondence",
      data: {
        _token: $('[name="csrf_token"]').attr("content"),
        due_date: $("#dueDateDpTaskCentralized").val(),
        due_time: $("#dueTimeDDTaskCentralized").val(),
        title: $("#taskTitleTaskCentralized").val(),
        assigned_to: $("#assigned_toTaskCentralized").val(),
        reminder_date: $("#reminderDateDpTaskCentralized").val(),
        reminder_time: $("#reminderTimeDDTaskCentralized").val(),
        mom: $("#momTaskMomCentralized").val(),
        customer_id: $("#customerIdTaskCentralized").val(),
        priority: $("#taskPriority").val(),
        type: "task",
      },
      success: function (response) {
        allTasksCreated = JSON.parse(response).data;
        renderTasksInTasksPage();
        $("#momTaskMomCentralized").val("");
        $("#taskTitleTaskCentralized").val("");
        $(this).removeAttr("disabled");
        $(this).text("Save Task");
        $("#assigned_toTaskCentralized").val(null).trigger("change");
        $("#customerIdTaskCentralized").val("0").trigger("change");
        $("#modalCloseTaskCentralized").click();
      }.bind($(this)),
    });
  });

  navItemsScript(); 
  actionListeners();

  $(".datepicker")
    .datepicker({
      format: "yyyy-mm-dd",
      todayHighlight: true, 
    })
    .on("changeDate", function (e) {
      $(this).datepicker("hide");
    });

  // $('.wrapper').click(function () {
  //     if ($('#page-top').hasClass('no-scroll')) {
  //         closeSidebar()
  //     } else {
  //         openSidebar()
  //     }
  // });

  $("#example").DataTable();

  $(".table-PL").dataTable({
    searching: false,
    paging: false,
    info: false,
  });

  // $("#pl-close, .close-sidebar, .overlay, .pl-close").on("click", function () {
  //     closeSidebar();
  // });

  // $(document).on("click", ".closeProductAddSidebar", function () {
  //     closeSidebar();
  // });

  $(document).on("click", "#SN-close, .overlay-blure", function (e) {
    // allClasses = e.target.classList;
    // if(allClasses[2] && allClasses[2] == 'snCloseBtn'){

    // }
    // alert($(window).width());
    closeSubNav();
  });

  // $(document).on("click", "#SN-close, .overlay-for-sidebar", function () {
  //     closeSidebar();
  // });

  $(document).on("click", ".openSubMenu", function () {
    let name = $(this).attr("attr-name");
    let item = subNavItems.find(
      (x) => x.parent.toLowerCase() == name.toLowerCase()
    );
    $(`.${name}`).empty();
    item.child.forEach((element) => {
      $(`.${name}`).append(element);
    });
    // closeSidebar();
    // openSubNav();
  });

  $(document).on("click", ".open_search_modal", function () {
    $(".SearchList").empty();
    $("#tblLoader_search").hide();
    $(".search_whole_site").val("");
  });

  $(document).on("input", ".search_whole_site", function () {
    if ($(this).val().length > 2) {
      $(".SearchList").empty();
      $("#tblLoader_search").show();
      fetchSiteSearchReasult($(this).val());
    } else if ($(this).val() == "") {
      $(".SearchList").empty();
      $("#tblLoader_search").hide();
    }
  });

  $(document).on("click", ".filter_checkBox", function () {
    //900 = order
    //901 = product
    //902 = customer
    //903 = supplier
    //904 = shipper
    //905 = task
    if ($(this).prop("checked")) {
      filtersArray.push($(this).attr("id"));
    } else {
      if (filtersArray.indexOf($(this).attr("id")) > -1) {
        filtersArray.splice(filtersArray.indexOf($(this).attr("id")), 1);
      }
    }
    $(".filters_count").text(`(${filtersArray.length}/11)`);
    renderDataAfterFilters(selected_customer_id, filtersArray, searchQuery);
  });

  $(document).on("change", "#emp_for_activity", function () {
    selected_customer_id = $(this).val();
    $(".all_activities").empty();
    renderDataAfterFilters(selected_customer_id, filtersArray, searchQuery);
  });

  $(document).on("change", ".date_filter", function () {
    filterDate = $(this).val();
    if ($(this).val() == 5) {
      $(".custom_filter_div").show();
    } else {
      $(".custom_filter_div").hide();
      fetchActivities(
        filtersArray,
        filterDate,
        filterStartDate,
        filterEndDate,
        searchQuery
      );
    }
  });

  $(document).on("change", ".filterStartDate", function () {
    filterStartDate = $(this).val();
    if (filterEndDate) {
      fetchActivities(
        filtersArray,
        filterDate,
        filterStartDate,
        filterEndDate,
        searchQuery
      );
    }
  });

  $(document).on("change", ".filterEndDate", function () {
    filterEndDate = $(this).val();
    if (filterStartDate) {
      fetchActivities(
        filtersArray,
        filterDate,
        filterStartDate,
        filterEndDate,
        searchQuery
      );
    }
  });

  $(document).on("input", ".searchActivities", function () {
    if ($(this).val() == "") {
      searchQuery = null;
    } else {
      searchQuery = $(this).val();
    }
    renderDataAfterFilters(selected_customer_id, filtersArray, searchQuery);
  });
});
$(document).on("click", function (event) {
  if (
    isSidebarOpen &&
    !$(event.target).closest("#access-right").length &&
    !$(event.target).hasClass("close_sidebar")
  ) {
    // closeSidebar();
  }
});
$(window).on("load", function () {
  $("._act-TL .body").mCustomScrollbar({
    theme: "dark-2",
  });
  // $("._activitycards, .left_Info").mCustomScrollbar({
  //     theme: "dark-2"
  // });
  $(".OrderDL .body").mCustomScrollbar({
    theme: "dark-2",
  });
  // $(".TaskCommentSec").mCustomScrollbar({
  //     theme: "dark-2"
  // });
});

$(".form-control")
  .on("focus blur", function (e) {
    $(this)
      .parent()
      .toggleClass("focused", e.type === "focus" || this.value.length > 0);
  })
  .trigger("blur");
$(".formselect").select2();
$(".sd-type").select2({
  createTag: function (params) {
    var term = $.trim(params.term);
    if (term === "") {
      return null;
    }
    return {
      id: term,
      text: term,
      newTag: true, // add additional parameters
    };
  },
});

(function ($) {
  $(window).on("load", function () {
    $("._act-TL .body").mCustomScrollbar({
      theme: "dark-2",
    });
    $("._activityEmp-timeline, .TaskAtachList, .TaskAddDoc").mCustomScrollbar({
      theme: "dark-2",
    });
  });
})(jQuery);

function deleteOnConfirmation(localRef, parentRef, url, module) {
  localRef.attr("disabled", true);
  localRef.text("Deleting");
  $.ajax({
    type: "POST",
    url: url,
    data: {
      _token: $('[name="csrf_token"]').attr("content"),
      id: parentRef.attr("c-id"),
    },
    success: function (response) {
      $(".completionCheckMark").show();
      $(".actionBtnsDiv").hide();
      if (module == "correspondence") {
        parentRef
          .parent()
          .parent()
          .parent()
          .parent()
          .parent()
          .parent()
          .parent()
          .remove();
        $("#deleteCorrespondenceMsg").text("Correspondence has been deleted");
      }
      localRef.removeAttr("disabled");
      localRef.text("Yes");
      setTimeout(() => {
        $(".closeConfirmationModal").click();
      }, 1500);
    }.bind(parentRef),
  });
}

function renderPage() { 
  $(document).find('#languageToggle').prop('checked', activeLang === 'es');
  var segments = location.href;
  segments = $.trim(segments.replace("#", ""));
  segments = segments.split("/");

    if (controller == "RegisterController" || controller == "Employee") { 
    if (segments[4] != "user-logs") {
      $.getScript("/js/custom/employee.js?v=1.5.0.1");
    }
  } else if (controller == "HomeController") { 
    $.getScript("/js/custom/home.js?v=1.8");
  } else if (controller == "SettingsController") {
    $.getScript("/js/custom/settings.js?v=2.1");
  }   
  $("#page-top").removeClass("no-scroll");
  $(".loader-page").hide();
  $(".wrapper").show();
}

function actionListeners() {
  $(document).on("click", ".deleteSubNavItem", function () {
    if (!window.confirm("Are you sure you want to delete this item?")) return;
    let itemId = $(this).attr("item-id");
    $(this).parent().remove();
    $.ajax({
      type: "POST",
      url: "/admin/Admin/DeleteSubNavItem",
      data: {
        _token: $('[name="csrf_token"]').attr("content"),
        id: itemId,
      },
      success: function () {
        location.reload();
      },
    });
  });

  $(document).on("click", ".savePriorityList", function () {
    let priorityList = [];
    let priority = 1;
    $(this).text("Saving..");
    $(".subPrior").each(function () {
      priorityList.push({
        id: $(this).attr("item-id"),
        priority: priority++,
      });
    });
    $.ajax({
      type: "POST",
      url: "/admin/Admin/UpdateSubModPriority",
      data: {
        _token: $('[name="csrf_token"]').attr("content"),
        data: priorityList,
      },
      success: function (response) {
        location.reload();
      },
    });
  });
  $(document).on("click", ".arrow", function () {
    if ($(this).hasClass("showMenu")) {
      $(".arrow").removeClass("showMenu");
    } else {
      $(".arrow").removeClass("showMenu");
      $(this).addClass("showMenu");
    }
  });
  function selectAssignToDesignations(type,selectedDesignation=null){
    console.log(selectedDesignation,'selectedDesignation');
    var initClass           =   "";
    var initClassParent     =   "";
    if(type == "parent"){
        initClass           =   ".assign_to_designationParent";
        initClassParent     =   ".assignToDesignationParent";
    }else{
        initClass           =   ".assign_to_designationSub";
        initClassParent     =   ".assignToDesignationSub";
    }
    if(selectedDesignation){
        selectedDesignation =   JSON.parse(selectedDesignation);
    }
    let AllDesignation      =   JSON.parse($('#get-all-designations').val());
    $(initClass).empty();
    var firstSelect         =   "0";

    AllDesignation.forEach(assign_designation => {
        var isSelected      =   "";
        if(selectedDesignation && selectedDesignation.length > 0){
            isSelected      =   selectedDesignation.find(x=> (x == assign_designation.id));
            if(isSelected && isSelected != undefined){
                isSelected  =   "selected = 'selected'";
                firstSelect =   "1";
            }else{
                isSelected  =   "";
            }
        }else{
            isSelected      =   "";
        }
        $(initClass).append(`<option value="${assign_designation.id}" class="designation-option" ${isSelected}  >${assign_designation.designation}</option>`);
    });
    setTimeout(() => { 
        console.log(initClass);
        window.fs_test      = $(initClass).fSelect(); 
        window.fs_test      = $(initClass).fSelect('reload'); 
    }, 500);
}
  $(document).on("click", ".bx-menu", function () {
    if ($(".sidebar").hasClass("close")) {
      $(".sidebar").removeClass("close");
      $("body").addClass("effect");
    } else {
      $(".sidebar").removeClass("close");
      $(".sidebar").addClass("close");
      $("body").removeClass("effect");
    }
  });

  $(document).on("keydown", ".parentModEditor", function (e) {
    if (e.keyCode == 13) {
      $(this).attr("disabled", true);
      $.ajax({
        type: "POST",
        url: "/admin/Admin/UpdateParentMod",
        data: {
          _token: $('[name="csrf_token"]').attr("content"),
          old_parent_mod: $(this).attr("module-name"),
          parentMod: $(this).val(),
        },
        success: function (response) {
          location.reload();
        },
      });
    }
  });

  $(document).on("click", ".deleteParentMod", function () {
    let itemFnd = allControllersData.find(
      (x) => x.parent_module == $(this).attr("parent-module")
    ).parent_module;
    if (!window.confirm("This action will delete this item permanently"))
      return;

    $(this).text("Deleting");
    $.ajax({
      type: "POST",
      url: "/admin/Admin/DeleteParentMod",
      data: {
        _token: $('[name="csrf_token"]').attr("content"),
        parent_module: itemFnd,
      },
      success: function () {
        location.reload();
      },
    });
  });

  $(document).on("click", ".new_parent_module", function () {
    $(".showParentModule").click();
    let itemFnd = allControllersData.find(
      (x) => x.parent_module == $(this).attr("data-text")
    );
    selectAssignToDesignations("parent");
    $('[name="parent_op"]').val("update");
    if (itemFnd) {
      $(".dropify-preview").show();
      $(".dropify-render").html(`
            <img src="/storage/menu_icons/${itemFnd.logo}">`);
      $("#newParentModForm").append(
        `<input name="parent_module_name_update" value="${itemFnd.parent_module}" hidden />`
      );
      $('#newParentModForm [name="newParentModForm"]').val(
        itemFnd.parent_module
      );
      $('#newParentModForm [name="parent_module_name"]').val(
        itemFnd.parent_module
      );
      $('#newParentModForm [name="show_in_sidebar"]').val(
        itemFnd.show_in_sidebar
      );
      $('#newParentModForm [name="parent_icon"]').attr(
        "data-default-file",
        "/storage/" + itemFnd.logo
      );
      setTimeout(() => {
        $('[name="parent_module_name"]').focus();
      }, 500);
      $("#newParentModForm").show();
    }
  });
  $(document).on("click", ".new_sub_module", function () {
    $(".showSubModule").click();
    item_id = "";
    parentModRef = $(this);
    editSubNavItem = allControllersData.find(
      (x) => x.id == $(this).attr("item-id")
    );
    selectAssignToDesignations("child");
    if (editSubNavItem) {
      item_id = $(this).attr("item-id");
      $("#newSubModForm [name='module_name']").val(
        editSubNavItem.sub_module
          ? editSubNavItem.sub_module
          : editSubNavItem.made_up_name
      );
      $("#newSubModForm [name='route']").val(editSubNavItem.controller);
      $("#newSubModForm [name='made_up_name']").val(
        editSubNavItem.made_up_name
      );
      selectAssignToDesignations("child",editSubNavItem.designation_ids);
      $("#newSubModForm [name='show_in_sub_menu']").val(
        editSubNavItem.show_in_sub_menu
      );
    }
  });

  $(document).on("click", "#saveNewSubMod", function () {
    let updateItemId = item_id;

    let parentMod = parentModRef
      .closest(".parent-li")
      .find(".new_parent_module")
      .data("text");
    let currentPriority = 1;

    let allItemsWithoutAddLi = parentModRef
      .parent()
      .find("li")
      .not(".addNewSubMob");

    allItemsWithoutAddLi.each(function () {
      if (updateItemId && parentModRef.text() == $(this).text()) {
        currentPriority = $(this).index() + 1;
      }
      if (!updateItemId) currentPriority++;
    });

    if (updateItemId) parentMod = parentModRef.attr("parent-module-name");

    $(this).text("Saving");
    $(this).attr("disabled", true);

    $("#newSubModForm").ajaxSubmit({
      type: "POST",
      url: "/admin/Admin/SaveSubMod",
      data: {
        priority: currentPriority,
        item_id: updateItemId,
        parent: parentMod,
        _token: $('[name="csrf_token"]').attr("content"),
      },
      success: function (response) {
        if (response.code == 200) {
          $("#notifDiv").fadeIn();
          $("#notifDiv").css("background", "green");
          $("#notifDiv").text("Sub Module Settings Saved");
          setTimeout(() => {
            $("#notifDiv").fadeOut();

            location.reload();
          }, 1000);
        } else {
          // Handle the case when there is an error in the response
          console.error(response.message);
        }

        $(this).text("Save");
        $(this).removeAttr("disabled");
      }.bind($(this)),
      error: function (error) {
        // Handle AJAX error
        console.error(error);

        $(this).text("Save");
        $(this).removeAttr("disabled");
      }.bind($(this)),
    });
  });

  $(document).on("click", ".close_sidebar", function () {
    closeSidebar();
  });
  $(document).on("click", "#saveParentMod", function () {
    let updateItemId = null;
    let currentPriority = $(".parentMod").length + 1;

    $(this).text("Saving");
    $(this).attr("disabled", true);

    $("#newParentModForm").ajaxSubmit({
      type: "POST",
      url: "/admin/Admin/SaveParentMod",
      data: {
        _token: $('[name="csrf_token"]').attr("content"),
        priority: currentPriority,
      },
      success: function (response) {
        $(this).text("Save");
        $(this).attr("disabled", false);
        if (response.code == 200) {
          $("#notifDiv").fadeIn();
          $("#notifDiv").css("background", "green");
          $("#notifDiv").text("Parent Module Settings Saved");
          setTimeout(() => {
            $("#notifDiv").fadeOut();
            location.reload();
          }, 1000);
          $(".close").click();
        } else {
          $("#notifDiv").fadeIn();
          $("#notifDiv").css("background", "red");
          $("#notifDiv").text("Unable to Save Parent Module Settings");
          setTimeout(() => {
            $("#notifDiv").fadeOut();
          }, 3000);
        }
        $(this).text("Save");
        $(this).removeAttr("disabled");
      }.bind($(this)),
    });
  });

  // $(document).on("click", ".addNewSubMob", function (e) {
  //     $('.openSubModModal').click();
  //     // if (e.target.classList.contains("savePriorityList") || e.target.classList.contains("deleteParentMod"))
  //     //     return;

  //     parentModRef = $(this);
  //     $('#saveParentMod').hide();
  //     $('#saveNewSubMod').show();
  //     $(".openSubModModal").click();
  //     $('#newSubModForm').show();
  //     $(".newSubModForm input").val("");
  // });

  // $(document).on("click", ".editSubNavItem", function (e) {
  //     parentModRef = $(this);
  //     $('#saveParentMod').hide();
  //     $('#saveNewSubMod').show();
  //     editSubNavItem = allControllersData.find(x => x.id == $(this).attr('item-id'));
  //     $(".openSubModModal").click();
  //     $('#newSubModForm').show();
  //     $("[name='module_name']").val(editSubNavItem.sub_module ? editSubNavItem.sub_module : editSubNavItem.made_up_name);
  //     $("[name='route']").val(editSubNavItem.controller);
  //     $("[name='made_up_name']").val(editSubNavItem.made_up_name);
  //     $("[name='show_in_sub_menu']").val(editSubNavItem.show_in_sub_menu);

  //     setTimeout(() => {
  //         $("[name='module_name']").focus();
  //         $("[name='route']").focus();
  //         $("[name='made_up_name']").focus();
  //     }, 500);
  // });

  $(document).on("click", ".addNewParentMod", function (e) {
    $(".openSubModModal").click();
    $("#exampleModalLabel").text("Parent Module Settings");
    $("#newSubModForm").hide();
    $("#newParentModForm").show();

    $('[name="parent_op"]').val("add");

    $('[name="parent_module_name_update"]').remove();

    $(".newParentModForm input").val("");
    $("#saveParentMod").show();
    $("#saveNewSubMod").hide();
  });

  $(document).on("click", ".saveParentPriorityList", function (e) {
    let priorityList = [];
    let priority = 1;
    $(".parentMod").each(function () {
      priorityList.push({
        module: $(this).attr("value"),
        priority: priority++,
      });
    });
    $(this).text("Saving..");
    $.ajax({
      type: "POST",
      url: "/admin/Admin/UpdateParentModPriority",
      data: {
        _token: $('[name="csrf_token"]').attr("content"),
        data: priorityList,
      },
      success: function (response) {
        $(this).text("Saved");
        $("#notifDiv").fadeIn();
        $("#notifDiv").css("background", "green");
        $("#notifDiv").text("Parent Module Priority Saved");
        setTimeout(() => {
          $("#notifDiv").fadeOut();
          location.reload();
        }, 1000);
      },
    });
  });

  $("#menuClose, .overlay-blure").on("click", function () {
    $("#sidebarblue").removeClass("menuShow");
    $("#content-wrapper").removeClass("blur-div");
    $("body").removeClass("no-scroll");
  });

  $("#modalShow").on("click", function () {
    $("#sidebarblue").addClass("menuShow");
    $("#content-wrapper").addClass("blur-div");
    $("body").addClass("no-scroll");
  });
}


function navItemsScript() { 
  Lang.setLocale(activeLang);  
  const parentModules = [
    ...new Set(
      allControllersData
        .filter((x) => x.show_in_sidebar == 1)
        .map((item) => item.parent_module)
    ),
  ];
  currentPageRight = false;
  let childModules = null;
  $(".new_nav").empty();
  subNavItems.push({
    parent: "search",
    child: [],
  });

  parentModules.forEach((element, index) => {
    var access_found = true;

    if (element == "Profit and Loss") {
      if (userDesignation != 1) {
        access_found = false;
      }
    }
    if (!access_found) return;

    let childMods = {
      parent: "",
      child: [],
    };
    childModules = allControllersData.filter((x) => x.parent_module == element);
    if (childModules.length)
      childModules.sort(
        (a, b) => a.sub_module_priority - b.sub_module_priority
      );

    let anyChildRight = false;

    childModules.forEach((child) => {
      
      if (rightsGiven.includes(child.controller)) {
         
        if (child.controller != "admin/home" && child.controller != "admin/Profile") {
          anyChildRight = true;
          console.log('second allowed',child.controller);
          if (child.show_in_sub_menu) {
            childMods.child.push(
              `<li> <a name="${element}" attr-name="${element}" href="/${
                child.controller
              }" class="openSubMenu">
                       ${
                         child.sub_module
                           ? Lang.get(`fields.`+child.sub_module.toLowerCase().replace(/\s+/g, '_'))
                           :  Lang.get(`fields.`+child.made_up_name.toLowerCase().replace(/\s+/g, '_'))  
                       }</a> </li>`
            );
          }
        } Lang.get(`fields.`+modifiedElement)
      }
    });
    console.log(currentSegment,anyChildRight,rightsGiven);
    if (
      rightsGiven.includes('admin/'+currentSegment) || rightsGiven.includes(currentSegment)||
      currentSegment == "home" ||
      currentSegment == "Profile" ||
      currentSegment == "/"
    ) {
      currentPageRight = true;
    }
    if (!anyChildRight) return;

    childMods.parent = element;
    subNavItems.push(childMods);
    if (element != "/admin/Dashboard" && element != "admin/home" && element != "admin/Profile") {
      console.log(messages,'testfasfdasdf');
      var modifiedElement = element.toLowerCase().replace(/\s+/g, '_')
      var actionsTranslation = Lang.get(`fields.`+modifiedElement);  
  
      $(".new_nav").append(
        `<li class="arrow">
                    <div class="iocn-link">
                        <a attr-name="${element}" class="openSubMenu" > 
                        <img src="${is_web.is_web ? '/storage/menu_icons/' + childModules[0].logo : '/menu_icons/' + childModules[0].logo}" alt="${element} Icon">
                            <span class="link_name">${actionsTranslation}</span>
                        </a>
                    </div>
                    <ul class="sub-menu ${element}"> 
                        ${childMods.child.map((item) => `${item}`).join("")}
                    </ul>
                </li>`
      );
    }
  });
  console.log(currentPageRight);
  if (currentPageRight) {
    renderPage();
    $(document).ready(function () {});
  } else {
    $(".btn-close").hide();
    $("#parentModulesUl").empty();
    $(".wrapper").show();
    $(".close").remove();
    $(".modal-title").text("403 Forbidden");
    $(".container-div-bl").addClass("blur-div");
    $(".modal").css("pointer-events", "none");
    $(".modal-header").css("justify-content", "center");
    $(".modal-custom-text").html(
      `<div style="text-align: center">You are not authorized to view this page. Please click here to go to <a href="/home">home</a></div>`
    );
    $(".modal-footer").hide();
    $("#hidden_btn_to_open_modal").click();
    $(".loader-page").hide();
    return;
  }
}

$(".offcanvas-backdrop").on("click", function () {
  closeSidebar();
});

function showNotification(message, color) {
  var notifDiv = $("#notifDiv");
  notifDiv.fadeIn().css("background", color).text(message);
  setTimeout(() => notifDiv.fadeOut(), 3000);
}

function openSidebar() {
  setTimeout(() => {
    isSidebarOpen = true;
  }, 200);
  // $(".overlay").addClass("active");
  // $(".collapse.in").toggleClass("in");
  $("#access-right").addClass("show");
  $("#contentContainerDiv").addClass("blur-div");
  $("body").addClass("no-scroll");
  // $(".sticky-footer").addClass("blur-div");
  // $(".overlay-for-sidebar").css("display", "block");
}
$(".scroll-y").mCustomScrollbar({
  axis: "y",
  theme: "dark-thick",
  advanced: {
    autoExpandHorizontalScroll: true,
  },
  callbacks: {
    onInit: function () {
      $(".mCSB_container").addClass("sortable");
      $(".sortable").sortable();
    },
  },
});

function initializeCKEditor(editorId, languageCode, isRTL, pageName = null) {
  var format_tags_shows = "p;h1;h2;h3;h4;h5;h6;pre;div";
  var removeButtonsPlugin =
    "Radio,SelectionField,Find,CopyFormatting,divcontainer,SelectAll,HiddenField,SpecialChar,ShowBlocks,PageBreak,ExportPdf,Cut,Copy,Paste,PasteText,PasteFromWord,Iframe,Language,Subscript,Superscript,Flash,Table,HorizontalRule,Smiley,-,Save,NewPage,DocProps,Preview,Print,Templates,Maximize,Form,TextField,Textarea,RadioButton,Checkbox";
  if (pageName == `${editorId}-about` && pageName != null) {
    format_tags_shows = "p";
    removeButtonsPlugin +=
      "Underline,Strike,Subscript,Superscript,NumberedList,Replace,BulletedList,Checkbox,Button,ImageButton,Outdent,Indent,Blockquote,CreateDiv,JustifyLeft,JustifyCenter,JustifyRight,JustifyBlock,Font,FontSize,TextColor,BGColor,Link,Unlink,Anchor,Image,Table,HorizontalRule,SpecialChar,Source,Maximize,ShowBlocks,About";
  }
  CKEDITOR.replace(editorId, {
    height: "300px",
    toolbar: "Classic",
    removePlugins: "blockquote,about,TextField",
    toolbarStartupExpanded: false,
    contentsCss: ["../css/menu.css?v=6.4"],
    format_tags: format_tags_shows,
    language: languageCode,
    contentsLangDirection: isRTL ? "rtl" : "ltr",
    removeButtons: removeButtonsPlugin,
  });
  CKFinder.setupCKEditor(CKEDITOR.instances[editorId]);
}

function closeSidebar() {
  setTimeout(() => {
    isSidebarOpen = false;
  }, 200);

  // $("#performaPreferences").removeClass("active");
  // $(".overlay").removeClass("active");
  $("#contentContainerDiv").removeClass("blur-div");
  // $(".sticky-footer").removeClass("blur-div");
  // $(".overlay-for-sidebar").css("display", "none");
  $("body").removeClass("no-scroll");
  $("#access-right").removeClass("show");
  $(".offcanvas-backdrop").removeClass("show");
}
// $(document).on('click', function(event) {
//     var $target = $(event.target);
//     if (!$target.closest('#access-right').length && !$target.closest('.sidebar-toggle').length) {
//         closeSidebar();
//     }
// });

// function openSubNav() {
//     $("#_subNav-id").addClass("active");
//     $("#content-wrapper").addClass("blur-div");
//     $("body").addClass("no-scroll");
// }

// function closeSubNav() {
//     $("#_subNav-id").removeClass("active");
//     $("#content-wrapper").removeClass("blur-div");
//     $("body").removeClass("no-scroll");
// }

function fetchSiteSearchReasult(str) {
  return new Promise((resolve, reject) => {
    $.ajax({
      type: "GET",
      url: "/admin/GetSiteSearchResult/" + str,
      success: function (response) {
        $(".SearchList").empty();
        $("#tblLoader_search").hide();
        var response = JSON.parse(response);
        var counter = 0;
        $.map(response.data, function (v, i) {
          if (v.length > 0) {
            $(".SearchList").append(`<ul id="${counter}"><h3>${i}</h3></ul>`);
            v.map(function (x) {
              $(`#${counter}`).append(
                `<li> <a href="/client-view/${
                  x.id
                }"><img src="/images/access-right-icon.svg" alt="">  ${
                  x.first_name ? x.first_name : ""
                } ${x.middle_name ? x.middle_name : ""} ${
                  x.last_name ? x.last_name : ""
                } </a></li>`
              );
            });
          }
          counter++;
        });
      },
    });
  });
}

function ajaxer(url, type, payload) {
  return new Promise((resolve, reject) => {
    $.ajax({
      type: type,
      url: url,
      data: payload,
      success: function (response) {
        resolve(response);
      },
    });
  });
}

function formatUnicorn(str) {
  var str = str;
  if (arguments.length) {
    var t = typeof arguments[0];
    var key;
    var args =
      "string" === t || "number" === t
        ? Array.prototype.slice.call(arguments)
        : arguments[0];

    for (key in args) {
      str = str.replace(new RegExp("\\{" + key + "\\}", "gi"), args[key]);
    }
  }

  return str;
}

var messages = {
  orders: {
    created_by_heading:
      "<span class='blue-text'>Follow Up:</span> New Sales Order",
    updated_by_heading:
      "<span class='blue-text'>Follow Up:</span> Sales Order Update",
    completed_by_heading:
      "<span class='blue-text'>Follow Up:</span> Sales Order Complete",
    processed_by_heading:
      "<span class='blue-text'>Follow Up:</span> Sales Order Dispatch",
    created_by:
      "{created_by} has created a New Sales Order for {customer_name} Worth {currency} {total_amount} Order # <a href='/OrderManagement'>{id}</a>",
    updated_by:
      "{updated_by} has updated Sales Order # <a href='/OrderManagement'>{id}</a> for {customer_name} worth {currency} {total_amount}",
    completed_by:
      "{completed_by} has completed the Sales Order # <a href='/OrderManagement'>{id}</a> for {customer_name} worth {currency} {total_amount}",
    processed_by:
      "{processed_by} has created a full dispatch for Sales Order # <a href='/OrderManagement'>{id}</a> for {customer_name} worth {currency} {total_amount}",
  },
  items: {
    created_by_heading: "<span class='blue-text'>Follow Up:</span> New Variant",
    updated_by_heading:
      "<span class='blue-text'>Follow Up:</span> Update Variant",
    created_by:
      "{created_by} has created a new Item <a href='/ProductItems/{product_sku}'>{name}</a> for {product_name}",
    updated_by:
      "{updated_by} has updated Item <a href='/ProductItems/{product_sku}'>{name}</a> for {product_name}",
  },
  products: {
    created_by_heading: "<span class='blue-text'>Follow Up:</span> New Product",
    updated_by_heading:
      "<span class='blue-text'>Follow Up:</span> Update Product",
    created_by:
      "{created_at} has created a new Product<a href='/BrandProducts/{brand_id}'>{name}",
    updated_by:
      "{updated_by} has Updated Product <a href='/BrandProducts/{brand_id}'>{name}",
  },
};

function fetchActivities(
  filteredArray,
  date,
  startDate,
  EndDate,
  searchQuery = null
) {
  $(".all_activities").empty();
  return new Promise((resolve, reject) => {
    $.ajax({
      type: "GET",
      url: "/admin/GetActivities",
      data: {
        date: date,
        start_date: startDate,
        end_date: EndDate,
      },
      success: function (response) {
        $(".SearchList").empty();
        $("#tblLoader").hide();
        var response = JSON.parse(response);
        allActivities = response;

        if (selected_customer_id != 0) {
          renderDataAfterFilters(selected_customer_id, filteredArray);
        } else {
          if (filteredArray.includes("900")) {
            $.map(response.orders, function (v) {
              if (v.created_by) {
                $(".all_activities").append(`<li>
                                <div class="dateFollowUP">${v.created_at}</div>
                                <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                                <div class="timeline-info">
                                    <h4 class="emp-name">${v.created_by}</h4>
                                    <div class="historyDiv">
                                        <h5><span class="blue-text">Follow Up:</span> New Sales Order</h5>
                                        <p class="_description">${v.created_by} has created a New Sales Order for ${v.customer_name} Worth ${v.currency}  ${v.total_amount} Order # <a style="color:#040725;" href="/OrderManagement">${v.id}</a></p>
                                    </div>
                                </div>
                                </li>`);
              }
              if (v.updated_by) {
                $(".all_activities").append(`<li>
                                <div class="dateFollowUP">${v.updated_at}</div>
                                <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                                <div class="timeline-info">
                                    <h4 class="update-emp-name">${v.updated_by}</h4>
                                    <div class="historyDiv">
                                        <h5><span class="blue-text">Follow Up:</span> Sales Order Update</h5>
                                        <p class="_description">${v.updated_by} has updated Sales Order # <a style="color:#040725;" href="/OrderManagement">${v.id}</a> for ${v.customer_name} worth ${v.currency} ${v.total_amount}</p>
                                    </div>
                                </div>
                                </li>`);
              }
              if (v.completed_by) {
                $(".all_activities").append(`<li>
                                <div class="dateFollowUP">${v.completed_at}</div>
                                <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                                <div class="timeline-info">
                                    <h4 class="processed-emp-name">${v.completed_by}</h4>
                                    <div class="historyDiv">
                                        <h5><span class="blue-text">Follow Up:</span> Sales Order Complete</h5>
                                        <p class="_description">${v.completed_by} has completed the Sales Order # <a style="color:#040725;" href="/OrderManagement">${v.id}</a> for ${v.customer_name} worth ${v.currency} ${v.total_amount}</p>
                                    </div>
                                </div>
                                </li>`);
              }
              if (v.processed_by) {
                $(".all_activities").append(`<li>
                                <div class="dateFollowUP">${v.processed_at}</div>
                                <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                                <div class="timeline-info">
                                    <h4 class="completed-emp-name">${v.processed_by}</h4>
                                    <div class="historyDiv">
                                        <h5><span class="blue-text">Follow Up:</span> Sales Order Dispatch</h5>
                                        <p class="_description">${v.processed_by} has created a full dispatch for Sales Order # <a style="color:#040725;" href="/OrderManagement">${v.id}</a> for ${v.customer_name} worth ${v.currency} ${v.total_amount}</p>
                                    </div>
                                </div>
                                </li>`);
              }
            });
          }

          if (filteredArray.includes("906")) {
            $.map(response.items, function (v) {
              if (v.created_by) {
                $(".all_activities").append(`<li>
                                <div class="dateFollowUP">${v.created_at}</div>
                                <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                                <div class="timeline-info">
                                    <h4 class="emp-name">${v.created_by}</h4>
                                    <div class="historyDiv">
                                        <h5><span class="blue-text">Follow Up:</span> New Variant</h5>
                                        <p class="_description">${v.created_by} has created a new Item <a style="color:#040725;" href="/ProductItems/${v.product_sku}">${v.name}</a> for ${v.product_name}</p>
                                    </div>
                                </div>
                                </li>`);
              }
              if (v.updated_by) {
                $(".all_activities").append(`<li>
                                <div class="dateFollowUP">${v.updated_at}</div>
                                <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                                <div class="timeline-info">
                                    <h4 class="update-emp-name">${v.updated_by}</h4>
                                    <div class="historyDiv">
                                        <h5><span class="blue-text">Follow Up:</span> Update Variant</h5>
                                        <p class="_description">${v.updated_by} has updated Item <a style="color:#040725;" href="/ProductItems/${v.product_sku}">${v.name}</a> for ${v.product_name}</p>
                                    </div>
                                </div>
                                </li>`);
              }
            });
          }

          if (filteredArray.includes("901")) {
            $.map(response.products, function (v) {
              if (v.created_by) {
                $(".all_activities").append(`<li>
                                <div class="dateFollowUP">${v.created_at}</div>
                                <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                                <div class="timeline-info">
                                    <h4 class="emp-name">${v.created_by}</h4>
                                    <div class="historyDiv">
                                        <h5><span class="blue-text">Follow Up:</span> New Product</h5>
                                        <p class="_description">${v.created_at} has created a new Product <a style="color:#040725;" href="/BrandProducts/${v.brand_id}">${v.name}</a></p>
                                    </div>
                                </div>
                                </li>`);
              }
              if (v.updated_by) {
                $(".all_activities").append(`<li>
                                <div class="dateFollowUP">${v.updated_at}</div>
                                <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                                <div class="timeline-info">
                                    <h4 class="update-emp-name">${v.updated_by}</h4>
                                    <div class="historyDiv">
                                        <h5><span class="blue-text">Follow Up:</span> Product Update</h5>
                                        <p class="_description">${v.updated_by} has Updated Product <a style="color:#040725;" href="/BrandProducts/${v.brand_id}">${v.name}</a></p>
                                    </div>
                                </div>
                                </li>`);
              }
            });
          }

          if (filteredArray.includes("902")) {
            $.map(response.customers, function (v) {
              if (v.created_by) {
                $(".all_activities").append(`<li>
                                <div class="dateFollowUP">${v.created_at}</div>
                                <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                                <div class="timeline-info">
                                    <h4 class="emp-name">${v.created_by}</h4>
                                    <div class="historyDiv">
                                        <h5><span class="blue-text">Follow Up:</span> New Customer</h5>
                                        <p class="_description">${v.created_by} has created a new customer <a style="color:#040725;" href="/Correspondence/create/${v.id}">${v.company_name}</a> from ${v.country}</p>
                                    </div>
                                </div>
                                </li>`);
              }
              if (v.updated_by) {
                $(".all_activities").append(`<li>
                                <div class="dateFollowUP">${v.updated_at}</div>
                                <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                                <div class="timeline-info">
                                    <h4 class="update-emp-name">${v.updated_by}</h4>
                                    <div class="historyDiv">
                                        <h5><span class="blue-text">Follow Up:</span> Update Customer</h5>
                                        <p class="_description">${v.updated_by} has updated customer details <a style="color:#040725;" href="/Correspondence/create/${v.id}">${v.company_name}</a> from ${v.country}</p>
                                    </div>
                                </div>
                                </li>`);
              }
            });
          }

          if (filteredArray.includes("907")) {
            $.map(response.pocs, function (v) {
              if (v.created_by) {
                $(".all_activities").append(`<li>
                                <div class="dateFollowUP">${v.created_at}</div>
                                <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                                <div class="timeline-info">
                                    <h4 class="emp-name">${v.created_by}</h4>
                                    <div class="historyDiv">
                                        <h5><span class="blue-text">Follow Up:</span> New POC</h5>
                                        <p class="_description">${v.created_by} has added a new POC ${v.first_name} for <a style="color:#040725;" href="/Correspondence/create/${v.customer_id}">${v.customer_name}</a> from ${v.cust_country}</p>
                                    </div>
                                </div>
                                </li>`);
              }
              if (v.updated_by) {
                $(".all_activities").append(`<li>
                                <div class="dateFollowUP">${v.updated_at}</div>
                                <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                                <div class="timeline-info">
                                    <h4 class="update-emp-name">${v.updated_by}</h4>
                                    <div class="historyDiv">
                                        <h5><span class="blue-text">Follow Up:</span> Update POC</h5>
                                        <p class="_description">${v.updated_by} has updated POC details of ${v.first_name} for <a style="color:#040725;" href="/Correspondence/create/${v.customer_id}">${v.customer_name}</a> from ${v.cust_country}</p>
                                    </div>
                                </div>
                                </li>`);
              }
            });
          }

          if (filteredArray.includes("903")) {
            $.map(response.suppliers, function (v) {
              if (v.created_by) {
                $(".all_activities").append(`<li>
                                <div class="dateFollowUP">${v.created_at}</div>
                                <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                                <div class="timeline-info">
                                    <h4 class="emp-name">${v.created_by}</h4>
                                    <div class="historyDiv">
                                        <h5><span class="blue-text">Follow Up:</span> New Supplier</h5>
                                        <p class="_description">${v.created_by} has created a new supplier <a style="color:#040725;" href="/Suppliers">${v.company_name}</a></p>
                                    </div>
                                </div>
                                </li>`);
              }
              if (v.updated_by) {
                $(".all_activities").append(`<li>
                                <div class="dateFollowUP">${v.updated_at}</div>
                                <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                                <div class="timeline-info">
                                    <h4 class="update-emp-name">${v.updated_by}</h4>
                                    <div class="historyDiv">
                                        <h5><span class="blue-text">Follow Up:</span> Update Supplier</h5>
                                        <p class="_description">${v.updated_by} has updated supplier details <a style="color:#040725;" href="/Suppliers">${v.company_name}</a></p>
                                    </div>
                                </div>
                                </li>`);
              }
            });
          }

          if (filteredArray.includes("908")) {
            $.map(response.forwarders, function (v) {
              if (v.created_by) {
                $(".all_activities").append(`<li>
                                <div class="dateFollowUP">${v.created_at}</div>
                                <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                                <div class="timeline-info">
                                    <h4 class="emp-name">${v.created_by}</h4>
                                    <div class="historyDiv">
                                        <h5><span class="blue-text">Follow Up:</span> New Forwarder</h5>
                                        <p class="_description">${v.created_by} has created a new Forwarding Company <a style="color:#040725;" href="/forwarder">${v.company_name}</a></p>
                                    </div>
                                </div>
                                </li>`);
              }
              if (v.updated_by) {
                $(".all_activities").append(`<li>
                                <div class="dateFollowUP">${v.updated_at}</div>
                                <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                                <div class="timeline-info">
                                    <h4 class="update-emp-name">${v.updated_by}</h4>
                                    <div class="historyDiv">
                                        <h5><span class="blue-text">Follow Up:</span> Update Forwarder</h5>
                                        <p class="_description">${v.updated_by} has updated Forwarding <a style="color:#040725;" href="/forwarder">${v.company_name}</a></p>
                                    </div>
                                </div>
                                </li>`);
              }
            });
          }

          if (filteredArray.includes("904")) {
            $.map(response.shippers, function (v) {
              if (v.created_by) {
                $(".all_activities").append(`<li>
                                <div class="dateFollowUP">${v.created_at}</div>
                                <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                                <div class="timeline-info">
                                    <h4 class="emp-name">${v.created_by}</h4>
                                    <div class="historyDiv">
                                        <h5><span class="blue-text">Follow Up:</span> New Shipping Company</h5>
                                        <p class="_description">${v.created_by} has created a new Shipping Company <a style="color:#040725;" href="/Shipping">${v.company_name}</a></p>
                                    </div>
                                </div>
                                </li>`);
              }
              if (v.updated_by) {
                $(".all_activities").append(`<li>
                                <div class="dateFollowUP">${v.updated_at}</div>
                                <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                                <div class="timeline-info">
                                    <h4 class="update-emp-name">${v.updated_by}</h4>
                                    <div class="historyDiv">
                                        <h5><span class="blue-text">Follow Up:</span> Update Shipping Company</h5>
                                        <p class="_description">${v.updated_by} has updated Shipping Company <a style="color:#040725;" href="/Shipping">${v.company_name}</a></p>
                                    </div>
                                </div>
                                </li>`);
              }
            });
          }

          if (filteredArray.includes("905")) {
            $.map(response.tasks, function (v) {
              if (v.created_by) {
                $(".all_activities").append(`<li>
                                <div class="dateFollowUP">${v.created_at}</div>
                                <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                                <div class="timeline-info">
                                    <h4 class="emp-name">${v.created_by}</h4>
                                    <div class="historyDiv">
                                        <h5><span class="blue-text">Follow Up:</span> New Task</h5>
                                        <p class="_description">${v.created_by} has created a new Task</p>
                                    </div>
                                </div>
                                </li>`);
              }
              if (v.updated_by) {
                $(".all_activities").append(`<li>
                                <div class="dateFollowUP">${v.updated_at}</div>
                                <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                                <div class="timeline-info">
                                    <h4 class="update-emp-name">${v.updated_by}</h4>
                                    <div class="historyDiv">
                                        <h5><span class="blue-text">Follow Up:</span> Update Task</h5>
                                        <p class="_description">${v.updated_by} has updated Task</p>
                                    </div>
                                </div>
                                </li>`);
              }

              if (v.completed_by) {
                $(".all_activities").append(`<li>
                                <div class="dateFollowUP">${v.completed_at}</div>
                                <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                                <div class="timeline-info">
                                    <h4 class="update-emp-name">${v.completed_by}</h4>
                                    <div class="historyDiv">
                                        <h5><span class="blue-text">Follow Up:</span> Task Completed</h5>
                                        <p class="_description">${v.completed_by} has Completed a Task</p>
                                    </div>
                                </div>
                                </li>`);
              }
            });
          }

          if (filteredArray.includes("909")) {
            $.map(response.employees, function (v) {
              if (v.created_by) {
                $(".all_activities").append(`<li>
                                <div class="dateFollowUP">${v.created_at}</div>
                                <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                                <div class="timeline-info">
                                    <h4 class="emp-name">${v.created_by}</h4>
                                    <div class="historyDiv">
                                        <h5><span class="blue-text">Follow Up:</span> New Employee</h5>
                                        <p class="_description">${v.created_by} has added a new Employee <a style="color:#040725;" href="/register">${v.name}</a></p>
                                    </div>
                                </div>
                                </li>`);
              }
              if (v.updated_by) {
                $(".all_activities").append(`<li>
                                <div class="dateFollowUP">${v.updated_at}</div>
                                <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                                <div class="timeline-info">
                                    <h4 class="update-emp-name">${v.updated_by}</h4>
                                    <div class="historyDiv">
                                        <h5><span class="blue-text">Follow Up:</span> Update Employee</h5>
                                        <p class="_description">${v.updated_by} has updated employee <a style="color:#040725;" href="/register">${v.name}</a></p>
                                    </div>
                                </div>
                                </li>`);
              }
            });
          }

          if (filteredArray.includes("910")) {
            $.map(response.payments, function (v) {
              if (v.created_by) {
                $(".all_activities").append(`<li>
                                <div class="dateFollowUP">${v.created_at}</div>
                                <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                                <div class="timeline-info">
                                    <h4 class="emp-name">${v.created_by}</h4>
                                    <div class="historyDiv">
                                        <h5><span class="blue-text">Follow Up:</span> Payment Created</h5>
                                        <p class="_description">New Payment Created</p>
                                    </div>
                                </div>
                                </li>`);
              }
              if (v.updated_by) {
                $(".all_activities").append(`<li>
                                <div class="dateFollowUP">${v.updated_at}</div>
                                <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                                <div class="timeline-info">
                                    <h4 class="update-emp-name">${v.updated_by}</h4>
                                    <div class="historyDiv">
                                        <h5><span class="blue-text">Follow Up:</span> Payment Updated</h5>
                                        <p class="_description"> Payment Updated</p>
                                    </div>
                                </div>
                                </li>`);
              }
            });
          }
        }
        if (searchQuery) {
          $(".dateFollowUP").mark(searchQuery);
          $(".emp-name").mark(searchQuery);
          $(".update-emp-name").mark(searchQuery);
          $("._description").mark(searchQuery);
          $(".processed-emp-name").mark(searchQuery);
          $(".completed-emp-name").mark(searchQuery);
        }
      },
    });
  });
}
 
function renderDataAfterFilters(cust, filteraArr, searchQuery = null) {
  //900 = order
  //901 = product
  //902 = customer
  //903 = supplier
  //904 = shipper
  //905 = task
  //906 = Items
  //907 = POC
  //908 = Forwarder
  //909 = Employee
  //910 = Payment
  $(".all_activities").empty();
  if (cust == 0) {
    //fetchActivities(filteraArr);
    if (filteraArr.includes("900")) {
      $.map(allActivities.orders, function (v) {
        if (v.created_by) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.created_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="emp-name">${v.created_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> New Sales Order</h5>
                            <p class="_description">${v.created_by} has created a New Sales Order for ${v.customer_name} Worth ${v.currency}  ${v.total_amount} Order # <a style="color:#040725;" href="/OrderManagement">${v.id}</a></p>
                        </div>
                    </div>
                    </li>`);
        }
        if (v.updated_by) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.updated_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="update-emp-name">${v.updated_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Sales Order Update</h5>
                            <p class="_description">${v.updated_by} has updated Sales Order # <a style="color:#040725;" href="/OrderManagement">${v.id}</a> for ${v.customer_name} worth ${v.currency} ${v.total_amount}</p>
                        </div>
                    </div>
                    </li>`);
        }
        if (v.completed_by) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.completed_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="processed-emp-name">${v.completed_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Sales Order Complete</h5>
                            <p class="_description">${v.completed_by} has completed the Sales Order # <a style="color:#040725;" href="/OrderManagement">${v.id}</a> for ${v.customer_name} worth ${v.currency} ${v.total_amount}</p>
                        </div>
                    </div>
                    </li>`);
        }
        if (v.processed_by) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.processed_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="completed-emp-name">${v.processed_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Sales Order Dispatch</h5>
                            <p class="_description">${v.processed_by} has created a full dispatch for Sales Order # <astyle="color:#040725;"  href="/OrderManagement">${v.id}</a> for ${v.customer_name} worth ${v.currency} ${v.total_amount}</p>
                        </div>
                    </div>
                    </li>`);
        }
      });
    }

    if (filteraArr.includes("906")) {
      $.map(allActivities.items, function (v) {
        if (v.created_by) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.created_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="emp-name">${v.created_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> New Variant</h5>
                            <p class="_description">${v.created_by} has created a new Item <a style="color:#040725;" href="/ProductItems/${v.product_sku}">${v.name}</a> for ${v.product_name}</p>
                        </div>
                    </div>
                    </li>`);
        }
        if (v.updated_by) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.updated_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="update-emp-name">${v.updated_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Update Variant</h5>
                            <p class="_description">${v.updated_by} has updated Item <a style="color:#040725;" href="/ProductItems/${v.product_sku}">${v.name}</a> for ${v.product_name}</p>
                        </div>
                    </div>
                    </li>`);
        }
      });
    }

    if (filteraArr.includes("901")) {
      $.map(allActivities.products, function (v) {
        if (v.created_by) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.created_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="emp-name">${v.created_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> New Product</h5>
                            <p class="_description">${v.created_at} has created a new Product <a style="color:#040725;" href="/BrandProducts/${v.brand_id}">${v.name}</a></p>
                        </div>
                    </div>
                    </li>`);
        }
        if (v.updated_by) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.updated_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="update-emp-name">${v.updated_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Product Update</h5>
                            <p class="_description">${v.updated_by} has Updated Product <a style="color:#040725;" href="/BrandProducts/${v.brand_id}">${v.name}</a></p>
                        </div>
                    </div>
                    </li>`);
        }
      });
    }

    if (filteraArr.includes("902")) {
      $.map(allActivities.customers, function (v) {
        if (v.created_by) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.created_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="emp-name">${v.created_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> New Customer</h5>
                            <p class="_description">${v.created_by} has created a new customer <a style="color:#040725;" href="/Correspondence/create/${v.id}">${v.company_name}</a> from ${v.country}</p>
                        </div>
                    </div>
                    </li>`);
        }
        if (v.updated_by) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.updated_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="update-emp-name">${v.updated_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Update Customer</h5>
                            <p class="_description">${v.updated_by} has updated customer details <a style="color:#040725;" href="/Correspondence/create/${v.id}">${v.company_name}</a> from ${v.country}</p>
                        </div>
                    </div>
                    </li>`);
        }
      });
    }

    if (filteraArr.includes("907")) {
      $.map(allActivities.pocs, function (v) {
        if (v.created_by) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.created_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="emp-name">${v.created_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> New POC</h5>
                            <p class="_description">${v.created_by} has added a new POC ${v.first_name} for <astyle="color:#040725;"  href="/Correspondence/create/${v.customer_id}">${v.customer_name}</a> from ${v.cust_country}</p>
                        </div>
                    </div>
                    </li>`);
        }
        if (v.updated_by) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.updated_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="update-emp-name">${v.updated_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Update POC</h5>
                            <p class="_description">${v.updated_by} has updated POC details of ${v.first_name} for <astyle="color:#040725;"  href="/Correspondence/create/${v.customer_id}">${v.customer_name}</a> from ${v.cust_country}</p>
                        </div>
                    </div>
                    </li>`);
        }
      });
    }

    if (filteraArr.includes("903")) {
      $.map(allActivities.suppliers, function (v) {
        if (v.created_by) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.created_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="emp-name">${v.created_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> New Supplier</h5>
                            <p class="_description">${v.created_by} has created a new supplier <a style="color:#040725;" href="/Suppliers">${v.company_name}</a></p>
                        </div>
                    </div>
                    </li>`);
        }
        if (v.updated_by) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.updated_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="update-emp-name">${v.updated_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Update Supplier</h5>
                            <p class="_description">${v.updated_by} has updated supplier details <a style="color:#040725;" href="/Suppliers">${v.company_name}</a></p>
                        </div>
                    </div>
                    </li>`);
        }
      });
    }

    if (filteraArr.includes("908")) {
      $.map(allActivities.forwarders, function (v) {
        if (v.created_by) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.created_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="emp-name">${v.created_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> New Forwarder</h5>
                            <p class="_description">${v.created_by} has created a new Forwarding Company <a style="color:#040725;" href="/forwarder">${v.company_name}</a></p>
                        </div>
                    </div>
                    </li>`);
        }
        if (v.updated_by) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.updated_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="update-emp-name">${v.updated_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Update Forwarder</h5>
                            <p class="_description">${v.updated_by} has updated Forwarding <a style="color:#040725;" href="/forwarder">${v.company_name}</a></p>
                        </div>
                    </div>
                    </li>`);
        }
      });
    }

    if (filteraArr.includes("904")) {
      $.map(allActivities.shippers, function (v) {
        if (v.created_by) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.created_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="emp-name">${v.created_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> New Shipping Company</h5>
                            <p class="_description">${v.created_by} has created a new Shipping Company <a style="color:#040725;" href="/Shipping">${v.company_name}</a></p>
                        </div>
                    </div>
                    </li>`);
        }
        if (v.updated_by) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.updated_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="update-emp-name">${v.updated_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Update Shipping Company</h5>
                            <p class="_description">${v.updated_by} has updated Shipping Company <a style="color:#040725;" href="/Shipping">${v.company_name}</a></p>
                        </div>
                    </div>
                    </li>`);
        }
      });
    }

    if (filteraArr.includes("905")) {
      $.map(allActivities.tasks, function (v) {
        if (v.created_by) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.created_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="emp-name">${v.created_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> New Task</h5>
                            <p class="_description">${v.created_by} has created a new Task</p>
                        </div>
                    </div>
                    </li>`);
        }
        if (v.updated_by) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.updated_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="update-emp-name">${v.updated_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Update Task</h5>
                            <p class="_description">${v.updated_by} has updated Task</p>
                        </div>
                    </div>
                    </li>`);
        }

        if (v.completed_by) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.completed_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="update-emp-name">${v.completed_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Task Completed</h5>
                            <p class="_description">${v.completed_by} has Completed a Task</p>
                        </div>
                    </div>
                    </li>`);
        }
      });
    }

    if (filteraArr.includes("909")) {
      $.map(allActivities.employees, function (v) {
        if (v.created_by) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.created_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="emp-name">${v.created_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> New Employee</h5>
                            <p class="_description">${v.created_by} has added a new Employee <a style="color:#040725;" href="/register">${v.name}</a></p>
                        </div>
                    </div>
                    </li>`);
        }
        if (v.updated_by) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.updated_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="update-emp-name">${v.updated_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Update Employee</h5>
                            <p class="_description">${v.updated_by} has updated employee <a style="color:#040725;" href="/register">${v.name}</a></p>
                        </div>
                    </div>
                    </li>`);
        }
      });
    }

    if (filteraArr.includes("910")) {
      $.map(allActivities.payments, function (v) {
        if (v.created_by) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.created_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="emp-name">${v.created_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Payment Created</h5>
                            <p class="_description">New Payment Created</p>
                        </div>
                    </div>
                    </li>`);
        }
        if (v.updated_by) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.updated_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="update-emp-name">${v.updated_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Payment Updated</h5>
                            <p class="_description"> Payment Updated</p>
                        </div>
                    </div>
                    </li>`);
        }
      });
    }
  } else {
    //orders
    if (filteraArr.includes("900")) {
      $.map(allActivities.orders, function (v) {
        if (v.created_by && v.created_by_id == cust) {
          $(".all_activities").append(`<li>
                        <div class="dateFollowUP">${v.created_at}</div>
                        <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                        <div class="timeline-info">
                            <h4 class="emp-name">${v.created_by}</h4>
                            <div class="historyDiv">
                                <h5><span class="blue-text">Follow Up:</span> New Sales Order</h5>
                                <p class="_description">${v.created_by} has created a New Sales Order for ${v.customer_name} Worth ${v.currency}  ${v.total_amount} Order # <a style="color:#040725;" href="/OrderManagement">${v.id}</a></p>
                            </div>
                        </div>
                        </li>`);
        }
        if (v.updated_by && v.updated_by_id == cust) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.updated_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="update-emp-name">${v.updated_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Sales Order Update</h5>
                            <p class="_description">${v.updated_by} has updated Sales Order # <a style="color:#040725;" href="/OrderManagement">${v.id}</a> for ${v.customer_name} worth ${v.currency} ${v.total_amount}</p>
                        </div>
                    </div>
                    </li>`);
        }
        if (v.completed_by && v.completed_by_id == cust) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.completed_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="processed-emp-name">${v.completed_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Sales Order Complete</h5>
                            <p class="_description">${v.completed_by} has completed the Sales Order # <a style="color:#040725;" href="/OrderManagement">${v.id}</a> for ${v.customer_name} worth ${v.currency} ${v.total_amount}</p>
                        </div>
                    </div>
                    </li>`);
        }
        if (v.processed_by && v.processed_by_id == cust) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.processed_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="completed-emp-name">${v.processed_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Sales Order Dispatch</h5>
                            <p class="_description">${v.processed_by} has created a full dispatch for Sales Order # <astyle="color:#040725;"  href="/OrderManagement">${v.id}</a> for ${v.customer_name} worth ${v.currency} ${v.total_amount}</p>
                        </div>
                    </div>
                    </li>`);
        }
      });
    }

    //Item
    if (filteraArr.includes("906")) {
      $.map(allActivities.items, function (v) {
        if (v.created_by && v.created_by_id == cust) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.created_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="emp-name">${v.created_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> New Variant</h5>
                            <p class="_description">${v.created_by} has created a new Item <a style="color:#040725;" href="/ProductItems/${v.product_sku}">${v.name}</a> for ${v.product_name}</p>
                        </div>
                    </div>
                    </li>`);
        }
        if (v.updated_by && v.updated_by_id == cust) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.updated_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="update-emp-name">${v.updated_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Update Variant</h5>
                            <p class="_description">${v.updated_by} has updated Item <a style="color:#040725;" href="/ProductItems/${v.product_sku}">${v.name}</a> for ${v.product_name}</p>
                        </div>
                    </div>
                    </li>`);
        }
      });
    }

    //products
    if (filteraArr.includes("901")) {
      $.map(allActivities.products, function (v) {
        if (v.created_by && v.created_by_id == cust) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.created_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="emp-name">${v.created_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> New Product</h5>
                            <p class="_description">${v.created_at} has created a new Product <a style="color:#040725;" href="/BrandProducts/${v.brand_id}">${v.name}</a></p>
                        </div>
                    </div>
                    </li>`);
        }
        if (v.updated_by && v.updated_by_id == cust) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.updated_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="update-emp-name">${v.updated_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Product Update</h5>
                            <p class="_description">${v.updated_by} has Updated Product <a style="color:#040725;" href="/BrandProducts/${v.brand_id}">${v.name}</a></p>
                        </div>
                    </div>
                    </li>`);
        }
      });
    }

    //Customer
    if (filteraArr.includes("902")) {
      $.map(allActivities.customers, function (v) {
        if (v.created_by && v.created_by_id == cust) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.created_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="emp-name">${v.created_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> New Customer</h5>
                            <p class="_description">${v.created_by} has created a new customer <a style="color:#040725;" href="/Correspondence/create/${v.id}">${v.company_name}</a> from ${v.country}</p>
                        </div>
                    </div>
                    </li>`);
        }
        if (v.updated_by && v.updated_by_id == cust) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.updated_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="update-emp-name">${v.updated_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Update Customer</h5>
                            <p class="_description">${v.updated_by} has updated customer details <a style="color:#040725;" href="/Correspondence/create/${v.id}">${v.company_name}</a> from ${v.country}</p>
                        </div>
                    </div>
                    </li>`);
        }
      });
    }

    //POC
    if (filteraArr.includes("907")) {
      $.map(allActivities.pocs, function (v) {
        if (v.created_by && v.created_by_id == cust) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.created_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="emp-name">${v.created_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> New POC</h5>
                            <p class="_description">${v.created_by} has added a new POC ${v.first_name} for <astyle="color:#040725;"  href="/Correspondence/create/${v.customer_id}">${v.customer_name}</a> from ${v.cust_country}</p>
                        </div>
                    </div>
                    </li>`);
        }
        if (v.updated_by && v.updated_by_id == cust) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.updated_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="update-emp-name">${v.updated_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Update POC</h5>
                            <p class="_description">${v.updated_by} has updated POC details of ${v.first_name} for <astyle="color:#040725;"  href="/Correspondence/create/${v.customer_id}">${v.customer_name}</a> from ${v.cust_country}</p>
                        </div>
                    </div>
                    </li>`);
        }
      });
    }

    //Supplier
    if (filteraArr.includes("903")) {
      $.map(allActivities.suppliers, function (v) {
        if (v.created_by && v.created_by_id == cust) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.created_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="emp-name">${v.created_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> New Supplier</h5>
                            <p class="_description">${v.created_by} has created a new supplier <a style="color:#040725;" href="/Suppliers">${v.company_name}</a></p>
                        </div>
                    </div>
                    </li>`);
        }
        if (v.updated_by && v.updated_by_id == cust) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.updated_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="update-emp-name">${v.updated_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Update Supplier</h5>
                            <p class="_description">${v.updated_by} has updated supplier details <a style="color:#040725;" href="/Suppliers">${v.company_name}</a></p>
                        </div>
                    </div>
                    </li>`);
        }
      });
    }

    //Forwarder
    if (filteraArr.includes("908")) {
      $.map(allActivities.forwarders, function (v) {
        if (v.created_by && v.created_by_id == cust) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.created_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="emp-name">${v.created_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> New Forwarder</h5>
                            <p class="_description">${v.created_by} has created a new Forwarding Company <a style="color:#040725;" href="/forwarder">${v.company_name}</a></p>
                        </div>
                    </div>
                    </li>`);
        }
        if (v.updated_by && v.updated_by_id == cust) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.updated_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="update-emp-name">${v.updated_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Update Forwarder</h5>
                            <p class="_description">${v.updated_by} has updated Forwarding <a style="color:#040725;" href="/forwarder">${v.company_name}</a></p>
                        </div>
                    </div>
                    </li>`);
        }
      });
    }

    //Shipper
    if (filteraArr.includes("904")) {
      $.map(allActivities.shippers, function (v) {
        if (v.created_by && v.created_by_id == cust) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.created_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="emp-name">${v.created_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> New Shipping Company</h5>
                            <p class="_description">${v.created_by} has created a new Shipping Company <a style="color:#040725;" href="/Shipping">${v.company_name}</a></p>
                        </div>
                    </div>
                    </li>`);
        }
        if (v.updated_by && v.updated_by_id == cust) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.updated_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="update-emp-name">${v.updated_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Update Shipping Company</h5>
                            <p class="_description">${v.updated_by} has updated Shipping Company <a style="color:#040725;" href="/Shipping">${v.company_name}</a></p>
                        </div>
                    </div>
                    </li>`);
        }
      });
    }

    //Tasks
    if (filteraArr.includes("905")) {
      $.map(allActivities.tasks, function (v) {
        if (v.created_by && v.created_by_id == cust) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.created_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="emp-name">${v.created_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> New Task</h5>
                            <p class="_description">${v.created_by} has created a new Task</p>
                        </div>
                    </div>
                    </li>`);
        }
        if (v.updated_by && v.updated_by_id == cust) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.updated_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="update-emp-name">${v.updated_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Update Task</h5>
                            <p class="_description">${v.updated_by} has updated Task </p>
                        </div>
                    </div>
                    </li>`);
        }

        if (v.completed_by && v.completed_by_id == cust) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.completed_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="update-emp-name">${v.completed_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Task Completed</h5>
                            <p class="_description">${v.completed_by} has completed a Task</p>
                        </div>
                    </div>
                    </li>`);
        }
      });
    }

    //Employee
    if (filteraArr.includes("909")) {
      $.map(allActivities.employees, function (v) {
        if (v.created_by && v.created_by_id == cust) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.created_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="emp-name">${v.created_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> New Employee</h5>
                            <p class="_description">${v.created_by} has added a new Employee <a style="color:#040725;" href="/register">${v.name}</a></p>
                        </div>
                    </div>
                    </li>`);
        }
        if (v.updated_by && v.updated_by_id == cust) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.updated_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="update-emp-name">${v.updated_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Update Employee</h5>
                            <p class="_description">${v.updated_by} has updated employee <a style="color:#040725;" href="/register">${v.name}</a></p>
                        </div>
                    </div>
                    </li>`);
        }
      });
    }

    //Payment
    if (filteraArr.includes("910")) {
      $.map(allActivities.payments, function (v) {
        if (v.created_by && v.created_by_id == cust) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.created_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="emp-name">${v.created_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Payment Created</h5>
                            <p class="_description">New Payment Created</p>
                        </div>
                    </div>
                    </li>`);
        }
        if (v.updated_by && v.updated_by_id == cust) {
          $(".all_activities").append(`<li>
                    <div class="dateFollowUP">${v.updated_at}</div>
                    <div class="timeline-icon"><img src="/images/avatar.svg" alt=""></div>
                    <div class="timeline-info">
                        <h4 class="update-emp-name">${v.updated_by}</h4>
                        <div class="historyDiv">
                            <h5><span class="blue-text">Follow Up:</span> Payment Updated</h5>
                            <p class="_description"> Payment Updated</p>
                        </div>
                    </div>
                    </li>`);
        }
      });
    }
  }
  if (searchQuery) {
    $(".dateFollowUP").mark(searchQuery);
    $(".emp-name").mark(searchQuery);
    $(".update-emp-name").mark(searchQuery);
    $("._description").mark(searchQuery);
    $(".processed-emp-name").mark(searchQuery);
    $(".completed-emp-name").mark(searchQuery);
  }
}

function renderTasksInTasksPage() {
  if (!allTasksCreated.length) {
    $(".tasksListTable tbody").html(
      '<tr><td colspan="8" style="text-align: center; line-height: 3; font-weight: bold; font-size: 14px">No tasks added yet </td> </tr>'
    );
    return;
  }
  $(".tasksListTable tbody").empty();
  allTasksCreated.forEach((x) => {
    let statusClass =
      x.task_status == "in-progress"
        ? "TS-InProgress"
        : x.task_status == "not-started"
        ? "TS-NotStarted"
        : x.task_status == "in-review"
        ? "TS-InReview"
        : x.task_status == "completed"
        ? "TS-Completed"
        : "TS-Cancelled";
    $(".tasksListTable tbody").append(`<tr>
        <td> ${moment(x.created_at, "YYYY-MM-DD HH:mm:ss").format(
          "YYYY-MM-DD"
        )} </td>
        <td> ${x.title} </td>
        <td> ${x.created_by} </td>
        <td> ${x.due_date} </td>
        <td>${moment(x.due_time, "hh:mm").format("h:mm A")}</td>
        <td>
            <span
                class="fa fa-flag Task${
                  x.task_priority ? titleCase(x.task_priority) : "Low"
                }"></span>${
      x.task_priority ? titleCase(x.task_priority) : "Low"
    }
        </td>
        <td>
        <span class="${
          x.completed_at
            ? "Tdone"
            : x.due_date < moment().format("YYYY-MM-DD")
            ? "_TOverdue"
            : "_TOverdue " + statusClass
        }">${
      x.completed_at
        ? "Done"
        : x.due_date < moment().format("YYYY-MM-DD")
        ? "Overdue"
        : titleCase(x.task_status.replace("-", " "))
    }</span>
        </td>
        <td>
            <button task-id="${
              x.id
            }" class="btn btn-default btn-line mb-0 viewTaskDetails"
                data-toggle="modal" data-target="#taskCommentsModal">Comment</button>
            <button task-id="${x.id}"
                class="btn btn-default mb-0 deleteTaskFromModal">Delete</button>
        </td>
    </tr>`);
  });
  $(".tasksListTable").DataTable();
}

function fetchTaskFromMaster() {
  $(".tasksListTable tbody").html(
    '<tr><td colspan="8" style="text-align: center; line-height: 3; font-weight: bold; font-size: 14px">LOADING</td> </tr>'
  );
  ajaxer("/GetAllTasks", "POST", {
    _token: $('meta[name="csrf_token"]').attr("content"),
  }).then((x) => {
    var response = JSON.parse(x);
    allTasksCreated = response["tasks"];
    files_url = response["files_url"];
    renderTasksInTasksPage();
    $(".viewTaskDetails").removeAttr("disabled");
  });

  setInterval(() => {
    if (!$("#taskCommentsModal").hasClass("show")) return;
    ajaxer("/GetCommentsForTask/" + activeTaskForComments.id, "GET", null).then(
      (x) => {
        let comments = JSON.parse(x).comments.task_comments;
        let typingStatus = JSON.parse(x).typing_status;
        if (typingStatus) {
          $("#typingStatusSpan").text(
            typingStatus.employee_name + " is typing"
          );
          $("#typingStatusSpan").show();
        } else {
          $("#typingStatusSpan").hide();
        }

        if (comments) {
          comments = JSON.parse(comments);
          if ($(".taskCommentsActivityWindow li").length >= comments.length)
            return;

          for (
            let i = $(".taskCommentsActivityWindow li").length;
            i <= comments.length;
            i++
          ) {
            $(".taskCommentsActivityWindow").append(`
            <li class="${
              loggedInUser.user_id == comments[i].employee_id
                ? "RS-Comments"
                : "LS-Comments"
            }">
            <div class="timeline-info">
                <div class="historyDiv">
                    <h4>${comments[i].name}</h4>
                    <p>${comments[i].comment}</p>
                    <small>${moment(
                      comments[i].at,
                      "YYYY-MM-DD HH:mm:ss"
                    ).format("hh:mm A")}</small>
                </div>
            </div>
            <div class="timeline-icon"><img src="${comments[i].picture.replace(
              ".",
              ""
            )}"
                    alt=""></div>
        </li>
            `);
          }
        }
      }
    );
  }, 3000);
}

function titleCase(str) {
  var splitStr = str.toLowerCase().split(" ");
  for (var i = 0; i < splitStr.length; i++) {
    // You do not need to check if i is larger than splitStr length, as your for does that for you
    // Assign it back to the array
    splitStr[i] =
      splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);
  }
  // Directly return the joined string
  return splitStr.join(" ");
}

function notification(type, message) {
  var bgColor = type == "error" ? "red" : "green";
  var el = $("#notifDiv");
  el.fadeIn();
  el.css("background", bgColor);
  el.text(message);
  setTimeout(() => {
    el.fadeOut();
  }, 3000);
}

function emailValidate(email) {
  var filter = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/g;
  if (filter.test(email)) {
    return true;
  } else {
    return false;
  }
}
$(document).on("input", ".numbersAndSpaces", function () {
  var input = $(this).val();
  // Allow only numbers, spaces, and special characters
  var sanitized = input.replace(/[^0-9\s+()]/g, "");
  $(this).val(sanitized);
  var keywordsArray = sanitized.split(",");
  var output = "";
  keywordsArray.forEach(function (keyword) {
    output += "<b>" + $.trim(keyword) + "</b> ";
  });
  $("#keywords-output").html(output);
});
$(document).on("input", ".onlyNumbers", function () {
  var input = $(this).val();

  var sanitized = input.replace(/[^0-9.]/g, "");
  $(this).val(sanitized);
  var keywordsArray = sanitized.split(",");
  var output = "";
  keywordsArray.forEach(function (keyword) {
    output += "<b>" + $.trim(keyword) + "</b> ";
  });
  $("#keywords-output").html(output);
});
// $(document).on('keypress', '.avoidSpecialChars', function (event) {
//     var charCode = event.which;
//     var charStr = String.fromCharCode(charCode);
//     var regex = /^[\p{L}\p{N} ]$/u;
//     let error = "";

//     if (!regex.test(charStr)) {
//         event.preventDefault();
//         error = "Special characters are not allowed.";
//     }
//     let errorSpan = $(this).next('.error');
//     if (error) {
//         if (errorSpan.length === 0) {
//             $(this).after('<span class="error text-danger">' + error + '</span>');
//         } else {
//             errorSpan.text(error);
//         }
//     } else {
//         errorSpan.text("");
//     }
// });

// $(document).on('paste', '.avoidSpecialChars', function (event) {
//     let inputField = $(this);
//     let originalValue = inputField.val();

//     setTimeout(function () {
//         let pastedValue = inputField.val();
//         var regex = /^[\p{L}\p{N} ]+$/u;
//         let error = "";

//         if (!regex.test(pastedValue)) {
//             error = "Special characters are not allowed.";
//             inputField.val(originalValue);
//         }
//         let errorSpan = inputField.next('.error');
//         if (error) {
//             if (errorSpan.length === 0) {
//                 inputField.after('<span class="error text-danger">' + error + '</span>');
//             } else {
//                 errorSpan.text(error);
//             }
//         } else {
//             errorSpan.text("");
//         }
//     }, 0);
// });

// $(document).on('focusout', '.avoidSpecialChars', function () {
//     let inputValue = $(this).val();
//     var regex = /^[\p{L}\p{N} ]+$/u;
//     let errorSpan = $(this).next('.error');
//     if(inputValue.trim() === "") {
//         errorSpan.remove();
//     }
//     else if (regex.test(inputValue)) {
//         errorSpan.remove();
//     }
// });
$('#slug').on('input paste', function(event) {
  console.log('inside slug')
  setTimeout(function() {
      let inputText = $('#slug').val();  
      inputText = inputText.replace(/\s+/g, '-'); 
      inputText = inputText.replace(/[^a-zA-Z0-9\-]/g, '');   
      inputText = inputText.replace(/-+/g, '-');
      $('#slug').val(inputText);
  }, 0);
});

$(document).on("input paste", ".createSlug", function () {
  console.log('inside crate slug')
  var input = $(this).val();
  var slug = createSlug(input);
  console.log("Generated Slug:", slug); 
  var closestForm = $(this).closest("form");
  var slugElement = closestForm.find("#slug");

  if (slugElement.length) {
    slugElement.val(slug);
  } else {
    console.log("No #slug element found within the closest form.");
  }
});
$(document).on('input paste', '.slug_validate', function(event) {
  var $input = $(this);
  setTimeout(function() { 
    var text = $input.val();   
    text = text.replace(/\s+/g, '-'); 
    text = text.replace(/[^a-zA-Z0-9\-]/g, '');   
    text = text.replace(/-+/g, '-');            
    $input.val(text);
  }, 0);  
});


function createSlug(text) { 
  text = text.toLowerCase(); 
  text = text.normalize("NFD").replace(/[\u0300-\u036f]/g, "");

  // Replace non-alphanumeric characters except spaces and hyphens with an empty string
  text = text.replace(/[^\p{L}\p{N}\s-]/gu, "");

  // Replace multiple spaces or hyphens with a single hyphen
  text = text.replace(/[\s-]+/g, "-");

  // Remove leading and trailing hyphens
  text = text.replace(/^-+|-+$/g, "");

  return text;
}

var characterCount = 0;
$(document).on("input change paste", ".acceptCharactersOnly", function () {
  let inputValue = $(this).val();
  let error = "";
  if (inputValue.trim() === "") {
    characterCount = 0;
  }

  console.log(characterCount, "characterCount");
  if (!/^[\p{L}\p{M}]+$/u.test(inputValue)) {
    error = "Input can only contain a letter from any language.";
    $(this).val(inputValue.slice(0, -1));
  } else if (inputValue.length > 1) {
    error = "Input can only contain one character.";
    $(this).val(inputValue.charAt(0));
  } else {
    characterCount = inputValue.length;
  }
  let errorSpan = $(this).next(".error");
  if (error) {
    if (errorSpan.length === 0) {
      $(this).after('<span class="error text-danger">' + error + "</span>");
    } else {
      errorSpan.text(error);
    }
  } else {
    errorSpan.text("");
  }
});
$(document).on("focusout", ".acceptCharactersOnly", function () {
  let inputValue = $(this).val();
  let errorSpan = $(this).next(".error");

  if (/^[\p{L}\p{M}]+$/u.test(inputValue) && inputValue.length === 1) {
    errorSpan.remove();
  }
});
$(document).on("click", ".delete-record", function () {
  var id = $(this).attr("id");
  var collection_type = $(this).attr("collection-type");
  var language_id = $(this).attr("language-id");
  $(".confirm_delete_sub_record").attr("id", id);
  $(".confirm_delete_sub_record").attr("collection_type", collection_type);
  $(".confirm_delete_sub_record").attr("language_id", language_id);
  $("#hidden_btn_to_open_sub_delte_modal").click();
});
$(document).on("click", ".confirm_delete_sub_record", function () {
  var currentRef = $(this);
  var id = $(this).attr("id");
  var collection_type = $(this).attr("collection_type");
  if(collection_type == 'webMenuDelete'){
    url = '/delete-menu';
  }else if(collection_type == 'activities_translation'){
    url  = '/delete-activities-translation'
  }else{
    url = '/delete-translated-records';
  }
  var language_id = $(this).attr("language_id");
  if (id.trim() && collection_type.trim()) {
    currentRef.attr("text", "Deleting");
    currentRef.attr("disabled", true);
    $.ajax({
      type: "POST",
      url: url,
      data: {
        _token: $('meta[name="csrf_token"]').attr("content"),
        id: id,
        collection_type: collection_type,
        language_id: language_id,
      },
      success: function (response) {
        currentRef.attr("text", "Yes");
        currentRef.attr("disabled", false);
        if(response.status == 'success'){
       
          $(".cancel_delete_modal_sub_record").click();
          $("#notifDiv").fadeIn();
          $("#notifDiv").css("background", "green");
          $("#notifDiv").text("Translated content deleted successfully");
          setTimeout(() => {
            $("#notifDiv").fadeOut();
            location.reload();
          }, 1500);
        }else{ 
          $("#notifDiv").fadeIn();
          $("#notifDiv").css("background", "red");
          $("#notifDiv").text(response.msg);
          setTimeout(() => {
            $("#notifDiv").fadeOut();
            location.reload();
          }, 1500);
        }
     
      },

      error: function () {
        currentRef.attr("text", "Yes");
        currentRef.attr("disabled", false);
        $("#notifDiv").fadeIn();
        $("#notifDiv").css("background", "red");
        $("#notifDiv").text("Failed to delete translated content");
        setTimeout(() => {
          $("#notifDiv").fadeOut();
        }, 1500);
      },
    });
  } else {
    $("#notifDiv").fadeIn();
    $("#notifDiv").css("background", "red");
    $("#notifDiv").text("Invalid Record!");
    setTimeout(() => {
      $("#notifDiv").fadeOut();
    }, 1500);
  }
});
$(document).on('change', '#languageToggle', function() {
  let languageToggle = $(this).prop('checked') ? 'es' : 'en';
  $.ajax({
      type: "POST",
      url: "/change-language",
      data: { 
          languageToggle: languageToggle
      },
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      cache: false,
      success: function (response) {
          console.log("Language changed successfully:", response);
          location.reload();
      },
      error: function (xhr) {
          console.log("Error:", xhr.responseText); 
      }
  });
});

