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
                    <h4 class="card-title">My Alliances</h4>
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
                            <th>Name</th>
                            <th>Telephone</th>
                            <th>NIN</th>
                            <th>Relationship</th>
                            <th>Status</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Telephone</th>
                            <th>NIN</th>
                            <th>Relationship</th>
                            <th>Status</th>
                        </tr>
                      </tfoot>
                      <tbody>
                        <?php
                            $ally = $auth::getAlliances(session('user_id'));
                            foreach ($ally as $key) {
                              # code...
                              if ($key['sms_verified_at']!==null) {
                                # code...
                                $status = 'Verified';
                                $badge = 'badge-success';
                              }else {
                                # code...
                                $status = 'Not erified';
                                $badge = 'badge-danger';
                              }
                        ?>
                        <tr>
                          <td>{{ $key['name'] }}</td>
                          <td>{{ $key['Phone_Number'] }}</td>
                          <td>{{ $key['NIN'] }}</td>
                          <td>{{ $key['relationship'] }}</td>
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