<form action="{{ route('admin.jamaah.remove-from-package', $jamaah->hash) }}" method="post"
      data-kt-form-id="delete-{{ $jamaah->hash }}">
    @csrf
    @method('DELETE')
    <input name="package_id" value="{{ request()->input('package_id') }}" hidden="hidden">
    <a class="menu-link px-3 btn-delete btn" data-id="{{ $jamaah->hash }}"
       data-bs-toggle="tooltip" title="Hapus Jamaah Dari Paket">
            <span class="svg-icon svg-icon-3">
                <i class="fa-solid fa-trash fs-2 text-danger"></i>
              </span>
    </a>
</form>
