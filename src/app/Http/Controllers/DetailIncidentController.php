<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDetailIncidentRequest;
use App\Models\DetailIncident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DetailIncidentController extends Controller
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
    public function store(CreateDetailIncidentRequest $request)
    {
        $detail_incident = $request->validated();

        $detail_incident['from_user_id'] = Auth::user()->id;

        $incident_id = url()->previous();
        $url_delete = 'http://localhost:8000/detail-incident/';
        $incident_id = str_replace($url_delete, '', $incident_id);
        $detail_incident['incident_id'] = (int) $incident_id;

        DetailIncident::create($detail_incident);

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $details_incident = DB::table('detail_incidents')
            ->join('incidents', 'detail_incidents.incident_id', '=', 'incidents.id')
            ->where('incident_id', $id)
            ->select(
                'detail_incidents.*',
                'incidents.incident_status'
            )
            ->get();

        return view('users.tech_user.show', compact('details_incident'));
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
