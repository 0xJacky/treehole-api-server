<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Comment extends Model
{
    public $incrementing = false;
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string) Uuid::generate(4);
        });
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content',
        'post_id',
        'parent',
        'upload_id'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the upload record associated with the post.
     */
    public function upload_id()
    {
        return $this->hasOne(Upload::class, 'id', 'upload_id');
    }
}
