<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TechController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_incidents = DB::table('incidents')->orderBy('incidents.created_at', 'DESC')
            ->join('detail_incidents', 'incidents.id', '=', 'detail_incidents.incident_id')
            ->leftJoin('users', 'incidents.user_id', '=', 'users.id')
            ->where('incidents.tech_id', '=', Auth::user()->id)
            ->orWhere('incidents.tech_id', '=', null)
            ->select(
                'incidents.*',
                'detail_incidents.incident_id',
                'detail_incidents.message_reply',
                DB::raw('CONCAT(users.firstname, " ", users.lastname) as user_name')
            )->orderBy('detail_incidents.created_at', 'DESC')
            ->get()
            ->unique('incident_id');

        return view('users.tech.index', compact('user_incidents'));
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
