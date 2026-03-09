<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index() {
        $notifications = Notification::with('user')->get();
        return view('notifications.index', compact('notifications'));
    }

    public function create() {
        $users = User::all();
        return view('notifications.create', compact('users'));
    }

    public function store(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required',
        ]);

        Notification::create($request->all());
        return redirect()->route('notifications.index')->with('success', 'Notification created successfully.');
    }

    public function show($id) {
        $notification = Notification::with('user')->findOrFail($id);
        return view('notifications.show', compact('notification'));
    }

    public function edit($id) {
        $notification = Notification::findOrFail($id);
        $users = User::all();
        return view('notifications.edit', compact('notification', 'users'));
    }

    public function update(Request $request, $id) {
        $notification = Notification::findOrFail($id);
        $notification->update($request->all());
        return redirect()->route('notifications.index')->with('success', 'Notification updated successfully.');
    }

    public function destroy($id) {
        Notification::destroy($id);
        return redirect()->route('notifications.index')->with('success', 'Notification deleted successfully.');
    }
}
