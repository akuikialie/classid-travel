"use strict";

// Class definition
let roleIndex = function () {

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
    $(".update-role").click(function (e) {
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

  // Public methods
  return {
    init: function () {
      handleCreate();
      handleUpdate();
      handleDelete();
    }
  }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
  roleIndex.init();
});
// Shared variables
let table;
let dt;
let filterStatus;

// Private functions
let initDatatable = function () {
  dt = $("#kt_datatable_example_1").DataTable({
    searchDelay: 500,
    processing: false,
    serverSide: true,
    destroy: true,
    order: [[5, 'desc']],
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
        scope_display_users: searchUsers,
      },
      error: function(error){
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
      }
    },
    columns: [
      {data: 'id'},
      {data: 'name'},
      {data: 'role'},
      {data: 'status'},
      {data: 'last_login'},
      {data: 'actions'},
    ],
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
        visible: false,
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
    initToggleToolbar();
    // toggleToolbars();
    handleDeleteRows();
    KTMenu.createInstances();
  });

  handleSearchDatatable();
  handleFilterDatatable();
};

// Init toggle toolbar
let initToggleToolbar = function () {
  // Toggle selected action toolbar
  // Select all checkboxes
  const container = document.querySelector('#kt_datatable_example_1');
  const checkboxes = container.querySelectorAll('[type="checkbox"]');

  // Select elements
  const deleteSelected = document.querySelector('[data-kt-docs-table-select="delete_selected"]');

  // Toggle delete selected toolbar
  checkboxes.forEach(c => {
    // Checkbox on click event
    c.addEventListener('click', function () {
      setTimeout(function () {
        // toggleToolbars();
      }, 50);
    });
  });

  // Deleted selected rows
  deleteSelected.addEventListener('click', function () {
    // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
    Swal.fire({
      text: "Are you sure you want to delete selected customers?",
      icon: "warning",
      showCancelButton: true,
      buttonsStyling: false,
      showLoaderOnConfirm: true,
      confirmButtonText: "Yes, delete!",
      cancelButtonText: "No, cancel",
      customClass: {
        confirmButton: "btn fw-bold btn-danger",
        cancelButton: "btn fw-bold btn-active-light-primary"
      },
    }).then(function (result) {
      if (result.value) {
        // Simulate delete request -- for demo purpose only
        Swal.fire({
          text: "Deleting selected customers",
          icon: "info",
          buttonsStyling: false,
          showConfirmButton: false,
          timer: 2000
        }).then(function () {
          Swal.fire({
            text: "You have deleted all selected customers!.",
            icon: "success",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            customClass: {
              confirmButton: "btn fw-bold btn-primary",
            }
          }).then(function () {
            // delete row data from server and re-draw datatable
            dt.draw();
          });

          // Remove header checked box
          const headerCheckbox = container.querySelectorAll('[type="checkbox"]')[0];
          headerCheckbox.checked = false;
        });
      } else if (result.dismiss === 'cancel') {
        Swal.fire({
          text: "Selected customers was not deleted.",
          icon: "error",
          buttonsStyling: false,
          confirmButtonText: "Ok, got it!",
          customClass: {
            confirmButton: "btn fw-bold btn-primary",
          }
        });
      }
    });
  });
}

// Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
let handleSearchDatatable = function () {
  const filterSearch = document.querySelector('[data-kt-user-table-filter="search"]');
  filterSearch.addEventListener('keyup', function (e) {
    dt.search(e.target.value).draw();
  });
}

// Filter Datatable
let handleFilterDatatable = () => {
  // Select filter options
  $('[data-kt-user-table-filter="role"]').select2({
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
    filterStatus = $('[data-kt-user-table-filter="role"] :selected').text();
    // Filter datatable --- official docs reference: https://datatables.net/reference/api/search()
    dt.search(filterStatus).draw();
  });
}

// Delete customer
let handleDeleteRows = () => {
  // Select all delete buttons
  const deleteButtons = document.querySelectorAll('[data-kt-user-table-filter="delete_row"]');

  deleteButtons.forEach(d => {
    // Delete button on click
    d.addEventListener('click', function (e) {
      e.preventDefault();

      // Select parent row
      const parent = e.target.closest('tr');

      // Get customer name
      const customerName = parent.querySelectorAll('td')[1].innerText;

      // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
      Swal.fire({
        text: "Are you sure you want to delete " + customerName + "?",
        icon: "warning",
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonText: "Yes, delete!",
        cancelButtonText: "No, cancel",
        customClass: {
          confirmButton: "btn fw-bold btn-danger",
          cancelButton: "btn fw-bold btn-active-light-primary"
        }
      }).then(function (result) {
        if (result.value) {
          // Simulate delete request -- for demo purpose only
          Swal.fire({
            text: "Deleting " + customerName,
            icon: "info",
            buttonsStyling: false,
            showConfirmButton: false,
            timer: 2000
          }).then(function () {
            Swal.fire({
              text: "You have deleted " + customerName + "!.",
              icon: "success",
              buttonsStyling: false,
              confirmButtonText: "Ok, got it!",
              customClass: {
                confirmButton: "btn fw-bold btn-primary",
              }
            }).then(function () {
              // delete row data from server and re-draw datatable
              dt.draw();
            });
          });
        } else if (result.dismiss === 'cancel') {
          Swal.fire({
            text: customerName + " was not deleted.",
            icon: "error",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            customClass: {
              confirmButton: "btn fw-bold btn-primary",
            }
          });
        }
      });
    })
  });
}



