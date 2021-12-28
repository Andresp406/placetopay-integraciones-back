<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',        
        'dni',
        'type_document',
        'password',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

      /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        //'years',
        'full_name'
    ];

    public $incrementing = true;


     //TODO Mutators models Client
     public function getFullNameAttribute()
     {
         return "$this->first_name $this->last_name";
     }


     //TODO Relations models Client

    public function r_sales()
    {
        return $this->hasMany(Sales::class, 'user_id', 'id');
    }
    //TODO Scopes models Client

    public function scopeSearch($query, $termino)
    {
        if($termino === '' || $termino === null) {
            return;
        }
        $query->where('first_name', 'like', "%{$termino}%")
            ->orWhere('last_name', 'like', "%{$termino}%");
    }

    
}
