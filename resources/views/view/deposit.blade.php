@extends('layouts.header')
@section('content')

          <div class="row">
            <div class="col-md-12">
              <div class="card ">
                <div class="card-header card-header-rose card-header-text">
                  <div class="card-text">
                    <h4 class="card-title">Make Deposit</h4>
                  </div>
                </div>
                <div class="card-body ">
                  <form method="get" action="#" class="form-horizontal">
                    <div class="row">
                      <label class="col-sm-2 col-form-label">Mobile Money Names</label>
                      <div class="col-sm-10">
                        <div class="form-group">
                          <input type="text" class="form-control">
                          <span class="bmd-help">Enter the valid Mobile Money Names</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">Select Saving Period</label>
                      <div class="col-sm-10">
                        <div class="form-group">
                          <select name="" id="" class="form-control">
                              <option value="">Select Period</option>
                              <option value="">1 Month</option>
                              <option value="">2 Months</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-rose">Make Payment</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
@endsection