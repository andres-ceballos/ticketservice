<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserRatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $incident = Incident::findOrFail($id);

        if ($incident->service_rating == 0) {
            //IF USER ID OF INCIDENT CORRESPOND TO USER ACTUAL ...
            if ($incident->user_id == Auth::user()->id) {
                return view('rating.show');
                //ELSE ... RETURN TO HOME
            } else {
                return redirect('/');
            }
        } else {
            //IF INCIDENT WAS RATED AND USER HAS LOGIN, RETURN TO USER PANEL AND SHOW NOTIFICATION
            if (Auth::check()) {
                return redirect('/user')->with('success', 'La solicitud ya se ha valorado con anterioridad');
                //ELSE ... RETURN TO HOME
            } else {
                return redirect('/')->with('success', 'La solicitud ya se ha valorado con anterioridad');
            }
        }
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
