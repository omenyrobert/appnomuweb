@extends('layouts.header')
@section('content')

          <div class="row">
            <div class="col-md-12">
              <div class="card ">
                <div class="card-header card-header-rose card-header-text">
                  <div class="card-text">
                    <h4 class="card-title">System General Settings</h4>
                  </div>
                </div>
                <div class="card-body ">
                  <form method="get" action="#" class="form-horizontal">
                    <div class="row">
                      <label class="col-sm-2 col-form-label">App Name</label>
                      <div class="col-sm-10">
                        <div class="form-group">
                          <input type="text" class="form-control">
                          <span class="bmd-help">Enter Application Name.</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">App Email</label>
                      <div class="col-sm-10">
                        <div class="form-group">
                          <input type="text" class="form-control">
                          <span class="bmd-help">Enter Application Email.</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">App Telephone</label>
                      <div class="col-sm-10">
                        <div class="form-group">
                          <input type="text" class="form-control">
                          <span class="bmd-help">Enter Application Telephone Number.</span>
                        </div>
                      </div>
                    </div>
                    </br>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">App Logo</label>
                      <div class="col-sm-10">
                         <!-- <h4 class="title">Regular Image</h4> -->
                        <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                            <div class="fileinput-new thumbnail">
                            <img src="../../assets/img/image_placeholder.jpg" alt="...">
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail"></div>
                            <div>
                            <span class="btn btn-rose btn-round btn-file">
                                <span class="fileinput-new">Select image</span>
                                <span class="fileinput-exists">Change</span>
                                <input type="file" name="..." />
                            </span>
                            <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                            </div>
                        </div>
                      </div>
                    </div>
                    </br>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">App Favicon</label>
                      <div class="col-sm-10">
                         <!-- <h4 class="title">Regular Image</h4> -->
                         <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                            <div class="fileinput-new thumbnail img-circle">
                            <img src="../../assets/img/placeholder.jpg" alt="...">
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail img-circle"></div>
                            <div>
                            <span class="btn btn-round btn-rose btn-file">
                                <span class="fileinput-new">Add Photo</span>
                                <span class="fileinput-exists">Change</span>
                                <input type="file" name="..." />
                            </span>
                            <br />
                            <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                            </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">Password</label>
                      <div class="col-sm-10">
                        <div class="form-group">
                          <input type="password" class="form-control">
                        </div>
                      </div>
                    </div>
</br>
                    <button class="btn btn-success" type="submit" >Edit Details</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
@endsection