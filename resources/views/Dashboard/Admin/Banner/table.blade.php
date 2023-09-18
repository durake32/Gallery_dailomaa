<table id="datatables" class="table table-striped table-no-bordered table-hover dataTable dtr-inline" cellspacing="0"
    width="100%" style="width: 100%;" role="grid" aria-describedby="datatables_info">

    <thead>
        <tr role="row">
            {{-- <th class="sorting_asc" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1" style="width: 30px;"
                aria-sort="ascending" aria-label="Name: activate to sort column descending">#
            </th> --}}
            {{-- <th class="text-center"></th> --}}
            <th class="sorting_asc" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1" style="width: 70px;"
                aria-sort="ascending" aria-label="Name: activate to sort column descending">Image
            </th>
            <th class="sorting_asc" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1"
                style="width: 177px;" aria-sort="ascending" aria-label="Name: activate to sort column descending">
                Title
            </th>

            <th class="sorting" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1" style="width: 110px;"
                aria-label="Position: activate to sort column ascending">Status
            </th>

            <th class="sorting" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1" style="width: 114px;"
                aria-label="Date: activate to sort column ascending">Added At
            </th>
            <th class="disabled-sorting text-right sorting" tabindex="0" aria-controls="datatables" rowspan="1"
                colspan="1" style="width: 170px;" aria-label="Actions: activate to sort column ascending">Actions</th>
        </tr>
    </thead>
    <tbody id="tablecontents">
        @foreach($banner as $bannerData)
        <tr class="odd row1" role="odd" data-id="{{$bannerData->id}}">
            <td>
                <div class="img-container">
                    <img class="image" src="{{asset($bannerData->banner_image)}}" alt="" height="100" width="100">

                </div>
            </td>
            <td>{{$bannerData->title}}</td>
            <td>
                <span class="{{$bannerData->active_status['status']}}">
                    {{$bannerData->active_status['message']}}
                </span>
            </td>
            <td>{{ date('M d, Y',strtotime($bannerData->created_at)) }}</td>

            </td>
            <td class="text-right">
                <a href="#" data-target="#modal-{{$bannerData->id}}" data-toggle="modal"
                    class="btn btn-link btn-warning btn-just-icon edit">
                    <i class="fa fa-eye"></i>
                </a>
                @can('banner edit')
                <a href="{{route('banner.edit',$bannerData->id)}}" class="btn btn-link btn-info btn-just-icon like"><i
                        class="fa fa-edit"></i></a>
                @endcan
                @can('banner delete')
                <a class="btn btn-link btn-danger btn-just-icon remove">
                    <form onsubmit="return confirm('Do you really want to delete?');"
                        action="{{route('banner.destroy',$bannerData->id)}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </a>
                @endcan
            </td>

            @include('Dashboard.Admin.Banner.modal')


        </tr>
        @endforeach
    </tbody>
</table>