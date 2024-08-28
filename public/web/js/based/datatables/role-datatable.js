"use strict";

// Class definition
let roleIndex = function () {

  // Shared variables
  let table;
  let dt;
  let filterStatus;

  $('#apply-filter').click(function () {
    initDatatable();
  });

  // Private functions
  let initDatatable = function () {
    dt = $("#kt_datatable_example_1").DataTable({
      searchDelay: 500,
      processing: false,
      serverSide: true,
      destroy: true,
      select: {
        style: 'multi',
        selector: 'td:first-child input[type="checkbox"]',
        className: 'row-selected'
      },
      ajax: {
        url: urlTable,
        type: 'post',
        dataType: "json",
        data: {
          _token: csrf_token,
          filter: $('#form-filter').serializeArray(),
        },
        error: function(error){
          Swal.fire({
            icon: error.responseJSON.icon,
            title: error.responseJSON.title,
            text: error.statusText,
          });
        }
      },
      columns: columns,
      columnDefs: [
        {
          targets: 0,
          orderable: false,
          visible: false,
          render: function (data) {
            return `<div class="form-check form-check-sm form-check-custom form-check-solid">
                <input class="form-check-input" type="checkbox" value="${data}" />
            </div>`;
          }
        },
        {
          targets: -1,
          data: null,
          orderable: false,
          className: 'text-end',
          render: function (data, type, row) {
            return data;
          },
        },
      ],
      // Add data-filter attribute
      createdRow: function (row, data, dataIndex) {
        $(row).find('td:eq(5)').attr('data-filter', data.name);
      }
    }),
      $("#kt_datatable_example_1 tbody").on("click", ".btn-edit-modal", function (e) {
        e.preventDefault();
        let hash = $(this).attr("data-id");
        let urlEdit = editUrl.replace(":id", hash);
        $.ajax({
          url: urlEdit,
          type: "GET",
          success: function (data) {
            if (!$("#modal-add-admin").is(":visible")) {
              $("#dynamic_modal").html(data.view);
              $("#modal-add-admin").modal("show");
            }
          },
          error: function (error) {
            Swal.fire({
              icon: error.responseJSON.icon,
              title: error.responseJSON.title,
              text: error.responseJSON.message,
              footer:
                '<a href="">Error Code: ' +
                error.status +
                ", " +
                error.statusText +
                "...</a>",
            });
          },
        });
      }),
      $("#kt_datatable_example_1 tbody").on("click", ".change-status", function (e) {
        e.preventDefault();
        let status = $(this).attr("data-status");
        let hash = $(this).attr("data-id");
        const form = $('form[data-kt-form-id="change-status-'+hash+'"]');
        form.append('<input type="hidden" name="status" value="'+status+'" />')
        form.submit();
      }),
      $("#kt_datatable_example_1 tbody").on("click", ".btn-delete", function (e) {
        e.preventDefault();
        let hash = $(this).attr("data-id");
        const form = $('form[data-kt-form-id="delete-'+hash+'"]');
        form.submit();
      });
    table = dt.$;
    // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
    dt.on('draw', function () {
      // initToggleToolbar();
      // toggleToolbars();
      // handleDeleteRows();
      KTMenu.createInstances();
    });
  };

  let handleCreate = function () {
    $('#create-new').click(function () {
      $.ajax({
        url: createUrl,
        type: "GET",
        success: function (data) {
          if (!$("#modal-role").is(":visible")) {
            $("#dynamic_modal").html(data.view);
            let modal = new bootstrap.Modal('#modal-role')
            modal.show();

            /* begin:: dismiss modal helper*/
            $("[data-bs-dismiss=modal]").click(function(){
              if ($("#modal-role").is(":visible")) {
                modal.hide();
              }

            });
            /* end:: dismiss modal helper*/
          }
        },
        error: function (error) {
          Swal.fire({
            icon: error.responseJSON.icon,
            title: error.responseJSON.title,
            text: error.responseJSON.message,
            footer:
              '<a href="">Error Code: ' +
              error.status +
              ", " +
              error.statusText +
              "...</a>",
          });
        },
      });
    });
  }

  let handleUpdate = function () {
    $("#kt_datatable_example_1 tbody").on("click", ".update-role", function (e) {
      e.preventDefault();

      let hash = $(this).attr("data-id");

      let urlEdit = editUrl.replace(":id", hash);

      $.ajax({
        url: urlEdit,
        type: "GET",
        success: function (data) {
          if (!$("#modal-role").is(":visible")) {
            $("#dynamic_modal").html(data.view);
            let modal = new bootstrap.Modal('#modal-role')
            modal.show();

            /* begin:: dismiss modal helper*/
            $("[data-bs-dismiss=modal]").click(function(){
              if ($("modal-role").is(":visible")) {
                modal.hide();
              }

            });
            /* end:: dismiss modal helper*/
          }
        },
        error: function (error) {
          Swal.fire({
            icon: error.responseJSON.icon,
            title: error.responseJSON.title,
            text: error.responseJSON.message,
            footer:
              '<a href="">Error Code: ' +
              error.status +
              ", " +
              error.statusText +
              "...</a>",
          });
        },
      });

    });
  }

  let handleDelete = function () {
    $("#kt_datatable_example_1 tbody").on("click", ".btn-delete", function (e) {
      e.preventDefault();
      let hash = $(this).attr("data-id");
      const form = $('form[data-kt-form-id="delete-' + hash + '"]');
      form.submit();
    });
  }

  // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
  let handleSearchDatatable = function () {
    const filterSearch = document.querySelector('[data-kt-user-table-filter="search"]');
    filterSearch.addEventListener('keypress', function (e) {
      // If the user presses the "Enter" key on the keyboard
      if (e.key === "Enter") {
        // Cancel the default action, if needed
        e.preventDefault();
        // Trigger the button element with a click
        dt.search(e.target.value).draw();
      }
    });
  }


// Filter Datatable
  let handleFilterDatatable = () => {
    // Select filter options
    $('[data-kt-user-table-filter="role_name"]').select2({
      templateSelection: function (data, container) {
        // Add custom attributes to the <option> tag for the selected option
        $(data.element).attr('data-custom-attribute', data.customValue);
        return data.text;
      }
    });
    const filterButton = document.querySelector('[data-kt-user-table-filter="filter"]');

    // Filter datatable on submit
    filterButton.addEventListener('click', function () {
      // Get filter values
      filterStatus = $('[data-kt-user-table-filter="role_name"] :selected').text();
      // Filter datatable --- official docs reference: https://datatables.net/reference/api/search()
      dt.search(filterStatus).draw();
    });

    // Select filter options
    $('[data-kt-user-table-filter="travel_name"]').select2({
      templateSelection: function (data, container) {
        // Add custom attributes to the <option> tag for the selected option
        $(data.element).attr('data-custom-attribute', data.customValue);
        return data.text;
      }
    });

    // Filter datatable on submit
    filterButton.addEventListener('click', function () {
      // Get filter values
      filterStatus = $('[data-kt-user-table-filter="travel_name"] :selected').text();
      // Filter datatable --- official docs reference: https://datatables.net/reference/api/search()
      dt.search(filterStatus).draw();
    });
  }


  // Public methods
  return {
    init: function () {
      initDatatable();
      handleCreate();
      handleUpdate();
      handleDelete();
      handleSearchDatatable();
      handleFilterDatatable();
    }
  }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
  roleIndex.init();
});





