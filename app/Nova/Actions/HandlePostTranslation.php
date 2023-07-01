<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class HandlePostTranslation extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Handle Translation';

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
            if (!$model->user->isPress()) {
                $this->markAsFailed($model);
                return Action::danger(__("The author is not a press center"));
            }

            if (!$model->user->packageActive()) {
                $this->markAsFailed($model);
                return Action::danger(__("The author does not have an active subscription"));
            }

            if (!$model->user->package_translate) {
                $this->markAsFailed($model);
                return Action::danger(__("The author has no available translations"));
            }

            $user = $model->user;
            $user->package_translate -= 1;
            $user->update();

            $this->markAsFinished($model);
            return Action::message(__("The number of available translations has been successfully changed for the author of the post"));
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [];
    }
}
