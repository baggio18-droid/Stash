@extends('layouts.appUser')

@section('content')
<div class="container">
    <div>
        @if ($message = Session::get('fail'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert" style="border-radius: 10px">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>
                <p style="margin: 0">{{ $message }}</p>
            </strong>
        </div>
        @elseif ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert"
            style=" text-align: center; border-radius: 10px">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>
                <p style="margin: 0">{{ $message }}</p>
            </strong>
        </div>
        @endif
    </div>
</div>
<div class="container">
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 10px">
        <strong>Whoops!</strong> There were some problems with your input.<br>
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
<div class="container">
    <div class="chooseCityContainer">
        <div style="width: 50%; display: flex; justify-content: center; height: 350px; width: 350;">
            <img class="img-fluid" src="{{ asset('storage/images/chooseCity.png') }}" alt="">
        </div>
        <div style="width: 50%" class="m-0">
            <p>Choose a city where you want to rent {{$unit->category_name}} unit.</p>
            <form action="{{ route('chooseLocation') }}" method="GET" enctype="multipart/form-data">
                @csrf
                <input hidden name="unit" type="text" value="{{$unit->ID_Category}}">
                <div class="form-group" style="margin: 10px 0">
                    <select name="city" style="width: 100%" class="select2">
                        <option value="0">Select City</option>
                        @foreach ($cities as $city)
                        <option value="{{$city->city}}">
                            {{$city->city}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-outline-primary">Next</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(".select2").select2({
        theme: "bootstrap-5",
        selectionCssClass: "select2--small", // For Select2 v4.1
        dropdownCssClass: "select2--small",
    });
</script>
@endsection