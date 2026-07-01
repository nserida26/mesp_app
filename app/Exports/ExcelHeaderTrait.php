<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

trait ExcelHeaderTrait
{
    // Subclasses must declare: protected string $sheetTitle = '...';
    // and $this->headerRowCount = 5 (constant across all sheets)

    protected int $headerRowCount = 5;

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $n     = $this->headerRowCount;

                // ── Insert n blank rows at the top ──────────────────────────
                $sheet->insertNewRowBefore(1, $n);

                $highestCol = $sheet->getHighestColumn();
                $colIdx     = Coordinate::columnIndexFromString($highestCol);
                $last       = Coordinate::stringFromColumnIndex($colIdx);

                // ── Row 1 : Logo A1, Slogan B1, Ministry name C1:last ───────
                $sheet->getRowDimension(1)->setRowHeight(80);
                $sheet->mergeCells("C1:{$last}1");
                $sheet->setCellValue('C1',
                    "وزارة التعليم العالي والبحث العلمي\n" .
                    "Ministère de l'Enseignement Supérieur et de la Recherche Scientifique"
                );
                $sheet->getStyle("A1:{$last}1")->applyFromArray([
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF1A7A4A'],
                    ],
                ]);
                $sheet->getStyle('C1')->applyFromArray([
                    'font'      => ['color' => ['argb' => 'FFFFFFFF'], 'size' => 13, 'bold' => true],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                        'wrapText'   => true,
                    ],
                ]);

                // Logo
                $logoPath = public_path('assets/logo_rim.png');
                if (file_exists($logoPath)) {
                    $logo = new Drawing();
                    $logo->setName('Logo');
                    $logo->setDescription('Logo MESRS');
                    $logo->setPath($logoPath);
                    $logo->setHeight(68);
                    $logo->setCoordinates('A1');
                    $logo->setOffsetX(6);
                    $logo->setOffsetY(6);
                    $logo->setWorksheet($sheet);
                }

                // Slogan
                $sloganPath = public_path('assets/slogan_rim.png');
                if (file_exists($sloganPath)) {
                    $slogan = new Drawing();
                    $slogan->setName('Slogan');
                    $slogan->setDescription('Slogan');
                    $slogan->setPath($sloganPath);
                    $slogan->setHeight(55);
                    $slogan->setCoordinates('B1');
                    $slogan->setOffsetX(4);
                    $slogan->setOffsetY(12);
                    $slogan->setWorksheet($sheet);
                }

                // ── Row 2 : Portal name ──────────────────────────────────────
                $sheet->getRowDimension(2)->setRowHeight(32);
                $sheet->mergeCells("A2:{$last}2");
                $sheet->setCellValue('A2', 'المنصة الوطنية للتعليم العالي الخاص — Plateforme de l\'Enseignement Supérieur Privé');
                $sheet->getStyle("A2:{$last}2")->applyFromArray([
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF166B41']],
                    'font'      => ['color' => ['argb' => 'FFFFFFFF'], 'size' => 11, 'bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // ── Row 3 : Sheet title + date ───────────────────────────────
                $sheet->getRowDimension(3)->setRowHeight(26);
                $sheet->mergeCells("A3:{$last}3");
                $sheet->setCellValue('A3',
                    ($this->sheetTitle ?? 'Export') . '  —  Export du ' . now()->format('d/m/Y à H:i')
                );
                $sheet->getStyle("A3:{$last}3")->applyFromArray([
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFEAF7F0']],
                    'font'      => ['color' => ['argb' => 'FF115635'], 'size' => 10, 'italic' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // ── Row 4 : thin green separator ────────────────────────────
                $sheet->getRowDimension(4)->setRowHeight(6);
                $sheet->getStyle("A4:{$last}4")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FF2ECC8B');

                // ── Row 5 (= $n) : column headers ────────────────────────────
                $sheet->getRowDimension($n)->setRowHeight(28);
                $sheet->getStyle("A{$n}:{$last}{$n}")->applyFromArray([
                    'fill'    => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1A7A4A']],
                    'font'    => ['color' => ['argb' => 'FFFFFFFF'], 'size' => 9, 'bold' => true],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                        'wrapText'   => true,
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF166B41']],
                    ],
                ]);

                // ── Data rows : alternating rows + light borders ─────────────
                $totalRows = $sheet->getHighestRow();
                for ($row = $n + 1; $row <= $totalRows; $row++) {
                    $bg = ($row % 2 === 0) ? 'FFF0FAF5' : 'FFFFFFFF';
                    $sheet->getStyle("A{$row}:{$last}{$row}")->applyFromArray([
                        'fill'    => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $bg]],
                        'font'    => ['size' => 9],
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFD1FAE5']],
                        ],
                        'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                    ]);
                }

                // ── Auto-size columns ────────────────────────────────────────
                for ($i = 1; $i <= $colIdx; $i++) {
                    $col = Coordinate::stringFromColumnIndex($i);
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // ── Freeze pane below header block ───────────────────────────
                $sheet->freezePane('A' . ($n + 1));
            },
        ];
    }
}
