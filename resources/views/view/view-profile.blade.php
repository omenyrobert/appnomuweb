@extends('layouts.header')
@section('content')

          <div class="row">
            <div class="col-md-8">
              <div class="card">
                <div class="card-header card-header-icon card-header-warning">
                  <div class="card-icon">
                    <i class="material-icons">savings</i>
                  </div>
                  <h4 class="card-title">Savings </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card card-stats">
                                <div class="card-header card-header-warning card-header-icon">
                                    
                                    <p class="card-category">Available</br>Balance</p>
                                    <h3 class="card-title">{{ $user_accounts[0]['available_balance'] ?? '0'}}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card card-stats">
                                <div class="card-header card-header-rose card-header-icon">
                                    
                                    <p class="card-category">Ledger</br>Balance</p>
                                    <h3 class="card-title">{{ $user_accounts['user-accounts'][0]['Ledger_Balance'] ?? '0'}}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card card-stats">
                            <div class="card-header card-header-success card-header-icon">
                                
                                <p class="card-category">Total</br>Saved</p>
                                <h3 class="card-title">{{ $user_accounts[0]['Total_Saved'] ?? '0'}}</h3>
                            </div>
                        
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card card-stats">
                            <div class="card-header card-header-info card-header-icon">
                                
                                <p class="card-category">Amount</br>Withdrawn</p>
                                <h3 class="card-title">{{ $user_accounts[0]['Amount_Withdrawn'] ?? '0'}}</h3>
                            </div>
                            
                            </div>
                        </div>
                    </div>
                </div>
              </div>
              <div class="row">
                  <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-icon card-header-rose">
                            <div class="card-icon">
                                <i class="material-icons">credit_score</i>
                            </div>
                            <h4 class="card-title">Loans 
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card card-stats">
                                        <div class="card-header card-header-warning card-header-icon">
                                            
                                            <p class="card-category">Loan</br>Balance</p>
                                            <h3 class="card-title">{{ $user_accounts[0]['Loan_Balance'] ?? '0'}}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card card-stats">
                                        <div class="card-header card-header-rose card-header-icon">
                                            
                                            <p class="card-category">Ledger</br>Balance</p>
                                            <h3 class="card-title">{{ $user_accounts[0]['Outstanding_Balance'] ?? '0'}}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card card-stats">
                                    <div class="card-header card-header-success card-header-icon">
                                        
                                        <p class="card-category">Total</br>Saved</p>
                                        <h3 class="card-title">{{ $user_accounts[0]['Total_Paid'] ?? '0'}}</h3>
                                    </div>
                                
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card card-stats">
                                    <div class="card-header card-header-info card-header-icon">
                                        
                                        <p class="card-category">Loan</br>Limit</p>
                                        <h3 class="card-title">{{ $user_accounts[0]['Loan_Limit'] ?? '0'}}</h3>
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
                    <div class="card">
                        <div class="card-header card-header-icon card-header-rose">
                            <div class="card-icon">
                                <i class="material-icons">people</i>
                            </div>
                            <h4 class="card-title">Alliances 
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php
                                    $all = $auth::getAlliances($_GET['u']);
                                    foreach ($all as $key ) {
                                        # code...
                                    
                                ?>
                                <div class="col-md-4">
                                    <div class="card card-profile">
                                        <div class="card-body">
                                            <?php
                                                if ($key['sms_verified_at'] == null) {
                                                    # code...
                                                    $status = 'Not Verified';
                                                    $badge = 'badge-rose';
                                                }else {
                                                    # code...
                                                    $status = 'Verified';
                                                    $badge = 'badge-success';
                                                }
                                            
                                            ?>
                                        <h4 class="card-title">{{$key['name'] ?? ' '}}</h4>
                                        <h6 class="card-category text-gray">{{$key['user_id']}}</h6>
                                        <h4 class="card-title">{{$key['NIN'] ?? ' '}}</h4>
                                        <h4 class="card-title">{{$key['card_no'] ?? ' '}}</h4>
                                        <h4 class="card-title">{{$key['relationship'] ?? ' '}}</h4>
                                        <h4 class="card-title">{{$key['Phone_Number']}} <span class="badge {{ $badge }}">{{ $status }}</span> </h4>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                  </div>

              </div>
              <div class="row">
                <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">assignment</i>
                    </div>
                    
                    <div class="float-left">
                        <h4 class="card-title">Refferals</h4>
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
                            $reffera = $auth::getRefferals($_GET['u']);
                            foreach ($reffera  as $key) {
                                # code...
                                $reffs = $auth::getUserById($key['user_id']);
                                $cash = $auth::getReffCommisisons($_GET['u'],$key['user_id']);
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
            </div>
            <div class="col-md-4">
              <div class="card card-profile">
                <div class="card-avatar">
                  <a href="#pablo">
                    <img class="img" src="{{$user_idnt['passport'] ?? '/assets/img/default-avatar.png'}}" />
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
                
              <div class="card card-profile">
                <div class="card-body">
                    <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                        <div class="fileinput-new thumbnail" >
                            <img src="{{$user_idnt['front_face'] ?? '../../uploads/ids/id1.png'}}" alt="..." >
                        </div>
                    </div>
                  <h4 class="card-title">ID FRONT FACE</h4>
                </div>
              </div>
              <div class="card card-profile">
                <div class="card-body">
                    <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                        <div class="fileinput-new thumbnail" >
                            <img src="{{$user_idnt['back_face'] ?? '../../uploads/ids/id1.png'}}" alt="..." >
                        </div>
                    </div>
                  <h4 class="card-title">ID BACK FACE</h4>
                </div>
              </div>
              <div class="card card-profile">
                <div class="card-body">
                    <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                        <div class="fileinput-new thumbnail" >
                            <img src="{{$user_idnt['passport'] ?? '../../uploads/ids/id1.png'}}" alt="..." >
                        </div>
                    </div>
                  <h4 class="card-title">PASSPORT PHOTO</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
@endsection