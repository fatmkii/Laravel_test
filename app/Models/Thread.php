<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Post;
use App\Models\Forum;
use Carbon\Carbon;

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
        'anti_jingfen', 'sub_id', 'nissin_time', 'author_name'
    ];
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    protected $casts = [];


    public function Forum()
    {
        return $this->belongsTo(Forum::class);
    }

    public function Posts()
    {
        return $this->hasMany(Post::class);
    }

    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i');
    }
}
