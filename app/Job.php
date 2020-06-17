<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = "jobs";

    protected $fillable = [
        'user_id', 'title',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function byStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeofUserType($query, array $args)
    {
        $status = $args['status'] ??  null;

        return $query->when(! is_null($status), function($q) use($status){
            return $q->where('status', $status);
        });
    }
}
