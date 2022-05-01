@extends('layouts.header')
@section('content')

          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-primary card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">chat</i>
                  </div>
                  <h4 class="card-title">Sent SMS</h4>
                </div>
                <div class="card-body">
                  <div class="toolbar">
                    <!--        Here you can write extra buttons/actions for the toolbar              -->
                  </div>
                  <div class="material-datatables">
                    <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                      <thead>
                        <tr>
                          <th>From</th>
                          <th>To</th>
                          <th>Title</th>
                          <th>Date</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                            <th>From</th>
                          <th>To</th>
                          <th>Title</th>
                          <th>Date</th>
                        </tr>
                      </tfoot>
                      <tbody>
                          <?php
                            $sms = $auth::getSentSMS();
                            foreach ($sms as $key) {
                                $user = $auth::getUserById($key['user_Id']);
                          ?>
                        <tr>
                          <td>{{$user[0]['name'] ?? ' '}}</td>
                          <td>{{$key['To']}}</td>
                          <td>{{$key['Title']}}</td>
                          <td>{{ date('l,d-m-Y',strtotime($key['created_at']))}}</td>
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