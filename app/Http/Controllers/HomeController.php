<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function userHome()
    {
        return view('home',["msg"=>"I am user role"]);
    }

    public function userFav()
    {
        return view('user.user-fav');
    }

    public function userLib()
    {
        return view('user.user-lib');
    }

    public function userDownload()
    {
        return view('user.user-download');
    }

    public function adminHome()
    {
        return view('admin.admin-dashboard',["msg"=>"I am admin role"]);
    }

    public function adminManage()
    {
        return view('admin.manage',["msg"=>"I am admin role"]);
    }

    public function adminBook()
    {
        return view('admin.book',["msg"=>"I am admin role"]);
    }

    public function addUser()
    {
        return view('admin.add-user');
    }

    public function addBook()
    {
        return view('admin.add-book');
    }

    public function crewHome()
    {
        return view('crew.home',["msg"=>"I am crew role"]);
    }

    public function crewProfile()
    {
        return view('crew.profile');
    }

    public function crewAdd()
    {
        return view('crew.add');
    }

    public function crewList()
    {
        return view('crew.list');
    }

    public function crewData()
    {
        return view('crew.data');
    }

}
