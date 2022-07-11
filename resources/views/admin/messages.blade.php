@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Subject</th>
                <th scope="col">Text</th>
            </tr>
            </thead>
            <tbody>
            @foreach($messages as $message)
            <tr>
                <th scope="row">{{$message->id}}</th>
                <td class="">{{$message->name}}</td>
                <td class="">{{$message->email}}</td>
                <td class="">{{$message->subject}}</td>
                <td class="td_message">{{$message->text}}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
