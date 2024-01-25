<?php

namespace pivotalso\LaravelAb\Models;

use pivotalso\LaravelAb\Events\Track;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Events extends Model
{
    protected $table = 'ab_events';

    protected $fillable = ['name', 'value', 'instance_id', 'experiments_id'];

    protected $touches = ['instance'];
    protected $appends = ['instance'];
    public static function boot()
    {
        parent::boot();
        self::created(function ($model) {
            event(new Track($model));
        });
    }
    public function getInstanceAttribute()
    {
        $instance =  $this->instance()->first();
        return !empty($instance) ? $instance->instance : null;
    }
    public function experiment()
    {
        return $this->belongsTo('pivotalso\LaravelAb\Models\Experiment');
    }

    public function instance()
    {
        return $this->belongsTo('pivotalso\LaravelAb\Models\Instance');
    }
}
