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
        //SAVE INCIDENT WITH ADD ID USER CURRENT SESSION
        $incident_req = $request->validated();
        $incident_req['user_id'] = Auth::user()->id;

        $incident = new Incident();
        $incident->title = $incident_req['title'];
        $incident->user_id = $incident_req['user_id'];
        $incident->save();

        //TAKE LAST ID INCIDENT REGISTER
        $last_incident = Incident::orderBy('id', 'DESC')->first();

        //SAVE DETAIL INCIDENT WITH ID INCIDENT
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
        //CONDITION FOR...
        //UPDATE ID TECH WHO TAKE INCIDENTS...
        if ($request->action == 'update_tech_id') {
            $incident = Incident::findOrFail($id);
            $tech_id = Auth::user()->id;
            $incident->update(['tech_id' => $tech_id]);

            return redirect('detail-incident/' . $id);
            //
            ////OR UPDATE STATUS IF INCIDENT WILL BE CLOSED
        } else if ($request->action == 'update_incident_status') {
            Incident::where('id', $id)
                ->update([
                    'incident_status' => 1,
                    'notification_user' => null,
                    'notification_tech' => null,
                ]);

            return redirect('/tech');
            //
            ////OR ADD NEW VALUES FOR NOTIFICATION COLUMNS IN DATABASE
        } elseif ($request->action == 'update_notification') {
            //CONDITION WHAT DECIDES IF UPDATE IS FOR TECH NOTIFICATION COLUMN
            if ($request->type_user == 'Tech') {
                Incident::where('id', $request->incident_id)
                    ->update(['notification_user' => $request->message_counter]);
                return response()->json(['success' => 'Update User Not. Success', 200]);
                //
                //OR USER NOTIFICATION COLUMN
            } else if ($request->type_user == 'User') {
                Incident::where('id', $request->incident_id)
                    ->update(['notification_tech' => $request->message_counter]);
                return response()->json(['success' => 'Update Tech Not. Success'], 200);
            }
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
