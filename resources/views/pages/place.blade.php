
@extends('layouts.app')

@section('content')
        
<div class="container mt-5 pt-5">

<chats :user="{{ auth()->user() }}" :place="{{ $place }}" ></chats>

</div>
        
@endsection

