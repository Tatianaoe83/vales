<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sale;
use App\Models\Vale;
use App\Models\ValeHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CheckValesVencidos extends Command
{
    // El nombre que usarás en la terminal para activarlo
    protected $signature = 'vales:check-vencidos';

    // Descripción para que sepas qué hace
    protected $description = 'Busca ventas a crédito vencidas y marca sus vales como Vencidos';

    public function handle()
    {
        $this->info('--- Iniciando revisión de créditos vencidos ---');

        // 1. Buscar Ventas a Crédito que ya vencieron (fecha menor a hoy)
        $ventasVencidas = Sale::where('tipo_venta', 'Credito')
                              ->whereDate('fecha_vencimiento', '<', Carbon::now())
                              ->get();

        $count = 0;

        foreach ($ventasVencidas as $venta) {   
            
            // 2. Buscar vales de esa venta que sigan "Vigentes"
            // (Si ya están Surtidos, Cancelados o En Planta, NO los tocamos)
            $valesVigentes = Vale::where('sale_id', $venta->id)
                                 ->where('estatus', 'Vigente')
                                 ->get();

            if ($valesVigentes->count() > 0) {
                
                // Usamos transaction para asegurar que se guarde todo o nada
                DB::transaction(function () use ($valesVigentes, &$count) {
                    foreach ($valesVigentes as $vale) {
                        
                        // A. Cambiar estatus a VENCIDO
                        $vale->update(['estatus' => 'Vencido']);

                        // B. Guardar Historial (Auditoría)
                        ValeHistory::create([
                            'vale_id' => $vale->id,
                            'user_id' => 1, // Usuario 1 suele ser el Admin o Sistema
                            'estatus_anterior' => 'Vigente',
                            'estatus_nuevo' => 'Vencido',
                            'comentarios' => 'Vencimiento automático (Plazo de crédito expirado).'
                        ]);
                        
                        $count++;
                        $this->line("Vale {$vale->folio_vale} marcado como Vencido.");
                    }
                });
            }
        }

        $this->info("¡Listo! Se procesaron y vencieron {$count} vales.");
    }
}