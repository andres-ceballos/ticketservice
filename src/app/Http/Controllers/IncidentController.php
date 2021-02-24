<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateIncidentRequest;
use App\Models\DetailIncident;
use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
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
        return view('users.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateIncidentRequest $request)
    {
        $incident_req = $request->validated();
        $incident_req['user_id'] = Auth::user()->id;

        $incident = new Incident();
        $incident->title = $incident_req['title'];
        $incident->user_id = $incident_req['user_id'];
        $incident->save();

        $last_incident = Incident::orderBy('id', 'DESC')->first();

        $detail_incident = new DetailIncident();
        $detail_incident->message_reply = $incident_req['message_reply'];
        $detail_incident->from_user_id = $incident_req['user_id'];
        $detail_incident->incident_id = $last_incident->id;
        $detail_incident->save();

        return view('users.user.create');
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
        if ($request->action == 'update_tech_id') {
            $incident = Incident::findOrFail($id);
            $tech_id = Auth::user()->id;
            $incident->update(['tech_id' => $tech_id]);

            return redirect('detail-incident/' . $id);
            //
        } else if ($request->action == 'update_incident_status') {
            $incident = Incident::findOrFail($id);
            $incident->update(['incident_status' => 1]);

            return redirect('/tech');
        }
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
