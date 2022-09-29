@extends('layouts.app-admin')

@section('content')

    <div class="row mt-3 mb-3 b-1 border-bottom" >
            <div class="col-sm-12">
                <label  class="col-form-label m-2" style="font-size: 30px;" >Lenders</label>
                {{-- <a  href="{{ route('register') }}" class="btn btn-success m-4 float-right" style="font-size: 18px; height:40px;width: 40px; border-radius: 50%;">{{ __('+') }}</a> --}}
            </div>
       </div>

    @if( !empty($lenders))
      @foreach($lenders as $lender)
      <div class="row mt-3 b-1 border-bottom" >
            <div class="col-sm-12">
                  <label  class="col-form-label m-2"> <a href="{{ route('lender-detail', $lender->id) }}" style="font-size: 20px;font-weight: bold;"> {{$lender->name}}</a>  ({{$lender->website}})</label> 
            </div>
       </div>
      @endforeach
    @else
     <label > No lender found </label>
    @endif

<Script> function setNavSelection(){ $("#menu_renders").addClass("menu_item_selcted");} </Script>
@endsection