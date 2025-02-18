<?php

namespace App\Http\Controllers;

use App\Mail\JoinedEventMail;
use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Support\Facades\Log;
use Mail;

class EventParticipantController extends CrudController
{
    protected $table = 'event_participants';

    protected $modelClass = EventParticipant::class;

    protected function getTable()
    {
        return $this->table;
    }

    protected function getModelClass()
    {
        return $this->modelClass;
    }

    public function join($id)
    {
        try {
            $event = Event::find($id);
            if (! $event) {
                return response()->json(['success' => false, 'errors' => [__('events.not_found')]]);
            }
            if ($event->host_id == auth()->id()) {
                return response()->json(['success' => false, 'errors' => [__('events.host_cannot_join')]]);
            }
            if ($event->participants()->count() >= $event->capacity) {
                return response()->json(['success' => false, 'errors' => [__('events.full')]]);
            }
            if ($event->participants()->where('user_id', auth()->id())->exists()) {
                return response()->json(['success' => false, 'errors' => [__('events.already_joined')]]);
            }

            $event->participants()->attach(auth()->id());

            Mail::to(auth()->user()->email)->queue(new JoinedEventMail($event, auth()->user()));

            return response()->json(['success' => true, 'message' => [__('events.joined')]]);
        } catch (\Exception $e) {
            Log::error('Error caught in function EventParticipantController.join: '.$e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json(['success' => false, 'errors' => [__('common.unexpected_error')]]);
        }
    }

    public function leave($id)
    {
        try {
            $event = Event::find($id);
            if (! $event) {
                return response()->json(['success' => false, 'errors' => [__('events.not_found')]]);
            }
            if ($event->participants()->where('user_id', auth()->id())->doesntExist()) {
                return response()->json(['success' => false, 'errors' => [__('events.not_joined')]]);
            }

            $event->participants()->detach(auth()->id());

            return response()->json(['success' => true, 'message' => [__('events.left')]]);
        } catch (\Exception $e) {
            Log::error('Error caught in function EventParticipantController.leave: '.$e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json(['success' => false, 'errors' => [__('common.unexpected_error')]]);
        }
    }

    public function participants($id)
    {
        try {
            $event = Event::find($id);
            if (! $event) {
                return response()->json(['success' => false, 'errors' => [__('events.not_found')]]);
            }

            $participants = $event->participants()
                ->get(['user_id as id', 'users.full_name', 'joined_at'])
                ->makeHidden(['rolesNames', 'permissionsNames', 'pivot']);

            return response()->json(['success' => true, 'data' => ['items' => $participants]]);
        } catch (\Exception $e) {
            Log::error('Error caught in function EventParticipantController.participants: '.$e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json(['success' => false, 'errors' => [__('common.unexpected_error')]]);
        }
    }
}
