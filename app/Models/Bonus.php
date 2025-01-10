<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type_id', 'amount', 'before_amount', 'after_amount', 'remark', 'user_id', 'agent_id', 'created_id'];

    public function type()
    {
        return $this->belongsTo(BonusType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'created_id');
    }
}
