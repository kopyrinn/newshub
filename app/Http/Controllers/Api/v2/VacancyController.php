<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Models\Vacancy;
use Illuminate\Http\Request;

class VacancyController extends Controller
{

    public function vacancies(Request $request)
    {
        $vacancies = Vacancy::select('vacancies.id', 'vacancies.job_title', 'vacancies.task', 'vacancies.user_id', 'vacancies.created_at', 'users.name', 'users.avatar', 'users.avatar_sm')
            ->join('users', 'users.id', 'vacancies.user_id')
            ->where('vacancies.status', 1)
            ->orderByDesc('vacancies.created_at')
            ->cursorPaginate(9);

        return response()->json([
            'ok' => true,
            'vacancies' => $vacancies,
        ]);
    }

    public function vacancy(Request $request, $id)
    {
        $vacancy = Vacancy::select('vacancies.*', 'users.avatar', 'users.avatar_sm', 'users.name', 'users.description')
            ->join('users', 'users.id', 'vacancies.user_id')
            ->where('vacancies.status', 1)
            ->where('vacancies.id', $id)
            ->first();

        abort_if(!$vacancy, 404);

        $vacancy->vacancy_view++;
        $vacancy->update();

        return response()->json([
            'ok' => true,
            'vacancy' => $vacancy,
        ]);
    }

    public function save(Request $request)
    {
        $request->validate([
            'job_title' => 'required',
            'requiremets' => 'required',
            'task' => 'required',
            'conditionsm' => 'required',
            'email_jobseeker' => 'required|email',
        ]);

        $user = auth('sanctum')->user();

        $vacancy = new Vacancy;
        $vacancy->status = 0;
        $vacancy->user_id = $user->id;

        foreach ($request->job_title as $locale => $value) {
            $vacancy->setTranslation('job_title', $locale, $value);
        }

        foreach ($request->requiremets as $locale => $value) {
            $vacancy->setTranslation('requiremets', $locale, $value);
        }

        foreach ($request->task as $locale => $value) {
            $vacancy->setTranslation('task', $locale, $value);
        }

        foreach ($request->conditionsm as $locale => $value) {
            $vacancy->setTranslation('conditionsm', $locale, $value);
        }

        $vacancy->email_jobseeker = $request->email_jobseeker;

        if ($user->packageActive() && $user->package_vacancies) {
            $user->package_vacancies -= 1;
            $user->update();

            $vacancy->status = 1;

            $message = __("Your news has been successfully published");
        } else {
            if ($user->balance < nova_get_setting('vacancy_price')) {
                return redirect()->back()->with('error', __("Insufficient funds on the balance sheet. Top up the balance in your account and try again."));
            }

            $user->subBalance(nova_get_setting('vacancy_price'), "Оплата публикации вакансии");
            $user->update();

            $message = __("Your vacancy has been successfully submitted for moderation");
        }

        $vacancy->save();

        return response()->json([
            'ok' => true,
            'message' => $message
        ]);
    }

}
