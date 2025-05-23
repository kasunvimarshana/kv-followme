<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class CommonExportWorkBook implements FromArray, WithHeadings, WithStrictNullComparison
{
    public function __construct(array $arguments){
        $this->arguments = $arguments;
    }

    /**
     * @return array
     */
    public function array(): array{
        return $this->arguments;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    /*
    public function collection(){
        //
        return User::all();
    }
    */
    
    /**
     * @return array
     */
    public function headings(): array{
        return [
            'ID',
            'MEETING CATEGORY',
            'TITLE',
            'DESCRIPTION',
            'DATE - START',
            'DATE - DUE',
            'DATE - DONE',
            'USER - CREATED',
            'USER - DONE',
            'USER - RESPONSIBLE',
            'STATUS',
        ];
    }
    
    /**
     * @return string
     */
    /*
    public function startCell(): string{
        return 'A1';
    }
    */
    
    /**
     * @return array
     */
    /*
    public function registerEvents(): array{
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
    }
    */
}
