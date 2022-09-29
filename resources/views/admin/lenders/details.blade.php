@extends('layouts.app-admin')

@section('content')

    <div class="row mt-3 mb-3 b-1 border-bottom" >
            <div class="col-sm-12">
                <label  class="col-form-label m-2" style="font-size: 30px;" >{{$lender->name}}</label>
               {{--  <a  href="{{ route('register') }}" class="btn btn-success m-4 float-right" style="font-size: 18px; height:40px;width: 40px; border-radius: 50%;">{{ __('+') }}</a> --}}
                 </div>
              </div>

                    {{-- <label class="page_title_sub"> </label> --}}


    <div class="row justify-content-center mt-3 ">

  

        <div class="col-md-12">
          
            <div class="card">
                {{-- <div class="card-header">Update Rate CSV</div> --}}
                <div class="card-body">
                   <form id="" method="POST" action="{{ route('update-rate-csv') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row m-3">
                      <label class="col-md-4 col-form-label text-md-right" > Update Rate CSV</label>
                      <input class="col-md-4  mt-2 " id="csv_file" type="file"  name="csv_file" required>  
                      <input  type="hidden" name="lender_id" value="{{$lender->id}}" >    
                   </div>

                   <div class=" row m-4 ">
                     <div class=" col-md-4 ">
                    </div>
                    <div class=" col-md-4 ">
                      <button class="btn m-1 " style="background-color: #ddd;" id="uploadCsvBtn" type="submit" > Update Rate CSV</button>
                    </div>
                   </div>
                 </form>
                </div>
            </div>
            <br>
            <br>
             <div class="card">
                {{-- <div class="card-header">Update Credit Score CSV</div> --}}
                <div class="card-body">
                   <form id="" method="POST" action="{{ route('update-credit-score-csv') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row m-3">
                      <label class="col-md-4 col-form-label text-md-right" > Update Credit Score CSV</label>
                      <input class="col-md-4  mt-2 " id="csv_file" type="file"  name="csv_file" required>  
                      <input  type="hidden" name="lender_id" value="{{$lender->id}}" >    
                   </div>

                   <div class=" row m-4 ">
                     <div class=" col-md-4 ">
                    </div>
                    <div class=" col-md-4 ">
                      <button class="btn m-1 " style="background-color: #ddd;" id="uploadCsvBtn" type="submit" > Update Credit Score CSV</button>
                    </div>
                   </div>
                 </form>
                </div>
            </div>



             @if( !empty($rates))
             <h2>CF 30 YR</h2>
             <table class='table table-responsive table-striped table-bordered   '>
              <thead class=''> 
                <tr> <th class='text-center '>Rate</th> <th class='text-center '>30 Day</th> </tr>
              </thead>
              @foreach($rates as $rate)
              <tr> <td>{{$rate->rate}}</td> <td>{{$rate->value}}</td> </tr>
              @endforeach
              </table>
            @else
             <label > No Rates found </label>
            @endif

            @if( !empty($creditScores))
             <h2>Credit Scores based LTV</h2>
             <table class='table table-responsive table-striped table-bordered   '>
              <thead class=''> 
                <tr> <th class='text-center '>Rate</th> <th class='text-center '>30 Day</th> </tr>
              </thead>
              @foreach($creditScores as $creditScore)
              <tr> <td>{{$creditScore->detail}}</td> <td>{{$creditScore->value}}</td> </tr>
              @endforeach
              </table>
            @else
             <label > No creditScore found </label>
            @endif



        </div>
    </div>

<Script> function setNavSelection(){ $("#menu_renders").addClass("menu_item_selcted");} </Script>
@endsection