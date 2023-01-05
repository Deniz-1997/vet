<?php

namespace App;

use App\Models\Channels\ModelChannelsUsers;
use App\Models\Dictionary\ModelDictionaryOrganizations;
use App\Models\User\ModelUserDevices;
use App\Models\User\ModelUserGroups;
use App\Modules\Dictionary\DictionaryOrganizationsModule;
use Eloquent;
use Hash;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $api_token
 * @property int $rate_limit
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereApiToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRateLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin Eloquent
 * @property Carbon|null $deleted_at
 * @property-read Collection|ModelUserGroups[] $groups
 * @property-read int|null $groups_count
 * @property-read Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @method static bool|null forceDelete()
 * @method static Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static Builder|User withTrashed()
 * @method static Builder|User withoutTrashed()
 * @property-read Collection|ModelChannelsUsers[] $channels
 * @property-read int|null $channels_count
 * @property string $phone
 * @property int|null $organization_id
 * @property int|null $main_organization_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\ModelUserDevices[] $devices
 * @property-read int|null $devices_count
 * @property-read \App\Models\Dictionary\ModelDictionaryOrganizations $organization
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereMainOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhone($value)
 */
class User extends Authenticatable
{
    use Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token', 'rate_limit', 'phone', 'organization_id', 'main_organization_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email_verified_at', 'created_at', 'rate_limit', 'deleted_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($item) {

            if ($item->organization_id === 0) {
                $item->organization_id = null;
            }

            $item->api_token = Str::random(60);

            $item->rate_limit = 360;

            $item->password = Hash::make($item->password);
        });
    }

    /**
     * @return HasMany
     */
    public function groups(): HasMany
    {
        return $this->hasMany(ModelUserGroups::class, 'user_id', 'id')->with(['dictionaryGroup']);
    }

    /**
     * @return HasMany
     */
    public function devices(): HasMany
    {
        return $this->hasMany(ModelUserDevices::class, 'user_id', 'id')->with(['currentDevice']);
    }

    /**
     * @return HasMany
     */
    public function channels(): HasMany
    {
        return $this->hasMany(ModelChannelsUsers::class, 'user_id', 'id')->with(['channel']);
    }

    /**
     * @return HasOne
     */
    public function organization(): HasOne
    {
        return $this->hasOne(ModelDictionaryOrganizations::class, 'id', 'organization_id');
    }

    public function getMainOrganization()
    {
        if (is_null($this->organization_id)) {
            return null;
        } else {
            return DictionaryOrganizationsModule::getMainOrganizationId($this->organization_id);
        }
    }

    /**
     * @return int
     */
    public function getDelaySend(): int
    {
        $group = $this->groups->first();

        if (is_null($group)) {
            return 30;
        }

        if (is_null($group->dictionaryGroup)) {
            return 30;
        }

        return $group->dictionaryGroup->delay_send;
    }
}
