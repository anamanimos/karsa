<?php

namespace App\Traits;

use App\Helpers\TenantHelper;
use App\Models\Business;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToBusiness
{
    public static function bootBelongsToBusiness(): void
    {
        static::addGlobalScope('business', function (Builder $builder) {
            $businessId = TenantHelper::currentBusinessId();
            if ($businessId !== null) {
                $model = $builder->getModel();
                $builder->where($model->getTable() . '.business_id', $businessId);
            }
        });

        static::creating(function (Model $model) {
            if (empty($model->business_id)) {
                $businessId = TenantHelper::currentBusinessId();

                // Fallback inference from related entities
                if ($businessId === null) {
                    if (!empty($model->employee_id)) {
                        $businessId = \App\Models\Employee::withoutGlobalScope('business')->find($model->employee_id)?->business_id;
                    } elseif (!empty($model->purchase_id)) {
                        $businessId = \App\Models\Purchase::withoutGlobalScope('business')->find($model->purchase_id)?->business_id;
                    } elseif (!empty($model->sale_id)) {
                        $businessId = \App\Models\Sale::withoutGlobalScope('business')->find($model->sale_id)?->business_id;
                    } elseif (!empty($model->stock_adjustment_id)) {
                        $businessId = \App\Models\StockAdjustment::withoutGlobalScope('business')->find($model->stock_adjustment_id)?->business_id;
                    }
                }

                // Final fallback if running in CLI or seeders
                if ($businessId === null) {
                    $businessId = Business::first()?->id;
                }

                if ($businessId !== null) {
                    $model->business_id = $businessId;
                }
            }
        });
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
