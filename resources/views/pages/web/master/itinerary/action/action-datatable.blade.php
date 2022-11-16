@can("update {$current_page}")
  <a type="button" data-id="{{ $itinerary->hash }}" data-bs-toggle="tooltip"
     title="Edit Kegiatan"
     class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btn-edit-modal">
  <span class="svg-icon svg-icon-3">
    <i class="fa-solid fa-pen-to-square"></i>
  </span>
    <!--end::Svg Icon-->
  </a>
@endcan

@can("delete {$current_page}")
  <a class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 text-muted btn-delete" data-id="{{ $itinerary->hash }}"
     data-bs-toggle="tooltip" title="Hapus Kegiatan">
    <i class="fa-solid fa-trash"></i>
    <form action="{{ route('admin.itinerary.destroy', $itinerary->hash) }}" method="post"
          data-kt-form-id="delete-{{ $itinerary->hash }}">
      @csrf
      @method('DELETE')

    </form>

  </a>


@endcan
