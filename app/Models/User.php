<?php

namespace App\Models;

use App\Notifications\EmailReconfirmationNotification;
use App\Scopes\EmpresaScope;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use URL;
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable,HasFactory,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'name', 
        'email', 
        'password',
        'role',
        'id_franquia',
        'email_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function franquia(){
        return $this->belongsTo(Franquias::class,'id_franquia','id');
    }
    public function dados(){
        return $this->hasOne(DadosClientes::class,'id_user','id');
    }
    public function enderecos(){
        return  $this->hasMany(Enderecos::class, 'id_user');
    }
   
    public function pedidos(){
        return $this->hasMany(Pedidos::class, 'id_cliente');
    }
 
 


}
