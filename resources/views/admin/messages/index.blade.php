@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title">Messages</h3>
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                            <tr>
                                <th class="border-top-0">#</th>
                                <th class="border-top-0">Name</th>
                                <th class="border-top-0">Email</th>
                                <th class="border-top-0">Subject</th>
                                <th class="border-top-0">Text</th>
                                <th class="border-top-0">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($messages as $message)


                                    <tr>
                                        <th scope="row">{{$message->id}}</th>
                                        <td class="td_message"><?= $message->name?></td>
                                        <td class="td_message"><?= $message->email?></td>
                                        <td class="td_message"><?= $message->subject?></td>
                                        <td class="td_message"><?= $message->text?></td>
                                        <td>
                                            <form onsubmit="if(confirm('Delete?')){return true}else return false"
                                                  method="post" action="{{route('admin.contact.destroy', $message)}}">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE">
                                                <a href="{{route('admin.contact.show', $message)}}"><i class="fa fa-envelope-open-o" aria-hidden="true"></i></a>
                                                <button type="submit" class="btn delete_btn"><i class="fa fa-trash"
                                                                                                aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
