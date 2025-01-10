@extends('admin_layouts.app')

@section('content')
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header pb-0">

                <div class="card-body">
                    <h5 class="mb-0">Win/Lose Details</h5>
                </div>
                <form action="{{route('admin.report.detail', $playerId)}}" method="GET">
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <div class="input-group input-group-static mb-4">
                                <label for="">Product Type</label>
                                <select name="product_type_id" id="" class="form-control">
                                    <option value="" disabled>Select Product type</option>
                                    @foreach($productTypes as $type)
                                    <option value="{{$type->provider_name}}">{{$type->provider_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <button class="btn btn-sm btn-primary" id="search" type="submit">Search</button>
                            <a href="{{route('admin.report.detail', $playerId)}}" class="btn btn-link text-primary ms-auto border-0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Refresh">
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
                        <th>Name</th>
                        <th>Product Name</th>
                        <th>Game Name</th>
                        <th>Valid Bet</th>
                        <th>Win/Lose Amt</th>
                        <th>Created At</th>
                    </thead>
                    <tbody>
                        @foreach($details as $detail)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$detail->user->user_name}}</td>
                            <td>{{$detail->user->name}}</td>
                            <td>{{$detail->game_provide_name}}</td>
                            <td>{{$detail->game_name}}</td>
                            <td>{{number_format($detail->total_bet_amount, 2)}}</td>
                            <td><span class="{{$detail->net_win > 0 ? 'text-success' : 'text-danger' }}">{{number_format($detail->net_win, 2)}}</span></td>
                            <td>{{$detail->created_at}}</td>
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
<script src="{{ asset('admin_app/assets/js/plugins/datatables.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
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

    };
</script>
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endsection