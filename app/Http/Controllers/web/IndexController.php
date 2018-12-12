<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function petugas()
    {
        return view('petugas');
    }

    public function kelas()
    {
        return view('kelas');
    }

    public function absen()
    {
        return view('absen');
    }

    public function siswa()
    {
        return view('siswa');
    }

    public function thAjaran()
    {
        return view('thAjaran');
    }
}