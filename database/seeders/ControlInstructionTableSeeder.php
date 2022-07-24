<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ControlInstruction;

class ControlInstructionTableSeeder extends Seeder
{
    public function run()
    {
        ControlInstruction::factory()->count(10)->create();
    }
}

