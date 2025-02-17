<?php

namespace App\Http\Controllers;

use App\Mail\EventCreatedMail;
use App\Models\Event as ModelsEvent;
use Illuminate\Http\Request;
use Log;
use Mail;

class EventController extends CrudController
{
    protected $table = 'events';

    protected $modelClass = ModelsEvent::class;

    protected function getTable()
    {
        return $this->table;
    }

    protected function getModelClass()
    {
        return $this->modelClass;
    }

    public function createOne(Request $request)
    {
        try {
            $request->merge(['host_id' => auth()->id()]);

            return parent::createOne($request);
        } catch (\Exception $e) {
            Log::error('Error caught in function EventController.createOne : '.$e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json(['success' => false, 'errors' => [__('common.unexpected_error')]]);
        }
    }

    public function afterCreateOne($item)
    {
        Mail::to($item->host->email)->send(new EventCreatedMail($item, $item->host));
    }
}
