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
                    <h4 class="card-title">My Refferals</h4>
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
                          <th>User Name</th>
                          <th>User Email</th>
                          <th>User Telephone</th>
                          <th>Date Joined</th>
                          <th>Refferal Points</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                          <th>User Name</th>
                          <th>User Email</th>
                          <th>User Telephone</th>
                          <th>Date Joined</th>
                          <th>Refferal Points</th>
                        </tr>
                      </tfoot>
                      <tbody>
                        <?php
                          $reffera = $auth::getRefferals(session('user_id'));
                          foreach ($reffera  as $key) {
                            # code...
                            $reffs = $auth::getUserById($key['user_id']);
                            $cash = $auth::getReffCommisisons(session('user_id'),$key['user_id']);
                        ?>
                        <tr>
                          <td>{{ $reffs[0]['name'] ?? 'Not Known'}}</td>
                          <td>{{ $reffs[0]['email'] ?? 'Not Known'}}</td>
                          <td>{{ $reffs[0]['telephone'] ?? 'Not Known'}}</td>
                          <td>{{ date('l,d-m-Y',strtotime($reffs[0]['created_at']))?? 'Not Known'}}</td>
                          <td>{{ $cash ?? ' '}}</td>
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