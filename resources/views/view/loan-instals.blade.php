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
                <h4 class="card-title">All User Loans</h4>
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
                        <th>Loan Id</th>
                        <th>Installment Number</th>
                        <th>Amount To Paid</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>Loan Id</th>
                        <th>Installment Number</th>
                        <th>Amount To Paid</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                    <tbody>
                        <?php
                            $cates = $auth::getloanInstalls(session('user_id'));
                            foreach ($cates as $key) {
                                
                                $lons = $auth::getLoanByCatID2($key['ULoan_Id']);

                                if (sizeof($lons)>0) {
                                    # code...
                                
                                $lonx = $auth::getLoanCatID($lons[0]['loan_amount']);

                                if ($key['status']==7) {
                                    $status = 'Paid';
                                    $but = 'badge-success';
                                }elseif ($key['status']==5) {
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
                        <td>{{$key['Amount_Paid']+$lonx[0]['processing_fees']+((($lonx[0]['interest_rate']/100)*$key['Amount_Paid']))}}</td>
                        <td>{{ date('d/M/Y',$key['pay_day'])}}</td>
                        <td><span class="badge {{$but}}">{{$status}}</span></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-rose dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Select Action
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <?php
                                        if ($key['status']==6) {
                                            # code...
                                        
                                    ?>
                                    <a class="dropdown-item" href="/pay_loan?l={{$key['id']}}&m=momo">Pay With Mobile Money </a>
                                    <?php
                                        }

                                        if ($key['status']==6) {
                                            # code...
                                        
                                    ?>
                                    <a class="dropdown-item" href="/pay_loan?l={{$key['id']}}&m=dash">Pay With Dashboard </a>
                                    <?php
                                        }
                                    ?>
                                    <!-- <a class="dropdown-item" href="/wait?l={{$key['ULoan_Id']}}">Send To Pending </a> sssssssssssssssssssss-->
                                    <!-- <a class="dropdown-item" href="/loan-view?l={{$key['ULoan_Id']}}">View Loan</a> -->
                                </div>
                            </div>
                        </td>

                    </tr>
                        <?php
                            }}
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