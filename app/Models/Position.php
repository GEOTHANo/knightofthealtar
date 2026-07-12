<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'position_name',
    ];

    /**
     * Get the member position records for the position.
     */
    public function memberPositions(): HasMany
    {
        return $this->hasMany(MemberPosition::class);
    }

    /**
     * Get members assigned to the position.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'member_positions')
            ->withPivot(['date_assigned', 'is_current'])
            ->withTimestamps();
    }

    /**
     * Scope positions ordered alphabetically.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('position_name');
    }

    /**
     * Search positions by name.
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        return $query->when(
            $search !== null && $search !== '',
            fn (Builder $builder): Builder => $builder->where('position_name', 'like', '%' . $search . '%')
        );
    }
}