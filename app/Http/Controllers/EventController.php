<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index(): EventResource
    {
        $events = Event::get();
        return new EventResource($events);
    }

    public function buyTicket(Request $request, $id)
    {
        $input = validator($request->all(), [
            'qty' => 'required|numeric|min:1'
        ])->validate();

        $user = auth()->user();
        $event = Event::findOrFail($id);
        if ($event->stock <= 0) {
            return response()->json([
                "status" => false,
                "message" => 'Event Sold Out!.'
            ], 400);
        }

        if ($event->stock < $input['qty']) {
            return response()->json([
                "status" => false,
                "message" => 'Ticket Slot Not Enough!.'
            ], 400);
        }

        DB::beginTransaction();
        try{
            $event->stock = $event->stock - $input['qty'];
            $event->save();

            $user->events()->attach($event);
        
            DB::commit();
            
            return response()->json([
                "status" => true,
                "message" => 'Purchase Ticket Successfully!.'
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                "status" => false,
                "message" => 'Server error!.'
            ], 500);
        } 
    }
}
