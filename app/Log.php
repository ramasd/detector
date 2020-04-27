<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Log extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'logs';

    /**
     * @var array
     */
    protected $fillable = [
        'project_id', 'data',
    ];

    // Relationships

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Scopes

    public function scopeCurrentUserLogs($scope)
    {
//        SELECT * FROM logs WHERE project_id IN (SELECT id FROM projects WHERE user_id = auth()->id())
//        return $scope->whereIn('project_id', User::findOrFail(auth()->id())->projects);
    }
}
