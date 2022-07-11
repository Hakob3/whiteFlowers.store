<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\App;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $messages = Message::all();
        return view('admin.messages.index')->with('messages', $messages);
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'email|required',
            'subject' => 'required',
            'text' => 'required',
        ]);

        $name = $request->name;
        $email = $request->email;
        $subject = $request->subject;
        $text = $request->text;

        $pattern  = "/^[a-zA-Z\p{Cyrillic}\s]+$/u";
        $result = (bool) preg_match($pattern, $name);

        if(!$result){
            Session::flash('response', [
                'status' => 'error',
                'message' => 'Message not sent'
            ]);
            return response()->json(array(
                'success' => false,
                'errors' => ['name'=>'The name must be only in Cyrillic or Latin and do not contain special characters']));
        }

        if ($validator->passes()) {

            $data = new Message();
            $data->name = $name;
            $data->email = $email;
            $data->subject = $subject;
            $data->text = $text;

            $data->save();
            Session::flash('response', [
                'status' => 'success',
                'message' => 'Message sent'
            ]);
            return response()->json(array(
                'success' => true,
                'success_msg' => 'Message sent'));
        }else {
            Session::flash('response', [
                'status' => 'error',
                'message' => 'Message not sent'
            ]);
            return response()->json(array(

                'success' => false,
                'errors' => $validator->errors()->toArray()));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        $message = Message::where('id', $message)->first();
        view('admin.messages.show')->with('message', $message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        try {
            $user = Message::find($message);
            $user->delete();
            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'false',
                'message' => $e->getMessage()
            ]);
        }
    }
}
