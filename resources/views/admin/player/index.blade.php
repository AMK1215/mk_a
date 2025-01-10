@extends('admin_layouts.app')
@section('styles')
<style>
  .transparent-btn {
    background: none;
    border: none;
    padding: 0;
    outline: none;
    cursor: pointer;
    box-shadow: none;
    appearance: none;
    /* For some browsers */
  }


  .custom-form-group {
    margin-bottom: 20px;
  }

  .custom-form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #555;
  }

  .custom-form-group input,
  .custom-form-group select {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid #e1e1e1;
    border-radius: 5px;
    font-size: 16px;
    color: #333;
  }

  .custom-form-group input:focus,
  .custom-form-group select:focus {
    border-color: #d33a9e;
    box-shadow: 0 0 5px rgba(211, 58, 158, 0.5);
  }

  .submit-btn {
    background-color: #d33a9e;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 18px;
    font-weight: bold;
  }

  .submit-btn:hover {
    background-color: #b8328b;
  }
</style>
@endsection
@section('content')
<div class="row mt-4">
  <div class="col-12">
    <div class="card">
      <!-- Card header -->
      <div class="card-header pb-0">
        <a href="{{ route('admin.player.create') }}" class="btn bg-gradient-primary btn-sm mb-0" style="float: right;">Create Player</a>

        <div class="card-body">
          <h5 class="mb-0">Player Dashboards</h5>

        </div>
        <form action="" method="GET">
          <div class="row mt-3">
            <div class="col-md-3">
              <div class="input-group input-group-static mb-4">
                <label for="">PlayerId</label>
                <input type="text" class="form-control" name="player_id" value="{{request()->player_id}}">
              </div>
            </div>
            <div class="col-md-3">
              <div class="input-group input-group-static mb-4">
                <label for="">Start Date</label>
                <input type="date" class="form-control" name="start_date" value="{{request()->get('start_date')}}">
              </div>
            </div>
            <div class="col-md-3">
              <div class="input-group input-group-static mb-4">
                <label for="">EndDate</label>
                <input type="date" class="form-control" name="end_date" value="{{request()->get('end_date')}}">
              </div>
            </div>
            <div class="col-md-3">
              <div class="input-group input-group-static mb-4">
                <label for="">RegisterIP</label>
                <input type="text" class="form-control" name="register_ip" value="{{request()->get('register_ip')}}">
              </div>
            </div>
            <div class="col-md-3">
              <div class="input-group input-group-static mb-4">
                <label for="">Lastlogin IP</label>
                <input type="text" class="form-control" name="ip_address" value="{{request()->get('ip_address')}}">
              </div>
            </div>
            <div class="col-md-3">
              <button class="btn btn-sm btn-primary mt-3" id="search" type="submit">Search</button>
              <button class="btn btn-outline-primary btn-sm  mb-0 mt-sm-0" data-type="csv" type="button" name="button" id="export-csv">Export</button>
              <a href="{{route('admin.player.index')}}" class="btn btn-link text-primary ms-auto border-0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Refresh">
                <i class="material-icons text-lg mt-0">refresh</i>
              </a>
            </div>
          </div>
        </form>
      </div>
      <div class="table-responsive">
        <table class="table table-flush" id="users-search">
          <thead class="thead-light">
            <th>#</th>
            <th>PlayerID</th>
            @can('master_access')
            <th>AgentName</th>
            @endcan
            <th>Name</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Balance</th>
            <th>Action</th>
            <th>Transaction</th>
          </thead>
          <tbody>
            {{-- kzt --}}
            @if(isset($users))
            @if(count($users)>0)
            @foreach ($users as $user)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>
                <span class="d-block">{{ $user->user_name }}</span>
              </td>
              @can('master_access')
              <td>{{$user->parent->name}}</td>
              @endcan
              <td>{{$user->name}}</td>
              <td>{{ $user->phone }}</td>
              <td>
                <small class="badge bg-gradient-{{ $user->status == 1 ? 'success' : 'danger' }}">{{ $user->status == 1 ? "active" : "inactive" }}</small>
              </td>
              <td>{{number_format($user->balanceFloat,2) }} </td>
              <td>
                @if ($user->status == 1)
                <a onclick="event.preventDefault(); document.getElementById('banUser-{{ $user->id }}').submit();" class="me-2" href="#" data-bs-toggle="tooltip" data-bs-original-title="Active Player">
                  <i class="fas fa-user-check text-success" style="font-size: 20px;"></i>
                </a>
                @else
                <a onclick="event.preventDefault(); document.getElementById('banUser-{{ $user->id }}').submit();" class="me-2" href="#" data-bs-toggle="tooltip" data-bs-original-title="InActive Player">
                  <i class="fas fa-user-slash text-danger" style="font-size: 20px;"></i>
                </a>
                @endif
                <form class="d-none" id="banUser-{{ $user->id }}" action="{{ route('admin.player.ban', $user->id) }}" method="post">
                  @csrf
                  @method('PUT')
                </form>
                <a class="me-1" href="{{ route('admin.player.getChangePassword', $user->id) }}" data-bs-toggle="tooltip" data-bs-original-title="Change Password">
                  <i class="fas fa-lock text-info" style="font-size: 20px;"></i>
                </a>
                <a class="me-1" href="{{ route('admin.player.edit', $user->id) }}" data-bs-toggle="tooltip" data-bs-original-title="Edit Player">
                  <i class="fas fa-pen-to-square text-info" style="font-size: 20px;"></i>
                </a>
              </td>
              <td>
                <a href="{{ route('admin.player.getCashIn', $user->id) }}" data-bs-toggle="tooltip" data-bs-original-title="Deposit To Player" class="btn btn-info btn-sm">
                  <i class="fas fa-plus text-white me-1"></i>
                  Dep
                </a>
                <a href="{{ route('admin.player.getCashOut', $user->id) }}" data-bs-toggle="tooltip" data-bs-original-title="WithDraw To Player" class="btn btn-info btn-sm">
                  <i class="fas fa-minus text-white me-1"></i>
                  WDL
                </a>

                <a href="{{ route('admin.logs', $user->id) }}" data-bs-toggle="tooltip" data-bs-original-title="Reports" class="btn btn-info btn-sm">
                  <i class="fas fa-right-left text-white me-1"></i>
                  Logs
                </a>
              </td>
            </tr>
            @endforeach
            @else
            <tr>
              <td col-span=8>
                There was no Players.
              </td>
            </tr>
            @endif
            @endif

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script src="{{ asset('admin_app/assets/js/plugins/datatables.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
<script>
  if (document.getElementById('users-search')) {
    const dataTableSearch = new simpleDatatables.DataTable("#users-search", {
      searchable: true,
      fixedHeight: false,
      perPage: 7
    });

    document.getElementById('export-csv').addEventListener('click', function () {
    dataTableSearch.export({
      type: "csv",
      filename: "player_list",
    });
  });
  };

</script>
<script>
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })
</script>
@endsection