@extends('layouts.appBranch')


@section('content')
<div class="container-fluid">
    <nav aria-label="breadcrumb" class="main-breadcrumb" style="border-radius: 20px">
        <ol class="breadcrumb" style="background-color: #fff8e6; border-radius: 10px">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Branch Employee: {{ Auth::user()->username }}</a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('branch.orders') }}">Orders</a></li>
            <li class="breadcrumb-item active" aria-current="page">Order Details in {{$branch->branch}} Branch</li>
        </ol>
    </nav>
    <div class="container-fluid" id="deliveryContainer" style="flex-wrap: wrap; flex-direction: column">
        <div class="headerO">
            <h1 style="margin-right: 10px">ORDER</h1>
            <div class="headerS">
                <div class="card" style="border-radius:20px;">
                    <div class="card-body">
                        <h6 class="mb-0"><strong>Order Details</strong></h6>
                        <div class="row" style="margin-top: 35px">
                            <div class="col-sm-3">
                                @php
                                $date1 = new DateTime($order->startsFrom);
                                $date2 = new DateTime($order->endsAt);
                                $today = new DateTime(date("Y-m-d H:i:s"));
                                $interval = $date1->diff($date2);
                                $orderCheck = $date2->diff($today);
                                @endphp
                                @if ($orderCheck->invert)
                                <div class="btn-sm btn-primary">Active
                                    <h6 class="mb-0"><strong>Period: {{ $interval->days }} @if ($interval->days <= 1)
                                                Day @else Days @endif Left </strong>
                                    </h6>
                                </div>
                                @else
                                <div class="btn-sm btn-danger">Expaired
                                    <h6 class="mb-0"><strong>Period: {{ $interval->days }} @if ($interval->days <= 1)
                                                Day @else Days @endif Exceeded </strong>
                                    </h6>
                                </div>
                                @endif

                            </div>
                            <div class="col-sm-9 text-secondary" style="display: flex">
                                <h6 class="mb-0"><strong>From</strong></h6><br>
                                {{ $order->startsFrom }}
                                <h6 class="mb-0"><strong>Until</strong></h6><br>
                                {{ $order->endsAt }}
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-2"><strong>Status</strong></h6>
                            </div>
                            <div class="col-sm-9 text-secondary"
                                style="display: flex; gap: 5px; justify-content: space-between">
                                @if ($order->order_status == 0)
                                <p id="staticStatusOrder" style="width: 70%" class="btn-sm btn-info">With Customer</p>
                                @elseif($order->order_status == 1)
                                <p id="staticStatusOrder" style="width: 70%" class="btn-sm btn-light">Waiting for
                                    Payment</p>
                                @elseif($order->order_status == 2)
                                <p id="staticStatusOrder" style="width: 70%" class="btn-sm btn-warning">Delivery</p>
                                @elseif($order->order_status == 3)
                                <p id="staticStatusOrder" style="width: 70%" class="btn-sm btn-success">In Stash</p>
                                @elseif($order->order_status == 4)
                                <p id="staticStatusOrder" style="width: 70%" class="btn-sm btn-secondary">Canceled</p>
                                @endif
                                <form action="{{ route('branch.changeOrderStatus', $order) }}"
                                    id="changeOrderStatusForm" enctype="multipart/form-data" method="POST"
                                    style="display: none">
                                    @csrf
                                    <p style="margin: 0">
                                        <small>Old Status:
                                            @if ($order->order_status == 0)
                                            With Customer
                                            @elseif($order->order_status == 1)
                                            Waiting for Payment
                                            @elseif($order->order_status == 2)
                                            Delivery
                                            @elseif($order->order_status == 3)
                                            In Stash
                                            @elseif($order->order_status == 4)
                                            Canceled
                                            @endif
                                        </small>
                                    <div style="display: flex; gap: 5px">
                                        <select class="form-select form-select-sm" name="status" id="editStatusOrder">
                                            <option class="btn-sm btn-info" value="0">With Customer</option>
                                            <option class="btn-sm btn-light" value="1">Waiting for Payment</option>
                                            <option class="btn-sm btn-warning" value="2">Delivery</option>
                                            <option class="btn-sm btn-success" value="3">In Stash</option>
                                            <option class="btn-sm btn-secondary" value="4">Canceled</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-outline-primary">Change</button>
                                    </div>
                                    <small>When you choose a status, it will change immediately</small>
                                    </p>
                                </form>
                                <a onclick="$('#staticStatusOrder').toggle(''); $('#changeOrderStatusForm').toggle('slow');"
                                    style="text-decoration: none;cursor: pointer">
                                    <i data-toggle="tooltip" title="Change Status"
                                        class="refresh-hover fa fa-magic icons"></i>
                                </a>
                            </div>
                            <div class="col-sm-3">
                                <h6 class="mb-2"><strong>Delivery</strong></h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                @if ($order->order_deliveries <= 0) <p style="width: 70%" class="btn-sm btn-secondary">
                                    No Deliveries
                                    </p>
                                    @else
                                    @php
                                    $status_waiting = 0;
                                    $status_On_Going = 0;
                                    $status_Done = 0;
                                    @endphp
                                    @foreach ($schedules as $schedule)
                                    @if ($schedule->ID_Order == $order->ID_Order && $schedule->schedule_status==0)
                                    @php
                                    $status_waiting++;
                                    @endphp
                                    @elseif ($schedule->ID_Order == $order->ID_Order &&
                                    $schedule->schedule_status==1)
                                    @php
                                    $status_On_Going++;
                                    @endphp
                                    @elseif ($schedule->ID_Order == $order->ID_Order &&
                                    $schedule->schedule_status==2)
                                    @php
                                    $status_Done++;
                                    @endphp
                                    @endif
                                    @endforeach
                                    <div style="width: 70%; margin-bottom: 1rem;" class="btn-sm btn-success">
                                        <h6 class="mb-0">
                                            Deliveries: {{$order->order_deliveries}}
                                            <i data-toggle="tooltip" title="Order Deliveries Details"
                                                onclick="$('#orderDeliveriesDetail{{$order->ID_Order}}').toggle('fast')"
                                                class="fas fa-arrow-down float-right"></i>
                                            <p class="mb-0" style="display: none; width: max-content"
                                                id="orderDeliveriesDetail{{$order->ID_Order}}">
                                                <small>
                                                    Waiting: {{$status_waiting}}
                                                    <br>On-Going: {{$status_On_Going}}
                                                    <br>Done: {{$status_Done}}
                                                </small>
                                            </p>
                                        </h6>
                                    </div>
                                    @endif
                            </div>
                            <div class="col-sm-3">
                                <h6 class="mb-2"><strong>Order Price</strong></h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <p style="width: 70%" class="btn-sm btn-success">{{ $order->order_totalPrice }}</p>
                            </div>
                            <div class="col-sm-3">
                                <h6 class="mb-2"><strong>Extend Time Price</strong></h6>
                            </div>
                            <div class="col-sm-9 text-secondary"
                                style="display: flex; gap: 5px; justify-content: space-between">
                                @if ($order->expandPrice <= 0) <p style="width: 70%" class="btn-sm btn-secondary">No
                                    extension yet</p>
                                    @else
                                    <p style="width: 70%" class="btn-sm btn-success">{{ $order->expandPrice }}</p>
                                    @endif
                                    <div>
                                        <a data-toggle="modal" data-target="#extendTimeOrder"
                                            style="text-decoration: none;cursor: pointer;">
                                            <i data-toggle="tooltip" title="Extend Time"
                                                class="use-hover far fa-calendar-plus icons" aria-hidden="true"></i>
                                        </a>
                                        <div class="modal fade" id="extendTimeOrder" tabindex="-1" role="dialog"
                                            aria-labelledby="extendTimeOrder" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="justify-content: center">
                                                        <h5 class="modal-title" id="extendTimeOrderTitle">
                                                            Extend Order Time for Customer {{$customer->name}}
                                                        </h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="alert-success"
                                                            style="padding: 10px; border-radius: 20px">
                                                            <form action="{{ route('branch.extendOrder', $order) }}"
                                                                id="extendOrderForm" enctype="multipart/form-data"
                                                                method="POST">
                                                                @csrf
                                                                <p>
                                                                    <center><strong>!! This Will Extend The Order
                                                                            Time!!</strong>
                                                                        <label for="endsAt">Extend Until</label>
                                                                        <input class="form-control"
                                                                            type="datetime-local" name="extendEndsAt">
                                                                        <small>Old:{{$order->endsAt}}</small>
                                                                        <br>
                                                                        Click Extend to Continue the Process
                                                                    </center>
                                                                </p>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <button onclick="$('#extendOrderForm').submit();" type="button"
                                                            class="btn btn-sm btn-outline-primary">Extend</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <a data-toggle="modal" data-target="#deleteOrder"
                                            style="text-decoration: none;cursor: pointer">
                                            <i data-toggle="tooltip" title="Delete Record"
                                                class="delete-hover far fa-trash-alt icons"></i>
                                        </a>
                                        <div class="modal fade" id="deleteOrder" tabindex="-1" role="dialog"
                                            aria-labelledby="deleteOrder" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="justify-content: center">
                                                        <h5 class="modal-title" id="deleteOrderTitle">
                                                            Delete Order for Customer {{$customer->name}}
                                                        </h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="alert-danger"
                                                            style="padding: 10px; border-radius: 20px">
                                                            <p>
                                                                <center><strong>!! This Will Delete The Order!!</strong>
                                                                    <br>
                                                                    Click Delete to Continue the Process
                                                                </center>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <button
                                                            onclick="$('#deleteOrder{{$order->ID_Order}}').submit();"
                                                            type="button"
                                                            class="btn btn-sm btn-outline-danger">Delete</button>
                                                        <form hidden action="{{ route('branch.deleteOrder', $order) }}"
                                                            id="deleteOrder{{$order->ID_Order}}"
                                                            enctype="multipart/form-data" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-2"><strong>Order Description</strong></h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <p style="width: 70%" class="btn-sm btn-light">{{$order->order_description}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="line" style="margin: 0 10px">||</div>
            <h1 style="margin-right: 10px">USER </h1>
            <div class="headerVehiclesSchedules widthHeader" style="text-align: center">
                <div class="card-body" style="background: #9D3488;border-radius:20px">
                    <a href="{{route('branch.orders', ['user' => $customer->ID_User])}}"
                        style="text-decoration: none;cursor: pointer" data-toggle="tooltip" title="View Profile">
                        <div class="d-flex flex-column align-items-center text-center">
                            <img width="100px" src="{{ asset('storage/' . $customer->user_img) }}"
                                alt="user{{ $customer->name }}" class="img-fluid rounded-circle"
                                style="border: white 5px solid;">
                            <div style="margin-top: 30px">
                                <h4 style="color: white;text-transform: uppercase">
                                    <strong>{{ $customer->username }}</strong>
                                </h4>
                                <hr style="height: 10px; color: #57244d">
                                <p style="color: white; margin: 0">Phone: {{ $customer->phone }}
                                </p>
                                <p style="color: white;">Email: {{ $customer->email }}
                                </p>
                                <p class="mt-auto" style="color: white;"><strong>Customr in STASH</strong></p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="headerO">

            <div class="headerVehiclesSchedules widthHeader" style="text-align: center;">
                <div>
                    <h5 style="margin: 5px 0 25px 0">
                        {{$category->name}} Category
                    </h5>
                    <div>
                        <img class="img-fluid" style="border-radius: 50%;" width="200px"
                            src="{{ asset('storage/'. $category->category_img) }}" alt="{{$category->name}}">
                    </div>
                </div>
            </div>
            <div style="text-align: center; margin: 0 5px">
                <h4>CATEGORY</h4>
                <h4>&</h4>
                <h4>UNIT</h4>
            </div>
            <div class="line" style="margin: 0 10px">||</div>
            <div class="headerS">
                <div class="card" style="border-radius:20px;">
                    <div class="card-body">
                        <div class="float-right" style="margin-bottom: 100px">
                            <a href="{{ route('branch.orderDetailsU', ['unit'=>$unit]) }}">
                                <p class="btn-sm btn-warning">Occupied</p>
                            </a>
                            <div class="btn-sm btn-success" style="background-color: #66377f">Capacity
                                <div class="progress mb-1" style="height: 5px" data-placement='left'
                                    data-toggle="tooltip" title="Capacity {{$unit->capacity}}%">
                                    @if ($unit->capacity >= 95)
                                    <div class="progress-bar bg-danger" role="progressbar"
                                        style="width: {{$unit->capacity}}%" aria-valuenow="{{$unit->capacity}}"
                                        aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                    @else
                                    <div class="progress-bar bg-primary" role="progressbar"
                                        style="width: {{$unit->capacity}}%" aria-valuenow="{{$unit->capacity}}"
                                        aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <h6 class="mb-0"><strong>Unit Details</strong></h6>
                        <div class="row" style="margin-top: 35px">
                            <div class="col-sm-3">
                                <h6 class="mb-0"><strong>Unit Name</strong></h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                {{ $unit->unit_name }}
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-2"><strong>Dimensions</strong></h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                {{ $category->dimensions }}
                            </div>
                            <div class="col-sm-3">
                                <h6 class="mb-2"><strong>Description</strong></h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                {{ $category->category_description }}
                            </div>
                            <div class="col-sm-3">
                                <h6 class="mb-2"><strong>Price Per Day</strong></h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                {{ $category->pricePerDay }}
                            </div>
                            <div class="col-sm-3">
                                <h6 class="mb-2"><strong>Private Key</strong></h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <div style="display: flex;gap: 10px">
                                    <input disabled style="width: 50%" type="password" value="{{$unit->privateKey}}"
                                        class="form-control" id="privateKey{{$unit->ID_Unit}}">
                                    <div style="display: flex;gap: 5px; margin-top: 5px">
                                        <input class="form-check-input" style="margin-top: 5px" type="checkbox"
                                            onclick="showPrivateKey({{$unit->ID_Unit}})">
                                        <p class="form-check-label">Show Key</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid" id="deliveryContainer" style="margin-top: 20px">
        <div style="text-align: center">
            <h4>Transactions History</h4>
        </div>
        <div class="container-fluid">
            @if(count($transactions)>0)
            <table>
                <thead>
                    <tr>
                        <th class="column">Bank</th>
                        <th class="column">Description</th>
                        <th class="column">Amount</th>
                        <th class="column">Status</th>
                        <th class="column">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                    <tr>
                        <td data-label="Bank" class="column">
                            @if ($transaction->ID_Bank == Null)
                            <p class="btn-sm btn-light">No bank (not paid yet)</p>
                            @else
                            @foreach ($banks as $bank)
                            @if ($transaction->ID_Bank == $bank->ID_Bank)
                            <p class="btn-sm btn-light">{{$bank->bank_name}} - {{$bank->accountNo}}</p>
                            @endif
                            @endforeach
                            @endif
                        </td>
                        <td data-label="Description" class="column">
                            <p class="btn-sm btn-light">{{$transaction->transactions_description}}</p>
                        </td>
                        <td data-label="Amount" class="column">
                            <p class="btn-sm btn-light">{{$transaction->transactions_totalPrice}}</p>
                        </td>
                        <td data-label="Status" class="column">
                            @if ($transaction->transactions_status == 0 )
                            <p class="btn-sm btn-warning">Unpaid</p>
                            @elseif ($transaction->transactions_status == 1 )
                            <p class="btn-sm btn-success">Paid</p>
                            @elseif ($transaction->transactions_status == 2 )
                            <p class="btn-sm btn-danger">Disapproved</p>
                            @elseif ($transaction->transactions_status == 3 )
                            <p class="btn-sm btn-success">Approved</p>
                            @endif
                        </td>
                        <td data-label="Action" class="column">
                            @if ($transaction->transactions_status == 0)
                            <a data-toggle="tooltip" title="Pay" style="text-decoration: none;cursor: pointer">
                                <i class="use-hover fas fa-receipt icons" aria-hidden="true"></i>
                            </a>
                            <a data-toggle="tooltip" title="Delete Transaction"
                                style="text-decoration: none;cursor: pointer">
                                <i class="delete-hover far fa-trash-alt icons"></i>
                            </a>
                            @elseif ($transaction->transactions_status == 1)
                            <a target="_blank" rel="noopener noreferrer"
                                href="{{ asset('storage/'.$transaction->proof) }}" data-toggle="tooltip"
                                title="View Proof" style="text-decoration: none;cursor: pointer">
                                <i class="use-hover fas fa-info-circle icons" aria-hidden="true"></i>
                            </a>
                            <a data-toggle="tooltip" title="Approve Transaction"
                                style="text-decoration: none;cursor: pointer">
                                <i class="delete-hover fas fa-check-circle icons"></i>
                            </a>
                            <a data-toggle="tooltip" title="Disapprove Transaction"
                                style="text-decoration: none;cursor: pointer">
                                <i class="delete-hover fas fa-ban icons"></i>
                            </a>
                            <a data-toggle="tooltip" title="Delete Transaction"
                                style="text-decoration: none;cursor: pointer">
                                <i class="delete-hover far fa-trash-alt icons"></i>
                            </a>
                            @elseif ($transaction->transactions_status == 2)
                            <a target="_blank" rel="noopener noreferrer"
                                href="{{ asset('storage/'.$transaction->proof) }}" data-toggle="tooltip"
                                title="View Proof" style="text-decoration: none;cursor: pointer">
                                <i class="use-hover fas fa-info-circle icons" aria-hidden="true"></i>
                            </a>
                            <a data-toggle="tooltip" title="Approve Transaction"
                                style="text-decoration: none;cursor: pointer">
                                <i class="delete-hover fas fa-check-circle icons"></i>
                            </a>
                            <a data-toggle="tooltip" title="Delete Transaction"
                                style="text-decoration: none;cursor: pointer">
                                <i class="delete-hover far fa-trash-alt icons"></i>
                            </a>
                            @elseif ($transaction->transactions_status == 3)
                            <a target="_blank" rel="noopener noreferrer"
                                href="{{ asset('storage/'.$transaction->proof) }}" data-toggle="tooltip"
                                title="View Proof" style="text-decoration: none;cursor: pointer">
                                <i class="use-hover fas fa-info-circle icons" aria-hidden="true"></i>
                            </a>
                            <a data-toggle="tooltip" title="Disapprove Transaction"
                                style="text-decoration: none;cursor: pointer">
                                <i class="delete-hover fas fa-ban icons"></i>
                            </a>
                            <a data-toggle="tooltip" title="Delete Transaction"
                                style="text-decoration: none;cursor: pointer">
                                <i class="delete-hover far fa-trash-alt icons"></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="headerS">
                <h3>
                    No Transactions Found<br>
                    <small>This order has no transactions</small>
                </h3>
            </div>

            @endif
        </div>
    </div>
    <div class="container-fluid" id="deliveryContainer" style="margin-top: 20px">
        <div style="text-align: center">
            <h4>Deliver History</h4>
        </div>
        <div>
            <a data-toggle="modal" data-target="#addDelivery" class="btn btn-sm btn-success float-right"
                style="border-radius: 10px; text-align: center; margin-top: 10px">Add
            </a>
            <div class="modal fade" id="addDelivery" tabindex="-1" aria-labelledby="addDelivery" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="justify-content: center">
                            <h5 class="modal-title" id="addDeliveryTitle">Add
                                Delivery Schedule
                            </h5>
                        </div>
                        <div class="modal-body">
                            <div class="alert-success" style="padding: 10px; border-radius: 20px">
                                <p>
                                    Here you can change the trip and time of the delivery as well as
                                    the Vehicle and the total price of the delivery.
                                </p>
                                <form method="POST" id="addDeliveryForm" class="row g-3"
                                    action="{{ route('branch.addSchedule')}}">
                                    @csrf
                                    <input hidden type="text" value="{{$order->ID_Order}}" name="ID_Order">
                                    <div class="col-md-12">
                                        <label for="phone" class="form-label">Vehicle</label>
                                        <div class="form-group">
                                            <select name="ID_DeliveryVehicle" style="width: 100%" class="select2">
                                                <option value="0">Select Vehicle</option>
                                                @php
                                                $vehicleNo = 1;
                                                @endphp
                                                @foreach ($vehicles as $vehicle)
                                                <option value="{{$vehicle->ID_DeliveryVehicle}}">
                                                    {{$vehicleNo++}}- (
                                                    {{$vehicle->plateNumber}} )
                                                    -
                                                    @ {{$vehicle->name}}
                                                    - {{$vehicle->model}}
                                                    - Price {{$vehicle->pricePerK}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="pickedUpFrom" class="form-label">Pick Up
                                            From</label>
                                        <input type="text" class="form-control" id="pickedUpFrom" name="pickedUpFrom">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="deliveredTo" class="form-label">Deliver
                                            To</label>
                                        <input type="text" class="form-control" id="deliveredTo" name="deliveredTo">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="pickedUp" class="form-label">Pick Up
                                            Date</label>
                                        <input type="datetime-local" class="form-control" id="pickedUp" name="pickedUp">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="delivered" class="form-label">Deliver
                                            Date</label>
                                        <input type="datetime-local" class="form-control" id="delivered"
                                            name="delivered">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="totalPrice" class="form-label">Total
                                            Price</label>
                                        <input type="text" class="form-control" id="totalPrice" name="totalPrice">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="model" class="form-label">Status</label>
                                        <div class="form-group">
                                            <div style="background: #fff; padding: 3px; border-radius: 5px;display: flex; justify-content: space-between;"
                                                class="form-check form-switch">
                                                <div>
                                                    <label style="color: #000;" for="status">Status:
                                                        <small style="display: none" id="statusDone">Done</small>
                                                    </label>
                                                </div>
                                                <input style="margin-left: 0; margin-right: 5px; position: inherit"
                                                    onchange="$('#statusDone').toggle('slow');" name="status"
                                                    class="form-check-input" type="checkbox"
                                                    id="flexSwitchCheckDefault">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                data-dismiss="modal">Close</button>
                            <button onclick="$('#addDeliveryForm').submit();" type="button"
                                class="btn btn-sm btn-outline-primary">Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            @if(count($schedules)>0)

            <table>
                <thead>
                    <tr>
                        <th class="column">Trip & Period</th>
                        <th class="column">Status</th>
                        <th class="column">Vehicle</th>
                        <th class="column">Total Price</th>
                        <th class="column">Description</th>
                        <th class="column">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedules as $schedule)
                    <tr>
                        <td data-label="Trip" class="column">
                            <div>
                                @php
                                $startsFrom = new DateTime($schedule->pickedUp);
                                $endsAt = new DateTime($schedule->delivered);
                                $today = new DateTime(date("Y-m-d H:i:s"));
                                $interval = $startsFrom->diff($endsAt);
                                $scheduleCheck = $endsAt->diff($today);
                                @endphp
                                @if ($scheduleCheck->invert)
                                <div class="btn-sm btn-primary">
                                    <h6 class="mb-0">@if ($schedule->schedule_status == 2) Done @else Active @endif:
                                        {{ $interval->days }} @if ($interval->days <= 1) Day @else Days @endif Left <i
                                            data-toggle="tooltip" title="Delivery Trip Details"
                                            onclick="$('#orderDateDetailsPositive{{$schedule->ID_DeliverySchedule}}').toggle('fast')"
                                            class="fas fa-arrow-down float-right"></i>
                                    </h6>
                                    <p class="mb-0" style="display: none; width: max-content"
                                        id="orderDateDetailsPositive{{$schedule->ID_DeliverySchedule}}">
                                        Pick-Up : {{$schedule->pickedUpFrom}}
                                        <br>Destination: {{$schedule->deliveredTo}}
                                        <br>
                                        @if($interval->days == 0)
                                        <i onmouseover="$('#fromIcon{{$schedule->ID_DeliverySchedule}}').toggle('fast');"
                                            class="fa fa-long-arrow-down fromIcon" aria-hidden="true">
                                            <small id="fromIcon{{$schedule->ID_DeliverySchedule}}"
                                                style="display: none">From:</small>
                                        </i>
                                        {{$order->startsFrom}}
                                        <br>
                                        <i onmouseover="$('#untilIcon{{$schedule->ID_DeliverySchedule}}').toggle('fast');"
                                            class="fa fa-long-arrow-up fromIcon" aria-hidden="true">
                                            <small id="untilIcon{{$schedule->ID_DeliverySchedule}}"
                                                style="display: none">Until:</small>
                                        </i>
                                        {{$order->endsAt}}
                                        <small>(The same day)</small>
                                        @elseif($interval->m == 0 && $interval->y == 0)
                                        <i onmouseover="$('#fromIcon{{$schedule->ID_DeliverySchedule}}').toggle('fast');"
                                            class="fa fa-long-arrow-down fromIcon" aria-hidden="true">
                                            <small id="fromIcon{{$schedule->ID_DeliverySchedule}}"
                                                style="display: none">From:</small>
                                        </i>
                                        {{$startsFrom->format('Y-m-d')}}
                                        <br>
                                        <i onmouseover="$('#untilIcon{{$schedule->ID_DeliverySchedule}}').toggle('fast');"
                                            class="fa fa-long-arrow-up fromIcon" aria-hidden="true">
                                            <small id="untilIcon{{$schedule->ID_DeliverySchedule}}"
                                                style="display: none">Until:</small>
                                        </i>
                                        {{$endsAt->format('Y-m-d')}}
                                        <small>({{$interval->d}}@if ($interval->days <= 1) Day @else Days @endif)
                                                </small>
                                                @elseif($interval->y == 0 && $interval->m > 0)
                                                <i onmouseover="$('#fromIcon{{$schedule->ID_DeliverySchedule}}').toggle('fast');"
                                                    class="fa fa-long-arrow-down fromIcon" aria-hidden="true">
                                                    <small id="fromIcon{{$schedule->ID_DeliverySchedule}}"
                                                        style="display: none">From:</small>
                                                </i>
                                                {{$startsFrom->format('Y-m-d')}}
                                                <br>
                                                <i onmouseover="$('#untilIcon{{$schedule->ID_DeliverySchedule}}').toggle('fast');"
                                                    class="fa fa-long-arrow-up fromIcon" aria-hidden="true">
                                                    <small id="untilIcon{{$schedule->ID_DeliverySchedule}}"
                                                        style="display: none">Until:</small>
                                                </i>
                                                {{$endsAt->format('Y-m-d')}}
                                                <small>({{$interval->m}} months, {{$interval->d}} days)</small>
                                                @elseif($interval->y > 0)
                                                <i onmouseover="$('#fromIcon{{$schedule->ID_DeliverySchedule}}').toggle('fast');"
                                                    class="fa fa-long-arrow-down fromIcon" aria-hidden="true">
                                                    <small id="fromIcon{{$schedule->ID_DeliverySchedule}}"
                                                        style="display: none">From:</small>
                                                </i>
                                                {{$startsFrom->format('Y-m-d')}}
                                                <br>
                                                <i onmouseover="$('#untilIcon{{$schedule->ID_DeliverySchedule}}').toggle('fast');"
                                                    class="fa fa-long-arrow-up fromIcon" aria-hidden="true">
                                                    <small id="untilIcon{{$schedule->ID_DeliverySchedule}}"
                                                        style="display: none">Until:</small>
                                                </i>
                                                {{$endsAt->format('Y-m-d')}}
                                                <small>({{$interval->y}} years, {{$interval->m}} months,
                                                    {{$interval->d}}
                                                    days)</small>
                                                @endif
                                    </p>
                                </div>
                                @else
                                <div class="btn-sm btn-danger">
                                    <h6 class="mb-0">@if ($schedule->schedule_status == 2) Done @else Expaired
                                        @endif:
                                        {{ $interval->days }} @if ($interval->days <= 1) Day @else Days @endif Exceeded
                                            <i data-toggle="tooltip" title="Delivery Trip Details"
                                            onclick="$('#orderDateDetailsPositive{{$schedule->ID_DeliverySchedule}}').toggle('fast')"
                                            class="fas fa-arrow-down float-right"></i>
                                    </h6>
                                    <p class="mb-0" style="display: none; width: max-content"
                                        id="orderDateDetailsPositive{{$schedule->ID_DeliverySchedule}}">
                                        Pick-Up : {{$schedule->pickedUpFrom}}
                                        <br>Destination: {{$schedule->deliveredTo}}
                                        <br>
                                        @if($interval->days == 0)
                                        <i onmouseover="$('#fromIcon{{$schedule->ID_DeliverySchedule}}').toggle('fast');"
                                            class="fa fa-long-arrow-down fromIcon" aria-hidden="true">
                                            <small id="fromIcon{{$schedule->ID_DeliverySchedule}}"
                                                style="display: none">From:</small>
                                        </i>
                                        {{$order->startsFrom}}
                                        <br>
                                        <i onmouseover="$('#untilIcon{{$schedule->ID_DeliverySchedule}}').toggle('fast');"
                                            class="fa fa-long-arrow-up fromIcon" aria-hidden="true">
                                            <small id="untilIcon{{$schedule->ID_DeliverySchedule}}"
                                                style="display: none">Until:</small>
                                        </i>
                                        {{$order->endsAt}}
                                        <small>(The same day)</small>
                                        @elseif($interval->m == 0 && $interval->y == 0)
                                        <i onmouseover="$('#fromIcon{{$schedule->ID_DeliverySchedule}}').toggle('fast');"
                                            class="fa fa-long-arrow-down fromIcon" aria-hidden="true">
                                            <small id="fromIcon{{$schedule->ID_DeliverySchedule}}"
                                                style="display: none">From:</small>
                                        </i>
                                        {{$startsFrom->format('Y-m-d')}}
                                        <br>
                                        <i onmouseover="$('#untilIcon{{$schedule->ID_DeliverySchedule}}').toggle('fast');"
                                            class="fa fa-long-arrow-up fromIcon" aria-hidden="true">
                                            <small id="untilIcon{{$schedule->ID_DeliverySchedule}}"
                                                style="display: none">Until:</small>
                                        </i>
                                        {{$endsAt->format('Y-m-d')}}
                                        <small>({{$interval->d}}@if ($interval->days <= 1) Day @else Days @endif)
                                                </small>
                                                @elseif($interval->y == 0 && $interval->m > 0)
                                                <i onmouseover="$('#fromIcon{{$schedule->ID_DeliverySchedule}}').toggle('fast');"
                                                    class="fa fa-long-arrow-down fromIcon" aria-hidden="true">
                                                    <small id="fromIcon{{$schedule->ID_DeliverySchedule}}"
                                                        style="display: none">From:</small>
                                                </i>
                                                {{$startsFrom->format('Y-m-d')}}
                                                <br>
                                                <i onmouseover="$('#untilIcon{{$schedule->ID_DeliverySchedule}}').toggle('fast');"
                                                    class="fa fa-long-arrow-up fromIcon" aria-hidden="true">
                                                    <small id="untilIcon{{$schedule->ID_DeliverySchedule}}"
                                                        style="display: none">Until:</small>
                                                </i>
                                                {{$endsAt->format('Y-m-d')}}
                                                <small>({{$interval->m}} months, {{$interval->d}} days)</small>
                                                @elseif($interval->y > 0)
                                                <i onmouseover="$('#fromIcon{{$schedule->ID_DeliverySchedule}}').toggle('fast');"
                                                    class="fa fa-long-arrow-down fromIcon" aria-hidden="true">
                                                    <small id="fromIcon{{$schedule->ID_DeliverySchedule}}"
                                                        style="display: none">From:</small>
                                                </i>
                                                {{$startsFrom->format('Y-m-d')}}
                                                <br>
                                                <i onmouseover="$('#untilIcon{{$schedule->ID_DeliverySchedule}}').toggle('fast');"
                                                    class="fa fa-long-arrow-up fromIcon" aria-hidden="true">
                                                    <small id="untilIcon{{$schedule->ID_DeliverySchedule}}"
                                                        style="display: none">Until:</small>
                                                </i>
                                                {{$endsAt->format('Y-m-d')}}
                                                <small>({{$interval->y}} years, {{$interval->m}} months,
                                                    {{$interval->d}}
                                                    days)</small>
                                                @endif
                                    </p>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td data-label="Status" class="column">
                            @if ($schedule->schedule_status == 0)
                            <p class="btn-sm btn-info">Waiting</p>
                            @elseif ($schedule->schedule_status == 1)
                            <p class="btn-sm btn-warning">On-Going</p>
                            @elseif ($schedule->schedule_status == 2)
                            <p class="btn-sm btn-success">Done</p>
                            @endif
                        </td>
                        <td data-label="Vehicle" class="column">
                            <a href="{{route('branch.delivery', ['driver' => $schedule->ID_DeliveryVehicle])}}">
                                <p class="btn-sm btn-light">{{$schedule->vehicle_name}}</p>
                            </a>

                        </td>
                        <td data-label="Total Price" class="column">
                            <p class="btn-sm btn-light">{{$schedule->schedule_totalPrice}}</p>
                        </td>
                        <td data-label="Description" class="column">
                            <p class="btn-sm btn-light">{{$schedule->schedule_description}}</p>
                        </td>
                        <td data-label="Action" class="column">
                            <div style="display: flex; justify-content:space-around">
                                <a style="text-decoration: none ;cursor: pointer" data-toggle="modal"
                                    data-target="#editDelivery{{$schedule->ID_DeliverySchedule}}">
                                    <i class="use-hover fa fa-pencil-square-o icons" aria-hidden="true"></i></a>

                                <div class="modal fade" id="editDelivery{{$schedule->ID_DeliverySchedule}}"
                                    tabindex="-1" aria-labelledby="editDelivery{{$schedule->ID_DeliverySchedule}}"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header" style="justify-content: center">
                                                <h5 class="modal-title"
                                                    id="editDelivery{{$schedule->ID_DeliverySchedule}}Title">Edit
                                                    Delivery Info
                                                </h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert-success" style="padding: 10px; border-radius: 20px">
                                                    <p>
                                                        Here you can change the trip and time of the delivery as well as
                                                        the Vehicle and the total price of the delivery.
                                                    </p>
                                                    <form method="POST"
                                                        id="editDelivery{{$schedule->ID_DeliverySchedule}}Form"
                                                        class="row g-3"
                                                        action="{{ route('branch.editSchedule', ['schedule'=>$schedule])}}">
                                                        @csrf
                                                        <div class="col-md-12">
                                                            <label for="phone" class="form-label">Vehicle</label>
                                                            <div class="form-group">
                                                                <select name="ID_DeliveryVehicle" style="width: 100%"
                                                                    class="select2">
                                                                    <option value="0">Select Vehicle</option>
                                                                    @php
                                                                    $vehicleNo = 1;
                                                                    @endphp
                                                                    @foreach ($vehicles as $vehicle)
                                                                    @if ($schedule->ID_DeliveryVehicle ==
                                                                    $vehicle->ID_DeliveryVehicle)
                                                                    <option selected
                                                                        value="{{$vehicle->ID_DeliveryVehicle}}">
                                                                        {{$vehicleNo++}}- (
                                                                        {{$vehicle->plateNumber}} )
                                                                        -
                                                                        @ {{$vehicle->name}}
                                                                        - {{$vehicle->model}}
                                                                        - Price {{$vehicle->pricePerK}}
                                                                    </option>
                                                                    @else
                                                                    <option value="{{$vehicle->ID_DeliveryVehicle}}">
                                                                        {{$vehicleNo++}}- (
                                                                        {{$vehicle->plateNumber}} )
                                                                        -
                                                                        @ {{$vehicle->name}}
                                                                        - {{$vehicle->model}}
                                                                        - Price {{$vehicle->pricePerK}}
                                                                    </option>
                                                                    @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="pickedUpFrom" class="form-label">Pick Up
                                                                From</label>
                                                            <input type="text" class="form-control" id="pickedUpFrom"
                                                                name="pickedUpFrom" value="{{$schedule->pickedUpFrom}}"
                                                                placeholder="{{$schedule->pickedUpFrom}}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="deliveredTo" class="form-label">Deliver
                                                                To</label>
                                                            <input type="text" class="form-control" id="deliveredTo"
                                                                name="deliveredTo" value="{{$schedule->deliveredTo}}"
                                                                placeholder="{{$schedule->deliveredTo}}">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="pickedUp" class="form-label">Pick Up
                                                                Date</label>
                                                            <input type="datetime-local" class="form-control"
                                                                id="pickedUp" name="pickedUp">
                                                            <small>Old:{{$schedule->pickedUp}}</small>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="delivered" class="form-label">Deliver
                                                                Date</label>
                                                            <input type="datetime-local" class="form-control"
                                                                id="delivered" name="delivered">
                                                            <small>Old:{{$schedule->delivered}}</small>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="totalPrice" class="form-label">Total
                                                                Price</label>
                                                            <input type="text" class="form-control" id="totalPrice"
                                                                name="totalPrice" value="{{$schedule->totalPrice}}"
                                                                placeholder="{{$schedule->totalPrice}}">
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button
                                                    onclick="$('#editDelivery{{$schedule->ID_DeliverySchedule}}Form').submit();"
                                                    type="button" class="btn btn-sm btn-outline-primary">Change</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a onclick="$('#deleteSchedule{{$schedule->ID_DeliverySchedule}}').submit();"
                                    data-toggle="tooltip" title="Delete Record"
                                    style="text-decoration: none;cursor: pointer">
                                    <i class="delete-hover far fa-trash-alt icons"></i>
                                </a>
                                <form hidden action="{{ route('branch.deleteSchedule', $schedule) }}"
                                    id="deleteSchedule{{$schedule->ID_DeliverySchedule}}" enctype="multipart/form-data"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="headerS">
                <h3>
                    No Schedules Found<br>
                    <small>Try again or add new</small>
                </h3>
            </div>

            @endif
        </div>

    </div>
</div>


<script>
    function showPrivateKey(id) {
      var x = document.getElementById("privateKey"+id);
      if (x.type === "password") {
        x.type = "text";
      } else {
        x.type = "password";
      }
    }
</script>
<script>
    $(".select2").select2({
        theme: "bootstrap-5",
        selectionCssClass: "select2--small", // For Select2 v4.1
        dropdownCssClass: "select2--small",
    });
</script>
@endsection