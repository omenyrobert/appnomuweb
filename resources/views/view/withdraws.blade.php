@extends('layouts.header')
@section('content')

          <div class="row">
          <div class="col-md-4">
              <div class="card ">
                <div class="card-header card-header-rose card-header-text">
                  <div class="card-text">
                    <h4 class="card-title">Make A Withdraw</h4>
                  </div>
                </div>
                <div class="card-body ">
                  <form method="post" action="/rave/withdraws" class="form-horizontal">
                    @csrf
                    <div class="row">
                      <label class="col-sm-4 col-form-label">Amount</label>
                      <div class="col-sm-8">
                        <div class="form-group">
                          <input type="number" name="amount" class="form-control">
                          <span class="bmd-help">Enter the valid Amount To Withdraw</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-4 col-form-label">Select Withdraw Account</label>
                      <div class="col-sm-8">
                        <div class="form-group">
                          <select name="account" id="" class="form-control">
                              <option value="">Select Account</option>
                              <option value="savings">Savings</option>
                              <!-- <option value="loans">Loan Account</option> -->
                          </select>
                        </div>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-rose">Make Withdraw</button>
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
                    <h4 class="card-title">My Withdraw History</h4>
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
                          <th>Date</th>
                          <th>Trans. Id</th>
                          <th>Amount</th>
                          <th>Mode</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                            <th>Date</th>
                            <th>Trans. Id</th>
                            <th>Amount</th>
                            <th>From</th>
                            <th>Status</th>
                        </tr>
                      </tfoot>
                      <tbody>
                        <?php
                          $cates = $auth::getMyWithdraws(session('user_id'));
                          foreach ($cates as $key) {
                            if ($key['status']==5) {
                              # code...
                              $badge = 'badge-rose';
                              $stat = 'FAILED';
                            }elseif ($key['status']==6) {
                              # code...
                              $badge = 'badge-warning';
                              $stat = 'PENDING';
                            }elseif ($key['status']==7) {
                              # code...
                              $badge = 'badge-success';
                              $stat = 'SUCCESSFUL';
                            }
                        ?>
                        <tr>
                          <td>{{ date('d/M/Y h:i:s A',time())}}</td>
                          <td>{{ $key['trans_id']}}</td>
                          <td>UGX. {{number_format($key['amount'])}}</td>
                          <td>{{ $key['withdraw_from'] }}</td>
                          <td><span class="badge {{$badge}}">{{$stat}}</span></td>
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