<?php

namespace App\Models;

use DB;

class Event extends BaseModel
{
    public static $cacheKey = 'events';

    protected $fillable = [
        'title',
        'description',
        'date',
        'time',
        'location',
        'capacity',
        'host_id',
        'category_id',
    ];

    protected $hidden = [
        'host',
    ];

    protected $appends = [
        'host_name',
    ];

    public function getHostNameAttribute()
    {
        return $this->host->full_name;
    }

    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'event_participants');
    }

    protected static function booted()
    {
        static::created(function ($event) {
            $event->host->givePermission("events.{$event->id}.update");
            $event->host->givePermission("events.{$event->id}.delete");
        });

        static::deleted(
            function ($event) {
                $permissions = Permission::where('name', 'like', 'events.'.$event->id.'.%')->get();
                DB::table('users_permissions')->whereIn('permission_id', $permissions->pluck('id'))->delete();
                Permission::destroy($permissions->pluck('id'));
            }
        );
    }

    public function rules($id = null)
    {
        $id = $id ?? request()->route('id');

        $rules = [
            'title' => 'required|string',
            'description' => 'required|string',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i:s',
            'location' => 'required|string',
            'capacity' => 'required|integer',
            'host_id' => 'required|integer|exists:users,id',
            'category_id' => 'required|integer|exists:categories,id',
        ];

        if ($id) {
            unset($rules['host_id']);
        }

        return $rules;
    }
}
