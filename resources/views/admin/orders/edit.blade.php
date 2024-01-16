@extends('layouts.internal.master')

@section('title')
   Edit Product
@endsection


@section('content')

<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Orders /</span> Details
</h4>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
  <div class="d-flex flex-column justify-content-center">
    <h5 class="mb-1 mt-3">Order #{{$order->id}} 
        @if($order->status == 'Preparing')
          <span class="badge bg-label-warning">{{$order->status}}</span>
        @elseif($order->status == 'Shipped')
          <span class="badge bg-label-info">{{$order->status}}</span>
        @elseif($order->status == 'Received')
          <span class="badge bg-label-success">{{$order->status}}</span>
        @else
          <span class="badge bg-label-primary">{{$order->status}}</span>
        @endif
    </h5>
    <p class="text-body">{{ \Carbon\Carbon::parse($order->created_at)->format('F j, Y h:iA') }}<span id="orderYear"></span></p>
  </div>
  <div class="d-flex align-content-center flex-wrap gap-2">
    <button class="btn btn-label-danger delete-order">Delete Order</button>
  </div>
</div>

<form method="POST" action="{{ route('orders.update', $order->id) }}" enctype="multipart/form-data">
  @method('PATCH')
  @csrf
  <div class="row">
    <div class="col-12 col-lg-8">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title m-0">Ordered Products</h5>
          <!-- <h6 class="m-0"><a href=" javascript:void(0)">Edit</a></h6> -->
        </div>
        <div class="card-datatable table-responsive">

          @if(session('status'))
              <div class="alert alert-warning alert-dismissible" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
              </div>
            @endif

          <table class="datatables-order-details table">
            <thead>
              <tr>
                <th></th>
                <th></th>
                <th class="w-50">Product</th>
                <th class="w-25">Price</th>
                <th class="w-25">Qty</th>
                <th>total</th>
              </tr>
            </thead>
            <tbody>
              @foreach($order->products as $prod)
              <tr>      
                <th></th>
                <th></th>
                <th>{{$prod->name}}</th>
                <th>{{$prod->price}}</th>
                <th>{{$prod->pivot->quantity}}</th>
                <th>{{$prod->price * $prod->pivot->quantity}}</th>
              </tr>
              @endforeach
            </tbody>
          </table>

          <div class="d-flex justify-content-end align-items-center m-3 mb-2 p-1">
            <div class="order-calculations">
              <!-- <div class="d-flex justify-content-between mb-2">
                <span class="w-px-100">Subtotal:</span>
                <span class="text-heading">₱ $999</span>
              </div> -->
              <!-- <div class="d-flex justify-content-between mb-2">
                <span class="w-px-100">Discount:</span>
                <span class="text-heading mb-0">$22</span>
              </div> -->
              <!-- <div class="d-flex justify-content-between mb-2">
                <span class="w-px-100">Tax:</span>
                <span class="text-heading">$30</span>
              </div> -->

              <div class="d-flex justify-content-between mb-2">
                <span class="w-px-100">Subtotal:</span>
                <span class="text-heading">₱ {{$order->total_price - 50}}</span>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span class="w-px-100">Delivery Fee:</span>
                <span class="text-heading mb-0">₱ 50</span>
              </div>

              <div class="d-flex justify-content-between">
                <h6 class="w-px-100 mb-0">Total:</h6>
                <h6 class="mb-0">₱ {{$order->total_price}}</h6>
              </div>
            </div>
          </div>

          <hr>

        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title m-0">Status</h5>
            <div class="progress-indicator-container">
              @if($order->status == 'Preparing')
                <button type="button" class="btn btn-outline-warning active" onclick="setStatus(0)">Placed</button>
                  <span class="tf-icons bx bx-chevron-right"></span>
                <button type="button" class="btn btn-outline-warning active" onclick="setStatus(1)">Preparing</button>
                  <span class="tf-icons bx bx-chevron-right"></span>
                <button type="button" class="btn btn-outline-warning" onclick="setStatus(2)">Shipped</button>
                  <span class="tf-icons bx bx-chevron-right"></span>
                <button type="button" class="btn btn-outline-warning" onclick="setStatus(3)">Received</button>
                <?php $status = 2?>
              @elseif($order->status == 'Shipped')
                <button type="button" class="btn btn-outline-warning active" onclick="setStatus(0)">Placed</button>
                  <span class="tf-icons bx bx-chevron-right"></span>
                <button type="button" class="btn btn-outline-warning active" onclick="setStatus(1)">Preparing</button>
                  <span class="tf-icons bx bx-chevron-right"></span>
                <button type="button" class="btn btn-outline-warning active" onclick="setStatus(2)">Shipped</button>
                  <span class="tf-icons bx bx-chevron-right"></span>
                <button type="button" class="btn btn-outline-warning" onclick="setStatus(3)">Received</button>
                <?php $status = 3?>
              @elseif($order->status == 'Received')
                <button type="button" class="btn btn-outline-warning active" onclick="setStatus(0)">Placed</button>
                  <span class="tf-icons bx bx-chevron-right"></span>
                <button type="button" class="btn btn-outline-warning active" onclick="setStatus(1)">Preparing</button>
                  <span class="tf-icons bx bx-chevron-right"></span>
                <button type="button" class="btn btn-outline-warning active" onclick="setStatus(2)">Shipped</button>
                  <span class="tf-icons bx bx-chevron-right"></span>
                <button type="button" class="btn btn-outline-warning active" onclick="setStatus(3)">Received</button>
                <?php $status = 4?>
              @else
                <button type="button" class="btn btn-outline-warning active" onclick="setStatus(0)">Placed</button>
                  <span class="tf-icons bx bx-chevron-right"></span>
                <button type="button" class="btn btn-outline-warning" onclick="setStatus(1)">Preparing</button>
                  <span class="tf-icons bx bx-chevron-right"></span>
                <button type="button" class="btn btn-outline-warning" onclick="setStatus(2)">Shipped</button>
                  <span class="tf-icons bx bx-chevron-right"></span>
                <button type="button" class="btn btn-outline-warning" onclick="setStatus(3)">Received</button>
                <?php $status = 1?>
              @endif

              <div class="demo-vertical-spacing">
                <div class="progress">
                @if($order->status == 'Preparing')
                  <div class="progress-bar bg-warning" id="progressBar" role="progressbar" style="width: 33.33%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                @elseif($order->status == 'Shipped')
                  <div class="progress-bar bg-warning" id="progressBar" role="progressbar" style="width: 66.66%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                @elseif($order->status == 'Received')
                  <div class="progress-bar bg-warning" id="progressBar" role="progressbar" style="width: 99.99%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                @else
                  <div class="progress-bar bg-warning" id="progressBar" role="progressbar" style="width: 0%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                @endif
                </div>
              </div>

            </div>
            
        </div>

        
        <input type="hidden" name="status" id="statusInput" value="{{$status}}">


        <div class="d-flex justify-content-end align-items-center m-3 mb-2 p-1">
            <button type="submit" class="btn btn-primary me-sm-3 me-1">
              Update
            </button>
          <a href="{{ route('orders.index') }}" type="button" class="btn btn-label-secondary">Back</a>
            
          </div>

        </div>  


        
      </div>
      
      <!-- <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title m-0">Shipping activity</h5>
        </div>
        <div class="card-body">
          <ul class="timeline pb-0 mb-0">
            <li class="timeline-item timeline-item-transparent border-primary">
              <span class="timeline-point-wrapper"><span class="timeline-point timeline-point-primary"></span></span>
              <div class="timeline-event">
                <div class="timeline-header">
                  <h6 class="mb-0">Order was placed (Order ID: #32543)</h6>
                  <span class="text-muted">Tuesday 11:29 AM</span>
                </div>
                <p class="mt-2">Your order has been placed successfully</p>
              </div>
            </li>
            <li class="timeline-item timeline-item-transparent border-primary">
              <span class="timeline-point-wrapper"><span class="timeline-point timeline-point-primary"></span></span>
              <div class="timeline-event">
                <div class="timeline-header">
                  <h6 class="mb-0">Pick-up</h6>
                  <span class="text-muted">Wednesday 11:29 AM</span>
                </div>
                <p class="mt-2">Pick-up scheduled with courier</p>
              </div>
            </li>
            <li class="timeline-item timeline-item-transparent border-primary">
              <span class="timeline-point-wrapper"><span class="timeline-point timeline-point-primary"></span></span>
              <div class="timeline-event">
                <div class="timeline-header">
                  <h6 class="mb-0">Dispatched</h6>
                  <span class="text-muted">Thursday 11:29 AM</span>
                </div>
                <p class="mt-2">Item has been picked up by courier</p>
              </div>
            </li>
            <li class="timeline-item timeline-item-transparent border-primary">
              <span class="timeline-point-wrapper"><span class="timeline-point timeline-point-primary"></span></span>
              <div class="timeline-event">
                <div class="timeline-header">
                  <h6 class="mb-0">Package arrived</h6>
                  <span class="text-muted">Saturday 15:20 AM</span>
                </div>
                <p class="mt-2">Package arrived at an Amazon facility, NY</p>
              </div>
            </li>
            <li class="timeline-item timeline-item-transparent border-left-dashed">
              <span class="timeline-point-wrapper"><span class="timeline-point timeline-point-primary"></span></span>
              <div class="timeline-event">
                <div class="timeline-header">
                  <h6 class="mb-0">Dispatched for delivery</h6>
                  <span class="text-muted">Today 14:12 PM</span>
                </div>
                <p class="mt-2">Package has left an Amazon facility, NY</p>
              </div>
            </li>
            <li class="timeline-item timeline-item-transparent border-transparent pb-0">
              <span class="timeline-point-wrapper"><span class="timeline-point timeline-point-secondary"></span></span>
              <div class="timeline-event pb-0">
                <div class="timeline-header">
                  <h6 class="mb-0">Delivery</h6>
                </div>
                <p class="mt-2 mb-0">Package will be delivered by tomorrow</p>
              </div>
            </li>
          </ul>
        </div>
      </div> -->

    </div>

    <div class="col-12 col-lg-4">
      <div class="card mb-4">
        <div class="card-header">
          <h6 class="card-title m-0">Customer details </h6>
        </div>
        <div class="card-body">
          <div class="d-flex justify-content-start align-items-center mb-4">
            <div class="avatar me-2">

              <img src="{{asset($order->user->customer->custimage)}}" alt="Avatar" class="rounded-circle">
            </div>
            <div class="d-flex flex-column">
              <a href="app-user-view-account.html" class="text-body text-nowrap">
                <h6 class="mb-0">{{$order->user->name}}</h6>
              </a>
              <small class="text-muted">Customer ID: {{$order->user->customer->id}}</small></div>
          </div>
          <div class="d-flex justify-content-start align-items-center mb-4">
            <span class="avatar rounded-circle bg-label-success me-2 d-flex align-items-center justify-content-center"><i class="bx bx-cart-alt bx-sm lh-sm"></i></span>
            <h6 class="text-body text-nowrap mb-0">{{$count}} Orders</h6>
          </div>
          <div class="d-flex justify-content-between">
            <h6>Contact info</h6>
          </div>
          <p class=" mb-1">Email: {{$order->user->email}}</p>
          <p class=" mb-0">Mobile: {{$order->user->customer->contact}}</p>
        </div>
      </div>

      <div class="card mb-4">

        <div class="card-header d-flex justify-content-between">
          <h6 class="card-title m-0">Shipping address</h6>
        </div>
        <div class="card-body">
          <p class="mb-0">Address: {{$order->address}}</p>
          <p class="mb-0">Contact: {{$order->contact}}</p>
        </div>

      </div>
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
          <h6 class="card-title m-0">Billing information</h6>
        </div>
        <div class="card-body">
          @if($order->MOP = 'cod')
            <p class="mb-4">Cash on Delivery</p>
          @else
            <p class="mb-4">Gcash Payement</p>
          @endif
        </div>

      </div>
    </div>

  </div>
</form>

@endsection

@section('script')

<script>
function setStatus(index) {
        // Remove the 'active' class from all indicators
        $('.btn-outline-warning').removeClass('active');

        // Add the 'active' class to the clicked indicator and all previous indicators
        for (var i = 0; i <= index; i++) {
            $('.btn-outline-warning').eq(i).addClass('active');
        }
        var progressValue = index * 33.33;
        $('#progressBar').css('width', progressValue + '%');
        
        $('#statusInput').val(index + 1);
    }


</script>

@endsection