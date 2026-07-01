var glob_type = "";
var deleteRef = "";
var dtrecord = "";
var city_sate_id = "";
var postal_state_id = "";
var postal_city_id = "";
var countries_search = [];
var states_search = [];
var countries_Table = "";
var states_Table = "";
var cities_Table = "";
var activeTabText = "";
var selectedCountry = 0;
var selectedState = 0;
$(document).ready(function () {
  $(document).on("click", "#nav-country-tab", function () {
    $(".Product-Filter").hide();
    $("#productlist01").removeClass("openDataSidebarForAddingCities");
    $("#productlist01").removeClass("openDataSidebarForAddingStates");
    $("#productlist01").addClass("openDataSidebarForAddingCountries");
    $("#productlist01").removeClass("openDataSidebarForAddingPostalCode");
  });

  $(document).on("click", "#nav-province-tab", function () {
    $(".Product-Filter").hide();
    $("#productlist01").addClass("openDataSidebarForAddingStates");
    $("#productlist01").removeClass("openDataSidebarForAddingCities");
    $("#productlist01").removeClass("openDataSidebarForAddingCountries");
    $("#productlist01").removeClass("openDataSidebarForAddingPostalCode");
  });
  $(document).on("click", "#nav-city-tab", function () {
    $(".Product-Filter").hide();
    $("#productlist01").addClass("openDataSidebarForAddingCities");
    $("#productlist01").removeClass("openDataSidebarForAddingStates");
    $("#productlist01").removeClass("openDataSidebarForAddingCountries");
    $("#productlist01").removeClass("openDataSidebarForAddingPostalCode");
  });
  $(document).on("click", "#nav-postal-tab", function () {
    $(".Product-Filter").show();
    $("#productlist01").addClass("openDataSidebarForAddingPostalCode");
    $("#productlist01").removeClass("openDataSidebarForAddingStates");
    $("#productlist01").removeClass("openDataSidebarForAddingCountries");
    $("#productlist01").removeClass("openDataSidebarForAddingCities");
  });
  ///Open Country Form
  $(document).on("click", ".openDataSidebarForAddingCountries", function () {
    openSidebar();
    $("#dataSidebarLoader").hide();
    $("#saveCountryForm").show();
    $("#saveStateForm").hide();
    $("#saveCityForm").hide();
    $("#savePostalCodeForm").hide();
    $("#saveBtn").addClass("saveCountry");
    $("#saveBtn").removeClass("updateCountry");
    $("#saveBtn").removeClass("saveState");
    $("#saveBtn").removeClass("updateState");
    $("#saveBtn").removeClass("saveCity");
    $("#saveBtn").removeClass("updateCity");
    $("#saveBtn").removeClass("saveCity");
    $("#saveBtn").removeClass("updateCity");
    $("#saveBtn").removeClass("savePostalCode");
    $("#saveBtn").removeClass("updatePostalCode");
    $('input[name="country_name"]').focus();
    $('input[name="country_name"]').val("");
    $('input[name="iso"]').val("");
    $('input[name="country_name"]').blur();
    $("#operation").val("add_country");
    $("#page_title").text("Country");
    $("#operation_city").val("");
    $("#operation_state").val("");
    $("#operation_postalcode").val("");
  });
  $(document).on("click", ".openDataSidebarForUpdateCountry", function () {
    openSidebar();
    $("#saveCountryForm").hide();
    $("#saveStateForm").hide();
    $("#saveCityForm").hide();
    $("#savePostalCodeForm").hide();
    $("#saveBtn").addClass("updateCountry");
    $("#saveBtn").removeClass("saveCountry");
    $("#saveBtn").removeClass("saveState");
    $("#saveBtn").removeClass("updateState");
    $("#saveBtn").removeClass("saveCity");
    $("#saveBtn").removeClass("updateCity");
    $("#saveBtn").removeClass("savePostalCode");
    $("#saveBtn").removeClass("updatePostalCode");
    $("#operation").val("update_country");
    $("#page_title").text("Country");
    $("#dataSidebarLoader").show();
    var id = $(this).attr("id");
    $.ajax({
      type: "GET",
      url: "/admin/GetCountry/" + id,
      success: function (response) {
        var response = JSON.parse(response);
        $("#dataSidebarLoader").hide();
        $('input[name="country_name"]').focus();
        $('input[name="country_name"]').val(response.name);
        $('input[name="country_name"]').blur();
        $('input[name="iso"]').focus();
        $('input[name="iso"]').val(response.iso);
        $('input[name="iso"]').blur();
        $('input[name="hidden_country_id"]').val(response.id);
        $("#saveCountryForm").show();
        $("#saveStateForm").hide();
        $("#saveCityForm").hide();
      },
    });
  });
  ///Open State Form
  $(document).on("click", ".openDataSidebarForAddingStates", function () {
    openSidebar();
    $("#dataSidebarLoader").hide();
    $("#saveCountryForm").hide();
    $("#saveStateForm").show();
    $("#saveCityForm").hide();
    $("#savePostalCodeForm").hide();
    $("#saveBtn").removeClass("saveCountry");
    $("#saveBtn").removeClass("updateCountry");
    $("#saveBtn").addClass("saveState");
    $("#saveBtn").removeClass("updateState");
    $("#saveBtn").removeClass("saveCity");
    $("#saveBtn").removeClass("updateCity");
    $("#saveBtn").removeClass("savePostalCode");
    $("#saveBtn").removeClass("updatePostalCode");
    $("#operation_state").val("add_state");
    $("#operation").val("");
    $("#operation_city").val("");
    $("#operation_postalcode").val("");
    $('select[name="country_id"]').val(0).trigger("change");
    $('input[name="state_name"]').focus();
    $('input[name="state_name"]').val("");
    $('input[name="state_name"]').blur();
    $("#page_title").text("State");
  });
  $(document).on("click", ".openDataSidebarForUpdateStates", function () {
    openSidebar();
    $("#saveCountryForm").hide();
    $("#saveStateForm").hide();
    $("#saveCityForm").hide();
    $("#savePostalCodeForm").hide();
    $("#saveBtn").addClass("updateState");
    $("#saveBtn").removeClass("saveState");
    $("#saveBtn").removeClass("saveCountry");
    $("#saveBtn").removeClass("updateCountry");
    $("#saveBtn").removeClass("saveCity");
    $("#saveBtn").removeClass("updateCity");
    $("#saveBtn").removeClass("savePostalCode");
    $("#saveBtn").removeClass("updatePostalCode");
    $("#operation_state").val("update_state");
    $("#dataSidebarLoader").show();
    $("#page_title").text("State");
    var id = $(this).attr("id");
    $.ajax({
      type: "GET",
      url: "/admin/GetState/" + id,
      success: function (response) {
        var response = JSON.parse(response);
        $("#dataSidebarLoader").hide();
        $('select[name="country_id"]')
          .val(response.country_id)
          .trigger("change");
        $('input[name="state_name"]').focus();
        $('input[name="state_name"]').val(response.name);
        $('input[name="state_name"]').blur();
        $('input[name="hidden_state_id"]').val(response.id);
        $("#saveStateForm").show();
        $("#saveCountryForm").hide();
        $("#saveCityForm").hide();
      },
    });
  });
  //Open City Form
  $(document).on("click", ".openDataSidebarForAddingCities", function () {
    $("#dataSidebarLoader").hide();
    $("#saveStateForm").hide();
    $("#saveCountryForm").hide();
    $("#saveCityForm").show();
    $("#savePostalCodeForm").hide();
    $("#saveBtn").removeClass("saveCountry");
    $("#saveBtn").removeClass("updateCountry");
    $("#saveBtn").removeClass("saveState");
    $("#saveBtn").removeClass("updateState");
    $("#saveBtn").addClass("saveCity");
    $("#saveBtn").removeClass("updateCity");
    $("#saveBtn").removeClass("savePostalCode");
    $("#saveBtn").removeClass("updatePostalCode");
    $("#operation_city").val("add_city");
    $("#operation").val("");
    $("#operation_state").val("");
    $("#operation_postalcode").val("");
    $('select[name="country_id"]').val(0).trigger("change");
    $('select[name="state_id"]').val(0).trigger("change");
    $('input[name="city_name"]').focus();
    $('input[name="city_name"]').val("");
    $('input[name="city_name"]').blur();
    $('input[name="postal_code"]').focus();
    $('input[name="postal_code"]').val("");
    $('input[name="postal_code"]').blur();
    $("#page_title").text("City");
    openSidebar();
  });
  $(document).on("click", ".openDataSidebarForUpdateCity", function () {
    openSidebar();
    city_sate_id = $(this).attr("city-state-id");
    $(".all_states").empty();
    $(".all_states_form").empty();
    $("#saveCountryForm").hide();
    $("#saveStateForm").hide();
    $("#saveCityForm").hide();
    $("#savePostalCodeForm").hide();
    $("#saveBtn").removeClass("saveState");
    $("#saveBtn").removeClass("updateState");
    $("#saveBtn").removeClass("saveCountry");
    $("#saveBtn").removeClass("updateCountry");
    $("#saveBtn").removeClass("saveCity");
    $("#saveBtn").addClass("updateCity");
    $("#operation_city").val("update_city");
    $("#saveBtn").removeClass("savePostalCode");
    $("#saveBtn").removeClass("updatePostalCode");
    $("#dataSidebarLoader").show();
    $("#page_title").text("City");
    var id = $(this).attr("id");
    $.ajax({
      type: "GET",
      url: "/admin/GetCity/" + id,
      success: function (response) {
        var response = JSON.parse(response);
        $("#dataSidebarLoader").hide();
        $('select[name="country_id"]')
          .val(response.country_id)
          .trigger("change");
        // $('select[name="state_id"]').val(response.state_id).trigger('change');
        $('input[name="city_name"]').focus();
        $('input[name="city_name"]').val(response.name);
        $('input[name="city_name"]').blur();
        $('input[name="postal_code"]').focus();
        $('input[name="postal_code"]').val(response.postal_code);
        $('input[name="postal_code"]').blur();
        $('input[name="hidden_city_id"]').val(response.id);
        $('input[name="hidden_city_state_id"]').val(response.state_id);
        $("#saveStateForm").hide();
        $("#saveCountryForm").hide();
        $("#saveCityForm").show();
      },
    });
  });
  ///Open Postal Code Form
  $(document).on("click", ".openDataSidebarForAddingPostalCode", function () {
    $("#dataSidebarLoader").hide();
    $(".all_cities_form_postal").empty();
    $("#saveCountryForm").hide();
    $("#saveStateForm").hide();
    $("#saveCityForm").hide();
    $("#savePostalCodeForm").show();
    $("#saveBtn").removeClass("saveCountry");
    $("#saveBtn").removeClass("updateCountry");
    $("#saveBtn").removeClass("saveState");
    $("#saveBtn").removeClass("updateState");
    $("#saveBtn").removeClass("saveCity");
    $("#saveBtn").removeClass("updateCity");
    $("#saveBtn").removeClass("updatePostalCode");
    $("#saveBtn").addClass("savePostalCode");
    $('select[name="country_id"]').val(0).trigger("change");
    $('select[name="state_id"]').val(0).trigger("change");
    $('select[name="city_id"]').val(0).trigger("change");
    $('input[name="postal_code"]').focus();
    $('input[name="postal_code"]').val("");
    $('input[name="postal_code"]').blur();
    $("#operation_postalcode").val("add_postalcode");
    $("#page_title").text("Postal Code");
    $("#operation_city").val("");
    $("#operation_state").val("");
    $("#operation").val("");
  });
  ///Update Postal Code
  $(document).on("click", ".openDataSidebarForUpdatePostalCode", function () {
    openSidebar();
    postal_state_id = $(this).attr("postal-state-id");
    postal_city_id = $(this).attr("postal-city-id");
    $(".all_states").empty();
    $(".all_cities").empty();
    $(".all_states_form_postal").empty();
    $(".all_cities_form_postal").empty();
    $("#saveCountryForm").hide();
    $("#saveStateForm").hide();
    $("#saveCityForm").hide();
    $("#savePostalCodeForm").hide();
    $("#saveBtn").removeClass("saveState");
    $("#saveBtn").removeClass("updateState");
    $("#saveBtn").removeClass("saveCountry");
    $("#saveBtn").removeClass("updateCountry");
    $("#saveBtn").removeClass("saveCity");
    $("#saveBtn").removeClass("updateCity");
    $("#saveBtn").removeClass("savePostalCode");
    $("#saveBtn").addClass("updatePostalCode");
    $("#operation_postalcode").val("update_postalcode");
    $("#dataSidebarLoader").show();
    $("#page_title").text("Postal Code");
    var id = $(this).attr("id");
    $.ajax({
      type: "GET",
      url: "/admin/GetPostalCode/" + id,
      success: function (response) {
        var response = JSON.parse(response);
        $("#dataSidebarLoader").hide();
        $('select[name="country_id"]')
          .val(response.country_id)
          .trigger("change");
        $('select[name="state_id"]').val(response.state_id).trigger("change");
        $('select[name="city_id"]').val(response.city_id).trigger("change");
        $('input[name="postal_code"]').focus();
        $('input[name="postal_code"]').val(response.postal_code);
        $('input[name="postal_code"]').blur();
        $('input[name="hidden_postal_id"]').val(response.id);
        var state_postal_id = response.state_id;

        $.ajax({
          type: "GET",
          url: "/admin/GetCitiesforPostal/" + state_postal_id,
          success: function (query_cities_p) {
            query_cities_p.forEach((element) => {
              $(".all_cities_form_postal").append(
                `<option value="${element["id"]}" ${
                  postal_city_id == element.id ? "selected" : ""
                } state_tag="${element["id"]}">${element["name"]}</option>`
              );
            });
          },
        });
        $("#saveStateForm").hide();
        $("#saveCountryForm").hide();
        $("#saveCityForm").hide();
        $("#savePostalCodeForm").show();
      },
    });
  });
  //Country Save
  $(document).on("click", ".saveCountry", function () {
    save_country();
  });
  $(document).on("click", ".updateCountry", function () {
    save_country();
  });
  $(document).on("click", ".saveState", function () {
    save_country();
  });
  $(document).on("click", ".updateState", function () {
    save_country();
  });
  $(document).on("click", ".saveCity", function () {
    save_country();
  });
  $(document).on("click", ".updateCity", function () {
    save_country();
  });
  $(document).on("click", ".savePostalCode", function () {
    save_country();
  });
  $(document).on("click", ".updatePostalCode", function () {
    save_country();
  });
  function save_country() {
    var form_id;
    var url;
    if ($("#saveBtn").hasClass("saveCountry")) {
      form_id = "saveCountryForm";
      url = "/admin/save_country";
      if (
        !$('input[name="country_name"]').val() ||
        !$('input[name="country_name"]').val().trim() ||
        !$('input[name="iso"]').val() ||
        !$('input[name="iso"]').val().trim()
      ) {
        $("#notifDiv").fadeIn();
        $("#notifDiv").css("background", "red");
        $("#notifDiv").text("Please provide all the required information (*)");
        setTimeout(() => {
          $("#notifDiv").fadeOut();
        }, 3000);
        return;
      }
    }
    if ($("#saveBtn").hasClass("updateCountry")) {
      form_id = "saveCountryForm";
      url = "/admin/save_country";
      if (
        !$('input[name="country_name"]').val() ||
        !$('input[name="country_name"]').val().trim() ||
        !$('input[name="iso"]').val() ||
        !$('input[name="iso"]').val().trim()
      ) {
        $("#notifDiv").fadeIn();
        $("#notifDiv").css("background", "red");
        $("#notifDiv").text("Please provide all the required information (*)");
        setTimeout(() => {
          $("#notifDiv").fadeOut();
        }, 3000);
        return;
      }
    }
    if ($("#saveBtn").hasClass("saveState")) {
      form_id = "saveStateForm";
      url = "/admin/save_country";
      if (
        !$('select[name="country_id"]').val() ||
        $('select[name="country_id"]').val() == 0 ||
        !$('input[name="state_name"]').val() ||
        !$('input[name="state_name"]').val().trim()
      ) {
        $("#notifDiv").fadeIn();
        $("#notifDiv").css("background", "red");
        $("#notifDiv").text("Please provide all the required information (*)");
        setTimeout(() => {
          $("#notifDiv").fadeOut();
        }, 3000);
        return;
      }
    }
    if ($("#saveBtn").hasClass("updateState")) {
      form_id = "saveStateForm";
      url = "/admin/save_country";
      if (
        !$('select[name="country_id"]').val() ||
        $('select[name="country_id"]').val() == 0 ||
        !$('input[name="state_name"]').val() ||
        !$('input[name="state_name"]').val().trim()
      ) {
        $("#notifDiv").fadeIn();
        $("#notifDiv").css("background", "red");
        $("#notifDiv").text("Please provide all the required information (*)");
        setTimeout(() => {
          $("#notifDiv").fadeOut();
        }, 3000);
        return;
      }
    }
    if ($("#saveBtn").hasClass("saveCity")) {
      form_id = "saveCityForm";
      url = "/admin/save_country";

      if (
        !$("#country_id").val() ||
        $("#country_id").val() == 0 ||
        !$('select[name="state_id"]').val() ||
        $('select[name="state_id"]').val() == 0 ||
        !$('input[name="city_name"]').val() ||
        !$('input[name="city_name"]').val().trim()
      ) {
        $("#notifDiv").fadeIn();
        $("#notifDiv").css("background", "red");
        $("#notifDiv").text("Please provide all the required information (*)");
        setTimeout(() => {
          $("#notifDiv").fadeOut();
        }, 3000);
        return;
      }
    }
    if ($("#saveBtn").hasClass("updateCity")) {
      form_id = "saveCityForm";
      url = "/admin/save_country";
      if (
        !$("#country_id").val() ||
        $("#country_id").val() == 0 ||
        !$('select[name="state_id"]').val() ||
        $('select[name="state_id"]').val() == 0 ||
        !$('input[name="city_name"]').val() ||
        !$('input[name="city_name"]').val().trim()
      ) {
        $("#notifDiv").fadeIn();
        $("#notifDiv").css("background", "red");
        $("#notifDiv").text("Please provide all the required information (*)");
        setTimeout(() => {
          $("#notifDiv").fadeOut();
        }, 3000);
        return;
      }
    }
    ///PostalCode
    if ($("#saveBtn").hasClass("savePostalCode")) {
      form_id = "savePostalCodeForm";
      url = "/admin/save_country";
      if (
        !$('select[name="country_id"]').val() ||
        !$('select[name="state_id"]').val() ||
        !$('select[name="city_id"]').val() ||
        !$('input[name="postal_code"]').val()
      ) {
        $("#notifDiv").fadeIn();
        $("#notifDiv").css("background", "red");
        $("#notifDiv").text("Please provide all the required information (*)");
        setTimeout(() => {
          $("#notifDiv").fadeOut();
        }, 3000);
        return;
      }
      var postal_code = $("#postal_code").val();
      var postal_expression = /^[a-zA-Z0-9]{6}$/;
      if (postal_expression.test(postal_code) == false) {
        $("#notifDiv").fadeIn();
        $("#notifDiv").css("background", "red");
        $("#notifDiv").text("Please Provide Correct Format of Postal Code");
        setTimeout(() => {
          $("#notifDiv").fadeOut();
        }, 3000);
        return;
      }
    }
    if ($("#saveBtn").hasClass("updatePostalCode")) {
      form_id = "savePostalCodeForm";
      url = "/admin/save_country";
      if (
        !$('select[name="country_id"]').val() ||
        !$('select[name="state_id"]').val() ||
        !$('select[name="city_id"]').val() ||
        !$('input[name="postal_code"]').val()
      ) {
        $("#notifDiv").fadeIn();
        $("#notifDiv").css("background", "red");
        $("#notifDiv").text("Please provide all the required information (*)");
        setTimeout(() => {
          $("#notifDiv").fadeOut();
        }, 3000);
        return;
      }
      var postal_code = $("#postal_code").val();
      var postal_expression = /^[a-zA-Z0-9]{6}$/;
      if (postal_expression.test(postal_code) == false) {
        $("#notifDiv").fadeIn();
        $("#notifDiv").css("background", "red");
        $("#notifDiv").text("Please Provide Correct Format of Postal Code");
        setTimeout(() => {
          $("#notifDiv").fadeOut();
        }, 3000);
        return;
      }
    }
    //End Postal Code
    $(`#${form_id}`).ajaxSubmit({
      type: "POST",
      url: url,
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf_token"]').attr("content"),
      },
      success: function (response) {
        if (
          JSON.parse(response) == "success" ||
          JSON.parse(response) == "update"
        ) {
          $("#operation").val("");
          $("#operation_state").val("");
          $('input[name="country_name"]').val("");
          $('input[name="country_name"]').focus();
          $('input[name="city_name"]').val("");
          $('input[name="city_name"]').focus();
          $('input[name="country_id"]').val("");
          $('input[name="country_id"]').focus();
          $('input[name="state_id"]').val("");
          $('input[name="state_id"]').focus();
          $('input[name="postal_code"]').val("");
          $('input[name="postal_code"]').focus();
          $('input[name="state_name"]').val("");
          $('input[name="state_name"]').focus();
        }
        if (JSON.parse(response) == "success") {
          closeSidebar();
          var operation = $("#operation").val();
          $("#notifDiv").fadeIn();
          $("#notifDiv").css("background", "green");
          $("#notifDiv").text("Added successfully");
          setTimeout(() => {
            fetchGeoList();
          }, 200);

          setTimeout(() => {
            $("#notifDiv").fadeOut();
          }, 3000);
        } else if (JSON.parse(response) == "update") {
          closeSidebar();
          $("#notifDiv").fadeIn();
          $("#notifDiv").css("background", "green");
          $("#notifDiv").text("Updated successfully");
          setTimeout(() => {
            fetchGeoList();
          }, 200);
          setTimeout(() => {
            $("#notifDiv").fadeOut();
          }, 3000);
        } else if (JSON.parse(response) == "already_exist") {
          $("#notifDiv").fadeIn();
          $("#notifDiv").css("background", "red");
          $("#notifDiv").text("Record Already Exist");
          setTimeout(() => {
            $("#notifDiv").fadeOut();
          }, 3000);
        } else {
          $("#notifDiv").fadeIn();
          $("#notifDiv").css("background", "red");
          $("#notifDiv").text("Failed to add at this moment");
          setTimeout(() => {
            $("#notifDiv").fadeOut();
          }, 3000);
        }
      },
      error: function (e) {
        notification(`error`, `Somthing Went Wrong`);
      },
    });
  }
  //Delete Geographical Data
  $(document).on("click", ".delete_geo", function () {
    var id = $(this).attr("id");
    glob_type = $(this).attr("name");
    $(".confirm_delete").attr("id", id);
    $("#hidden_btn_to_open_modal").click();
  });

  $(document).on("click", ".confirm_delete", function () {
    var thisRef = $(this);
    thisRef.attr("disabled", "disabled");
    thisRef.text("Processing...");
    var id = $(this).attr("id");

    $.ajax({
      type: "POST",
      url: "/admin/delete_geographical",
      data: {
        _token: $('meta[name="csrf-token"]').attr("content"),
        type: glob_type,
        id: id,
      },
      success: function (response) {
        thisRef.attr("disabled", false);
        thisRef.text("Yes");
        if (JSON.parse(response) === "success") {
          fetchGeoList();
          $(".cancel_delete_modal").click();
          $("#notifDiv").fadeIn();
          $("#notifDiv").css("background", "green");
          $("#notifDiv").text("Deleted.");
          setTimeout(() => {
            $("#notifDiv").fadeOut();
          }, 3000);
        }
      },
      error: function (err) {
        thisRef.attr("disabled", false);
        thisRef.text("Yes");
        $("#notifDiv").fadeIn();
        $("#notifDiv").css("background", "green");
        $("#notifDiv").text("Unable to delete at this moment.");
        setTimeout(() => {
          $("#notifDiv").fadeOut();
        }, 3000);
      },
    });
  });

  $(".all_countries").change(function () {
    $(".all_states").empty();
    var country_id = $(this).val();
    $.ajax({
      url: `/admin/GetStatesagianstCountry/${country_id}`,
      success: function (query) {
        $(".all_states").append(`<option>${Lang.get('fields.select_state')}</option>`);
        query.forEach((element) => {
          $(".all_states").append(
            `<option value="${element["id"]}">${element["name"]}</option>`
          );
        });
      },
      error: function (e) {
        fetchGeoList();
        $(".Product-Filter").show();
      },
    });
  });
  $(".all_states").change(function () {
    $(".all_cities").empty();
    var state_id = $(this).val();
    $.ajax({
      url: `/get-cities-against-state/${state_id}`,
      success: function (data) {
        $(".all_cities").append(`<option>${Lang.get('fields.select_city')}</option>`);
        data.cities.forEach((element) => {
          $(".all_cities").append(
            `<option value="${element["id"]}">${element["name"]}</option>`
          );
        });
      },
      error: function (e) {
        fetchGeoList();
        $(".Product-Filter").show();
      },
    });
  });
  $(".all_cities").change(function () {
    dtrecord.clear();
    dtrecord.destroy();
    $(".postalcodeListTable tbody").empty();
    var city_id = $(this).val();
    $.ajax({
      url: `/get-postal-code-against-city/${city_id}`,
      success: function (data) {
        data.postalcodes.forEach((element, key) => {
          $(".postalcodeListTable tbody").append(
            `<tr><td>${key + 1}</td><td>${element["postal_code"]}</td><td>${
              element["city_name"]
            }</td><td>${element["state_name"]}</td><td>${
              element["country_name"]
            }</td><td>${element["iso"]}</td><td><button id="${
              element["postalcode_id"]
            }" postal-state-id="${element["city_state"]}" postal-city-id="${
              element["city_id"]
            }" class="btn btn-default btn-line openDataSidebarForUpdatePostalCode">Edit</button><button id="${
              element["postalcode_id"]
            }" class="btn btn-default red-bg delete_geo" name="postal_code" style="background: #e20000!important; color: #fff!important">Delete</button></td></tr>`
          );
        });
        dtrecord = $(".postalcodeListTable").DataTable({
          responsive: true,
          searching: false,
          lengthChange: false,
          info: false,
          pagingType: "simple_numbers",
          pageLength: 10,
        });
        dtrecord.rows.add().draw(false);
      },
      error: function (e) {
        fetchGeoList();
        $(".Product-Filter").show();
      },
    });
  });
  // All States against Country for Add or Edit City
  $(".all_countries_form").change(function () {
      var country_id = $(this).val(); 
    $(".all_states_form").empty();
    $('.all_states_form').append(`<option value="0">${Lang.get('fields.select_state')}*</option>`);
    if (country_id == 0) {
        return;
    } else {
      $.ajax({
        url: `/admin/GetStatesagianstCountry/${country_id}`,
        success: function (query) {
          query.forEach((element) => {
            $(".all_states_form").append(
              `<option value="${element["id"]}" ${
                city_sate_id == element.id ? "selected" : ""
              } >${element["name"]}</option>`
            );
          });
        },
        error: function (e) {},
      });
    }
  });
  $(".all_states_form_postal").change(function () {
    $(".all_cities_form_postal").empty();
    var state_id = $(this).val();
    $.ajax({
      url: `/GetCitiesagianstStatesforPostal/${state_id}`,
      success: function (querycitiespostal) {
        $(".all_cities_form_postal").append(
          `<option value="0">${Lang.get('fields.select_city')}*</option>`
        );
        querycitiespostal.forEach((element) => {
          $(".all_cities_form_postal").append(
            `<option value="${element["id"]}" ${
              postal_city_id == element.id ? "selected" : ""
            } >${element["name"]}</option>`
          );
        });
      },
      error: function (e) {},
    });
  });
  // All States against Country for Add or Edit Postal
  $(".all_countries_form_postal").change(function () {
    $(".all_states_form_postal").empty();
    var country_id = $(this).val();
    $.ajax({
      url: `/admin/GetStatesagianstCountryforPostal/${country_id}`,
      success: function (query) {
        $(".all_states_form_postal").append(
          `<option value="0">${Lang.get('fields.select_state')}*</option>`
        );
        query.forEach((element) => {
          $(".all_states_form_postal").append(
            `<option value="${element["id"]}" ${
              postal_state_id == element.id ? "selected" : ""
            } state_tag="${element["id"]}">${element["name"]}</option>`
          );
        });
      },
      error: function (e) {
        alert("error");
      },
    });
  });
  // All Cities against Postal for Add or Edit Postal
  // $('.all_states_form_postal').change(function () {
  // $('.all_cities_form_postal').empty();
  //     var state_id = $(this).val();
  //     $.ajax({
  //         url:`/GetCitiesagianstStatesforPostal/${state_id}`,
  //         success: function (querycitiespostal) {
  //             $('.all_cities_form_postal').append(`<option value="0">${Lang.get('fields.select_city')}*</option>`);
  //             querycitiespostal.forEach((element) => {
  //                 $('.all_cities_form_postal').append(`<option value="${element['id']}" ${postal_city_id == element.id ? 'selected' : ''} >${element['name']}</option>`);
  //             })
  //         },
  //         error: function (e) {
  //             alert('error')
  //         }
  //     });
  // });

  ///Country Status Change
  $(document).on("click", ".ChangeCountryStatus", function () {
    $(".ChangeCountryStatus").attr("disabled", "disabled");
    var thisrow = $(this);
    let change_country_id = $(this).attr("id");
    $.ajax({
      type: "POST",
      url: "/admin/country-status",
      data: {
        _token: $('meta[name="csrf_token"]').attr("content"),
        id: change_country_id,
      },
      success: function (response) {
        $(".ChangeCountryStatus").removeAttr("disabled");
        thisrow.attr("disabled", "disabled");
        $("#notifDiv").fadeIn();
        $("#notifDiv").css("background", "green");
        $("#notifDiv").text("Default country updated successfully.");
        setTimeout(() => {
          $("#notifDiv").fadeOut();
        }, 3000);
      },
    });
  });
});
function fetchGeoList() {
  $("#customSearchInput").val("");
  $("#searchCountry").empty();
  $("#searchState").empty();
  $(".loader").show();
  $(".CountriesTbl").empty();
  $(".StatesTbl").empty();
  $(".CitiesTbl").empty();
  $(".PostalCodesTbl").empty();
  $(".Product-Filter").hide();
  $.ajax({
    type: "GET",
    url: "/admin/GetGeoData",
    data: {
      // _token: $('meta[name="csrf_token"]').attr('content')
    },
    success: function (response) {
      $(".total_countries").text(response.total_countries);
      $(".total_states").text(response.total_states);
      $(".CountriesTbl").append(
        `<table class="table table-hover dt-responsive nowrap countriesListTable" style="width:100%;"><thead>
              <tr>
              <th>S.No</th>
              <th>${Lang.get('fields.country')}</th>
              <th>${Lang.get('fields.iso')}</th>
              <th>${Lang.get('fields.actions')}</th>
              </tr>
              </thead><tbody></tbody></table>`
      );
      $(".countriesListTable tbody").empty();
      countries_search = response.countries;
      states_search = response.states;
      response.countries.forEach((element, key) => {
        $(".countriesListTable tbody").append(`
                    <tr><td>${key + 1}</td>
                    <td>${element["name"]}</td>
                    <td>${element["iso"]}</td>
                    <td>
                    <button title="Edit" id="${
                      element["id"]
                    }" class="btn btn-outline-primary openDataSidebarForUpdateCountry">
                    <svg width="10" height="13" viewBox="0 0 10 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                    d="M4.68745 11.18L4.5055 10.7143L4.68745 11.18ZM1.98319 11.6993L1.73319 12.1323H1.73319L1.98319 11.6993ZM1.08078 9.09767L0.586477 9.17295L1.08078 9.09767ZM1.40764 7.36282L1.84065 7.61282L1.40764 7.36282ZM1.01821 8.18767L0.526854 8.09509H0.526854L1.01821 8.18767ZM6.02644 10.0295L6.45946 10.2795L6.02644 10.0295ZM5.50683 10.7792L5.83268 11.1584L5.50683 10.7792ZM3.93993 2.97677L3.50692 2.72677L3.93993 2.97677ZM8.12572 5.39343L5.59343 9.77949L6.45946 10.2795L8.99175 5.89343L8.12572 5.39343ZM1.84065 7.61282L4.37295 3.22677L3.50692 2.72677L0.974629 7.11282L1.84065 7.61282ZM4.5055 10.7143C3.77452 10.9999 3.27668 11.193 2.89236 11.2828C2.52111 11.3696 2.35265 11.3352 2.23319 11.2663L1.73319 12.1323C2.1687 12.3837 2.63595 12.3697 3.11987 12.2566C3.59073 12.1466 4.16753 11.9199 4.86941 11.6457L4.5055 10.7143ZM0.586477 9.17295C0.699936 9.91791 0.792032 10.5308 0.93219 10.9935C1.07624 11.4692 1.29768 11.8808 1.73319 12.1323L2.23319 11.2663C2.11373 11.1973 1.99977 11.0686 1.88926 10.7037C1.77486 10.326 1.69324 9.79823 1.57508 9.02239L0.586477 9.17295ZM0.974629 7.11282C0.760081 7.48443 0.588305 7.76894 0.526854 8.09509L1.50956 8.28024C1.5322 8.16011 1.59202 8.04348 1.84065 7.61282L0.974629 7.11282ZM1.57508 9.02239C1.5002 8.53078 1.48693 8.40038 1.50956 8.28024L0.526854 8.09509C0.465403 8.42124 0.521869 8.74875 0.586477 9.17295L1.57508 9.02239ZM5.59343 9.77949C5.34479 10.2101 5.2737 10.3203 5.18097 10.3999L5.83268 11.1584C6.08441 10.9421 6.24491 10.6511 6.45946 10.2795L5.59343 9.77949ZM4.86941 11.6457C5.26908 11.4896 5.58095 11.3747 5.83268 11.1584L5.18097 10.3999C5.08825 10.4796 4.96869 10.5333 4.5055 10.7143L4.86941 11.6457ZM7.33267 2.43371C7.88924 2.75505 8.26258 2.97182 8.51981 3.17033C8.76556 3.35997 8.84384 3.48771 8.87564 3.60638L9.84157 3.34756C9.73042 2.93278 9.46507 2.63665 9.13075 2.37865C8.80792 2.12952 8.36476 1.87489 7.83267 1.56769L7.33267 2.43371ZM8.99175 5.89343C9.29895 5.36134 9.55542 4.91925 9.71044 4.54208C9.87097 4.15149 9.95271 3.76235 9.84157 3.34756L8.87564 3.60638C8.90744 3.72506 8.90351 3.87483 8.78551 4.16193C8.66199 4.46246 8.44706 4.83686 8.12572 5.39343L8.99175 5.89343ZM7.83267 1.56769C7.30058 1.26048 6.85848 1.00401 6.48131 0.848997C6.09072 0.688463 5.70159 0.606727 5.2868 0.717869L5.54562 1.68379C5.66429 1.652 5.81406 1.65592 6.10117 1.77392C6.4017 1.89744 6.77609 2.11237 7.33267 2.43371L7.83267 1.56769ZM4.37295 3.22677C4.69428 2.67019 4.91106 2.29686 5.10956 2.03962C5.2992 1.79388 5.42694 1.71559 5.54562 1.68379L5.2868 0.717869C4.87201 0.829011 4.57588 1.09436 4.31788 1.42869C4.06875 1.75152 3.81412 2.19468 3.50692 2.72677L4.37295 3.22677ZM8.80873 5.21042L4.18993 2.54375L3.68993 3.40978L8.30873 6.07645L8.80873 5.21042Z"
                    fill="" />
                    </svg>
                </button> 
                <button title="Delete" type="button" id="${
                  element["id"]
                }" class="btn btn-outline-primary btn-delete delete_geo" name="country">
                    <svg width="12" height="15" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                    d="M10.3451 8.01958L10.8404 8.0885L10.3451 8.01958ZM10.1702 9.27626L10.6655 9.34518L10.1702 9.27626ZM1.83042 9.27627L2.32564 9.20735L1.83042 9.27627ZM1.65553 8.01958L1.1603 8.0885L1.65553 8.01958ZM4.12276 13.9911L3.92836 14.4518H3.92836L4.12276 13.9911ZM2.31705 11.8735L2.78637 11.701L2.31705 11.8735ZM9.6836 11.8735L10.1529 12.0459L9.6836 11.8735ZM7.87789 13.9911L7.6835 13.5305L7.87789 13.9911ZM1.83142 5.45263C1.8053 5.17772 1.56127 4.97604 1.28637 5.00216C1.01146 5.02828 0.809781 5.27231 0.835901 5.54721L1.83142 5.45263ZM11.1648 5.54721C11.1909 5.27231 10.9892 5.02828 10.7143 5.00216C10.4394 4.97604 10.1954 5.17772 10.1692 5.45262L11.1648 5.54721ZM11.3337 4.66659C11.6098 4.66659 11.8337 4.44273 11.8337 4.16659C11.8337 3.89044 11.6098 3.66659 11.3337 3.66659V4.66659ZM0.666992 3.66659C0.39085 3.66659 0.166992 3.89044 0.166992 4.16659C0.166992 4.44273 0.39085 4.66659 0.666992 4.66659L0.666992 3.66659ZM4.16699 11.4999C4.16699 11.7761 4.39085 11.9999 4.66699 11.9999C4.94313 11.9999 5.16699 11.7761 5.16699 11.4999H4.16699ZM5.16699 6.16659C5.16699 5.89044 4.94313 5.66659 4.66699 5.66659C4.39085 5.66659 4.16699 5.89044 4.16699 6.16659H5.16699ZM6.83366 11.4999C6.83366 11.7761 7.05752 11.9999 7.33366 11.9999C7.6098 11.9999 7.83366 11.7761 7.83366 11.4999H6.83366ZM7.83366 6.16659C7.83366 5.89044 7.6098 5.66659 7.33366 5.66659C7.05752 5.66659 6.83366 5.89044 6.83366 6.16659H7.83366ZM8.66699 4.16659V4.66659H9.16699V4.16659H8.66699ZM3.33366 4.16659H2.83366V4.66659H3.33366V4.16659ZM9.8499 7.95066L9.67501 9.20735L10.6655 9.34518L10.8404 8.0885L9.8499 7.95066ZM2.32564 9.20735L2.15075 7.95066L1.1603 8.0885L1.33519 9.34519L2.32564 9.20735ZM6.00033 13.6666C4.98088 13.6666 4.61728 13.6571 4.31715 13.5305L3.92836 14.4518C4.45975 14.676 5.07071 14.6666 6.00033 14.6666L6.00033 13.6666ZM1.33519 9.34519C1.52166 10.6851 1.62303 11.4344 1.84772 12.0459L2.78637 11.701C2.60803 11.2156 2.51933 10.5991 2.32564 9.20735L1.33519 9.34519ZM4.31715 13.5305C3.70219 13.271 3.1303 12.6371 2.78637 11.701L1.84772 12.0459C2.25746 13.1611 2.98836 14.0551 3.92836 14.4518L4.31715 13.5305ZM9.67501 9.20735C9.48132 10.5991 9.39262 11.2156 9.21428 11.701L10.1529 12.0459C10.3776 11.4344 10.479 10.6851 10.6655 9.34518L9.67501 9.20735ZM6.00033 14.6666C6.92994 14.6666 7.5409 14.676 8.07229 14.4518L7.6835 13.5305C7.38337 13.6571 7.01977 13.6666 6.00033 13.6666L6.00033 14.6666ZM9.21428 11.701C8.87035 12.6371 8.29846 13.271 7.6835 13.5305L8.07229 14.4518C9.01229 14.0551 9.74319 13.1611 10.1529 12.0459L9.21428 11.701ZM2.15075 7.95066C2.00267 6.88661 1.8921 6.09127 1.83142 5.45263L0.835901 5.54721C0.899118 6.21256 1.0134 7.03295 1.1603 8.0885L2.15075 7.95066ZM10.8404 8.0885C10.9873 7.03295 11.1015 6.21256 11.1648 5.54721L10.1692 5.45262C10.1086 6.09127 9.99798 6.8866 9.8499 7.95066L10.8404 8.0885ZM11.3337 3.66659L0.666992 3.66659L0.666992 4.66659L11.3337 4.66659V3.66659ZM5.16699 11.4999L5.16699 6.16659H4.16699L4.16699 11.4999H5.16699ZM7.83366 11.4999L7.83366 6.16659H6.83366L6.83366 11.4999H7.83366ZM8.16699 3.49992V4.16659H9.16699V3.49992H8.16699ZM8.66699 3.66659L3.33366 3.66659V4.66659L8.66699 4.66659V3.66659ZM3.83366 4.16659V3.49992H2.83366V4.16659H3.83366ZM6.00033 1.33325C7.19694 1.33325 8.16699 2.3033 8.16699 3.49992H9.16699C9.16699 1.75102 7.74923 0.333252 6.00033 0.333252V1.33325ZM6.00033 0.333252C4.25142 0.333252 2.83366 1.75102 2.83366 3.49992H3.83366C3.83366 2.3033 4.80371 1.33325 6.00033 1.33325V0.333252Z"
                    fill="" /> </svg>
                </button> 
                   
                </td></tr>`);
      });

      countries_Table = $(".countriesListTable").DataTable({
        responsive: true,
        // searching: false,
        lengthChange: false,
        info: false,
        pagingType: "simple_numbers",
        pageLength: 10,
        dom: "lrtip",
      });
      if (activeTabText == "" || activeTabText == "Countries") {
        $("#customSearchInput").on("keyup", function () {
          countries_Table.search(this.value).draw();
        });
      }

      //States
      $(".StatesTbl").append(
        `
        <table class="table table-hover dt-responsive nowrap statesListTable" style="width:100%;">
        <thead>
          <tr><th>S.No</th>
          <th>${Lang.get('fields.state')}</th><th>
          ${Lang.get('fields.country')}</th>
          <th>${Lang.get('fields.actions')}</th>
          </tr></thead><tbody></tbody>
          </table>`
      );
      $(".statesListTable tbody").empty();
      response.states.forEach((element, key) => {
        $(".statesListTable tbody").append(`
                    <tr><td>${key + 1}</td>
                    <td>${element["name"]}</td>
                    <td>${element["country_name"]}</td>
                    <td>
                    <button title="Edit" id="${
                      element["state_id"]
                    }" class="btn btn-outline-primary openDataSidebarForUpdateStates">
                    <svg width="10" height="13" viewBox="0 0 10 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                    d="M4.68745 11.18L4.5055 10.7143L4.68745 11.18ZM1.98319 11.6993L1.73319 12.1323H1.73319L1.98319 11.6993ZM1.08078 9.09767L0.586477 9.17295L1.08078 9.09767ZM1.40764 7.36282L1.84065 7.61282L1.40764 7.36282ZM1.01821 8.18767L0.526854 8.09509H0.526854L1.01821 8.18767ZM6.02644 10.0295L6.45946 10.2795L6.02644 10.0295ZM5.50683 10.7792L5.83268 11.1584L5.50683 10.7792ZM3.93993 2.97677L3.50692 2.72677L3.93993 2.97677ZM8.12572 5.39343L5.59343 9.77949L6.45946 10.2795L8.99175 5.89343L8.12572 5.39343ZM1.84065 7.61282L4.37295 3.22677L3.50692 2.72677L0.974629 7.11282L1.84065 7.61282ZM4.5055 10.7143C3.77452 10.9999 3.27668 11.193 2.89236 11.2828C2.52111 11.3696 2.35265 11.3352 2.23319 11.2663L1.73319 12.1323C2.1687 12.3837 2.63595 12.3697 3.11987 12.2566C3.59073 12.1466 4.16753 11.9199 4.86941 11.6457L4.5055 10.7143ZM0.586477 9.17295C0.699936 9.91791 0.792032 10.5308 0.93219 10.9935C1.07624 11.4692 1.29768 11.8808 1.73319 12.1323L2.23319 11.2663C2.11373 11.1973 1.99977 11.0686 1.88926 10.7037C1.77486 10.326 1.69324 9.79823 1.57508 9.02239L0.586477 9.17295ZM0.974629 7.11282C0.760081 7.48443 0.588305 7.76894 0.526854 8.09509L1.50956 8.28024C1.5322 8.16011 1.59202 8.04348 1.84065 7.61282L0.974629 7.11282ZM1.57508 9.02239C1.5002 8.53078 1.48693 8.40038 1.50956 8.28024L0.526854 8.09509C0.465403 8.42124 0.521869 8.74875 0.586477 9.17295L1.57508 9.02239ZM5.59343 9.77949C5.34479 10.2101 5.2737 10.3203 5.18097 10.3999L5.83268 11.1584C6.08441 10.9421 6.24491 10.6511 6.45946 10.2795L5.59343 9.77949ZM4.86941 11.6457C5.26908 11.4896 5.58095 11.3747 5.83268 11.1584L5.18097 10.3999C5.08825 10.4796 4.96869 10.5333 4.5055 10.7143L4.86941 11.6457ZM7.33267 2.43371C7.88924 2.75505 8.26258 2.97182 8.51981 3.17033C8.76556 3.35997 8.84384 3.48771 8.87564 3.60638L9.84157 3.34756C9.73042 2.93278 9.46507 2.63665 9.13075 2.37865C8.80792 2.12952 8.36476 1.87489 7.83267 1.56769L7.33267 2.43371ZM8.99175 5.89343C9.29895 5.36134 9.55542 4.91925 9.71044 4.54208C9.87097 4.15149 9.95271 3.76235 9.84157 3.34756L8.87564 3.60638C8.90744 3.72506 8.90351 3.87483 8.78551 4.16193C8.66199 4.46246 8.44706 4.83686 8.12572 5.39343L8.99175 5.89343ZM7.83267 1.56769C7.30058 1.26048 6.85848 1.00401 6.48131 0.848997C6.09072 0.688463 5.70159 0.606727 5.2868 0.717869L5.54562 1.68379C5.66429 1.652 5.81406 1.65592 6.10117 1.77392C6.4017 1.89744 6.77609 2.11237 7.33267 2.43371L7.83267 1.56769ZM4.37295 3.22677C4.69428 2.67019 4.91106 2.29686 5.10956 2.03962C5.2992 1.79388 5.42694 1.71559 5.54562 1.68379L5.2868 0.717869C4.87201 0.829011 4.57588 1.09436 4.31788 1.42869C4.06875 1.75152 3.81412 2.19468 3.50692 2.72677L4.37295 3.22677ZM8.80873 5.21042L4.18993 2.54375L3.68993 3.40978L8.30873 6.07645L8.80873 5.21042Z"
                    fill="" />
                    </svg>
                </button> 
                <button title="Delete" type="button" id="${
                  element["state_id"]
                }" class="btn btn-outline-primary btn-delete delete_geo"name="state">
                    <svg width="12" height="15" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                    d="M10.3451 8.01958L10.8404 8.0885L10.3451 8.01958ZM10.1702 9.27626L10.6655 9.34518L10.1702 9.27626ZM1.83042 9.27627L2.32564 9.20735L1.83042 9.27627ZM1.65553 8.01958L1.1603 8.0885L1.65553 8.01958ZM4.12276 13.9911L3.92836 14.4518H3.92836L4.12276 13.9911ZM2.31705 11.8735L2.78637 11.701L2.31705 11.8735ZM9.6836 11.8735L10.1529 12.0459L9.6836 11.8735ZM7.87789 13.9911L7.6835 13.5305L7.87789 13.9911ZM1.83142 5.45263C1.8053 5.17772 1.56127 4.97604 1.28637 5.00216C1.01146 5.02828 0.809781 5.27231 0.835901 5.54721L1.83142 5.45263ZM11.1648 5.54721C11.1909 5.27231 10.9892 5.02828 10.7143 5.00216C10.4394 4.97604 10.1954 5.17772 10.1692 5.45262L11.1648 5.54721ZM11.3337 4.66659C11.6098 4.66659 11.8337 4.44273 11.8337 4.16659C11.8337 3.89044 11.6098 3.66659 11.3337 3.66659V4.66659ZM0.666992 3.66659C0.39085 3.66659 0.166992 3.89044 0.166992 4.16659C0.166992 4.44273 0.39085 4.66659 0.666992 4.66659L0.666992 3.66659ZM4.16699 11.4999C4.16699 11.7761 4.39085 11.9999 4.66699 11.9999C4.94313 11.9999 5.16699 11.7761 5.16699 11.4999H4.16699ZM5.16699 6.16659C5.16699 5.89044 4.94313 5.66659 4.66699 5.66659C4.39085 5.66659 4.16699 5.89044 4.16699 6.16659H5.16699ZM6.83366 11.4999C6.83366 11.7761 7.05752 11.9999 7.33366 11.9999C7.6098 11.9999 7.83366 11.7761 7.83366 11.4999H6.83366ZM7.83366 6.16659C7.83366 5.89044 7.6098 5.66659 7.33366 5.66659C7.05752 5.66659 6.83366 5.89044 6.83366 6.16659H7.83366ZM8.66699 4.16659V4.66659H9.16699V4.16659H8.66699ZM3.33366 4.16659H2.83366V4.66659H3.33366V4.16659ZM9.8499 7.95066L9.67501 9.20735L10.6655 9.34518L10.8404 8.0885L9.8499 7.95066ZM2.32564 9.20735L2.15075 7.95066L1.1603 8.0885L1.33519 9.34519L2.32564 9.20735ZM6.00033 13.6666C4.98088 13.6666 4.61728 13.6571 4.31715 13.5305L3.92836 14.4518C4.45975 14.676 5.07071 14.6666 6.00033 14.6666L6.00033 13.6666ZM1.33519 9.34519C1.52166 10.6851 1.62303 11.4344 1.84772 12.0459L2.78637 11.701C2.60803 11.2156 2.51933 10.5991 2.32564 9.20735L1.33519 9.34519ZM4.31715 13.5305C3.70219 13.271 3.1303 12.6371 2.78637 11.701L1.84772 12.0459C2.25746 13.1611 2.98836 14.0551 3.92836 14.4518L4.31715 13.5305ZM9.67501 9.20735C9.48132 10.5991 9.39262 11.2156 9.21428 11.701L10.1529 12.0459C10.3776 11.4344 10.479 10.6851 10.6655 9.34518L9.67501 9.20735ZM6.00033 14.6666C6.92994 14.6666 7.5409 14.676 8.07229 14.4518L7.6835 13.5305C7.38337 13.6571 7.01977 13.6666 6.00033 13.6666L6.00033 14.6666ZM9.21428 11.701C8.87035 12.6371 8.29846 13.271 7.6835 13.5305L8.07229 14.4518C9.01229 14.0551 9.74319 13.1611 10.1529 12.0459L9.21428 11.701ZM2.15075 7.95066C2.00267 6.88661 1.8921 6.09127 1.83142 5.45263L0.835901 5.54721C0.899118 6.21256 1.0134 7.03295 1.1603 8.0885L2.15075 7.95066ZM10.8404 8.0885C10.9873 7.03295 11.1015 6.21256 11.1648 5.54721L10.1692 5.45262C10.1086 6.09127 9.99798 6.8866 9.8499 7.95066L10.8404 8.0885ZM11.3337 3.66659L0.666992 3.66659L0.666992 4.66659L11.3337 4.66659V3.66659ZM5.16699 11.4999L5.16699 6.16659H4.16699L4.16699 11.4999H5.16699ZM7.83366 11.4999L7.83366 6.16659H6.83366L6.83366 11.4999H7.83366ZM8.16699 3.49992V4.16659H9.16699V3.49992H8.16699ZM8.66699 3.66659L3.33366 3.66659V4.66659L8.66699 4.66659V3.66659ZM3.83366 4.16659V3.49992H2.83366V4.16659H3.83366ZM6.00033 1.33325C7.19694 1.33325 8.16699 2.3033 8.16699 3.49992H9.16699C9.16699 1.75102 7.74923 0.333252 6.00033 0.333252V1.33325ZM6.00033 0.333252C4.25142 0.333252 2.83366 1.75102 2.83366 3.49992H3.83366C3.83366 2.3033 4.80371 1.33325 6.00033 1.33325V0.333252Z"
                    fill="" /> </svg>
                </button> 
                         </td></tr>`);
      });

      states_Table = $(".statesListTable").DataTable({
        responsive: true,
        lengthChange: false,
        info: false,
        pagingType: "simple_numbers",
        pageLength: 10,
        dom: "lrtip",
      });
      if (activeTabText == "States") {
        $("#customSearchInput").on("keyup", function () {
          states_Table.search(this.value).draw();
        });
      }

      //Postal Codes

      //All Countries in select Boxs
      $(".all_countries").empty();
      $(".all_countries").append(`<option value="">${Lang.get('fields.select_country')}</option>`);
      response.countries.forEach((element) => {
        $(".all_countries").append(
          `<option value="${element["id"]}" >${element["name"]}</option>`
        );
      });
      //All States in select Boxs
      $(".all_states").empty();
      $(".all_states").append(`<option value="">${Lang.get('fields.select_state')}</option>`);

      //All States in select Boxs
      $(".all_cities").empty();
      $(".all_cities").append(`<option value="">${Lang.get('fields.select_city')}</option>`);

      //All Countries in select Boxs For Edit or Add State
      $(".all_countries_form_state").empty();
      $(".all_countries_form_state").append(
        `<option value="0">${Lang.get('fields.select_country')}*</option>`
      );
      response.countries.forEach((element) => {
        $(".all_countries_form_state").append(
          `<option value="${element["id"]}" >${element["name"]}</option>`
        );
      });
      //All Countries in select Boxs For Edit or Add City
      $(".all_countries_form").empty();
      $(".all_countries_form").append(
        `<option value="0">${Lang.get('fields.select_country')}*</option>`
      );
      response.countries.forEach((element) => {
        $(".all_countries_form").append(
          `<option value="${element["id"]}" >${element["name"]}</option>`
        );
      });
      //All States against Country For Add or Edit City
      $(".all_states_form").empty();
      $(".all_states_form").append(`<option value="0">${Lang.get('fields.select_state')}*</option>`);

      //All Countries in select Boxs For Edit or Add Postal
      $(".all_countries_form_postal").empty();
      $(".all_countries_form_postal").append(
        `<option value="0">${Lang.get('fields.select_country')}*</option>`
      );
      response.countries.forEach((element) => {
        $(".all_countries_form_postal").append(
          `<option value="${element["id"]}" >${element["name"]}</option>`
        );
      });
      //All States against Country For Add or Edit Postal
      $(".all_states_form_postal").empty();
      $(".all_states_form_postal").append(
        `<option value="0">${Lang.get('fields.select_state')}*</option>`
      );

      //All Cities agianst selected state for edit or Add
      $(".all_cities_form_postal").empty();
      $(".all_cities_form_postal").append(
        `<option value="0">${Lang.get('fields.select_city')}*</option>`
      );

      $(".loader").hide();
      $(".countriesListTable").fadeIn();
      $(".statesListTable").fadeIn();
      appendFilterSelect();
      //Cities
      if (selectedCountry && selectedCountry > 0) {
        $("#searchCountry").val(selectedCountry).trigger("change");
        if (selectedState && selectedState > 0) {
          $("#searchState").val(selectedState).trigger("change");
          $(".searchCity").trigger("click");
        }
      } else {
        $(".Cititesloader").hide();
      }
    },
  });
}
fetchGeoList();
$(document).on("click", ".searchCity", function () {
  $(".CitiesTbl").empty();

  var state = $("#searchState").find(":selected").text();
  var country = $("#searchCountry").find(":selected").text();
  if ($("#searchState").val() && $("#searchCountry").val()) {
    selectedState = $("#searchState").val();
    selectedCountry = $("#searchCountry").val();

    $(".CitiesTbl").append(
      `<table class="table table-hover dt-responsive nowrap citiesListTable" style="width:100%;">
      <thead><tr>
      <th>S.No</th>
      <th>${Lang.get('fields.city')}</th>
      <th>${Lang.get('fields.state')}</th>
      <th>${Lang.get('fields.country')}</th>
      <th>${Lang.get('fields.actions')}</th>
      </tr></thead><tbody></tbody></table>`
    );
    $(".Cititesloader").show();
    $.ajax({
      type: "GET",
      url: "/admin/get-searched-cities",
      data: {
        _token: $('meta[name="csrf-token"]').attr("content"),
        searchState: $("#searchState").val(),
        searchCountry: $("#searchCountry").val(),
      },
      success: function (response) {
        if (response.cities) {
          response.cities.forEach((element, key) => {
            $(".citiesListTable tbody").append(`
                            <tr>
                                <td>${key + 1}</td>
                                <td>${element["name"] ?? ""}</td>
                                <td>${state ?? ""}</td>
                                <td>${country ?? ""}</td>
                                <td>
                                <button title="Edit" id="${
                                  element["id"]
                                }" city-state-id="${
              element["state_id"]
            }" class="btn btn-outline-primary openDataSidebarForUpdateCity">
                                <svg width="10" height="13" viewBox="0 0 10 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                d="M4.68745 11.18L4.5055 10.7143L4.68745 11.18ZM1.98319 11.6993L1.73319 12.1323H1.73319L1.98319 11.6993ZM1.08078 9.09767L0.586477 9.17295L1.08078 9.09767ZM1.40764 7.36282L1.84065 7.61282L1.40764 7.36282ZM1.01821 8.18767L0.526854 8.09509H0.526854L1.01821 8.18767ZM6.02644 10.0295L6.45946 10.2795L6.02644 10.0295ZM5.50683 10.7792L5.83268 11.1584L5.50683 10.7792ZM3.93993 2.97677L3.50692 2.72677L3.93993 2.97677ZM8.12572 5.39343L5.59343 9.77949L6.45946 10.2795L8.99175 5.89343L8.12572 5.39343ZM1.84065 7.61282L4.37295 3.22677L3.50692 2.72677L0.974629 7.11282L1.84065 7.61282ZM4.5055 10.7143C3.77452 10.9999 3.27668 11.193 2.89236 11.2828C2.52111 11.3696 2.35265 11.3352 2.23319 11.2663L1.73319 12.1323C2.1687 12.3837 2.63595 12.3697 3.11987 12.2566C3.59073 12.1466 4.16753 11.9199 4.86941 11.6457L4.5055 10.7143ZM0.586477 9.17295C0.699936 9.91791 0.792032 10.5308 0.93219 10.9935C1.07624 11.4692 1.29768 11.8808 1.73319 12.1323L2.23319 11.2663C2.11373 11.1973 1.99977 11.0686 1.88926 10.7037C1.77486 10.326 1.69324 9.79823 1.57508 9.02239L0.586477 9.17295ZM0.974629 7.11282C0.760081 7.48443 0.588305 7.76894 0.526854 8.09509L1.50956 8.28024C1.5322 8.16011 1.59202 8.04348 1.84065 7.61282L0.974629 7.11282ZM1.57508 9.02239C1.5002 8.53078 1.48693 8.40038 1.50956 8.28024L0.526854 8.09509C0.465403 8.42124 0.521869 8.74875 0.586477 9.17295L1.57508 9.02239ZM5.59343 9.77949C5.34479 10.2101 5.2737 10.3203 5.18097 10.3999L5.83268 11.1584C6.08441 10.9421 6.24491 10.6511 6.45946 10.2795L5.59343 9.77949ZM4.86941 11.6457C5.26908 11.4896 5.58095 11.3747 5.83268 11.1584L5.18097 10.3999C5.08825 10.4796 4.96869 10.5333 4.5055 10.7143L4.86941 11.6457ZM7.33267 2.43371C7.88924 2.75505 8.26258 2.97182 8.51981 3.17033C8.76556 3.35997 8.84384 3.48771 8.87564 3.60638L9.84157 3.34756C9.73042 2.93278 9.46507 2.63665 9.13075 2.37865C8.80792 2.12952 8.36476 1.87489 7.83267 1.56769L7.33267 2.43371ZM8.99175 5.89343C9.29895 5.36134 9.55542 4.91925 9.71044 4.54208C9.87097 4.15149 9.95271 3.76235 9.84157 3.34756L8.87564 3.60638C8.90744 3.72506 8.90351 3.87483 8.78551 4.16193C8.66199 4.46246 8.44706 4.83686 8.12572 5.39343L8.99175 5.89343ZM7.83267 1.56769C7.30058 1.26048 6.85848 1.00401 6.48131 0.848997C6.09072 0.688463 5.70159 0.606727 5.2868 0.717869L5.54562 1.68379C5.66429 1.652 5.81406 1.65592 6.10117 1.77392C6.4017 1.89744 6.77609 2.11237 7.33267 2.43371L7.83267 1.56769ZM4.37295 3.22677C4.69428 2.67019 4.91106 2.29686 5.10956 2.03962C5.2992 1.79388 5.42694 1.71559 5.54562 1.68379L5.2868 0.717869C4.87201 0.829011 4.57588 1.09436 4.31788 1.42869C4.06875 1.75152 3.81412 2.19468 3.50692 2.72677L4.37295 3.22677ZM8.80873 5.21042L4.18993 2.54375L3.68993 3.40978L8.30873 6.07645L8.80873 5.21042Z"
                                fill="" />
                                </svg>
                            </button> 
                            <button title="Delete" type="button" id="${
                              element["id"]
                            }" class="btn btn-outline-primary btn-delete delete_geo"  name="city">
                                <svg width="12" height="15" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg" >
                                <path
                                d="M10.3451 8.01958L10.8404 8.0885L10.3451 8.01958ZM10.1702 9.27626L10.6655 9.34518L10.1702 9.27626ZM1.83042 9.27627L2.32564 9.20735L1.83042 9.27627ZM1.65553 8.01958L1.1603 8.0885L1.65553 8.01958ZM4.12276 13.9911L3.92836 14.4518H3.92836L4.12276 13.9911ZM2.31705 11.8735L2.78637 11.701L2.31705 11.8735ZM9.6836 11.8735L10.1529 12.0459L9.6836 11.8735ZM7.87789 13.9911L7.6835 13.5305L7.87789 13.9911ZM1.83142 5.45263C1.8053 5.17772 1.56127 4.97604 1.28637 5.00216C1.01146 5.02828 0.809781 5.27231 0.835901 5.54721L1.83142 5.45263ZM11.1648 5.54721C11.1909 5.27231 10.9892 5.02828 10.7143 5.00216C10.4394 4.97604 10.1954 5.17772 10.1692 5.45262L11.1648 5.54721ZM11.3337 4.66659C11.6098 4.66659 11.8337 4.44273 11.8337 4.16659C11.8337 3.89044 11.6098 3.66659 11.3337 3.66659V4.66659ZM0.666992 3.66659C0.39085 3.66659 0.166992 3.89044 0.166992 4.16659C0.166992 4.44273 0.39085 4.66659 0.666992 4.66659L0.666992 3.66659ZM4.16699 11.4999C4.16699 11.7761 4.39085 11.9999 4.66699 11.9999C4.94313 11.9999 5.16699 11.7761 5.16699 11.4999H4.16699ZM5.16699 6.16659C5.16699 5.89044 4.94313 5.66659 4.66699 5.66659C4.39085 5.66659 4.16699 5.89044 4.16699 6.16659H5.16699ZM6.83366 11.4999C6.83366 11.7761 7.05752 11.9999 7.33366 11.9999C7.6098 11.9999 7.83366 11.7761 7.83366 11.4999H6.83366ZM7.83366 6.16659C7.83366 5.89044 7.6098 5.66659 7.33366 5.66659C7.05752 5.66659 6.83366 5.89044 6.83366 6.16659H7.83366ZM8.66699 4.16659V4.66659H9.16699V4.16659H8.66699ZM3.33366 4.16659H2.83366V4.66659H3.33366V4.16659ZM9.8499 7.95066L9.67501 9.20735L10.6655 9.34518L10.8404 8.0885L9.8499 7.95066ZM2.32564 9.20735L2.15075 7.95066L1.1603 8.0885L1.33519 9.34519L2.32564 9.20735ZM6.00033 13.6666C4.98088 13.6666 4.61728 13.6571 4.31715 13.5305L3.92836 14.4518C4.45975 14.676 5.07071 14.6666 6.00033 14.6666L6.00033 13.6666ZM1.33519 9.34519C1.52166 10.6851 1.62303 11.4344 1.84772 12.0459L2.78637 11.701C2.60803 11.2156 2.51933 10.5991 2.32564 9.20735L1.33519 9.34519ZM4.31715 13.5305C3.70219 13.271 3.1303 12.6371 2.78637 11.701L1.84772 12.0459C2.25746 13.1611 2.98836 14.0551 3.92836 14.4518L4.31715 13.5305ZM9.67501 9.20735C9.48132 10.5991 9.39262 11.2156 9.21428 11.701L10.1529 12.0459C10.3776 11.4344 10.479 10.6851 10.6655 9.34518L9.67501 9.20735ZM6.00033 14.6666C6.92994 14.6666 7.5409 14.676 8.07229 14.4518L7.6835 13.5305C7.38337 13.6571 7.01977 13.6666 6.00033 13.6666L6.00033 14.6666ZM9.21428 11.701C8.87035 12.6371 8.29846 13.271 7.6835 13.5305L8.07229 14.4518C9.01229 14.0551 9.74319 13.1611 10.1529 12.0459L9.21428 11.701ZM2.15075 7.95066C2.00267 6.88661 1.8921 6.09127 1.83142 5.45263L0.835901 5.54721C0.899118 6.21256 1.0134 7.03295 1.1603 8.0885L2.15075 7.95066ZM10.8404 8.0885C10.9873 7.03295 11.1015 6.21256 11.1648 5.54721L10.1692 5.45262C10.1086 6.09127 9.99798 6.8866 9.8499 7.95066L10.8404 8.0885ZM11.3337 3.66659L0.666992 3.66659L0.666992 4.66659L11.3337 4.66659V3.66659ZM5.16699 11.4999L5.16699 6.16659H4.16699L4.16699 11.4999H5.16699ZM7.83366 11.4999L7.83366 6.16659H6.83366L6.83366 11.4999H7.83366ZM8.16699 3.49992V4.16659H9.16699V3.49992H8.16699ZM8.66699 3.66659L3.33366 3.66659V4.66659L8.66699 4.66659V3.66659ZM3.83366 4.16659V3.49992H2.83366V4.16659H3.83366ZM6.00033 1.33325C7.19694 1.33325 8.16699 2.3033 8.16699 3.49992H9.16699C9.16699 1.75102 7.74923 0.333252 6.00033 0.333252V1.33325ZM6.00033 0.333252C4.25142 0.333252 2.83366 1.75102 2.83366 3.49992H3.83366C3.83366 2.3033 4.80371 1.33325 6.00033 1.33325V0.333252Z"
                                fill="" /> </svg>
                            </button>  
                                </td></tr>`);
          });
        }

        $(".Cititesloader").hide();
        $(".citiesListTable").fadeIn();
        cities_Table = $(".citiesListTable").DataTable({
          responsive: true,
          lengthChange: false,
          info: false,
          pagingType: "simple_numbers",
          pageLength: 10,
          dom: "lrtip",
        }); 
        if (activeTabText == "Cities" || activeTabText == "Ciudades") {
          $(".filters-citites").fadeIn();
          $("#customSearchInput").on("keyup", function () {
            cities_Table.search(this.value).draw();
          });
        } else {
          $(".filters-citites").fadeOut();
        }
      },
    });
  } else {
    $("#notifDiv").fadeIn();
    $("#notifDiv").css("background", "red");
    $("#notifDiv").text("Please ${Lang.get('fields.select_country')} and State first");
    setTimeout(() => {
      $("#notifDiv").fadeOut();
    }, 3000);
  }
  $(this).attr("disabled", false);
});
function appendFilterSelect() {
  $("#searchCountry").empty();
  $("#searchCountry").append(`<option value="">${Lang.get('fields.select_country')}</option>`);

  $("#searchState").append(`<option value="">${Lang.get('fields.select_state')}</option>`);
  countries_search.forEach((element) => {
    $("#searchCountry").append(
      `<option value="${element["id"]}">${element["name"]}</option>`
    );
  });
}

$(document).on("change", "#searchCountry", function () {
  $("#searchState").empty();
  $("#searchState").append(`<option value="">${Lang.get('fields.select_state')}</option>`);
  if ($(this).val()) {
    let filterState = states_search.filter(
      (state) => state.country_id == $(this).val()
    );
    filterState.forEach((element) => {
      $("#searchState").append(
        `<option value="${element["state_id"]}">${element["name"]}</option>`
      );
    });
  }
});

$(document).on("click", ".geographical-tabs", function () {
  activeTabText = $(this).text().trim();
  var field = `Search ${activeTabText} Here...`;
  field =  Lang.get();

  $("#customSearchInput").attr(
    "placeholder",
    `Search ${activeTabText} Here...`
  );
  var tableName =
    activeTabText == "Countries"
      ? countries_Table
      : activeTabText == "States"
      ? states_Table
      : activeTabText == "Cities"
      ? cities_Table
      : null;
  if (activeTabText == "Cities" || activeTabText == "Ciudades") {
    $(".filters-citites").fadeIn();
  } else {
    $(".filters-citites").fadeOut();
  }
  $("#customSearchInput").off("keyup");

  if (tableName) {
    $("#customSearchInput").on("keyup", function () {
      tableName.search(this.value).draw();
    });
  }
});
