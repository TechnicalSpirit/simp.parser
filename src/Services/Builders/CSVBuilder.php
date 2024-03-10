<?php

namespace SimpParser\Services\Builders;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use SimpParser\Contracts\Interfaces\InterfaceFileBuilder;
use SimpParser\Services\Logger\Log;

class CSVBuilder implements InterfaceFileBuilder
{

    public function buildFile(array $data, string $fileName): void
    {
        Log::info("start: creation of CSV [$fileName]");

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray([array_keys($data[0])]);

        for($i = 0; $i < count($data); $i++)
        {
            $rowNumber = $i + 2;
            $sheet->fromArray([array_values($data[$i])], null, "A$rowNumber");
        }

        $writer = new Csv($spreadsheet);
        $writer->save($fileName);

        Log::info("end: creation of CSV [$fileName]");
    }
}