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
                <form action="{{route('admin.agent.withdraw')}}" method="GET">
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
                                <label for="">Status</label>
                                <select name="status" id="" class="form-control">
                                    <option value="disabled">Select Status</option>
                                    <option value="1">approved</option>
                                    <option value="2">Reject</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-sm btn-primary mt-3" id="search" type="submit">Search</button>
                            <button class="btn btn-outline-primary btn-sm  mb-0 mt-sm-0" data-type="csv" type="button" name="button" id="export-csv">Export</button>
                            <a href="{{route('admin.agent.withdraw')}}" class="btn btn-link text-primary ms-auto border-0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Refresh">
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
                        <th>PlayerId</th>
                        <th>PlayerName</th>
                        <th>Requested Amount</th>
                        <th>Payment Method</th>
                        <th>Bank Account Name</th>
                        <th>Bank Account Number</th>
                        <th>Status</th>
                        <th>Created_at</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach ($withdraws as $withdraw)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{$withdraw->user->user_name}}</td>
                            <td>{{$withdraw->user->name}}</td>
                            <td>{{ number_format($withdraw->amount) }}</td>
                            <td>{{$withdraw->paymentType->name}}</td>
                            <td>{{$withdraw->account_name}}</td>
                            <td>{{$withdraw->account_no}}</td>
                            <td>
                                @if ($withdraw->status == 0)
                                <span class="badge text-bg-warning text-white mb-2">Pending</span>
                                @elseif ($withdraw->status == 1)
                                <span class="badge text-bg-success text-white mb-2">Approved</span>
                                @elseif ($withdraw->status == 2)
                                <span class="badge text-bg-danger text-white mb-2">Rejected</span>
                                @endif
                            </td>
                            <td>{{ $withdraw->created_at->format('d-m-Y H:m:i') }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <form action="{{ route('admin.agent.withdrawStatusUpdate', $withdraw->id) }}" method="post">
                                        @csrf
                                        <input type="hidden" name="amount" value="{{ $withdraw->amount }}">
                                        <input type="hidden" name="status" value="1">
                                        <input type="hidden" name="player" value="{{ $withdraw->user_id }}">
                                        @if($withdraw->status == 0)
                                        <button class="btn btn-success p-1 me-1" type="submit">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        @endif
                                    </form>

                                    <form action="{{ route('admin.agent.withdrawStatusreject', $withdraw->id) }}" method="post">
                                        @csrf
                                        <input type="hidden" name="status" value="2">
                                        @if($withdraw->status == 0)
                                        <button class="btn btn-danger p-1 me-1" type="submit">
                                            <i class="fas fa-xmark"></i>
                                        </button>
                                        @endif
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
<script src="{{ asset('admin_app/assets/js/plugins/datatables.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
<script src="{{ asset('admin_app/assets/js/plugins/datatables.js') }}"></script>
<script>
    if (document.getElementById('users-search')) {
        const dataTableSearch = new simpleDatatables.DataTable("#users-search", {
            searchable: true,
            fixedHeight: false,
            perPage: 7
        });

        document.getElementById('export-csv').addEventListener('click', function() {
            dataTableSearch.export({
                type: "csv",
                filename: "deposit",
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var errorMessage = @json(session('error'));
        var successMessage = @json(session('success'));

        @if(session() -> has('success'))
        Swal.fire({
            icon: 'success',
            title: successMessage,
            text: '{{ session('
            SuccessRequest ') }}',
            timer: 3000,
            showConfirmButton: false
        });
        @elseif(session() -> has('error'))
        Swal.fire({
            icon: 'error',
            title: '',
            text: errorMessage,
            timer: 3000,
            showConfirmButton: false
        });
        @endif
    });
</script>
@endsection