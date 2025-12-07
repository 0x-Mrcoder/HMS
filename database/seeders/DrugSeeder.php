<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Drug;

class DrugSeeder extends Seeder
{
    public function run(): void
    {
        $drugs = [
            ['name' => 'Paracetamol 500mg', 'price' => 50.00, 'stock' => 1000, 'description' => 'Pain reliever and fever reducer'],
            ['name' => 'Amoxicillin 500mg', 'price' => 150.00, 'stock' => 500, 'description' => 'Antibiotic'],
            ['name' => 'Ibuprofen 400mg', 'price' => 80.00, 'stock' => 800, 'description' => 'Anti-inflammatory'],
            ['name' => 'Ciprofloxacin 500mg', 'price' => 200.00, 'stock' => 300, 'description' => 'Antibiotic'],
            ['name' => 'Metronidazole 400mg', 'price' => 100.00, 'stock' => 400, 'description' => 'Antibiotic'],
            ['name' => 'Artemether/Lumefantrine', 'price' => 1200.00, 'stock' => 200, 'description' => 'Antimalarial'],
            ['name' => 'Vitamin C 100mg', 'price' => 20.00, 'stock' => 2000, 'description' => 'Supplement'],
            ['name' => 'Omeprazole 20mg', 'price' => 150.00, 'stock' => 600, 'description' => 'Acid reflux'],
            ['name' => 'Loratadine 10mg', 'price' => 100.00, 'stock' => 500, 'description' => 'Antihistamine'],
            ['name' => 'Amlodipine 5mg', 'price' => 120.00, 'stock' => 400, 'description' => 'Hypertension'],
        ];

        foreach ($drugs as $drug) {
            Drug::create($drug);
        }
    }
}
