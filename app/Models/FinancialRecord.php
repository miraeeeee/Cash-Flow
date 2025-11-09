<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FinancialRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','type','amount','transacted_at','title','notes','cover','category'
    ];

    protected $casts = [
        'transacted_at' => 'datetime',
        'amount' => 'float',
    ];
    

    public function user(){ return $this->belongsTo(User::class); }

    public function scopeOwned(Builder $q): Builder
    {
        return $q->where('user_id', auth()->id());
    }

    public function scopeFilter(Builder $q, array $f): Builder
    {
        return $q
            ->when($f['search'] ?? null, function ($qq, $v) {
                $qq->where(function ($x) use ($v) {
                    $x->where('title','ilike',"%$v%")
                      ->orWhere('category','ilike',"%$v%");
                });
            })
            ->when(($f['type'] ?? null) && in_array($f['type'], ['income','expense']),
                fn($qq) => $qq->where('type', $f['type'])
            )
            ->when($f['date_from'] ?? null, fn($qq,$v) => $qq->whereDate('transacted_at','>=',$v))
            ->when($f['date_to'] ?? null,   fn($qq,$v) => $qq->whereDate('transacted_at','<=',$v));
    }
}
