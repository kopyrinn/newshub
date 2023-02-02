<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Textarea;
use App\Notifications\RejectPost;

class ModeratePostAction extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Отказ';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            if ($fields->reject) {
                $model->reject = 1;
                $model->reason = $fields->reason;

                $model->user->notify(new RejectPost($model));
            } else {
                $model->reject = 0;
                $model->reason = null;
            }

            $model->update();
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Boolean::make(__('Отказ'), 'reject')->default(1),
            Textarea::make(__('Причина'), 'reason')->help('<p>4.4.7. Публиковать материалы о деятельности третьих юридических лиц (Государственных, коммерческих и не коммерческих организации).</p><p>4.4.8. Публиковать материалы компании или о компании, если пользователь не представляет пресс-службу данной компании.</p>'),
        ];
    }
}
