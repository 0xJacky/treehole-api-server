<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Post extends Model
{
    public $incrementing = false;

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string)Uuid::generate(4);
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content',
        'category_id'
    ];

    /**
     * Get the category associated with the post.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * Get the comments associated with the post.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getComments()
    {
        return $this->comments()->get()->groupBy('parent');
    }

    /**
     * Get the upload record associated with the post.
     */
    public function upload_id()
    {
        return $this->hasOne(Upload::class, 'id', 'upload_id');
    }
}
