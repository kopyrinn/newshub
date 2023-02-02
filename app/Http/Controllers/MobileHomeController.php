<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\UserCategory;
use App\Models\Category;
use App\Models\GrammaticalError;
use App\Models\Page;
use App\Models\Post;
use App\Models\Region;
use App\Models\Rubric;
use App\Models\User;
use App\Models\Vacancy;
use App\Models\Widget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\SchemaOrg\Schema;
use App\Helpers\Util;

class MobileHomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $breaking = Post::where('is_breaking', 1)
            ->where('status', 1)
            ->where('created_at', '<', \Carbon\Carbon::now())
            ->latest('created_at')
            ->limit(10)
            ->get();

        $slider = Post::where('is_slider', 1)
            ->where('status', 1)
            ->where('created_at', '<', \Carbon\Carbon::now())
            ->latest('created_at')
            ->limit(10)
            ->get();

        $featured = Post::where('is_featured', 1)
            ->where('status', 1)
            ->where('created_at', '<', \Carbon\Carbon::now())
            ->latest('created_at')
            ->limit(4)
            ->get();

        $widgets = Widget::orderBy('position')
            ->get();
        // ~r($widgets);

        return view('mobile.home', [
            'breaking' => $breaking,
            'slider' => $slider,
            'featured' => $featured,
            'widgets' => $widgets,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
