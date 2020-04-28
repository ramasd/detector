<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'projects';

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'url', 'status', 'user_id', 'check_frequency', 'last_check', 'checked',
    ];

    /**
     * Override parent boot and Call deleting event
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function($project) {
            foreach ($project->logs()->get() as $log) {
                $log->delete();
            }
        });
    }

    // Relationships

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    // Scopes

    /**
     * @param $scope
     * @return mixed
     */
    public function scopeActive($scope)
    {
        return $scope->where('status', 1);
    }

    /**
     * @param $scope
     * @return mixed
     */
    public function scopeNotChecked($scope)
    {
        return $scope->whereNull('checked');
    }

    /**
     * @param $scope
     * @return mixed
     */
    public function scopeCheckTime($scope)
    {
        return $scope->whereRaw('check_frequency <= TIMESTAMPDIFF(MINUTE, last_check, NOW())')->orWhereNull('last_check');
    }

    /**
     * @param $scope
     * @return mixed
     */
    public function scopeCurrentUserProjects($scope)
    {
        return $scope->where('user_id', auth()->id());
    }
}
