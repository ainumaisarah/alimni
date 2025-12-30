<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class ActivitiesExport implements FromCollection
{
    protected $activities;

    public function __construct($activities)
    {
        $this->activities = $activities;
    }

    public function collection()
    {
        return $this->activities->map(function($a){
            return [
                'User' => $a->causer?->name ?? 'System',
                'Action' => $a->description,
                'Properties' => json_encode($a->properties),
                'Date' => $a->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}
