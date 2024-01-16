@extends('layouts.internal.master')


@section('title')
   Dashboard
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Orders /</span> Table
</h4>
<!-- DataTable with Buttons -->
<div class="card">
  <div class="card-datatable table-responsive">
    <table class="table" id="orders-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Addres</th>
          <th>Contact</th>
          <th>Total Price</th>
          <th>MOP</th>
          <th>Status</th>
          <th>Status Act</th>
          <th>Action</th>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Addres</th>
          <th>Contact</th>
          <th>Total Price</th>
          <th>MOP</th>
          <th>Status</th>
          <th>Status Act</th>
          <th>Action</th>
        </tr>
      </tfoot>
    </table>
  </div>
</div>


  <script src="../../../../../../../../../../../../../../../../../../../../../../assets/vendor/libs/jquery/jquery.js"></script>


<script>
$(document).ready(function () {
  var admin = {!! json_encode(url('/')) !!}
    $('#orders-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('orders.index') !!}',
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'address', name: 'address'},
            {data: 'contact', name: 'contact'},
            {data: 'total_price', name: 'total_price'},
            {data: 'MOP', name: 'MOP'},

            {
                data: 'status',
                name: 'status',
                render: function (data, type, full, meta) {
                  // Assuming 'status' contains the status text
                  if (data === 'Placed') {
                    return '<span class="badge bg-label-primary">' + data + '</span>';
                  } else if (data === 'Preparing'){
                    return '<span class="badge bg-label-warning">' + data + '</span>';
                  } else if (data === 'Shipped') {
                    return '<span class="badge bg-label-info">' + data + '</span>';
                  } else {
                    return '<span class="badge bg-label-success">' + data + '</span>';
                  }
                }
            },
            {
                data: 'statusAct',
                name: 'statusAct',
                orderable: false,
                searchable: false,
                render: function (data, type, full, meta) {
                    // Assuming $id is available in your JavaScript code
                    var backwardLink = '{{ route("admin.orders.backward", ":id") }}';
                    var forwardLink = '{{ route("admin.orders.forward", ":id") }}';

                    backwardLink = backwardLink.replace(':id', full.id);
                    forwardLink = forwardLink.replace(':id', full.id);
                    console.log(full.status)
                    if(full.status === 'Placed'){
                      return '<a href="' + forwardLink + '" class="btn rounded-pill btn-icon btn-label-dark">' +
                        '<span class="tf-icons bx bx-chevron-right"></span>' +
                        '</a>';
                    } else if(full.status === 'Preparing'){
                      return '<a href="' + backwardLink + '" class="btn rounded-pill btn-icon btn-label-dark">' +
                        '<span class="tf-icons bx bx-chevron-left"></span>' +
                        '</a>' +
                        '<a href="' + forwardLink + '" class="btn rounded-pill btn-icon btn-label-dark">' +
                        '<span class="tf-icons bx bx-chevron-right"></span>' +
                        '</a>';
                    } else if (full.status === 'Shipped'){
                      return '<a href="' + backwardLink + '" class="btn rounded-pill btn-icon btn-label-dark">' +
                        '<span class="tf-icons bx bx-chevron-left"></span>' +
                        '</a>' +
                        '<a href="' + forwardLink + '" class="btn rounded-pill btn-icon btn-label-dark">' +
                        '<span class="tf-icons bx bx-chevron-right"></span>' +
                        '</a>';
                    } else {
                      return '<a href="' + backwardLink + '" class="btn rounded-pill btn-icon btn-label-dark">' +
                        '<span class="tf-icons bx bx-chevron-left"></span>' +
                        '</a>'
                    }


                }
            },

            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
    });
});

</script>
  
@endsection