<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\UserMessage;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserMessageController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Message View', only: ['index']),
            new Middleware('permission:Message Delete', only: ['destroy', 'bulkDestroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['items'] = UserMessage::latest()->get();
        return view('backend.user-message.index', $data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        UserMessage::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Message deleted successfully');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        if (is_array($ids) && count($ids) > 0) {
            UserMessage::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Selected messages deleted successfully']);
        }
        return response()->json(['success' => false, 'message' => 'No messages selected']);
    }
}
