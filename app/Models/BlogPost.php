<?php

namespace DaaluPay\Models;

use Illuminate\Database\Eloquent\Model;
use DaaluPay\Models\Admin;
class BlogPost extends Model
{
    protected $fillable = [
        'title',
         'content',
          'featured_image',
          'status',
          'author_id',
          'created_at',
          'updated_at'
        ];

    public function author()
    {
        return $this->belongsTo(Admin::class);
    }
}
