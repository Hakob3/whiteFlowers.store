@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title">Messages</h3>
                    <div class="table-responsive">
                        <div>{{$message->name}}</div>
                        <div>{{$message->subject}}</div>
                        <div>{{$message->text}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
