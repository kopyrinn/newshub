<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use App\Models\PollRequest;
use App\Models\PollVote;
use App\Models\User;
use App\Notifications\NewPollRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PollController extends Controller
{

    public function polls(Request $request)
    {
        $polls = Poll::select('polls.id', 'polls.question', 'polls.image', 'polls.slug', 'polls.description', 'polls.created_at')
            ->where('polls.is_active', 1)
            ->withCount('requests')
            // ->where('expired_at', '>', Carbon::now())
            ->orderByDesc('polls.created_at')
            ->cursorPaginate(9);

        foreach ($polls as $poll) {
            $poll->summary = $poll->getSummary();
        }

        return response()->json([
            'ok' => true,
            'polls' => $polls
        ]);
    }

    public function poll(Request $request, $slug)
    {
        $user = auth('sanctum')->user();

        $query = Poll::select('polls.*')
            ->whereIsActive(1)
            ->whereSlug($slug);

        if ($user) {
            $query->selectRaw("(SELECT 1 FROM poll_requests WHERE poll_requests.poll_id = polls.id AND poll_requests.user_id = {$user->id} LIMIT 1) as is_participant");
            $query->selectRaw("(SELECT 1 FROM poll_votes WHERE poll_votes.poll_id = polls.id AND poll_votes.user_id = {$user->id} LIMIT 1) as is_voted");
        }

        $poll = $query->first();
        abort_if(!$poll, 404);

        $is_started = $poll->start_at && $poll->start_at <= Carbon::now();
        $is_expired = $poll->expired_at && $poll->expired_at <= Carbon::now();

        $poll->can_participate = $user && $user->email_verified_at && $user->isPress() && !$is_started && !$is_expired && !$poll->is_participant;
        $poll->can_vote = $user && $user->email_verified_at && $user->isUser() && $is_started && !$is_expired && !$poll->is_voted;

        $query = $poll->requests()
            ->select('poll_requests.id', 'poll_requests.photo', 'poll_requests.name', 'poll_requests.position', 'users.id as uid')
            ->selectRaw('(SELECT COUNT(*) FROM poll_votes WHERE poll_votes.poll_request_id = poll_requests.id AND poll_votes.poll_id = poll_requests.poll_id) as votes_count')
            ->join('users', 'users.id', 'poll_requests.user_id')
            ->where('poll_requests.status', 'done')
            ->orderBy('votes_count', 'DESC');

        if ($poll->is_hide_after_expired) {
            $query->take(1);
        }

        if ($user) {
            $query->selectRaw("(SELECT 1 FROM poll_votes WHERE poll_votes.poll_request_id = poll_requests.id AND poll_votes.user_id = {$user->id} LIMIT 1) as is_voted");
        }

        $poll->participants = $query->get();

        $poll->max_votes = $poll->participants->count()? $poll->participants->first()->votes_count: 0;

        $poll->total_votes = $poll->participants->count()? $poll->participants->sum('votes_count'): 0;

        return response()->json([
            'ok' => true,
            'poll' => $poll
        ]);
    }

    public function pollRequest(Request $request, $slug)
    {
        $request->validate([
            'name' => 'required',
            'position' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'photo' => 'required',
        ]);

        $poll = Poll::whereSlug($slug)->first();

        if (!$poll) {
            return response()->json([
                'ok' => false,
                'message' => __('Not found.')
            ]);
        }

        $user = auth('sanctum')->user();

        if (!$user->email_verified_at) {
            return response()->json([
                'ok' => false,
                'message' => __('Please confirm your email.')
            ]);
        }

        if ($poll->requests()->where('user_id', $user->id)->exists()) {
            return response()->json([
                'ok' => false,
                'message' => __('You have already submitted a request.')
            ]);
        }

        $participant = new PollRequest;
        $participant->user_id = $user->id;
        $participant->name = $request->name;
        $participant->position = $request->position;
        $participant->phone = $request->phone;
        $participant->email = $request->email;
        $participant->photo = ltrim(str_replace(url('storage'), '', $request->photo), '/');
        $poll->requests()->save($participant);

        $admins = User::select('users.*')
            ->join('role_user', 'role_user.user_id', 'users.id')
            ->join('roles', 'role_user.role_id', 'roles.id')
            ->where('roles.slug', 'admin')
            ->get();

        foreach ($admins as $admin) {
            $admin->notify(new NewPollRequest($participant));
        }

        return response()->json([
            'ok' => true,
            'message' => __('Your request has been successfully submitted for moderation.')
        ]);
    }

    public function pollVote(Request $request, $slug)
    {
        $request->validate([
            'participant' => 'required|numeric',
        ]);

        $user = auth('sanctum')->user();

        if (!$user->email_verified_at) {
            return response()->json([
                'ok' => false,
                'message' => __('Please confirm your email.')
            ]);
        }

        if (!$user->isUser()) {
            return response()->json([
                'ok' => false,
                'message' => __('Error.')
            ]);
        }

        $poll = Poll::whereSlug($slug)->first();
        abort_if(!$poll, 404);

        if ($poll->votes()->where('user_id', $user->id)->exists()) {
            return response()->json([
                'ok' => false,
                'message' => __('You have already voted.')
            ]);
        }

        $participant = $poll->requests()->where('id', $request->participant)->first();
        if (!$participant) {
            return response()->json([
                'ok' => false,
                'message' => __('Unknown participant.')
            ]);
        }

        $vote = new PollVote;
        $vote->user_id = $user->id;
        $vote->poll_request_id = $participant->id;
        $vote->ip = $request->ip();
        $poll->votes()->save($vote);

        return response()->json([
            'ok' => true,
            'message' => __("You voted successfully.")
        ]);
    }
}
