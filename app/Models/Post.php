<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Thread;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'forum_id',
        'thread_id',
        'floor',
        'random_head',
        'create_by_admin',
        'content',
        'created_IP',
        'created_binggan',
    ];

    protected $hidden = [
        'created_IP',
        'created_binggan',
    ];

    protected $casts = [];

    protected $guarded = [];

    public function Thread()
    {
        return $this->belongsTo(Thread::class);
    }

    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }


}
