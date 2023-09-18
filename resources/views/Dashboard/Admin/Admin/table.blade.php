<table id="datatables" class="table table-striped table-no-bordered table-hover dataTable dtr-inline" cellspacing="0"
    width="100%" style="width: 100%;" role="grid" aria-describedby="datatables_info">
    <thead>
        <tr role="row">
            {{-- <th class="text-center"></th> --}}
            <th class="sorting_asc" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1"
                style="width: 177px;" aria-sort="ascending" aria-label="Name: activate to sort column descending">Image
            </th>
            <th class="sorting_asc" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1"
                style="width: 177px;" aria-sort="ascending" aria-label="Name: activate to sort column descending">Name
            </th>
            <th class="sorting" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1" style="width: 261px;"
                aria-label="Position: activate to sort column ascending">Email
            </th>
            <th class="sorting" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1" style="width: 261px;"
                aria-label="Position: activate to sort column ascending">Is SuperAdmin
            </th>
            <th class="sorting" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1" style="width: 261px;"
                aria-label="Position: activate to sort column ascending">Role
            </th>
            <th class="sorting" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1" style="width: 114px;"
                aria-label="Date: activate to sort column ascending">Registered At
            </th>
            <th class="disabled-sorting text-right sorting" tabindex="0" aria-controls="datatables" rowspan="1"
                colspan="1" style="width: 208px;" aria-label="Actions: activate to sort column ascending">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($admins as $admin)
        <tr role="row" class="odd">
            <td>
                <div class="img-container">
                    <img src="{{ !empty($admin->image) ? asset('Asset/Uploads/Users/' . $admin->image) : url('images/Static/profile.png') }}"
                        alt="" height="100" width="100">
                </div>
            </td>
            <td tabindex="0" class="sorting_1">{{ $admin->name }}</td>
            <td>{{ $admin->email }}</td>
            <td>
                <span class="{{ $admin->super_admin['status'] }}">
                    {{ $admin->super_admin['message'] }}
                </span>
            </td>
            <td>
                @foreach($admin->roles as $role)
                <span class="btn btn-sm">
                    {{ $role->name  }}
                </span>
                @endforeach
            </td>
            <td>{{ date('M d, Y',strtotime($admin->created_at)) }}</td>
            <td class="text-right">
                <a href="{{ route('admin.show',$admin->id) }}" data-title="Assign Permission"
                    class="btn btn-link btn-info btn-just-icon edit"><i class="fa fa-cog"></i>
                </a>
                <a href="#" data-target="#modal-{{ $admin->id }}" data-toggle="modal"
                    class="btn btn-link btn-warning btn-just-icon edit"><i class="fa fa-eye"></i>
                </a>
                <a href="{{ route('admin.edit', $admin->id) }}" class="btn btn-link btn-info btn-just-icon like"><i
                        class="fa fa-edit"></i></a>
                <a class="btn btn-link btn-danger btn-just-icon remove">
                    <form onsubmit="return confirm('Do you really want to delete?');"
                        action="{{ route('admin.destroy', $admin->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </a>
            </td>
            @include('Dashboard.Admin.Admin.modal')
        </tr>
        @empty
        <p>No Admins</p>
        @endforelse
    </tbody>
</table>