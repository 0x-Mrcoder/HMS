<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LabTemplate;

class LabTemplateSeeder extends Seeder
{
    public function run()
    {
        $templates = [
            'Malaria Test' => [
                ['parameter' => 'Parasite Density', 'unit' => 'Score', 'range' => 'Negative', 'options' => ['No MPS Seen', '+', '++', '+++']],
                ['parameter' => 'Species', 'unit' => '', 'range' => 'P. falciparum', 'options' => ['P. falciparum', 'P. vivax', 'P. malariae', 'Mixed Inf.']],
            ],
            'Widal Reaction' => [
                ['parameter' => 'Salmonella Typhi O', 'unit' => 'Titer', 'range' => '< 1:80', 'options' => ['< 1:20', '1:20', '1:40', '1:80', '1:160', '1:320', '> 1:320']],
                ['parameter' => 'Salmonella Typhi H', 'unit' => 'Titer', 'range' => '< 1:80', 'options' => ['< 1:20', '1:20', '1:40', '1:80', '1:160', '1:320', '> 1:320']],
                ['parameter' => 'Salmonella Paratyphi A-O', 'unit' => 'Titer', 'range' => '< 1:80', 'options' => ['< 1:20', '1:20', '1:40', '1:80', '1:160', '1:320', '> 1:320']],
                ['parameter' => 'Salmonella Paratyphi A-H', 'unit' => 'Titer', 'range' => '< 1:80', 'options' => ['< 1:20', '1:20', '1:40', '1:80', '1:160', '1:320', '> 1:320']],
                ['parameter' => 'Salmonella Paratyphi B-O', 'unit' => 'Titer', 'range' => '< 1:80', 'options' => ['< 1:20', '1:20', '1:40', '1:80', '1:160', '1:320', '> 1:320']],
                ['parameter' => 'Salmonella Paratyphi B-H', 'unit' => 'Titer', 'range' => '< 1:80', 'options' => ['< 1:20', '1:20', '1:40', '1:80', '1:160', '1:320', '> 1:320']],
            ],
            'HIV Screening' => [
                ['parameter' => 'HIV 1/2 Screening', 'unit' => 'Result', 'range' => 'Non-Reactive', 'options' => ['Non-Reactive', 'Reactive']],
                ['parameter' => 'Method', 'unit' => '', 'range' => 'Rapid Test', 'options' => ['Rapid Test', 'ELISA', 'Simplot']],
            ],
            'Urinalysis' => [
                ['parameter' => 'Color', 'unit' => '', 'range' => 'Pale Yellow', 'options' => ['Colorless', 'Pale Yellow', 'Yellow', 'Amber', 'Red']],
                ['parameter' => 'Appearance', 'unit' => '', 'range' => 'Clear', 'options' => ['Clear', 'Hazy', 'Cloudy', 'Turbid']],
                ['parameter' => 'pH', 'unit' => '', 'range' => '4.5 - 8.0', 'options' => ['5.0', '5.5', '6.0', '6.5', '7.0', '7.5', '8.0']],
                ['parameter' => 'Specific Gravity', 'unit' => '', 'range' => '1.005 - 1.030', 'options' => ['1.005', '1.010', '1.015', '1.020', '1.025', '1.030']],
                ['parameter' => 'Protein', 'unit' => 'mg/dL', 'range' => 'Negative', 'options' => ['Negative', 'Trace', '+', '++', '+++', '++++']],
                ['parameter' => 'Glucose', 'unit' => 'mg/dL', 'range' => 'Negative', 'options' => ['Negative', 'Trace', '+', '++', '+++', '++++']],
                ['parameter' => 'Ketones', 'unit' => 'mg/dL', 'range' => 'Negative', 'options' => ['Negative', 'Trace', '+', '++', '+++', '++++']],
                ['parameter' => 'Blood', 'unit' => '', 'range' => 'Negative', 'options' => ['Negative', 'Trace', '+', '++', '+++', '++++']],
                ['parameter' => 'Bilirubin', 'unit' => '', 'range' => 'Negative', 'options' => ['Negative', '+', '++', '+++']],
                ['parameter' => 'Urobilinogen', 'unit' => 'EU/dL', 'range' => '0.2 - 1.0', 'options' => ['Normal', 'Increased']],
                ['parameter' => 'Nitrite', 'unit' => '', 'range' => 'Negative', 'options' => ['Negative', 'Positive']],
                ['parameter' => 'Leukocytes', 'unit' => '', 'range' => 'Negative', 'options' => ['Negative', 'Trace', '+', '++', '+++']],
            ],
            'Full Blood Count' => [
                ['parameter' => 'Hemoglobin (Hb)', 'unit' => 'g/dL', 'range' => 'M: 13-17 / F: 12-15'],
                ['parameter' => 'Packed Cell Volume (PCV)', 'unit' => '%', 'range' => 'M: 40-50 / F: 36-46'],
                ['parameter' => 'White Blood Cells (WBC)', 'unit' => 'x10^9/L', 'range' => '4.0 - 11.0'],
                ['parameter' => 'Platelets', 'unit' => 'x10^9/L', 'range' => '150 - 400'],
                ['parameter' => 'Neutrophils', 'unit' => '%', 'range' => '40 - 75'],
                ['parameter' => 'Lymphocytes', 'unit' => '%', 'range' => '20 - 45'],
            ],
            'Blood Sugar' => [
                ['parameter' => 'Fasting Blood Sugar', 'unit' => 'mg/dL', 'range' => '70 - 100'],
                ['parameter' => 'Random Blood Sugar', 'unit' => 'mg/dL', 'range' => '< 140'],
            ]
        ];

        foreach ($templates as $name => $fields) {
            LabTemplate::updateOrCreate(
                ['test_name' => $name],
                ['fields' => $fields]
            );
        }
    }
}
