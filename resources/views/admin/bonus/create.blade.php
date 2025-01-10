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

    .custom-select-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .custom-select-wrapper::after {
        content: '\25BC';
        /* Unicode character for "downwards black arrow" */
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        pointer-events: none;
        /* This makes sure clicks pass through to the select element underneath */
    }

    .form-control.custom-select {
        appearance: none;
        /* This removes default browser styling */
        -webkit-appearance: none;
        /* For Safari */
        -moz-appearance: none;
        /* For Firefox */
        padding-right: 30px;
        /* Make space for custom arrow */
    }

    /* Add more styling here for the select and wrapper elements as needed */
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
@endsection
@section('content')
<div class="container text-center mt-4">
    <div class="row">
        <div class="col-12 col-md-8 mx-auto">
            <div class="card">
                <!-- Card header -->
                <div class="card-header pb-0">
                    <div class="d-lg-flex">
                        <div>
                            <h5 class="mb-0">Add Bonus</h5>
                        </div>
                        <div class="ms-auto my-auto mt-lg-0 mt-4">
                            <div class="ms-auto my-auto">
                                <a class="btn btn-icon btn-2 btn-primary" href="{{ route('admin.bonus.index') }}">
                                    <span class="btn-inner--icon mt-1"><i class="material-icons">arrow_back</i>Back</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="custom-form-group">
                        <label for="title" style="text-align: justify;">Player ID</label>
                        <input type="text"  placeholder="search player id" name="user_name" id="user_name" value="{{old('user_name')}}">
                    </div> 
                    <form role="form" method="POST" class="text-start" action="{{ route('admin.bonus.store') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{old('id')}}">
                        <div class="custom-form-group">
                            <label for="title">BonusTypes</label>
                            <select name="type_id" class="form-control form-select" id="">
                                <option value="">Select Bonustype</option>
                                @foreach ($types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            @error('type_id')
                            <span class="text-danger">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="custom-form-group">
                            <label for="title">UserName<span class="text-danger">*</span></label>
                            <input type="text" name="user_name" class="form-control" value="{{old('user_name')}}">
                            @error('user_name')
                            <span class="text-danger d-block">*{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="custom-form-group">
                            <label for="title">PlayerName<span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{old('name')}}">
                            @error('name')
                            <span class="text-danger d-block">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="custom-form-group">
                            <label for="title">Amount<span class="text-danger">*</span></label>
                            <input type="text" name="amount" class="form-control" value="{{old('amount')}}" placeholder="0.00">
                            @error('amount')
                            <span class="text-danger d-block">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="custom-form-group">
                            <label for="title">Remark</label>
                            <input type="text" name="" id="" class="form-control">
                        </div>
                        <div class="custom-form-group">
                            <button class="btn btn-info" type="button" id="resetFormButton">Cancel</button>

                            <button type="submit" class="btn btn-primary" type="button">Submit</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('resetFormButton').addEventListener('click', function() {
            var form = this.closest('form');
            form.querySelectorAll('input[type="text"]').forEach(input => {
                // Resets input fields to their default values
                input.value = '';
            });
            form.querySelectorAll('select').forEach(select => {
                // Resets select fields to their default selected option
                select.selectedIndex = 0;
            });
            // Add any additional field resets here if necessary
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#user_name').on('keyup', function() {
            let playerId = $(this).val();

            if (playerId.trim() !== '') {
                $.ajax({
                    url: '/admin/bonusPlayer', 
                    type: 'GET',
                    data: { user_name: playerId },
                    success: function(response) {
                        if (response.success && response.data) {
                            let player = response.data;
                            $('input[name="user_name"]').val(player.user_name);
                            $('input[name="name"]').val(player.name);
                            $('input[name="phone"]').val(player.phone);
                            $('input[name="id"]').val(player.id);
                            $('span.badge').text(player.balance.toFixed(2)); // Display formatted balance
                        } else {
                            clearPlayerData();
                            alert('Player not found');
                        }
                    },
                    error: function(xhr, status, error) {
                       
                        clearPlayerData();
                    }
                });
            } else {
                clearPlayerData();
            }
        });

        // Function to clear player-related form fields
        function clearPlayerData() {
            $('input[name="user_name"]').val('');
            $('input[name="name"]').val('');
            $('input[name="phone"]').val('');
            $('input[name="id"]').val('');
            $('span.badge').text('');
        }
    });
</script>


@endsection