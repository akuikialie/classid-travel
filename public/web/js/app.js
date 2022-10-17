
	$(document).on("click", "table.table-row-addable .add-row", function () {
		console.log('dasdas');
		var row = $(this).closest("table").find("tbody tr:first-child").clone();

		$("input, textarea, select", $(row)).val("");

		$(row).appendTo($(this).closest("table").find("tbody"));
	});

	$(document).on("click", "table.table-row-addable .remove-row", function () {
		var row = $(this).closest("tr");
		$(row).fadeOut().detach();
	});

	$(document).on("click", ".btn-delete", function (e) {
		e.preventDefault();

		var _this = $(this);

		swal.fire({
			title: "Are You Sure?",
			text: _this.data().confirm,
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "OK!",
			closeOnConfirm: false
		}).then(function (result) {
			if (result.value) {
				$.ajax({
					url: _this.data().url,
					method: "delete",
					data: {
						_token: $('meta[name=csrf-token').attr('content') //window.Laravel.csrfToken
					},
					success: function success(response) {
						swal.fire({
							position: 'top-right',
							type: 'success',
							title: 'Deleted!',
							text: response.message,
							showConfirmButton: false,
							timer: 2000
						}).then(function () {
							response.redirect ? window.location.href = response.redirect : window.location.reload();
						});
					},
					error: function error(response) {
						swal.fire("Failed!", response.message, "error");
					}
				});
			}
		});
	});

	$(document).on("change", ".role-selection-table input[type=checkbox]", function (e) {
		var data = $(this).data();
		var dependencies = data.dependencies.split(",");

		dependencies.forEach(function (item, index) {
			$('input[value="' + data.namespace + "." + item + '"]').prop("checked", true);
		});
	});

	$(document).on("change", "table thead input[type=checkbox]", function (e) {
		var $table = $(this).closest("table");
		if ($(this).is(":checked") == true) {
			$("tbody input[type=checkbox]", $($table)).prop("checked", true);
		} else {
			$("tbody input[type=checkbox]", $($table)).prop("checked", false);
		}
	});

	_flatDate = $('.flat-date').flatpickr({
		wrap: true,
		altInput: true,
		altFormat: "d F Y",
		dateFormat: "Y-m-d"
	});

	_flatDate = $('.flat-datetime').flatpickr({
		wrap: true,
		altInput: true,
		enableTime: true,
		time_24hr: true,
		altFormat: "d F Y, H:i",
		dateFormat: "Y-m-d H:i:s"
	});

	$('.time').flatpickr({
		wrap: true,
		enableTime: true,
		time_24hr: true,
		noCalendar: true,
		altFormat: "H:i",
		dateFormat: "H:i"
	});
