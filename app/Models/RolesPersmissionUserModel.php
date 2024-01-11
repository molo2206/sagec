<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class RolesPersmissionUserModel extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    protected $table = "t_roles_has_permissions";
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }
    public function ressource()
    {
        return $this->belongsTo(RessourceModel::class, 'ressourceid', 'id');
    }
}
