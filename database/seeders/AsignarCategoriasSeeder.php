<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class AsignarCategoriasSeeder extends Seeder
{
    public function run(): void
    {
        // Asignaciones básicas por lotes
        
        // Grunge (categoria_id: 2, decada_id: 4, pais_id: 1)
        Producto::whereIn('codigo', ['NIR001', 'PJ001', 'SG001', 'SG002', 'AIC001'])
            ->update(['categoria_id' => 2, 'decada_id' => 4, 'pais_id' => 1]);
            
        // Rock Clásico UK (categoria_id: 1, decada_id: 1, pais_id: 2)
        Producto::whereIn('codigo', ['BT001', 'BT002', 'PF001', 'PF002', 'PF003', 'PF004'])
            ->update(['categoria_id' => 1, 'decada_id' => 1, 'pais_id' => 2]);
            
        // New Wave UK (categoria_id: 4, decada_id: 3, pais_id: 2)
        Producto::whereIn('codigo', ['TC001', 'TC002', 'DM001', 'DM002'])
            ->update(['categoria_id' => 4, 'decada_id' => 3, 'pais_id' => 2]);
            
        // Britpop (categoria_id: 5, decada_id: 4, pais_id: 2)
        Producto::whereIn('codigo', ['OA001', 'OA002'])
            ->update(['categoria_id' => 5, 'decada_id' => 4, 'pais_id' => 2]);
            
        // Rock Nacional ARG (categoria_id: 7, decada_id: 3, pais_id: 3)
        Producto::whereIn('codigo', ['SE001', 'SE002', 'CG001'])
            ->update(['categoria_id' => 7, 'decada_id' => 3, 'pais_id' => 3]);
            
        // Punk USA (categoria_id: 3, decada_id: 2, pais_id: 1)
        Producto::whereIn('codigo', ['RA001', 'RA002'])
            ->update(['categoria_id' => 3, 'decada_id' => 2, 'pais_id' => 1]);

        $this->command->info('🎸 Productos categorizados por lotes');
    }
}