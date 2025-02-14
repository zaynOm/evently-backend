<?php

namespace App\Http\Controllers;

use App\Enums\ROLE;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Log;

class UserController extends CrudController
{
    protected $table = 'users';

    protected $modelClass = User::class;

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
            $request->merge(['password' => Hash::make($request->password)]);

            return parent::createOne($request);
        } catch (\Exception $e) {
            Log::error('Error caught in function UserController.createOne : '.$e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json(['success' => false, 'errors' => [__('common.unexpected_error')]]);
        }
    }

    public function afterCreateOne($item, $request)
    {
        try {
            $roleEnum = ROLE::from($request->role);
            $item->syncRoles([$roleEnum]);
        } catch (\Exception $e) {
            Log::error('Error caught in function UserController.afterCreateOne : '.$e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json(['success' => false, 'errors' => [__('common.unexpected_error')]]);
        }
    }

    public function updateOne($id, Request $request)
    {
        try {
            if (isset($request->password) && ! empty($request->password)) {
                $request->merge(['password' => Hash::make($request->password)]);
            } else {
                $request->request->remove('password');
            }

            return parent::updateOne($id, $request);
        } catch (\Exception $e) {
            Log::error('Error caught in function UserController.updateOne : '.$e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json(['success' => false, 'errors' => [__('common.unexpected_error')]]);
        }
    }

    public function afterUpdateOne($item, $request)
    {
        try {
            $roleEnum = ROLE::from($request->role);
            $item->syncRoles([$roleEnum]);
        } catch (\Exception $e) {
            Log::error('Error caught in function UserController.afterUpdateOne : '.$e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json(['success' => false, 'errors' => [__('common.unexpected_error')]]);
        }
    }

    public function getHostedEvents()
    {
        try {
            $user = auth()->user();
            if (! $user) {
                return response()->json(['success' => false, 'errors' => [__('users.not_found')]]);
            }

            return response()->json(['success' => true, 'data' => ['items' => $user->hostedEvents]]);
        } catch (\Exception $e) {
            Log::error('Error caught in function UserController.getHostedEvents : '.$e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json(['success' => false, 'errors' => [__('common.unexpected_error')]]);
        }
    }

    public function getAttendedEvents()
    {
        try {
            $user = auth()->user();
            if (! $user) {
                return response()->json(['success' => false, 'errors' => [__('users.not_found')]]);
            }

            $events = $user->participatingIn->makeHidden(['pivot']);

            return response()->json(['success' => true, 'data' => ['items' => $events]]);
        } catch (\Exception $e) {
            Log::error('Error caught in function UserController.getAttendedEvents : '.$e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json(['success' => false, 'errors' => [__('common.unexpected_error')]]);
        }
    }
}
