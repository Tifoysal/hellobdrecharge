@extends('admin.master')

@section('content')
    <div class="content">
       <p>Settings</p>
        <div class="block block-rounded block-bordered">
            <div class="block-content">
                <form action="{{route('settings.update')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method("PUT")
                    <div class="row push">
                        <div class="col-lg-8 col-xl-8">
                            <div class="form-group">
                                <label for="company_name">Company Name</label>
                                <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                       id="company_name"
                                       name="company_name" value="{{$edit->company_name}}">
                                <span
                                        class="text-danger">@error('company_name'){{$message}} @enderror
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror"
                                       id="address"
                                       name="address" value="{{$edit->address}}">
                                <span
                                        class="text-danger">@error('address'){{$message}} @enderror
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="google_location">Google Location</label>
                                <input type="text" class="form-control @error('google_location') is-invalid @enderror"
                                       id="google_location"
                                       name="google_location" value="{{$edit->google_location}}">
                                <span
                                        class="text-danger">@error('google_location'){{$message}} @enderror
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control @error('email') is-invalid @enderror" id="email"
                                       name="email" value="{{$edit->email}}">
                                <span
                                        class="text-danger">@error('email'){{$message}} @enderror
                                </span>
                            </div>

                            <div class="form-group">
                                <label for="phone_number">Phone Number</label>
                                <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                                       id="phone_number"
                                       name="phone_number" value="{{$edit->phone_number}}">
                                <span
                                        class="text-danger">@error('phone_number'){{$message}} @enderror
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="web_address">Web Address</label>
                                <input type="text" class="form-control @error('web_address') is-invalid @enderror"
                                       id="web_address"
                                       name="web_address" value="{{$edit->phone_number}}">
                                <span
                                        class="text-danger">@error('web_address'){{$message}} @enderror
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="hot_line">Hot Line</label>
                                <input type="text" class="form-control @error('hot_line') is-invalid @enderror"
                                       id="hot_line"
                                       name="hot_line" value="{{$edit->hot_line}}">
                                <span
                                        class="text-danger">@error('hot_line'){{$message}} @enderror
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="facebook">Facebook</label>
                                <input type="text" class="form-control @error('facebook') is-invalid @enderror"
                                       id="facebook"
                                       name="facebook" value="{{$edit->facebook}}">
                                <span
                                        class="text-danger">@error('facebook'){{$message}} @enderror
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="twitter">Twitter</label>
                                <input type="text" class="form-control @error('twitter') is-invalid @enderror"
                                       id="twitter"
                                       name="twitter" value="{{$edit->twitter}}">
                                <span
                                        class="text-danger">@error('twitter'){{$message}} @enderror
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="instagram">Instagram</label>
                                <input type="text" class="form-control @error('instagram') is-invalid @enderror"
                                       id="instagram"
                                       name="instagram" value="{{$edit->instagram}}">
                                <span
                                        class="text-danger">@error('instagram'){{$message}} @enderror
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="pinterest">Pinterest</label>
                                <input type="text" class="form-control @error('pinterest') is-invalid @enderror"
                                       id="pinterest"
                                       name="pinterest" value="{{$edit->pinterest}}">
                                <span
                                        class="text-danger">@error('pinterest'){{$message}} @enderror
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="youtube">youtube</label>
                                <input type="text" class="form-control @error('youtube') is-invalid @enderror"
                                       id="youtube"
                                       name="youtube" value="{{$edit->youtube}}">
                                <span
                                        class="text-danger">@error('youtube'){{$message}} @enderror
                                </span>
                            </div>


                            <div class="form-group">
                                <label for="tag_line">Tag Line</label>
                                <input type="text" class="form-control @error('tag_line') is-invalid @enderror"
                                       id="tag_line"
                                       name="tag_line" value="{{$edit->tag_line}}">
                                <span
                                        class="text-danger">@error('tag_line'){{$message}} @enderror
                                </span>
                            </div>

                            <div class="form-group">
                                <label for="about">About</label>
                                <textarea class="form-control" id="about" name="about"
                                          rows="4">{{$edit->about}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row push">
                        <div class="col-lg-8 col-xl-8">
                            <div class="form-group">
                                <label>Logo</label>
                                <div class="custom-file">
                                    <!-- Populating custom file input label with the selected filename (data-toggle="custom-file-input" is initialized in Helpers.coreBootstrapCustomFileInput()) -->
                                    <!-- When multiple files are selected, we use the word 'Files'. You can easily change it to your own language by adding the following to the input, eg for DE: data-lang-files="Dateien" -->
                                    <input type="file" class="custom-file-input js-custom-file-input-enabled"
                                           data-toggle="custom-file-input" id="imag"
                                           name="logo">
                                    <img src="{{asset('uploads/business/'.$edit->logo)}}" alt="" width="80px;" style="background: black">
                                    <label class="custom-file-label" for="example-file-input-multiple-custom"
                                           style="overflow-x: hidden;"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row push">
                        <div class="col-lg-8 col-xl-8">
                            <div class="form-group">
                                <label>Favicon</label>
                                <div class="custom-file">
                                    <!-- Populating custom file input label with the selected filename (data-toggle="custom-file-input" is initialized in Helpers.coreBootstrapCustomFileInput()) -->
                                    <!-- When multiple files are selected, we use the word 'Files'. You can easily change it to your own language by adding the following to the input, eg for DE: data-lang-files="Dateien" -->
                                    <input type="file" class="form-control"
                                           data-toggle="custom-file-input" id="imag"
                                           name="favicon">
                                    <img src="{{asset('uploads/business/'.$edit->favicon)}}" alt="" width="80px;">
                                    <label class="custom-file-label" for="example-file-input-multiple-custom"
                                           style="overflow-x: hidden;"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group ">
                        <button type="submit" class="bnt btn-primary">Save</button>
                        <button type="button" class="bnt btn-danger">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
