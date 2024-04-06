<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Helpers;
use Notification;

class NotificaitonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reminder_count=Helpers::fetch_reminder_notification();
        $count_notify=auth()->user()->unreadNotifications()->count();
        $notify_data=auth()->user()->unreadNotifications()->where('type','App\Notifications\PushNotification')->get();
        return compact('count_notify','notify_data','reminder_count');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function all_notifications(Request $request){
        $notify_data=auth()->user()->unreadNotifications()->where('type','App\Notifications\PushNotification')->get();
        $notify_data->markAsRead();
        return view('all-notifications',compact('notify_data'));
    }
}
