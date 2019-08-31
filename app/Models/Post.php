<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
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
    public function category() {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the comments associated with the post.
     */
    public function comments() {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }

    /**
     * Get the upload record associated with the post.
     */
    public function upload_id() {
        return $this->hasOne(Upload::class, 'id', 'upload_id');
    }
}
