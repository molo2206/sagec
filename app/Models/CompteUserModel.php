<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class CompteUserModel extends Model
{
    use HasFactory,HasFactory,Notifiable,HasUuids;

    protected $table="t_compte_user";
    protected $fillable = [
        'count_number',
        'userid',
        'typecompte','currency',
        'id'
    ];

    public function membre(){
        return $this->belongsTo(User::class, 'userid','id')->orderby('name','asc');
    }

    public function transaction(){
        return $this->hasMany(TransactionModel::class, 'compteid','id');
    }

    public function validTransactions()
    {
        return $this->transaction()->where('status', 0);
    }
      public function credit()
    {
        return $this->validTransactions()
            ->sum('credit');
    }

    public function debit()
    {
        return $this->validTransactions()
            ->sum('debit');
    }

    public function balance()
    {
        return $this->debit() - $this->credit();
    }


    public function allowWithdraw($amount): bool
    {
        return $this->balance() >= $amount;
    }


}
