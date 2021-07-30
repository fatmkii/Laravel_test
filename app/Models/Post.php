<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\myModel;
use App\Models\Thread;

class Post extends myModel
{
    use HasFactory;

    protected $binggan = '';

    protected $fillable = [
        'forum_id',
        'thread_id',
        'floor',
        'random_head',
        'created_by_admin',
        'content',
        'created_IP',
        'created_binggan',
    ];

    protected $hidden = [
        'created_IP',
        'created_binggan',
        'is_anonymous',
        'is_delete',
    ];

    protected $appends = [
        'is_your_post',
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

    public function setBinggan($binggan)
    {
        $this->binggan = $binggan;
    }

    public static function Binggan($binggan)
    {
        $instance = new static;
        $instance->setBinggan($binggan);

        return $instance->newQuery();
    }


    public function getIsYourPostAttribute()
    {
        if ($this->binggan) {
            if ($this->binggan == $this->created_binggan) {
                return true;
            } else {
                return false;
            }
        }
    }
}
