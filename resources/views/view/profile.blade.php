@extends('layouts.header')
@section('content')

          <div class="row">
            <div class="col-md-8">
              <div class="card">
                <div class="card-header card-header-icon card-header-warning">
                  <div class="card-icon">
                    <i class="material-icons">perm_identity</i>
                  </div>
                  <h4 class="card-title">Edit Profile -
                    <small class="category">Complete your profile</small>
                  </h4>
                </div>
                <div class="card-body">
                  <form method="POST" action="/editProfile">
                    @csrf
                    <div class="row">
                      <div class="col-md-5">
                        <div class="form-group">
                          <label class="bmd-label-floating">{{session('dashboard')['User']['name']}}</label>
                          <input type="text"  name="name" class="form-control" >
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="bmd-label-floating">{{session('dashboard')['User']['telephone']}}</label>
                          <input type="text" name="telephone" class="form-control" >
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="bmd-label-floating">{{session('dashboard')['User']['email']}}</label>
                          <input type="email" name="email" class="form-control" readonly>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">N.I.N <strong>({{session('dashboard')['User']['NIN'] ?? ' '}} )</strong></label>
                          <input type="text" name="NIN" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Card No. <strong>({{session('dashboard')['User']['card_no'] ?? ' '}})</strong>  </label>
                          <input type="text" name="card_no" class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="bmd-label-floating">Address <strong>(  {{session('dashboard')['User']['address'] ?? ' '}} )</strong></label>
                          <input type="text" name="address" class="form-control">
                        </div>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-rose pull-right">Update Profile</button>
                    <div class="clearfix"></div>
                  </form>
                </div>
              </div>
              <div class="row">
                  <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-icon card-header-rose">
                            <div class="card-icon">
                                <i class="material-icons">perm_identity</i>
                            </div>
                            <h4 class="card-title">Edit Passwords
                            </h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="/editPasswords">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                        <label class="bmd-label-floating">Old Password</label>
                                        <input type="password" name="name" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                        <label class="bmd-label-floating">New Password</label>
                                        <input type="password" name="name" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                        <label class="bmd-label-floating">Repeat Password</label>
                                        <input type="password" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-rose pull-right">Update Password</button>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                  </div>
                  <!-- <div class="col-md-6">
                    <div class="card">
                        <div class="card-header card-header-icon card-header-">
                            <div class="card-icon">
                                <i class="material-icons">perm_identity</i>
                            </div>
                            <h4 class="card-title">Edit Passwords
                            </h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="/edit_Password">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                        <label class="bmd-label-floating">Old Password</label>
                                        <input type="password" name="old_password" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                        <label class="bmd-label-floating">New Password</label>
                                        <input type="password" name="new_password" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                        <label class="bmd-label-floating">Repeat Password</label>
                                        <input type="password" name="repeat_password" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-rose pull-right">Update Password</button>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                  </div> -->
              </div>
            </div>
            <div class="col-md-4">
              <div class="card card-profile">
                <div class="card-avatar">
                  <a href="#pablo">
                    <img class="img" src="{{session('dashboard')['user-identification']['passport'] ?? '../../uploads/faces/img.png'}}" />
                  </a>
                </div>
                <div class="card-body">
                    <?php
                        if (session('dashboard')['User']['sms_verified_at'] == null) {
                            # code...
                            $status = 'Not Verified';
                            $badge = 'badge-rose';
                        }else {
                            # code...
                            $status = 'Verified';
                            $badge = 'badge-success';
                        }
                    
                    ?>
                  <h6 class="card-category text-gray">{{session('user_id')}}</h6>
                  <h4 class="card-title">{{session('dashboard')['User']['name']}}</h4>
                  <h4 class="card-title">{{session('dashboard')['User']['telephone']}} <span class="badge {{ $badge }}">{{ $status }}</span> </h4>
                  <h4 class="card-title">{{session('dashboard')['User']['email']}}</h4>
                  
                  <a href="#pablo" class="btn btn- btn-round">Follow</a>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
              <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-icon card-header-rose">
                        <div class="card-icon">
                            <i class="material-icons">perm_identity</i>
                        </div>
                        <h4 class="card-title">Edit Identification
                        </h4>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-4">
                          <center>
                            <form action="/frontface" method="post" enctype="multipart/form-data" >
                              @csrf
                              <h4 class="title">Id Front Face</h4>
                              <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" >
                                  <img src="{{session('dashboard')['user-identification']['front_face'] ?? '../../uploads/ids/id1.png'}}" alt="..." >
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail"></div>
                                <div>
                                  <span class="btn btn-rose btn-round btn-file">
                                    <span class="fileinput-new">Select image</span>
                                    <span class="fileinput-exists">Change</span>
                                    <input type="file" name="image" required />
                                  </span>
                                  <button type="submit" class="btn btn-danger btn-round fileinput-exists">Submit</button>
                                </div>
                              </div>
                            </form>
                          </center>
                        </div>
                        <div class="col-md-4">
                          <center>
                            <form action="/backface" method="post" enctype="multipart/form-data">
                              @csrf
                              <h4 class="title">Id Back Face</h4>
                              <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                                <div class="fileinput-new thumbnail">
                                  <img src="{{session('dashboard')['user-identification']['back_face'] ?? '../../uploads/ids/id1.png'}}" alt="...">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail"></div>
                                <div>
                                  <span class="btn btn-rose btn-round btn-file">
                                    <span class="fileinput-new">Select image</span>
                                    <span class="fileinput-exists">Change</span>
                                    <input type="file" name="image" required />
                                  </span>
                                  <button type="submit" class="btn btn-danger btn-round fileinput-exists">Submit</button>
                                </div>
                              </div>
                            </form>
                          </center>
                        </div>
                        <div class="col-md-4">
                          <center>
                            <form action="/passport" method="post" enctype="multipart/form-data">
                              @csrf
                              <h4 class="title">Passport Photo</h4>
                              <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                                <div class="fileinput-new thumbnail">
                                  <img src="{{session('dashboard')['user-identification']['passport'] ?? '../../uploads/faces/img.png'}}" alt="...">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail"></div>
                                <div>
                                  <span class="btn btn-rose btn-round btn-file">
                                    <span class="fileinput-new">Select image</span>
                                    <span class="fileinput-exists">Change</span>
                                    <input type="file" name="image" required/>
                                  </span>
                                  <button type="submit" class="btn btn-danger btn-round fileinput-exists">Submit</button>
                                </div>
                              </div>
                            </form>
                          </center>
                        </div>
                      </div>
                    </div>
                </div>
              </div>
            </div>
        </div>
      </div>
@endsection