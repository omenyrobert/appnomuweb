@extends('layouts.header')
@section('content')

          <div class="row">
          <div class="col-md-5">
              <div class="card ">
                <div class="card-header card-header-rose card-header-text">
                  <div class="card-text">
                    <h4 class="card-title">Get Loan</h4>
                  </div>
                </div>
                <div class="card-body ">
                  <form method="post" action="/request_loan" class="form-horizontal">
                  @csrf
                    <div class="row">
                      <label class="col-sm-4 col-form-label">Select Loan Type</label>
                      <div class="col-sm-8">
                        <div class="form-group">
                          <select name="category" id="" class="form-control">
                              <option value="">Select Loan Type</option>
                              <?php
                                $loan_Cates = $auth::getAllloanCategories();

                                foreach ($loan_Cates as $key) {
                                  # code...
                                
                              ?>
                              <option value="{{$key['loan_id']}} ">{{$key['loan_amount']}} - {{$key['loan_period']}} @ {{$key['interest_rate']}}% </option>
                              <?php
                                }
                              ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-rose">Request A Loan</button>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-md-7">
              <div class="card">
                <div class="card-header card-header-primary card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">assignment</i>
                  </div>
                  
                  <div class="float-left">
                    <h4 class="card-title">My Loan History</h4>
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
                          <th>Due Date</th>
                          <th>Loan. Id</th>
                          <th>Loan Amount</th>
                          <th>Amount Paid</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                            <th>Due Date</th>
                            <th>Loan. Id</th>
                            <th>Loan Amount</th>
                            <th>Amount Paid</th>
                            <th>Status</th>
                        </tr>
                      </tfoot>
                      <tbody>
                        <?php
                          foreach (session('dashboard')['user-loans'] as $key ) {
                            # code... 
                            #Statues 7 paid 6 approved 5 requested 4 overdue 3 Denied 2 Waiting
                            if ($key['status']==7) {
                              # code...
                              $status = 'Paid';
                              $badge = 'badge-success';
                              
                            }elseif ($key['status']==6) {
                              # code...
                              $status = 'Approved';
                              $badge = 'badge-primary';

                            }elseif ($key['status']==5) {
                              # code...
                              $status = 'Requested';
                              $badge = 'badge-secondary';

                            }elseif ($key['status']==4) {
                              # code...
                              $status = 'Overdued';
                              $badge = 'badge-rose';

                            }elseif ($key['status']==3) {
                              # code...
                              $status = 'Denied';
                              $badge = 'badge-danger';

                            }elseif ($key['status']==2) {
                              # code...
                              $status = 'Warning';
                              $badge = 'badge-warning';

                            }
                        ?>
                        <tr>
                          <td>{{ date('d/M/Y',$key['dueDate'])}}</td>
                          <td>{{$key['ULoan_Id']}}</td>
                          <td>{{$key['loan_amount']}}</td>
                          <td>{{$key['amount_paid']}} </td>
                          <td><span class="badge {{$badge}} ">{{ $status }}</span></td>
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