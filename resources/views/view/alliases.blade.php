@extends('layouts.header')
@section('content')

          <div class="row">
            <div class="col-md-8">
              <div class="card">
                <div class="card-header card-header-icon card-header-rose">
                  <div class="card-icon">
                    <i class="material-icons">perm_identity</i>
                  </div>
                  <h4 class="card-title">Save Alliance
                  </h4>
                </div>
                <div class="card-body">
                  <?php
                    if (session('code')==null) {
                      # code...
                    
                  ?>
                  <form method="post" action="/save-alliaces">
                    @csrf
                    <div class="row">
                      <div class="col-md-5">
                        <div class="form-group">
                          <label class="bmd-label-floating">Name</label>
                          <input type="text" name="name" class="form-control" >
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="bmd-label-floating">Telephone (+2567xxxxxxxx)</label>
                          <input type="text" name="telephone" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="bmd-label-floating">Relationship</label>
                          <input type="text" name="relationship" class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">NIN Number</label>
                          <input type="text" name="nin" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">National ID Card Number</label>
                          <input type="text" name="card_no" class="form-control">
                        </div>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-warning pull-right">Save Alliance</button>
                    <div class="clearfix"></div>
                  </form>
                  <?php
                    }else {
                      # code...
                  ?>
                  <form method="post" action="/confirm-alliaces">
                    @csrf
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="bmd-label-floating">Enter Confirmation Code</label>
                          <input type="text" name="token" class="form-control" >
                        </div>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-warning pull-right">Confirm Alliance</button>
                    <div class="clearfix"></div>
                  </form>
                  <?php
                    }
                  ?>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card card-profile">
                <div class="card-avatar">
                  <a href="#pablo">
                    <img class="img" src="/assets/img/default-avatar.png" />
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
        </div>
      </div>
@endsection