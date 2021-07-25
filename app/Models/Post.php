<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\myModel;
use App\Models\Thread;

class Post extends myModel
{
    use HasFactory;

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

    protected $appends = [
        'created_binggan_hash'
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

    public function getCreatedBingganHashAttribute()
    {
        return hash('sha256', $this->created_binggan);
    }

    public function getContentAttribute($content)
    {
        switch ($this->is_deleted) {
            case 0:
                return $content;
            case 1:
                return '*此贴已被作者删除*';
            case 2:
                return '*此贴已被管理员删除*';
        }
    }
}
