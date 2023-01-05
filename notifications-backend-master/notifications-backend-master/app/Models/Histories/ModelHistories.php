<?php

namespace App\Models\Histories;

use App\Models\Dictionary\ModelDictionaryHistoriesType;
use App\User;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * Class ModelHistories
 *
 * @package App\Models\Histories
 * @property int $id
 * @property int $history_id
 * @property int $user_id
 * @property array $data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read ModelDictionaryHistoriesType $typeHistory
 * @property-read User $user
 * @method static Builder|ModelHistories newModelQuery()
 * @method static Builder|ModelHistories newQuery()
 * @method static Builder|ModelHistories query()
 * @method static Builder|ModelHistories whereCreatedAt($value)
 * @method static Builder|ModelHistories whereData($value)
 * @method static Builder|ModelHistories whereHistoryId($value)
 * @method static Builder|ModelHistories whereId($value)
 * @method static Builder|ModelHistories whereUpdatedAt($value)
 * @method static Builder|ModelHistories whereUserId($value)
 * @mixin Eloquent
 */
class ModelHistories extends ModeHistoriesSchema
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['history_id', 'user_id', 'data'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array'
    ];

    /**
     * Типа истории
     *
     * @return HasOne
     */
    public function typeHistory(): HasOne
    {
        return $this->hasOne(ModelDictionaryHistoriesType::class, 'id', 'history_id');
    }

    /**
     * Пользователь который относится к истории
     *
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
