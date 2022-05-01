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
                        <th>User Id</th>
                        <th>User Name</th>
                        <th>Loan Amount</th>
                        <th>Amount Paid</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>User Id</th>
                        <th>User Name</th>
                        <th>Loan Amount</th>
                        <th>Amount Paid</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                    <tbody>
                        <?php
                            $cates = $auth::getAllLoans();
                            foreach ($cates as $key) {
                                $user = $auth::getUserById($key['user_id']);
                                //statues 7 paid 6 Ongoing 5 requested 8 approved 9 over-due
                                if ($key['status']==7) {
                                    # code...
                                    $status = 'Paid';
                                    $but = 'badge-success';
                                  }elseif ($key['status']==5) {
                                    # code...
                                    $status = 'Requested';
                                    $but = 'badge-warning';
                                  }elseif ($key['status']==6) {
                                    $status = 'Ongoing';
                                    $but = 'badge-primary';
                                  } elseif ($key['status']==8) {
                                    $status = 'approved';
                                    $but = 'badge-secondary';
                                  }elseif ($key['status']==9) {
                                    $status = 'Over Due';
                                    $but = 'badge-rose';
                                  } else {
                                    # code...
                                    $status = 'Denied';
                                    $but = 'badge-rose';
                                    
                                  }
                        ?>
                    <tr>
                        <td>{{$key['ULoan_Id'] ?? ' '}}</td>
                        <td>{{$user[0]['name'] ?? ' '}}</td>
                        <td>{{$key['loan_amount'] ?? ' '}}</td>
                        <td>{{$key['amount_paid'] ?? ' '}}</td>
                        <td>{{ date('d/M/Y h:i:s A',$key['dueDate']) ?? ' '}}</td>
                        <td><span class="badge {{$but ?? ' '}}">{{$status ?? ' '}}</span></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-rose dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Select Action
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <?php
                                        if ($key['status']==5) {
                                            # code...
                                        
                                    ?>
                                    <a class="dropdown-item" href="/approve?l={{$key['ULoan_Id']}}">Approve Loan</a>
                                    <?php
                                        }

                                        if ($key['status']==5) {
                                            # code...
                                        
                                    ?>
                                    <a class="dropdown-item" href="/deny?l={{$key['ULoan_Id']}}">Deny Loan</a>
                                    <?php
                                        }
                                    ?>
                                    <!-- <a class="dropdown-item" href="/wait?l={{$key['ULoan_Id']}}">Send To Pending </a> -->
                                    <a class="dropdown-item" href="/loan-view?l={{$key['ULoan_Id']}}">View Loan</a>
                                </div>
                            </div>
                        </td>

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