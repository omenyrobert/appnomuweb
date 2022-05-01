@extends('layouts.header')
@section('content')

          <div class="row">
            <div class="col-md-8">
                <div class="material-datatables">
                    <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                        <thead>
                        <tr>
                            <th>Loan Id</th>
                            <th>Installment Number</th>
                            <th>Amount To Paid</th>
                            <th>Due Date</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>Loan Id</th>
                            <th>Installment Number</th>
                            <th>Amount To Paid</th>
                            <th>Due Date</th>
                            <th>Status</th>
                        </tr>
                        </tfoot>
                        <tbody>
                            <?php
                                
                                foreach ($loan_installs as $key) {
                                    
                                    if ($key['status']==7) {
                                        # code...
                                        $status = 'Paid';
                                        $but = 'badge-success';
                                    }elseif ($key['status']==5) {
                                        # code...
                                        $status = 'Over Due';
                                        $but = 'badge-rose';
                                    }elseif ($key['status']==6) {
                                        $status = 'Pending';
                                        $but = 'badge-warning';
                                    }
                            ?>
                        <tr>
                            <td>{{$key['ULoan_Id']}}</td>
                            <td>{{$key['Installement_No']}}</td>
                            <td>{{$key['Amount_Paid']}}</td>
                            <td>{{ date('d/M/Y',$key['pay_day'])}}</td>
                            <td><span class="badge {{$but}}">{{$status}}</span></td>


                        </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
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
                        if ($User[0]['sms_verified_at'] == null) {
                            # code...
                            $status = 'Not Verified';
                            $badge = 'badge-rose';
                        }else {
                            # code...
                            $status = 'Verified';
                            $badge = 'badge-success';
                        }
                    
                    ?>
                  <h6 class="card-category text-gray">{{$User[0]['user_id']}}</h6>
                  <h4 class="card-title">{{$User[0]['name'] ?? ' '}}</h4>
                  <h4 class="card-title">{{$User[0]['NIN'] ?? ' '}}</h4>
                  <h4 class="card-title">{{$User[0]['card_no'] ?? ' '}}</h4>
                  <h4 class="card-title">{{$User[0]['address'] ?? ' '}}</h4>
                  <h4 class="card-title">{{$User[0]['refferer'] ?? ' '}}</h4>
                  <h4 class="card-title">{{$User[0]['telephone']}} <span class="badge {{ $badge }}">{{ $status }}</span> </h4>
                  <h4 class="card-title">{{$User[0]['email']}}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
@endsection