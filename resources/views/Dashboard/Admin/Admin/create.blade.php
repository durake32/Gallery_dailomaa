@extends('Dashboard.layouts.master')

@section('content')
    <div class="container-fluid">
        <form role="form" action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data">
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
            <div class="row">
                <div class="col-md-8">
                    <div class="card ">
                        <div class="card-header card-header-rose card-header-icon">
                            <div class="card-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            {{-- <h4 class="card-title">Details</h4>
                            --}}
                            @include('Dashboard.Commons.breadcrum')
                        </div>
                        @include('Dashboard.Admin.Admin.fields')
                    </div>
                    @include('Dashboard.Admin.Admin.Partials.image-section')
                </div>
                <div class="col-md-4">
                    @include('Dashboard.Admin.Admin.Partials.is_super')

                    @include('Dashboard.Includes.Components.password-create-section')

                    @include('Dashboard.Includes.Buttons.submit-button-section')

                </div>
            </div>
        </form>
    </div>
@endsection
