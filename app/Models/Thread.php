<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Thread extends Model
{
    use SoftDeletes;
    use HasFactory;

    public $hidden = [
        'created_binggan',
        'created_ip'
    ];
    protected $fillable = [
        'forum_id', 'title', 'content', 'created_binggan', 'created_IP',
        'anti_jingfen', 'sub_id', 'nissin_time',
    ];
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    protected $casts = [];


    public function Forum()
    {
        return $this->belongsTo(Forum::class);
    }

    public function Replies()
    {
        return $this->hasMany(Reply::class);
    }
}
