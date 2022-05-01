@extends('layouts.header')
@section('content')

          <div class="row">
            <div class="col-md-12">
              <div class="card ">
                <div class="card-header card-header-rose card-header-text">
                  <div class="card-text">
                    <h4 class="card-title">Send SMS</h4>
                  </div>
                </div>
                <div class="card-body ">
                  <form method="Post" action="/sendSms" class="form-horizontal">
                  @csrf
                    <div class="row">
                      <label class="col-sm-2 col-form-label">Message Title</label>
                      <div class="col-sm-10">
                        <div class="form-group">
                          <input type="text" name="title" class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">Contact List</label>
                      <div class="col-sm-10">
                        <div class="form-group">
                          <select name="list" id="" class="form-control" required>
                              <option value="">Select Contact List To Send To</option>
                              <?php
                                $lis = $auth::getAllContactList();
                                foreach ($lis as $key) {
                                    # code...
                                
                              ?>
                              <option value="{{$key['List_id']}}">{{$key['List_Name']}} - {{$key['List_id']}}</option>
                              <?php
                                }
                              ?>
                              
                              <option value="New">New Contact List</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">Phone Numbers</label>
                      <div class="col-sm-10">
                        <div class="form-group">
                            <textarea name="telephones" id="" cols="30" rows="10" class="form-control" ></textarea>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <label class="col-sm-2 col-form-label"><strong>Enter Message Here (200 Characters)</strong></label>
                      <div class="col-sm-10">
                        <div class="form-group">
                            <textarea name="message" id="" cols="30" rows="10" class="form-control" required></textarea>
                        </div>
                      </div>
                    </div>
                    <center>
                        <button type="submit" class="btn btn-rose">Send SMS</button>
                    </center>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
@endsection