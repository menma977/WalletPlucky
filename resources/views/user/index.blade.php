@extends('layouts.app')

@section('title')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>User List</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">User List</li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="card card-outline card-teal">
    <div class="card-body">
      <form action="{{ route('user.find') }}" method="post">
        @csrf
        <div class="input-group mb-3">
          <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" placeholder="Username/Wallet" value="{{ old('username') }}">
          <div class="input-group-append">
            <button type="submit" class="btn btn-success">Find</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">List Transaction</h3>
    </div>
    <div class="card-body p-0 table-responsive">
      <div class="form-group col-12">
        <label for="search-user">Search: </label>
        <input type="text" id="search-user" class="form-control" onkeyup="refreshTable()"/>
      </div>
      <table class="table table-sm" id="data" style="width: 100%">
        <thead class="text-center">
        <tr>
          <th>Detail</th>
          <th>Username</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Wallet</th>
          <th>LOT</th>
          <th style="width: 150px">Status</th>
          <th>Date</th>
          <th>Delete Treding</th>
        </tr>
        </thead>
        <template id="template-user-row">
          <tr>
            <td>
              <a class="detail" href="{{ route('user.show','##id##') }}">
                <button type="button" class="btn btn-block btn-default btn-xs">Detail</button>
              </a>
            </td>
            <td class="username"></td>
            <td class="email"></td>
            <td class="phone"></td>
            <td class="wallet"></td>
            <td class="lot"></td>
            <td>
              <a class="status" href="{{ route('user.activate','##id##') }}">
                <button type="button" class="btn btn-block btn-info btn-xs status_view"></button>
              </a>
            </td>
            <td class="date"></td>
            <td>
              <a class="delete_treding" href="{{ route('user.logoutSession','##id##') }}">
                <button type="button" class="btn btn-block btn-danger btn-xs">Delete Treding</button>
              </a>
            </td>
          </tr>
        </template>
      </table>
    </div>
  </div>
@endsection

@section('addCss')
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
@endsection

@section('addJs')
  <!-- DataTables -->
  <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

  <!-- Toastr -->
  <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>

  <script>
    const table = document.querySelector("#data")
    const row = document.querySelector('#template-user-row').content.querySelector("tr");

    $(function () {
      @error("error")
      toastr.error("{{ $message }}");
      @enderror
      @error("username")
      toastr.error("{{ $message }}");
      @enderror

      refreshTable(null);

      $('#data').DataTable({
        "paging": false,
        "lengthChange": true,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": true,
        "responsive": true,
      });

    });

    async function refreshTable(e) {
      if (e) {
        e.preventDefault();
      }
      const filter = document.getElementById('search-user').value;
      if (filter.length < 1 || filter.length > 3) {
        const response = await fetch("{{ route('user.filter', '##filter##') }}".replace("##filter##", filter), {
          method: 'GET',
          headers: {
            Accept: "application/json",
            "X-CSRF-TOKEN": $("input[name='_token']").val()
          }
        });

        if (response && response.ok) {
          const users = await response.json();
          const old_tbody = table.querySelector("tbody");
          const new_tbody = document.createElement('tbody');

          for (const user of users) {
            // console.log(user)
            const newRow = row.cloneNode(true);
            newRow.querySelector(".detail").href = newRow.querySelector(".detail").href.replace("##id##", user.id)
            newRow.querySelector(".username").innerText = user.username;
            newRow.querySelector(".email").innerText = user.email;
            newRow.querySelector(".phone").innerText = user.phone;
            newRow.querySelector(".wallet").innerText = user.wallet;
            newRow.querySelector(".lot").innerText = user.lot;
            if (user.status == 0) {
              newRow.querySelector(".status_view").innerText = 'Wait Confirmation. Activate Now';
              newRow.querySelector(".status").href = newRow.querySelector(".status").href.replace("##id##", user.id)
            } else if (user.status == 2) {
              newRow.querySelector(".status_view").innerText = 'Active';
              newRow.querySelector(".status").href = newRow.querySelector(".status").href.replace("##id##", user.id)
            } else {
              newRow.querySelector(".status_view").innerText = 'Process Registration';
              newRow.querySelector(".status").href = newRow.querySelector(".status").href.replace("##id##", user.id)
            }
            newRow.querySelector(".date").innerText = user.date;
            newRow.querySelector(".delete_treding").href = newRow.querySelector(".delete_treding").href.replace("##id##", user.id)
            new_tbody.appendChild(newRow);
          }
          if (old_tbody) {
            old_tbody.parentNode.replaceChild(new_tbody, old_tbody)
          } else {
            table.appendChild(new_tbody)
          }
        }
      }
    }
  </script>
@endsection