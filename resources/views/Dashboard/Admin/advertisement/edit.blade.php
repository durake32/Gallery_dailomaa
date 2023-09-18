@extends('Dashboard.layouts.master')

@section('content')
<div class="container-fluid">
    <?php $segment = Request::segment(1); ?>
    <form role="form" enctype="multipart/form-data"
        action="{{ route('admin.advertisement.update', $advertisement->id) }}" method="POST">
        @csrf
        @method('put')
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
                            <i class="fas fas fa-cube fa-2x"></i>
                        </div>
                        @include('Dashboard.Commons.breadcrum')
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-md-12">
                                    <div>
                                        <label for="name"> Name *</label>
                                        <input type="text" class="form-control" value="{{ $advertisement->title }}"
                                            name="title" id="name">
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="card ">
                    <div class="card-header card-header-rose card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">contacts</i>
                        </div>
                        <h4 class="card-title">Image</h4>
                    </div>
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-md-4 col-sm-4">
                                <div class="fileinput fileinput-new text-center" data-provides="fileinput">

                                    @isset($advertisement->url)
                                    <div class="fileinput-new thumbnail">
                                        <img src="{{ asset('Asset/Uploads/advertisements/' . $advertisement->url) }}"
                                            alt="{{ $advertisement->title }}">
                                    </div>
                                    @else
                                    <div class="fileinput-new thumbnail">
                                        <img src="{{ asset('Asset/Uploads/Static/avatar.jpg') }}" alt="...">
                                    </div>
                                    @endif
                                    <div class="fileinput-preview fileinput-exists thumbnail"></div>
                                    <div>
                                        <span class="btn btn-rose btn-round btn-file">
                                            <span class="fileinput-new">Select image</span>
                                            <span class="fileinput-exists">Change</span>
                                            <input type="file" name="url" kl_vkbd_parsed="true">
                                        </span>
                                        <a href="#pablo" class="btn btn-danger btn-round fileinput-exists"
                                            data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h3>Link</h3>
                        <input type="url" name="link" value="{{ $advertisement->link }}" class="form-control">
                    </div>


                    <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                            <label for="type"> Type select</label>
                            <select class="browser-default custom-select" name="type" id="type">
                                <option disabled selected>Please Type</option>
                                <option value="category" {{ $advertisement->type == 'category'  ? 'selected' : ''}}>
                                    Category</option>
                                <option value="subcategory"
                                    {{ $advertisement->type == 'subcategory'  ? 'selected' : ''}}>
                                    SubCategory</option>
                                <option value="brand" {{ $advertisement->type == 'brand'  ? 'selected' : ''}}>Brand
                                </option>
                            </select>
                        </div>
                    </div>

                    @if($advertisement->type == 'category')
                    <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                            <label for="type_id"> Select Category *</label>
                            <select class="browser-default custom-select" name="type_id">
                                @foreach($categories as $cat)
                                <option value="{{$cat->id}}" {{$cat->id == $advertisement->type_id  ? 'selected' : ''}}>
                                    {{$cat->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif

                    @if($advertisement->type == 'subcategory')
                    <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                            <label for="type_id"> Select Sub Category *</label>
                            <select class="browser-default custom-select" name="type_id">
                                @foreach($subcategories as $cat)
                                <option value="{{$cat->id}}" {{$cat->id == $advertisement->type_id  ? 'selected' : ''}}>
                                    {{$cat->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif

                    @if($advertisement->type == 'brand')
                    <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                            <label for="type_id"> Select Brand*</label>
                            <select class="browser-default custom-select" name="type_id">
                                @foreach($brands as $cat)
                                <option value="{{$cat->id}}" {{$cat->id == $advertisement->type_id  ? 'selected' : ''}}>
                                    {{$cat->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="box-header with-border">
                            <h3 class="box-title">Action</h3>
                        </div>
                        <div class="box-footer" style="display: block;">
                            <input class="btn btn-primary" type="submit">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">

            </div>
        </div>
    </form>
</div>
@endsection