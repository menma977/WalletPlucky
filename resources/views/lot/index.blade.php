@extends('layouts.app')

@section('title')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>LOT List</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">LOT List</li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <form action="{{ route('lot.store') }}" method="post">
    @csrf
    <div class="card card-outline card-primary collapsed-card">
      <div class="card-header">
        <h3 class="card-title">Add LOT</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <label for="price" class="input-group-text">Price</label>
          </div>
          <input type="text" class="form-control @error('price') is-invalid @enderror" placeholder="Total Price" id="price" name="price" value="{{ old('price') }}">
          <div class="input-group-append">
            <label class="input-group-text">DOGE</label>
          </div>
        </div>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <label for="plucky" class="input-group-text">Plucky</label>
          </div>
          <input type="text" class="form-control @error('plucky') is-invalid @enderror" placeholder="Total Plucky" id="plucky" name="plucky" value="{{ old('plucky') }}">
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-block btn-outline-primary">Send</button>
      </div>
    </div>
  </form>

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">List LOT</h3>
    </div>
    <div class="card-body p-0">
      <table class="table table-sm" id="table" style="width: 100%">
        <thead>
        <tr>
          <th style="width: 20px">#</th>
          <th>price</th>
          <th>plucky</th>
          <th>Edit</th>
          <th>Delete</th>
        </tr>
        </thead>
        <tbody>
        @foreach($grade as $item)
          <tr>
            <td>
              {{ $loop->index + 1 }}.
            </td>
            <td>
              {{ number_format($item->price/ 100000000, 8, '.', '') }} DOGE
            </td>
            <td>
              {{ number_format($item->plucky/ 100000000, 4, '.', '') }} Plucky
            </td>
            <td>
              <button type="button" class="btn btn-block btn-info btn-xs" data-toggle="modal" data-target="#modal-{{ $item->id }}">Edit</button>
            </td>
            <td>
              <a href="#">
                <button type="button" class="btn btn-block btn-danger btn-xs">Delete</button>
              </a>
            </td>
          </tr>
          <div class="modal fade" id="modal-{{ $item->id }}">
            <div class="modal-dialog">
              <div class="modal-content bg-primary">
                <div class="modal-header">
                  <h4 class="modal-title">Edit LOT</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true">×</i>
                  </button>
                </div>
                <form action="{{ route('lot.update', $item->id) }}" method="post">
                  @csrf
                  <div class="modal-body">
                    <div class="form-group">
                      <label for="price">Price</label>
                      <input type="text" class="form-control @error('price') is-invalid @enderror" placeholder="Total Plucky" id="price" name="price"
                             value="{{ old('price') ?: number_format($item->price/ 100000000, 8, '.', '') }}">
                    </div>
                    <div class="form-group">
                      <label for="plucky">Plucky</label>
                      <input type="text" class="form-control @error('plucky') is-invalid @enderror" placeholder="Total Plucky" id="plucky" name="plucky"
                             value="{{ old('plucky') ?: number_format($item->plucky/ 100000000, 4, '.', '') }}">
                    </div>
                  </div>
                  <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-outline-light">Save changes</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection

@section('addCss')
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
@endsection

@section('addJs')
  <!-- DataTables -->
  <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

  <!-- Select2 -->
  <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

  <!-- Toastr -->
  <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>

  <script>
    $(function () {
      $('.select2').select2();

      $('#table').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true,
        "responsive": true,
      });

      @error('price')
      toastr.error('{{ $message }}');
      @enderror

      @error('plucky')
      toastr.error('{{ $message }}');
      @enderror
    });
  </script>
@endsection