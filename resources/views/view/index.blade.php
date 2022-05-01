@extends('layouts.header')
@section('content')

                <?php
                    if (session('role') =='admin') {
                        # code...
                    
                ?>
                <!-- Admin Dashboard -->
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                        <div class="card-header card-header-warning card-header-icon">
                            <div class="card-icon">
                            <i class="material-icons">person</i>
                            </div>
                            <p class="card-category">All</br>Users</p>
                            <h3 class="card-title">{{ $users}}</h3>
                        </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                        <div class="card-header card-header-rose card-header-icon">
                            <div class="card-icon">
                            <i class="material-icons">credit_score</i>
                            </div>
                            <p class="card-category">Loan</br>Categories</p>
                            <h3 class="card-title">{{ $loans_cate }}</h3>
                        </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                        <div class="card-header card-header-success card-header-icon">
                            <div class="card-icon">
                            <i class="material-icons">savings</i>
                            </div>
                            <p class="card-category">Saving</br>Categories</p>
                            <h3 class="card-title">{{ $savingcategories}}</h3>
                        </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                        <div class="card-header card-header-info card-header-icon">
                            <div class="card-icon">
                            <i class="material-icons">account_balance</i>
                            </div>
                            <p class="card-category">Savings</br>Available</p>
                            <h3 class="card-title">{{ $savings }}</h3>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                        <div class="card-header card-header-rose card-header-icon">
                            <div class="card-icon">
                            <i class="material-icons">equalizer</i>
                            </div>
                            <p class="card-category">Loans</br>Running</p>
                            <h3 class="card-title">{{ $Running_loans}}</h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                            <i class="material-icons">date_range</i> {{ $Running_loans_count }} Loans
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                        <div class="card-header card-header-success card-header-icon">
                            <div class="card-icon">
                            <i class="material-icons">store</i>
                            </div>
                            <p class="card-category">Loan</br>Requests</p>
                            <h3 class="card-title">{{ $loan_requests}}</h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                            <i class="material-icons">date_range</i> {{ $loan_requests_count}} Loans
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card ">
                            <div class="card-header card-header-success card-header-icon">
                                <div class="card-icon">
                                <i class="material-icons"></i>
                                </div>
                                <h4 class="card-title">Top Most Saling Loan Categories</h4>
                            </div>
                            <div class="card-body ">
                                <div class="row">
                                <div class="col-md-6">
                                    <div class="table-responsive table-sales">
                                    <table class="table">
                                        <tbody>
                                            <?php
                                            $i = 0;
                                            $j = 0;
                                            $k = 0;
                                            $array = arsort(session('dashboard')['loanchart']);
                                            // print_r(session('dashboard')['loanchart']);
                                                foreach (session('dashboard')['loanchart'] as $key => $value) {
                                                    # code...
                                                    $k= $k + ($value*$key);
                                                }

                                                foreach (session('dashboard')['loanchart'] as $key=>$value) { 
                                                    $i++;
                                                    
                                                    if ($j<11) {
                                                        # code...
                                                        $j++; 
                                                        $per = (($value*$key)/$k)*100;
                                            ?>
                                        <tr>
                                            <td>
                                            <div class="flag">
                                                <img src="/assets/img/flags/US.png"> </div>
                                            </td>
                                            <td>{{$key}}</td>
                                            <td class="text-right">
                                           {{ $value* $key}}
                                            </td>
                                            <td class="text-right">
                                            {{$per ?? ' '}}%
                                            </td>
                                        </tr>
                                            <?php
                                                }}

                                                if ($i<1) {
                                                
                                            
                                            ?>
                                        <tr>
                                            <td>
                                                <center>
                                            <span class="badge badge-success">
                                                    <h5>No Categories Saved</h5>
                                            </span>
                                                </center>
                                            </td>
                                        </tr>
                                            <?php
                                                }
                                            
                                            ?>
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                                <div class="col-md-6 ml-auto mr-auto">
                                    <div class="card card-chart">
                                        <div class="card-header card-header-success" data-header-animation="true">
                                            <div class="ct-chart" id="dailySalesChart"></div>
                                        </div>
                                        <div class="card-body">
                                            <div class="card-actions">
                                            <button type="button" class="btn btn-danger btn-link fix-broken-card">
                                                <i class="material-icons">build</i> Fix Header!
                                            </button>
                                            <button type="button" class="btn btn-info btn-link" rel="tooltip" data-placement="bottom" title="Refresh">
                                                <i class="material-icons">refresh</i>
                                            </button>
                                            <button type="button" class="btn btn-default btn-link" rel="tooltip" data-placement="bottom" title="Change Date">
                                                <i class="material-icons">edit</i>
                                            </button>
                                            </div>
                                            <h4 class="card-title">Weekly Loan Data</h4>
                                            <p class="card-category">
                                            <span class="text-success"><i class="fa fa-long-arrow-up"></i> 45% </span> increase in today sales.</p>
                                        </div>
                                        <div class="card-footer">
                                            <div class="stats">
                                            <i class="material-icons">access_time</i> updated 14 minutes ago
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Dashboard -->
                <?php
                    }else {
                        # code...
                    
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card ">
                            <div class="card-header card-header-success card-header-icon">
                                <div class="card-icon">
                                <i class="material-icons">savings</i>
                                </div>
                                <div class="float-left">
                                    <h4 class="card-title">My Savings Account</h4>
                                </div>
                                
                                <div class="float-right" style="margin:5px;">
                                    <a class="btn btn-warning" href="/savings">Deposit Money</a>
                                    <a class="btn btn-success" href="/withdraws">Withdraw Money</a>
                                </div>
                            </div>
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="card card-stats">
                                            <div class="card-header card-header-warning card-header-icon">
                                                <div class="card-icon">
                                                <i class="material-icons">account_balance_wallet</i>
                                                </div>
                                                <p class="card-category">Available</br>Balance</p>
                                                <h3 class="card-title">{{ session('dashboard')['user-accounts'][0]['available_balance'] ?? '0'}}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="card card-stats">
                                            <div class="card-header card-header-rose card-header-icon">
                                                <div class="card-icon">
                                                <i class="material-icons">account_balance</i>
                                                </div>
                                                <p class="card-category">Ledger</br>Balance</p>
                                                <h3 class="card-title">{{ session('dashboard')['user-accounts'][0]['Ledger_Balance'] ?? '0'}}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="card card-stats">
                                        <div class="card-header card-header-success card-header-icon">
                                            <div class="card-icon">
                                            <i class="material-icons">savings</i>
                                            </div>
                                            <p class="card-category">Total</br>Saved</p>
                                            <h3 class="card-title">{{ session('dashboard')['user-accounts'][0]['Total_Saved'] ?? '0'}}</h3>
                                        </div>
                                    
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="card card-stats">
                                        <div class="card-header card-header-info card-header-icon">
                                            <div class="card-icon">
                                            <i class="material-icons">money_off</i>
                                            </div>
                                            <p class="card-category">Amount</br>Withdrawn</p>
                                            <h3 class="card-title">{{ session('dashboard')['user-accounts'][0]['Amount_Withdrawn'] ?? '0'}}</h3>
                                        </div>
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card ">
                            <div class="card-header card-header-success card-header-icon">
                                <div class="card-icon">
                                <i class="material-icons"></i>
                                </div>
                                <div class="float-left">
                                    <h4 class="card-title">My Loans</h4>
                                </div>
                            </div>
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="card card-stats">
                                                    <div class="card-header card-header-warning card-header-icon">
                                                        <div class="card-icon">
                                                        <i class="material-icons">credit_score</i>
                                                        </div>
                                                        <p class="card-category">Loan</br>Balance</p>
                                                        <h3 class="card-title">UGX {{ session('dashboard')['user-accounts'][0]['Loan_Balance'] ?? '0'}}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="card card-stats">
                                                    <div class="card-header card-header-rose card-header-icon">
                                                        <div class="card-icon">
                                                        <i class="material-icons">money_off_csred</i>
                                                        </div>
                                                        <p class="card-category">Outstanding</br>Balance</p>
                                                        <h3 class="card-title">UGX {{ session('dashboard')['user-accounts'][0]['Outstanding_Balance'] ?? '0'}}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="card card-stats">
                                                <div class="card-header card-header-success card-header-icon">
                                                    <div class="card-icon">
                                                    <i class="material-icons">paid</i>
                                                    </div>
                                                    <p class="card-category">Total</br>Paid</p>
                                                    <h3 class="card-title">UGX {{ session('dashboard')['user-accounts'][0]['Total_Paid'] ?? '0'}}</h3>
                                                </div>
                                            
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="card card-stats">
                                                <div class="card-header card-header-info card-header-icon">
                                                    <div class="card-icon">
                                                    <i class="material-icons">do_not_disturb</i>
                                                    </div>
                                                    <p class="card-category">Loan</br>Limit</p>
                                                    <h3 class="card-title">UGX {{ session('dashboard')['user-accounts'][0]['Loan_Limit'] ?? '0'}}</h3>
                                                </div>
                                                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-9 col-md-12 col-sm-12">
                                        <div class="card">
                                            <div class="card-header card-header-icon card-header-rose">
                                                <div class="card-icon">
                                                <i class="material-icons">assignment</i>
                                                </div>
                                                <h4 class="card-title ">My Loan History</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead class=" text-primary">
                                                            <th>
                                                                Loan</br>ID
                                                            </th>
                                                            <th>
                                                                Loan</br>Amount 
                                                            </th>
                                                            <th>
                                                                Amount</br>Paid 
                                                            </th>
                                                            <th>
                                                                Loan</br>Date
                                                            </th>
                                                            <th>
                                                                Due</br>Date
                                                            </th>
                                                            <th>
                                                                Loan</br>Status
                                                            </th>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $i = 0;
                                                                foreach ($user->loans as $key) {
                                                                # code...
                                                                    $i++;
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
                                                                <td>
                                                                {{$key['ULoan_Id']}}
                                                                </td>
                                                                <td>
                                                                UGX.{{number_format($key['loan_amount'])}}
                                                                </td>
                                                                <td>
                                                                UGX.{{number_format($key['loan_amount'])}}
                                                                </td>
                                                                <td>
                                                                {{ date('l, d/M/Y', strtotime($key['created_at']))}}
                                                                </td>
                                                                <td>
                                                                {{ date('l, d/M/Y', $key['dueDate'])}}
                                                                </td>
                                                                <td>
                                                                <span class="badge {{$badge}}">
                                                                    {{$status }}
                                                                </span>
                                                                
                                                                </td>
                                                            </tr>
                                                            <?php
                                                            
                                                                }
                                                                if ($i<1) {
                                                                    
                                                                
                                                            ?>
                                                            <tr>
                                                                
                                                                <td colspan="6">
                                                                    <center>
                                                                <span class="badge badge-success">
                                                                    <h5>
                                                                        You Have No Outstanding Loans
                                                                    </h5>
                                                                </span>
                                                                </center>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    }
                ?>
            </div>
        </div>
    </div>
@endsection