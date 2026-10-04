<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class ApiKey extends Model {
    protected $fillable=['user_id','name','key_hash','key_prefix','abilities','expires_at','revoked_at','last_used_at'];
    protected $casts=['abilities'=>'array','expires_at'=>'datetime','revoked_at'=>'datetime','last_used_at'=>'datetime'];
    public static function issue(int $userId, string $name, array $abilities=[], ?\DateTimeInterface $expiresAt=null): array {
        $plain='inv_'.Str::random(48);
        $model=self::create(['user_id'=>$userId,'name'=>$name,'key_hash'=>hash('sha256',$plain),'key_prefix'=>substr($plain,0,12),'abilities'=>$abilities,'expires_at'=>$expiresAt]);
        return [$model,$plain];
    }
    public function user(){ return $this->belongsTo(User::class); }
    public function active(): bool { return !$this->revoked_at && (!$this->expires_at || $this->expires_at->isFuture()); }
}
