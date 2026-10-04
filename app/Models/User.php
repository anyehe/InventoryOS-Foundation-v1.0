<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable {
    use Notifiable, \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $fillable=['name','email','password','role','role_id'];
    protected $hidden=['password','remember_token'];
    protected $with=['roleRelation'];
    protected function casts(): array { return ['email_verified_at'=>'datetime','password'=>'hashed']; }
    public function roleRelation(){ return $this->belongsTo(Role::class,'role_id'); }
    public function apiKeys(){ return $this->hasMany(ApiKey::class); }
    public function hasRole(string $role): bool { return strtolower((string)($this->roleRelation?->name ?? $this->role)) === strtolower($role); }
    public function hasPermission(string $permission): bool {
        if ($this->hasRole('admin')) return true;
        return (bool)$this->roleRelation?->permissions()->where('name',$permission)->exists();
    }
}
