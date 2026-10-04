<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AuditLog extends Model {
    protected $fillable=['user_id','action','resource_type','resource_id','ip_address','request_id','result','metadata'];
    protected function casts(): array { return ['metadata'=>'array']; }
    public function user(){ return $this->belongsTo(User::class); }
}
