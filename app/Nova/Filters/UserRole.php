<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class UserRole extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query
            ->select('users.*')
            ->join('role_user', 'role_user.user_id', 'users.id')
            ->join('roles', 'role_user.role_id', 'roles.id')
            ->where('roles.slug', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'Admin' => 'admin',
            'Модератор' => 'moderator',
            'Пресс-центр' => 'press',
            'Журналист' => 'journalist',
            'Читатель' => 'reader',
        ];
    }
}
