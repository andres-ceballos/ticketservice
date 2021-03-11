<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Http\Requests\CreateDetailIncidentRequest;
use App\Models\DetailIncident;
use App\Models\Incident;
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

        //ADD ID WHO SEND MESSAGE
        $detail_incident['from_user_id'] = Auth::user()->id;

        //METHOD TO TAKE ID INCIDENT
        $url_complete = url()->previous();
        $url_delete = 'http://localhost:8000/detail-incident/';
        $incident_id = str_replace($url_delete, '', $url_complete);
        $detail_incident['incident_id'] = (int) $incident_id;

        //CREATE DETAIL INCIDENT
        DetailIncident::create($detail_incident);

        //TAKE THE LAST DETAIL INCIDENT REGISTER CORRESPONDENT FOR ID USER WHO SEND MESSAGE
        $last_detail_incident = DetailIncident::orderBy('id', 'DESC')
            ->join('incidents', 'detail_incidents.incident_id', '=', 'incidents.id')
            ->where('from_user_id', Auth::user()->id)
            ->select(
                'detail_incidents.*',
                'incidents.notification_user',
                'incidents.notification_tech',
                'incidents.user_id',
                'incidents.tech_id',
            )
            ->latest()
            ->get()
            ->first();

        //DECIDES WHAT VIEW WILL HAVE NOTIFICATION MESSAGE
        if ($last_detail_incident['user_id'] == Auth::user()->id) {
            $type_user = 'User';
        } else if ($last_detail_incident['tech_id'] == Auth::user()->id) {
            $type_user = 'Tech';
        }

        //DATA TO SEND FOR EVENT AND JSON METHOD
        $event_data = [
            'message_reply' => $last_detail_incident['message_reply'],
            'created_at' => $last_detail_incident['created_at']->format('Y-m-d h:i:s'),
            'incident_id' => $incident_id,
            'type_user' => $type_user,
            'notification_user' => $last_detail_incident->notification_user,
            'notification_tech' => $last_detail_incident->notification_tech
        ];

        broadcast(new NewMessage($event_data));

        //JSON METHOD FOR UPDATE NOTIFICATION COLUMNS MAIN JS
        return response()->json($event_data, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $details_incident = DetailIncident::join('incidents', 'detail_incidents.incident_id', '=', 'incidents.id')
            ->where('incident_id', $id)
            ->select(
                'detail_incidents.*',
                'incidents.incident_status',
                'incidents.user_id',
                'incidents.tech_id'
            )
            ->get();

            //IF USER SEE INDEX VIEW, NOTIFICATION WILL BE RESET
            //CONDITION DECIDES WHAT USER IS
        if ($details_incident[0]['user_id'] == Auth::user()->id) {
            Incident::where('id', $id)
                ->update(['notification_user' => null]);
        } else if ($details_incident[0]['tech_id'] == Auth::user()->id) {
            Incident::where('id', $id)
                ->update(['notification_tech' => null]);
        }

        //RESET NOTIFICATION COUNTER FROM MAIN JS
        $event_data = [
            'reset_count' => 1,
        ];

        broadcast(new NewMessage($event_data));

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
