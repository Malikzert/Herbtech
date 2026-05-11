<?php

namespace App\Observers;

use App\Models\Production;
use App\Models\QualityControl;
use App\Models\FinishedGoodsInventory;
use App\Models\Recipe;

class QualityControlObserver
{
    public function created(QualityControl $qc): void
    {
        $production = $qc->production;

        if (!$production) {
            return;
        }

        $this->updateProductionStatus($production, $qc->action);
        $this->handleSideEffects($production, $qc);
    }

    public function updated(QualityControl $qc): void
    {
        $production = $qc->production;

        if (!$production) {
            return;
        }

        $this->updateProductionStatus($production, $qc->action);
    }

    private function updateProductionStatus(Production $production, ?string $action): void
    {
        $status = match ($action) {
            'release' => 'completed',
            'reject'  => 'cancelled',
            'rework'  => 'rework',
            default   => $production->status,
        };

        $data = ['status' => $status];

        if (in_array($status, ['completed', 'cancelled', 'rework'])) {
            $data['end_date'] = now();
        }

        $production->update($data);
    }

    private function handleSideEffects(Production $production, QualityControl $qc): void
    {
        switch ($qc->action) {
            case 'release':
                $this->addToInventory($production, $qc->total_passed);
                break;

            case 'rework':
                $this->createReworkProduction($production, $qc);
                break;
        }
    }

    private function addToInventory(Production $production, int $quantity): void
    {
        FinishedGoodsInventory::create([
            'production_id' => $production->id,
            'product_id'    => $production->product_id,
            'quantity_added' => $quantity,
            'expired_date'  => now()->addMonths(6),
            'storage_location' => 'warehouse',
        ]);
    }

    private function createReworkProduction(Production $originalProduction, QualityControl $qc): void
    {
        $newBatchNumber = $originalProduction->batch_number . '-R';

        if (Production::where('batch_number', $newBatchNumber)->exists()) {
            $newBatchNumber = $originalProduction->batch_number . '-R' . time();
        }

        Production::create([
            'batch_number'     => $newBatchNumber,
            'product_id'       => $originalProduction->product_id,
            'target_quantity'  => $qc->total_rejected,
            'start_date'       => now(),
            'status'           => 'pending',
            'user_id'          => $originalProduction->user_id,
            'rework_of'        => $originalProduction->id,
            'pic_name'         => $originalProduction->pic_name,
        ]);
    }
}
