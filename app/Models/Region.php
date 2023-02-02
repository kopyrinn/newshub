<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function getUsersCount($roles = [])
    {
        $query = User::query();
        $query->join("cities", "cities.id", "=", "users.city_id");
        $query->join("regions", "regions.id", "=", "cities.region_id");
        $query->join("role_user", "role_user.user_id", "=", "users.id");
        $query->join("roles", "role_user.role_id", "=", "roles.id");
        $query->where("regions.id", $this->id);
        $query->whereNotNull("users.email_verified_at");

        if ($roles) {
            $query->whereIn("roles.slug", $roles);
        }

        // $query->groupBy("users.id");

        return $query->count();
    }
}
