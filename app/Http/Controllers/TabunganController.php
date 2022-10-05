<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TabunganController extends Controller
{
    protected $moneyboxs = []; // tabungan
    protected $moneyboxsHistories = []; // history tabungan

    public function __construct()
    {
        $this->moneyboxs = [
            [
                'id' => 1,
                'savings' => '900.000',
                'lastSavings' => \Carbon\Carbon::now()->addDays(rand(-10, 0))->format('d M Y, H:i'),
                'showDetails' => true,
            ],
            [
                'id' => 2,
                'namaTabungan' => 'Tabungan Umrah 2024',
                'lastSavings' => \Carbon\Carbon::now()->addDays(rand(-10, 0))->format('d M Y, H:i'),
                'savings' => '12.500.000',
                'targetSavings' => '32.000.000',
                'showDetails' => true,
            ],
            [
                'id' => 3,
                'namaTabungan' => 'Tabungan Wisata',
                'lastSavings' => \Carbon\Carbon::now()->addDays(rand(-10, 0))->format('d M Y, H:i'),
                'savings' => '890.000',
                'targetSavings' => '2.000.000',
                'showDetails' => true,
            ]
        ];
    }

    public function index()
    {
        /* tampilkan semua tabungan yang ada */

        return view('pages.mobile.tabungan.tabungan-index', [
            'list_moneyboxs' => collect($this->moneyboxs),
        ]);
    }

    public function show($id)
    {
        $moneybox = collect($this->moneyboxs)->where('id', $id)->first();
        return view('pages.mobile.tabungan.tabungan-show', [
            'moneybox' => array_merge( $moneybox, ['showDetails' => false]),
            'moneyboxs_histories' => collect($this->moneyboxsHistories),
        ]);
    }
}
