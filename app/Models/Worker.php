<?php

namespace App\Models;

use App\Http\Controllers\Admin\VisitorController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Worker extends Model
{
    use HasFactory;

    protected $table = 'workers';
    protected $fillable = ['id','name' ,'nat_id','visit_id','visitor_id', 'is_scaned', 'created_at','updated_at'];

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class,'visitor_id','id');
    }

    public function visit(): BelongsTo
    {
        return $this->belongsTo(VisitingDetails::class,'visit_id','id');
    }


}
