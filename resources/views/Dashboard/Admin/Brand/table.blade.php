<table id="datatables" class="table table-striped table-no-bordered table-hover dataTable dtr-inline" cellspacing="0"
    width="100%" style="width: 100%;" role="grid" aria-describedby="datatables_info">
    <thead>
        <tr role="row">
            {{-- <th class="text-center"></th> --}}
            <th class="sorting_asc" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1"
                style="width: 100px;" aria-sort="ascending" aria-label="Name: activate to sort column descending">Image
            </th>
            <th class="sorting_asc" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1"
                style="width: 177px;" aria-sort="ascending" aria-label="Name: activate to sort column descending">Name
            </th>
            @can('product view')
            <th class="sorting_asc" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1"
                style="width: 100px;" aria-sort="ascending" aria-label="Name: activate to sort column descending">
                Products
            </th>
            @endcan
            <th class="sorting" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1" style="width: 100px;"
                aria-label="Position: activate to sort column ascending">Featured
            </th>
            <th class="sorting" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1" style="width: 70px;"
                aria-label="Position: activate to sort column ascending">Status
            </th>

            <th class="sorting" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1" style="width: 114px;"
                aria-label="Date: activate to sort column ascending">Added At
            </th>
            <th class="sorting" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1" style="width: 114px;"
                aria-label="Date: activate to sort column ascending">Updated At
            </th>
            <th class="disabled-sorting text-right sorting" tabindex="0" aria-controls="datatables" rowspan="1"
                colspan="1" style="width: 208px;" aria-label="Actions: activate to sort column ascending">Actions
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($brands as $brand)
        <tr role="row" class="odd">
            <td>
                <div class="img-container">
                    <img src="{{ !empty($brand->image) ? asset('Asset/Uploads/Brands/' . $brand->image) : url('images/Static/profile.png') }}"
                        alt="{{ $brand->name }}" height="100" width="100">
                </div>
            </td>
            <td>{{ $brand->name }}</td>
            <td>
                @can('product view')
                <a href="{{ route($segment . '.' . 'brand-wise-products.show', $brand->slug) }}">
                    <i class="fas fa-folder-open"></i>
                    {{ $brand->products->count() }}
                </a>
                @endcan
            </td>
            <td>
                <i class="{{ $brand->featured_status['status'] }}">
                </i>
            </td>
            <td>
                <span class="{{ $brand->active_status['status'] }}">
                    {{ $brand->active_status['message'] }}
                </span>
            </td>
            <td>{{ date('M d, Y',strtotime($brand->created_at)) }}</td>
            <td>{{ date('M d, Y',strtotime($brand->updated_at)) }}</td>

            <td class="text-right">
                <a href="#" data-target="#modal-{{ $brand->id }}" data-toggle="modal"
                    class="btn btn-link btn-warning btn-just-icon edit"><i class="fa fa-eye"></i>
                </a>
                @can('brand edit')
                <a href="{{ route($segment . '.' . 'brand.edit', $brand->id) }}"
                    class="btn btn-link btn-info btn-just-icon like"><i class="fa fa-edit"></i></a>
                @endcan
                @can('brand delete')
                <a class="btn btn-link btn-danger btn-just-icon remove" onclick="return confirm('Are you sure?')"
                    href="{{ route('admin.destroy.brand',$brand->id) }}"><i class="fa fa-trash"></i></a>
                @endcan
            </td>
            @include('Dashboard.Admin.Brand.Partials.modal')
        </tr>
        @endforeach
    </tbody>
</table>