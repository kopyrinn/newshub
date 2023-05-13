<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Models\Draft;
use App\Models\Package;
use App\Models\Poll;
use App\Models\PollRequest;
use App\Models\PollVote;
use App\Models\Post;
use App\Models\Region;
use App\Models\User;
use App\Models\Vacancy;
use App\Notifications\AdminNotice;
use App\Notifications\ChannelNotification;
use App\Notifications\NewPollRequest;
use App\Notifications\NewPost;
use App\Notifications\NewPress;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function settings(Request $request)
    {
        $user = auth()->user();

        return view('settings', [
            'user' => $user,
        ]);
    }

    public function account(Request $request)
    {
        $user = auth()->user();

        return view('account', [
            'user' => $user,
        ]);
    }

    public function settingsUpload(Request $request)
    {
        request()->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($files = $request->file('image')) {
            $uuid = \Str::uuid()->toString();
            $extension = $request->image->getClientOriginalExtension();

            $fileName =  "{$uuid}.{$extension}";
            $request->image->storeAs('image', $fileName, 'public');

            return response()->json([
                "image" => "/image/{$fileName}"
            ]);
        }
    }

    public function settingsBasic(Request $request)
    {
        $user = \Auth::user();

        $request->validate([
            'name' => 'required',
        ]);

        if ($user->isPress()) {
            $request->validate([
                'user_category_id' => 'required',
                'description' => 'required',
            ]);

            $user->user_category_id = $request->user_category_id;
            $user->description = $request->description;
            $user->bin = $request->bin;
            $user->iban = $request->iban;
            $user->bank = $request->bank;
            $user->bik = $request->bik;
            $user->kbe = $request->kbe;
        }

        if ($user->isUser()) {
            $request->validate([
                'lastname' => 'required',
            ]);

            $user->lastname = $request->lastname;
        }

        if (strcmp($user->email, $request->email) !== 0) {
            $request->validate([
                'email' => 'required|email|unique:users,email',
            ]);

            $user->email = $request->email;
        }

        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->name = $request->name;
        $user->avatar = ltrim(str_replace(url('storage'), '', $request->avatar), '/');

        $user->save();

        $user->addAction('update_contacts');
        $user->addAction('update_profile');

        return redirect()->back()->with("success", __("Saved."));
    }

    public function settingsAccess(Request $request)
    {
        if (!(\Hash::check($request->get('current-password'), \Auth::user()->password))) {
            return redirect()->back()->with("error", __("The provided password does not match your current password."));
        }

        $request->validate([
            'current-password' => 'required',
            'new-password'     => 'required|string|min:6|confirmed',
        ]);

        $user = \Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();

        $user->addAction('update_password');

        return redirect()->back()->with("success", __("Saved."));
    }

    public function registerRole(Request $request)
    {
        $user = auth()->user();

        if ($user->roles()->exists()) {
            return redirect('profile')->with("warning", __("Role already selected."));
        }

        if ($request->method() == 'POST') {
            if (in_array($request->role, ['press', 'journalist'])) {
                $user->assignRole($request->role);
            } else {
                $user->assignRole('journalist');
            }

            // add balance before 1 jan 2022  && time() < strtotime('2022-01-01 00:00:00')
            if ($request->role == 'press') {
                $user->balance = 125000;
                $user->update();

                $admins = User::select('users.*')
                    ->join('role_user', 'role_user.user_id', 'users.id')
                    ->join('roles', 'role_user.role_id', 'roles.id')
                    ->where('roles.slug', 'admin')
                    ->get();

                foreach ($admins as $admin) {
                    $admin->notify(new NewPress($user));
                }
            }

            if ($request->role == 'journalist') {
                return redirect('register/place');
            } else {
                return redirect('settings');
            }
        }

        return view('auth.register-role');
    }

    public function registerPlace(Request $request)
    {
        $user = auth()->user();

        if (!$user->isUser()) {
            return redirect('profile');
        }

        if ($request->method() == 'POST') {
            $city = City::find($request->city);
            if (!$city) {
                return redirect()->back()->with("error", __("Incorrect City."));
            }

           // $user->name = preg_replace('@[^A-ZА-Яa-zа-я\-\ ]@Usi', '', $request->name);
        //    $user->lastname = preg_replace('@[^A-ZА-Яa-zа-я\-\ ]@Usi', '', $request->lastname);
            $user->name = $request->name;
            $user->lastname = $request->lastname;
            $user->city_id = $city->id;
            $user->update();

            return redirect('profile')->with('success', __("Saved."));
        }

        $regions = Region::all();
        $cities = City::all();

        return view('auth.register-place', [
            'regions' => $regions,
            'cities' => $cities,
        ]);
    }


}
