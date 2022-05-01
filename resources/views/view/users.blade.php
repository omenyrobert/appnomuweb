@extends('layouts.header')
@section('content')

        <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header card-header-primary card-header-icon">
                <div class="card-icon">
                <i class="material-icons">person</i>
                </div>
                
                <div class="float-left">
                <h4 class="card-title">All Users</h4>
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
                        <th>User Id</th>
                        <th>User Name</th>
                        <th>Phones</th>
                        <th>Email</th>
                        <th>Date Joined</th>
                        <th>Refferer</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>User Id</th>
                        <th>User Name</th>
                        <th>Phones</th>
                        <th>Email</th>
                        <th>Date Joined</th>
                        <th>Refferer</th>
                        <th>Status</th>
                    </tr>
                    </tfoot>
                    <tbody>
                        <?php
                            $cates = $auth::getAllUsers();
                            foreach ($cates as $key) {
                                # code...
                            if ($key['sms_verified_at']==null) {
                                $status = 'Not Verified';
                                $badge ='badge-rose';
                                
                            }else {
                                $status = 'Verified';
                                $badge ='badge-success';
                            }

                            //get refferals 
                            if ($key['refferer']==null) {
                                # code...
                                $ref = 'System';
                            }else {
                                $refx = $auth::getUserById($key['refferer']);
                                if (sizeof($refx)>0) {
                                    $ref = $refx[0]['name'];
                                }else {
                                    $ref = 'System';
                                }
                                
                            }
                        ?>

                        
                    <tr>
                        <td> <a href="/user-profile?u={{ $key['user_id']}}">{{ $key['user_id']}}</a> </td>
                        <td>{{ $key['name']}}</td>
                        <td>{{ $key['telephone']}}</td>
                        <td>{{ $key['email']}}</td>
                        <td>{{ date('l,d/m/Y',strtotime($key['created_at']))}}</td>
                        <td>{{ $ref }}</td>
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