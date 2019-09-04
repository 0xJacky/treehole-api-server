<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Report extends Model
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
        'post_id',
        'comment_id'
    ];

    public function post()
    {
        return $this->hasOne(Post::class, 'id', 'post_id');
    }

    public function comment()
    {
        return $this->hasOne(Comment::class, 'id', 'comment_id');
    }
}
