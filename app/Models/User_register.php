<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class User_register extends Model
{
    use HasFactory;

    // Opcionalmente, define la tabla si no sigue la convención de pluralización
    protected $table = 'user_registers';



    // Relación con la tabla roles
    public function Role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    public function Apprentice(){
        return $this->hasMany('App\Models\Apprentice');
    }
    public function Message(){
        return $this->hasMany('App\Models\Message');
    }
    public function Notification(){
        return $this->hasMany('App\Models\Notification');
    }

    // Encriptar la contraseña automáticamente al crear o actualizar
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
    // protected $fillable =
    //  [ 'identificacion',
    // 'name',
    // 'last_name',
    // 'telephone',
    // 'email',
    // 'adress',
    // 'department',
    // 'municipality',
    // 'id_role'];

    protected  $guarded = [];

    protected $allowIncluded = ['Role','Apprentice','Message','Notification'];
    protected $allowFilter = ['id', 'identification',
    'name',
    'last_name',
    'telephone',
    'email',
    'address',
    'department',
    'municipality', 'password',
    'id_role'];
    protected $allowSort = ['id','identification',
    'name',
    'last_name',
    'telephone',
    'email',
    'address',
    'department',
    'municipality', 'password',
    'id_role'];

    public function scopeIncluded(Builder $query)
    {
        if (empty($this->allowIncluded) || empty(request('included'))) {
            return;
        }

        $relations = explode(',', request('included'));
        $allowIncluded = collect($this->allowIncluded);

        foreach ($relations as $key => $relationship) {
            if (!$allowIncluded->contains($relationship)) {
                unset($relations[$key]);
            }
        }

        $query->with($relations);
    }

    public function scopeFilter(Builder $query)
    {
        if (empty($this->allowFilter) || empty(request('filter'))) {
            return;
        }

        $filters = request('filter');
        $allowFilter = collect($this->allowFilter);

        foreach ($filters as $filter => $value) {
            if ($allowFilter->contains($filter)) {
                $query->where($filter, 'LIKE', '%' . $value . '%');
            }
        }
    }

    public function scopeSort(Builder $query)
    {
        if (empty($this->allowSort) || empty(request('sort'))) {
            return;
        }

        $sortFields = explode(',', request('sort'));
        $allowSort = collect($this->allowSort);

        foreach ($sortFields as $sortField) {
            $direction = 'asc';

            if (substr($sortField, 0, 1) === '-') {
                $direction = 'desc';
                $sortField = substr($sortField, 1);
            }

            if ($allowSort->contains($sortField)) {
                $query->orderBy($sortField, $direction);
            }
        }
    }

    public function scopeGetOrPaginate(Builder $query)
    {
        if (request('perPage')) {
            $perPage = intval(request('perPage'));

            if ($perPage) {
                return $query->paginate($perPage);
            }
        }
        return $query->get();
    }
}
