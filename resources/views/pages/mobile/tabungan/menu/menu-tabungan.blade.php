<div class="card card-style">
    <div class="content">
        <h3 class="font-600">{{ $namaTabungan ?? 'Tabungan Pribadi' }}</h3>
        <p class="font-11 mt-n2 color-highlight">VA: {{ $va }}</p>

        <div class="float-start">
            <p class="font-12 opacity-80 mb-n1"><i class="far fa-calendar"></i>
                {{ (($savings ?? 0) > 0) ? 'Tabungan' : 'Belum Pernah Menabung' }}</p>
            <p class="font-12 opacity-100"> <strong><i class="fa-solid fa-wallet"></i> Rp
                    {{ $savings ?? '0' }}</strong> <small>dari Rp
                    {{ $targetSavings ?? '~' }}</small></p>

        </div>
    </div>
    <div class="body">
        <div class="d-flex justify-content-end">
            <div class=""><a href="{{ route('tabungan.billing', $moneybox['id']) }}"
                    class="back-button btn btn-s bg-highlight rounded-s shadow-xl text-uppercase font-900 font-11 me-2 mb-2">Tabung</a>
            </div>
            @if (isset($showDetails) && $showDetails)
                <div class=""><a href="{{ route('tabungan.show', $moneybox['id']) }}" data-menu="mulai-menabung"
                        class="back-button btn btn-border border-highlight btn-center-s color-highlight rounded-sm font-700 text-uppercase me-2 mb-2">Rincian</a>
                </div>
            @endif
        </div>
    </div>
</div>



