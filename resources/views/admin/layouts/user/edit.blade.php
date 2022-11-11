@extends('admin.master')
@section('content')
    @foreach ($errors->all() as $error)
        <p class="alert alert-danger">{{ $error }}</p>
    @endforeach
    @if (session('message'))
        <div class="alert alert-success alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">x</a>
            {{ session('message')}}
        </div>
    @endif
    <div class="col-md-12 content-panel">
        <form enctype="multipart/form-data" action="{{route('users.update',$user->id)}}" method="POST" role="form">
            @csrf
            @method('PUT')
            <h3>Update User</h3>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">User Name</label>
                        <input required type="text" class="form-control" id="exampleFormControlInput1"
                               value="{{$user->username}}" name="user_name">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">User Email</label>
                        <input type="text" class="form-control" id="exampleFormControlInput1" value="{{$user->email}}"
                               name="email">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input required name="phone_number" type="number" class="form-control" id="phone"
                               value="{{$user->phone_number}}">
                    </div>


                    <div class="form-group">
                        <label for="type">Change User Type</label>
                        <select class="form-control" id="type" name="type">
                            <option @if($user->type=='user') selected @endif value="user">End User</option>
                            <option @if($user->type=='admin') selected @endif value="admin">Admin</option>
                            <option @if($user->type=='vendor') selected @endif value="vendor">Vendor</option>
                            <option @if($user->type=='seller') selected @endif value="seller">Seller</option>
                            <option @if($user->type=='developer') selected @endif value="developer">Developer</option>

                        </select>
                    </div>

                </div>

                <div class="col-md-6" style="">
                    <div class="form-group" style="text-align: center">
                        <span><img style="width:150px;" src="{{asset('/uploads/user/'.$user->image)}}" alt="User Image"></span>
                        <p style="padding: 20px;"><input type="file" name="file"></p>
                    </div>
                </div>

            </div>


            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="type">Status</label>
                        <select class="form-control" id="type" name="status">
                            <option value="active" {{$user->status=='active' ? 'selected':''}}>Active</option>
                            <option value="inactive" {{$user->status=='inactive' ? 'selected':''}} >Inactive</option>
                            <option value="close" {{$user->status=='close' ? 'selected':''}} >Close</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="balance">User Balance </label>
                        <input type="text" class="form-control" id="balance" name="balance" value="{{$user->balance}}"
                               readonly>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="nid_passport">NID / Passport: </label>
                <input type="text" class="form-control" id="nid_passport" name="nid_passport"
                       value="{{$user->nid_passport}}">
            </div>
            <div class="form-group">
                <label for="address">Address *</label>
                <textarea required class="form-control" name="address" id="address">{{$user->address}}</textarea>
            </div>
            <p>

   @if(!is_null($user->docs) AND $user->docs!='null')

       @foreach(json_decode($user->docs) as $key=>$doc)
           <a class="btn btn-warning" target="_blank" href="{{asset('/uploads/docs/'.$doc)}}">
               <span>View {{ucfirst(str_replace("'","",$key))}}</span></a>
           {{--        <img style="width: 300px; height: 300px;" src=""/>--}}
       @endforeach
   @endif

</p>
<p>Update Docs:</p>
<div class="row">
   <div class="col-md-4">
       <div class="form-group">
           <label for="doc1">Doc 01 </label>
           <input type="file" class="form-control" id="doc1" name="docs['doc1']">
       </div>

   </div><div class="col-md-4">
       <div class="form-group">
           <label for="doc2">Doc 02 </label>
           <input type="file" class="form-control" id="doc2" name="docs['doc2']" >
       </div>

   </div><div class="col-md-4">
       <div class="form-group">
           <label for="doc3">Doc 03 </label>
           <input type="file" class="form-control" id="doc3" name="docs['doc3']">
       </div>

   </div>
</div>

<p>
   <button type="submit" class="btn btn-success mr-2">Update</button>
   <a href="{{route('users.data')}}" class="btn btn-primary">Back</a>
</p>
</form>

<hr>
<div class="row">
<div class="col-md-6">
   <div class="form-group">
       <h3>Update password</h3>
       <form action="{{route('update.password',$user->id)}}" method="post" role="form">
           @csrf
           @method('put')

           <div class="form-group">
               <label for="password">Enter New Password * </label>
               <input required placeholder="Enter Password" type="password" class="form-control"
                      id="password" name="password">
           </div>
           <div class="form-group">
               <label for="pin">Enter PIN * </label>
               <input required placeholder="Enter PIN" type="pin" class="form-control" id="pin" name="pin">
           </div>

           <button class="btn btn-success" type="submit">Update</button>


       </form>
   </div>
</div>
<div class="col-md-6">
   <div class="form-group">
       <h3>Update PIN</h3>
       <form action="{{route('update.pin',$user->id)}}" method="post" role="form">
           @csrf
           @method('put')

           <div class="form-group">
               <label for="pin">Enter New PIN * </label>
               <input required placeholder="Enter New PIN" type="text" class="form-control" id="pin"
                      name="pin">
           </div>
           <div class="form-group">
               <label for="old_pin">Enter Admin PIN * </label>
               <input required placeholder="Enter Admin PIN" type="text" class="form-control"
                      id="admin_pin" name="admin_pin">
           </div>

           <button class="btn btn-success" type="submit">Update</button>


       </form>
   </div>
</div>
</div>

</div>
@stop
