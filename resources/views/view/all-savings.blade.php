@extends('layouts.header')
@section('content')

          <div class="row">
          <div class="col-md-5">
              <div class="card ">
                <div class="card-header card-header-rose card-header-text">
                  <div class="card-text">
                    <h4 class="card-title">Make Deposit</h4>
                  </div>
                </div>
                <div class="card-body ">
                  <form method="post" action="{{route('pay')}}" class="form-horizontal">
                    @csrf
                    <div class="row">
                      <label class="col-sm-4 col-form-label"> Saving Category</label>
                      <div class="col-sm-8">
                        <div class="form-group">
                          <select name="category" id="" class="form-control">
                            <option value="">Choose Saving Category</option>
                            <?php
                              $cates = $auth::getAllSavingSubcategories();
                              foreach ($cates as $key) {
                                  $cat_det = $auth::getSavingCatByCatId($key['cate_id']);

                                  if ($key['Saving_Period']<7) {
                                    $mea = 'Days';
                                    $value = $key['Saving_Period'];
                                  }elseif ($key['Saving_Period']>6 && $key['Saving_Period']<30) {
                                    $mea = 'Weeks';
                                    $mea2 = 'Days';
                                    $value = intdiv($key['Saving_Period'],7);
                                    $remainder = $key['Saving_Period'] % 7;
                                  }elseif ($key['Saving_Period']>29 && $key['Saving_Period']<365) {
                                    $mea = 'Months';
                                    $mea2 = 'days';
                                    $value = intdiv($key['Saving_Period'],30);
                                    $remainder = $key['Saving_Period'] % 30;
                                  }else {
                                    $mea = 'Years';
                                    $mea2 = 'days';
                                    $value = intdiv($key['Saving_Period'],365);
                                    $remainder = $key['Saving_Period'] % 365;
                                  }
                            ?>
                            <option value="{{$key['SubCateId'] ??' '}}">{{$cat_det[0]['lowerlimit'] ?? ' '}}-{{$cat_det[0]['upperlimit'] ?? ' '}} - {{ $value }} {{ $mea}} {{ $remainder ?? ' '}} {{ $mea2 }} - {{$key['Interest'] ?? ' '}}%</option>
                            <?php
                              }
                            ?>
                          </select>
                          <span class="bmd-help">Select A Saving Category.</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-4 col-form-label">Amount To Save</label>
                      <div class="col-sm-8">
                        <div class="form-group">
                          <input type="number" name="amount" class="form-control">
                          <span class="bmd-help">Enter the valid Amount To save</span>
                        </div>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-rose">Make Payment</button>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-md-7">
              <div class="card">
                <div class="card-header card-header-primary card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">assignment</i>
                  </div>
                  
                  <div class="float-left">
                    <h4 class="card-title">My Saving History</h4>
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
                            <th>Amount</th>
                            <th>Interest</th>
                            <th>Due Date</th>
                            <th>Status</th>
                        </tr>
                      </tfoot>
                      <tbody>
                        <?php
                            // $savin$auth::
                            foreach (session('dashboard')['user-savings'] as $key) {
                              # code...
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
                          <td>{{ $key['amount'] }}</td>
                          <td>{{ $key['Interest'] }}</td>
                          <td>{{ date('d/M/Y h:i:s A',$key['duedate'])}}</td>
                          <td><span class="badge {{$badge ?? ' ' }}">{{$status ?? ' '}}</span></td>
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