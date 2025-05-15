<?php

namespace App\Exports;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UserJamaahExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, WithEvents, WithColumnWidths, WithCustomValueBinder
{
    use Exportable;

    private static $lastColoumn = "A";
    private static $startRow = 1;

    private $rowNumber = 1;

    private $fileName;

    public function __construct(
        protected Builder $query,
    )
    {
        $this->fileName = "Data Jamaah" . date("Y-m-d") . ".xlsx";
    }

    public function collection()
    {
        return $this->query->get();
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 40,
            'C' => 25,
            'D' => 20,
            'E' => 20,
        ];
    }

    public function headings(): array
    {
        $header = [
            "A" => "No.",
            "B" => "Nama Jamaah",
            "C" => "Status",
            "D" => "Terakhir Login",
            "E" => "Tanggal Pendaftaran",
        ];

        $headers = [
            ["A" => "Data Jamaah"],
            ["A" => ""],

            $header,
        ];

        self::$startRow = count($headers);

        end($header);
        self::$lastColoumn = key($header);

        return $headers;
    }

    public function map($row): array
    {
        /** @var User $row */

        return [
            $this->rowNumber++,
            $row->name,
            $row->status,
            Carbon::parse($row->last_login_at)->toDateTimeString(),
            Carbon::parse($row->created_at)->toDateTimeString(),
        ];
    }

    public function columnFormats(): array
    {
        return [
            "B" => NumberFormat::FORMAT_TEXT,
            "C" => NumberFormat::FORMAT_TEXT,
            "D" => NumberFormat::FORMAT_DATE_DATETIME,
            'E' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $worksheet = $event->sheet->getDelegate();
                $this->setHeaderStyle($worksheet);
                $this->setHeaderTableStyle($worksheet);
                $this->setTableStyle($worksheet);
            },
        ];
    }

    private function setHeaderStyle(Worksheet $worksheet)
    {
        $lastCol = self::$lastColoumn;
        $lastRow = self::$startRow - 1;

        for ($x = 1; $x < $lastRow; ++$x) {
            $cellRange = "A{$x}:{$lastCol}{$x}";
            $worksheet->mergeCells($cellRange)
                ->getStyle($cellRange)
                ->applyFromArray([
                    "font" => [
                        "bold" => true
                    ],
                    "alignment" => [
                        "horizontal" => Alignment::HORIZONTAL_CENTER
                    ],
                ]);
        }
    }

    private function setHeaderTableStyle(Worksheet $worksheet)
    {
        $lastColoumn = self::$lastColoumn;
        $startRow = self::$startRow;

        $worksheet->getStyle("A{$startRow}:{$lastColoumn}{$startRow}")
            ->applyFromArray([
                "fill" => [
                    "fillType" => Fill::FILL_SOLID,
                    "color" => [
                        "argb" => "ffcccccc"
                    ]
                ],
                "font" => [
                    "bold" => true
                ],
                "alignment" => [
                    "horizontal" => Alignment::HORIZONTAL_CENTER
                ],
            ]);
    }

    private function setTableStyle(Worksheet $worksheet)
    {
        $lastCell = self::$lastColoumn . ($worksheet->getHighestRow());
        $startRow = self::$startRow;

        $worksheet->getStyle("A{$startRow}:{$lastCell}")
            ->applyFromArray([
                "borders" => [
                    "allBorders" => [
                        "borderStyle" => Border::BORDER_THIN,
                    ],
                ],
                "alignment" => [
                    "vertical" => Alignment::VERTICAL_CENTER,
                    "horizontal" => Alignment::HORIZONTAL_CENTER
                ],
            ]);
    }

    /**
     * @param Cell $cell
     * @param $value
     * @return bool
     * @throws Exception
     */
    public function bindValue(Cell $cell, $value): bool
    {
        if (is_numeric($value)) {
            $dateColumn = ['D', 'E'];
            if (in_array($cell->getColumn(), $dateColumn)) {
                $cell->setValueExplicit($value, DataType::TYPE_ISO_DATE);
            } else {
                $cell->setValueExplicit($value, DataType::TYPE_STRING);
            }
            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }
}
