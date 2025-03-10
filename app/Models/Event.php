<?php

namespace App\Models;

use Cache;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends BaseModel
{
    use HasFactory;

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
        'category',
        'participants',
    ];

    protected $appends = [
        'category_name',
        'is_participant',
        'host_name',
        'participants_count',
    ];

    public function getCategoryNameAttribute()
    {
        return $this->category->name;
    }

    public function getIsParticipantAttribute()
    {
        return $this->participants->contains('id', auth()->id());
    }

    public function getHostNameAttribute()
    {
        return $this->host->full_name;
    }

    public function getParticipantsCountAttribute()
    {
        return $this->participants()->count();
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
            Cache::forget(static::$cacheKey);
            $event->host->givePermission("events.{$event->id}.update");
            $event->host->givePermission("events.{$event->id}.delete");
        });

        static::updated(function ($event) {
            Cache::forget(static::$cacheKey);
        });

        static::deleted(
            function ($event) {
                Cache::forget(static::$cacheKey);
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
            'time' => 'required|date_format:H:i,H:i:s',
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
