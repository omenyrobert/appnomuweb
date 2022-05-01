@extends('layouts.header')
@section('content')

          <div class="row">
          <div class="col-md-4">
              <div class="card ">
                <div class="card-header card-header-rose card-header-text">
                  <div class="card-text">
                    <h4 class="card-title">New Category</h4>
                  </div>
                </div>
                <div class="card-body ">
                  <form method="post" action="/save_loan_category" class="form-horizontal">
                    @csrf
                    <div class="row">
                      <label class="col-sm-4 col-form-label">Loan Amount</label>
                      <div class="col-sm-8">
                        <div class="form-group">
                          <input type="number" name="amount" class="form-control">
                          <span class="bmd-help">Enter the actual loan Amount</span>
                        </div>
                      </div>
                      
                    </div>
                    <div class="row">
                      <label class="col-sm-4 col-form-label">Loan Period</label>
                      <div class="col-sm-8">
                        <div class="form-group">
                          <input type="number" name="period" class="form-control">
                          <span class="bmd-help">All Periods are in Days (1 week = 7 Days)</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-4 col-form-label">Interest Rate</label>
                      <div class="col-sm-8">
                        <div class="form-group">
                          <input type="number" name="interest" class="form-control">
                          <span class="bmd-help">Enter the valid loan Interest Rate</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-4 col-form-label">Installments</label>
                      <div class="col-sm-8">
                        <div class="form-group">
                          <input type="number" name="installements" class="form-control">
                          <span class="bmd-help">Enter the valid Payment Installments</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-4 col-form-label">Processing Fee</label>
                      <div class="col-sm-8">
                        <div class="form-group">
                          <input type="number" name="processing" class="form-control">
                          <span class="bmd-help">Enter the valid loan Processing Fee</span>
                        </div>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-rose">Save Category</button>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-md-8">
              <div class="card">
                <div class="card-header card-header-primary card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">assignment</i>
                  </div>
                  
                  <div class="float-left">
                    <h4 class="card-title">Loan Categories</h4>
                  </div>
                </div>
                <div class="card-body">
                  <div class="toolbar">
                    <!--        Here you can write extra buttons/actions for the toolbar              -->
                  </div>
                  <div class="material-datatables">
                    <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                      <thead>
                        <tr>
                          <!-- <th>Category Id</th> -->
                          <th>Amount</th>
                          <th>Interest</th>
                          <th>Processing</th>
                          <th>Period</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                            <!-- <th>Category Id</th> -->
                            <th>Amount</th>
                            <th>Interest</th>
                            <th>Processing</th>
                            <th>Period</th>
                            <th>Status</th>
                        </tr>
                      </tfoot>
                      <tbody>
                        <?php
                            $cates = $auth::getAllloanCategories();
                            foreach ($cates as $key) {
                              # code...

                              if ($key['loan_period']<7) {
                                $mea = 'Days';
                                $value = $key['loan_period'];
                              }elseif ($key['loan_period']>6 && $key['loan_period']<30) {
                                $mea = 'Weeks';
                                $mea2 = 'Days';
                                $value = intdiv($key['loan_period'],7);
                                $remainder = $key['loan_period'] % 7;
                              }elseif ($key['loan_period']>29 && $key['loan_period']<365) {
                                $mea = 'Months';
                                $mea2 = 'days';
                                $value = intdiv($key['loan_period'],30);
                                $remainder = $key['loan_period'] % 30;
                              }else {
                                $mea = 'Years';
                                $mea2 = 'days';
                                $value = intdiv($key['loan_period'],365);
                                $remainder = $key['loan_period'] % 365;
                              }

                              if ($key['status']==7) {
                                # code...
                                $status = 'Active';
                                $but = 'badge-success';
                                $route = 'deactivate';
                              }elseif ($key['status']==5) {
                                # code...
                                $status = 'Not Active';
                                $but = 'badge-danger';
                                $route = 'activate';
                              }else {
                                # code...
                                $status = 'Deleted';
                                $but = 'badge-danger';
                                
                              }
                            
                        ?>
                        <tr>
                          <!-- <td>CAT001</td> -->
                          <td>{{$key['loan_amount']}}</td>
                          <td>{{$key['interest_rate']}}%</td>
                          <td>{{$key['processing_fees']}}</td>
                          <td>{{ $value }} {{ $mea ?? ' '}} {{ $remainder ?? ' '}} {{ $mea2 ?? ' ' }}</td>
                          <td><span class="badge {{$but}}">{{$status}}</span></td>
                        </tr>
                        <?php
                            }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
                <!-- end content-->
              </div>
              <!--  end card  -->
            </div>
            <!-- end col-md-12 -->
          </div>
          <!-- end row -->
        </div>
      </div>
@endsection