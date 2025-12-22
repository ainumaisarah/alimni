<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Excel;

class TeachersImport
{
    /**
     * Map each row to a User model.
     */
    public function model(array $row)
    {
        return new User([
            'name' => $row['name'] ?? '',
            'username' => $row['username'] ?? null,
            'password' => isset($row['password']) ? Hash::make($row['password']) : Hash::make('password123'),
            'role' => 'teacher',
        ]);
    }
}
