<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasUuids;

    protected $table="t_users";

     protected $fillable = [
        'name',
        'post_name',
        'prename',
        'email',
        'pswd',
        'phone',
        'gender',
        'status',
        'profil',
        'dateBorn',
        'adress',
        'roleid',
        'typeid',
        'id',
    ];

    protected $hidden = [
        'pswd',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles(){
        return $this->belongsTo(RoleModel::class, 'roleid','id');
    }

    public function type(){
         return $this->belongsTo(TypePersonneModel::class, 'typeid','id');
    }

    public function count(){
        return $this->hasMany(CompteUserModel::class,'userid' ,'id')->whereNot('userid')->where('status',0);
    }

    public function permissions()
    {
        return $this->belongsToMany(RessourceModel::class, 't_roles_has_permissions', 'userid', 'ressourceid')->
        withPivot(['create','read','update','delete','status'])->as('access')->where('deleted',0);
    }


}
