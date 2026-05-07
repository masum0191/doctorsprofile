<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $table = 'templates'; // ✅ explicitly define correct table name

    protected $fillable = ['title', 'value', 'image', 'status','url'];
}
