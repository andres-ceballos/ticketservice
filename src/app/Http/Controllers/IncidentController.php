<?php

namespace App\Http\Controllers;

use App\Events\NewIncident;
use App\Events\NewPanelNotification;
use App\Http\Requests\CreateIncidentRequest;
use App\Models\DetailIncident;
use App\Models\Incident;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
        //CREATE INCIDENT WITH ID, NAME USER CURRENT SESSION
        $incident = $request->validated();
        $incident['user_id'] = Auth::user()->id;
        $incident['firstname'] = Auth::user()->firstname;
        $incident['lastname'] = Auth::user()->lastname;
        $incident['notification_tech'] = 1;

        Incident::create($incident);

        //TAKE LAST ID INCIDENT REGISTER
        $last_incident = Incident::orderBy('id', 'DESC')->first();

        $detail_incident['message_reply'] = $incident['message_reply'];
        $detail_incident['from_user_id'] = $incident['user_id'];
        $detail_incident['incident_id'] = $last_incident->id;

        //CREATE DETAIL INCIDENT WITH SOME LAST DATA
        DetailIncident::create($detail_incident);

        //EVENT DATA TO SEND AND SHOW DATA IN REAL TIME
        $event_data = [
            'incident' => $incident,
            'incident_created' => $last_incident['created_at']->format('Y-m-d h:i:s'),
            'detail_incident' => $detail_incident
        ];

        broadcast(new NewIncident($event_data));

        return redirect()->back()->with('success', 'Solicitud creada correctamente');
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
            Incident::where('id', $id)
                ->update(['tech_id' => Auth::user()->id]);

            //DELETE ROW ACCEPT FOR EVERYONE ELSE TECH
            broadcast(new NewIncident(['action' => 'delete-row', 'incident_id' => $id]));

            //FIRST AND LASTNAME OF TECH FOR NOTIFICATION USER PANEL
            $tech = User::findOrFail(Auth::user()->id);

            $event_data = [
                'panel' => 'User',
                'incident_id' => $id,
                'tech_name' => $tech->firstname . ' ' . $tech->lastname
            ];

            //SEND TECH NAME FOR USER PANEL VIEW INDEX
            broadcast(new NewPanelNotification($event_data));

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

            $detail_incident = DetailIncident::where('incident_id', $id)->firstOrFail();
            $incident = Incident::findOrFail($id);
            $user = User::findOrFail($incident->user_id);

            $username = $user->firstname . ' ' . $user->lastname;

            $event_data = [
                'panel' => 'User-Chat',
                'incident_id' => $id,
            ];

            broadcast(new NewPanelNotification($event_data));

            $this->sendEmail($username, $user->email, $incident->id, $incident->title, $detail_incident->message_reply);

            return redirect()->back()->with('success', 'La solicitud ha sido cerrada');
            //
            ////OR ADD NEW VALUES FOR NOTIFICATION COLUMNS IN DATABASE
        } elseif ($request->action == 'update_notification') {
            //CONDITION WHAT DECIDES IF UPDATE IS FOR TECH NOTIFICATION COLUMN
            if ($request->type_user == 'Tech') {
                Incident::where('id', $id)
                    ->update(['notification_user' => $request->message_counter]);
                return response()->json(['success' => 'Update User Not. Success', 200]);
                //
                //OR USER NOTIFICATION COLUMN
            } else if ($request->type_user == 'User') {
                Incident::where('id', $id)
                    ->update(['notification_tech' => $request->message_counter]);
                return response()->json(['success' => 'Update Tech Not. Success'], 200);
            }
        } elseif ($request->action == 'update_service_rating') {
            Incident::where('id', $id)
                ->update(['service_rating' => $request->star_rating]);

            $event_data = [
                'panel' => 'Tech',
                'incident_id' => $id,
                'star_rating' => $request->star_rating,
            ];

            //SEND TECH NAME FOR USER PANEL VIEW INDEX
            broadcast(new NewPanelNotification($event_data));

            return response()->json(['success' => 'Valoración del servicio enviada.'], 200);
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

    public function sendEmail($name, $email, $incident_id, $title, $message)
    {
        $url_domain = url('/');
        $url_rating = $url_domain . '/service-rating' . '/' . $incident_id;

        $data = array(
            'name' => $name,
            'email' => $email,
            'title' => $title,
            'message_reply' => $message,
            'rating_page' => $url_rating
        );

        Mail::send('email.mail', $data, function ($var) use ($name, $email) {
            $var->to($email, $name)->subject('Incidencia resuelta');
            $var->from('empresanonyma@outlook.es', 'Tickets Service');
        });
    }
}
