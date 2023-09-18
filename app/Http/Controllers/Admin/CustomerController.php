<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CreateCustomer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index()
    {
        $users = User::where('is_wholesaler',0)->latest()->get();

        return view('Dashboard.Admin.Customer.index', compact('users'));
    }

    public function deactiveCustomer()
    {
        $users = User::where('is_wholesaler',0)->whereNotNull('remarks')->latest()->get();

        return view('Dashboard.Admin.Customer.index2', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(User $user)
    {
        return view('Dashboard.Admin.Customer.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCustomer $request, User $user)
    {
        $user['name'] = $request->name;
        $user['email'] = $request->email;
        $user['address'] = $request->address;
        $user['city'] = $request->city;
        $user['country'] = $request->country;
        $user['contact_no'] = $request->contact_no;
        $user['is_wholesaler'] = 0;
        $user['password'] = Hash::make($request['password']);

        $name = strtolower($request->name);

        $removeSpace = str_replace(' ', '-', $name);

        if ($request->hasFile('image')) {
            $user['image'] = $removeSpace . uniqid() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move('Asset/Uploads/Users', $user['image']);
        }

        $user->save();

        return redirect(route('customer.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('Dashboard.Admin.Customer.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $name = strtolower($user->name);

        $removeSpace = str_replace(' ', '-', $name);

        switch ($request->input('action')) {
            case 'update':
                // Save 
                $this->validate($request, [
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                    'address' => 'nullable', 'string', 'max:255',
                    'city' => 'nullable', 'string', 'max:255',
                    'country' => 'nullable', 'string', 'max:255',
                    'is_wholesaler' => 'required', 'boolean',
                    'contact_no' => 'nullable', 'string', 'max:255',
                    'image' => 'nullable', 'image', 'max:255',

                ]);
                // dd($request->all());

                $user['name'] = $request->name;
                $user['email'] = $request->email;
                $user['address'] = $request->address;
                $user['city'] = $request->city;
                $user['country'] = $request->country;
                $user['is_wholesaler'] = $request->is_wholesaler;
                $user['contact_no'] = $request->contact_no;
                $user['status'] = $request->status;
                $user['remarks'] = $request->remarks;


                    if ($request->hasFile('image')) {
                        $existingImage = 'Asset/Uploads/Users/' . $user->image;
            
                        if (file_exists($existingImage)) {
                       @unlink($existingImage);
                        }
            
                        $file = $request->file('image');
                        $extension = $file->getClientOriginalExtension();
                        $fileName = $removeSpace . uniqid() . '.' . $extension;
                        $file->move('Asset/Uploads/Users/', $fileName);
                        $user->image = $fileName;
                    }


                $user->save();

                break;

            case 'change-password':
                // Preview model
                $this->validate($request, [
                    'password' => 'required|string|min:8|confirmed',

                ]);
                $user->password = Hash::make($request->password);

                $user->save();

                break;
        }

        return redirect(route('customer.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // dd($user);

        $existingImage = 'Asset/Uploads/Users/' . $user->image;


        if (file_exists($existingImage)) {
       @unlink($existingImage);
        }
      
      
        $user->orders()->delete(); // See below


        $user->delete();

        return redirect(route('customer.index'));
    }
}