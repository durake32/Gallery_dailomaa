@extends('Dashboard.layouts.master')

@section('content')
    <div class="container-fluid">

        <form role="form" action="{{ route('customer.update', $user->id) }}" method="POST"
            enctype="multipart/form-data">
            @method('put')
            @csrf
            @if ($errors->any())
                <div class="alert alert-danger col-md-12">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif
            <div class="row">
                <div class="col-md-8">
                    <div class="card ">
                        <div class="card-header card-header-rose card-header-icon">
                            <div class="card-icon">
                                <i class="fas fa-user fa-2x"></i>
                            </div>
                            @include('Dashboard.Commons.breadcrum')
                        </div>
                        @include('Dashboard.Admin.Customer.fields')

                    </div>
                    @include('Dashboard.Admin.Customer.Partials.image-section')

                </div>
                <div class="col-md-4">

                <div class="card">
                   
                    @include('Dashboard.Admin.Customer.Partials.edit-is_wholesaler')

                    <div class="card-header card-header-rose card-header-icon">
                        <h4 class="card-title">Status</h4>
                    </div>
                    <div class="card-body">
                        <div class="box-footer" style="display: block;">
                            <select class="browser-default custom-select" name="status">
                                <option value="1" {{$user->status == 1 ? 'selected' : ''}}>Active</option>
                                <option value="0" {{$user->status == 0 ? 'selected' : ''}}>Inactive</option>
                            </select>
                        </div>
                    </div>
                    </div>
                    @include('Dashboard.Admin.Customer.Partials.password-edit-section')

                    <div class="card">
                        <div class="card-body">
                            <div class="box-header with-border">
                                <h4 class="box-title">Customer Type *</h4>
                            </div>
                            <div class="box-footer" style="display: block;">
                                <select class="browser-default custom-select" name="customer_type_id">
                                    <option selected disabled>Select Customer Type</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}" {{ $user->customer_type_id == $type->id ? 'selected' : ''}}>{{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
    
                    <div class="card">
                        <div class="card-body">
                            <div class="box-header with-border">
                                <h4 class="box-title">Location *</h4>
                            </div>
                            <div class="box-footer" style="display: block;">
                                <select class="browser-default custom-select" name="location_id">
                                    <option selected disabled>Select Location</option>
                                    @foreach ($locations as $location)
                                        <option value="{{ $location->id }}" {{ $user->location_id == $location->id ? 'selected' : ''}}>{{ $location->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    @include('Dashboard.Commons.update-button-section')
                </div>
            </div>
        </form>

    </div>
@endsection
