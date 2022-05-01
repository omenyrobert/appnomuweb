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
                    <h4 class="card-title">Saving History</h4>
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
                          <th>User</th>
                          <th>Amount</th>
                          <th>Interest</th>
                          <th>Due Date</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                            <th>Date</th>
                            <th>Trans. Id</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Interest</th>
                            <th>Due Date</th>
                            <th>Status</th>
                        </tr>
                      </tfoot>
                      <tbody>
                        <?php
                            $savin = $auth::getAllSavings();
                            foreach ($savin as $key) {
                              # code...
                              $user = $auth::getUserById($key['user_id']);
                              if ($key['status']==5) {
                                # code...
                                $status = 'Initiated';
                                $badge = 'badge-primary';
                              }elseif ($key['status']==7) {
                                # code...
                                $status = 'Running';
                                $badge = 'badge-success';
                              }elseif ($key['status']==8) {
                                # code...
                                $status = 'Paid';
                                $badge = 'badge-warning';
                              }elseif ($key['status']==9) {
                                # code...
                                $status = 'Failed';
                                $badge = 'badge-danger';
                              }
                        ?>
                        <tr>
                          <td>{{ date('d/M/Y h:i:s A',$key['savingdate'])}}</td>
                          <td>{{ $key['saving_id'] }}</td>
                          <td>{{$user[0]['name']}}</td>
                          <td>{{ $key['amount'] }}</td>
                          <td>{{ $key['Interest'] }}</td>
                          <td>{{ date('d/M/Y h:i:s A',$key['duedate'])}}</td>
                          <td><span class="badge {{$badge}}">{{$status}}</span></td>
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