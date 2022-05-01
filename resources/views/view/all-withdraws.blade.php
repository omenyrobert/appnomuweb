@extends('layouts.header')
@section('content')

          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-primary card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">assignment</i>
                  </div>
                  
                  <div class="float-left">
                    <h4 class="card-title">Withdraw History</h4>
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
                          <th>Beneficiary</th>
                          <th>Amount</th>
                          <th>Mode</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                            <th>Date</th>
                            <th>Trans. Id</th>
                            <th>Beneficiary</th>
                            <th>Amount</th>
                            <th>Mode</th>
                            <th>Status</th>
                        </tr>
                      </tfoot>
                      <tbody>
                        <?php
                          $cates = $auth::getAllWithdraws();
                          foreach ($cates as $key) {
                            $user = $auth::getUserById($key['user_id']);
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
                            // 06 pending 07 successful 05 failed
                        ?>
                        <tr>
                          <td>{{ date('d/M/Y h:i:s A',strtotime($key['created_at']))}}</td>
                          <td>{{$key['trans_id']}}</td>
                          
                          <td>UGX. {{number_format($key['amount'])}}</td>
                          <td>{{$user[0]['name'] ?? ' '}}</td>
                          <td>{{ $key['mode']}}</td>
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