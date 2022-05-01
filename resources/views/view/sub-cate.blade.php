@extends('layouts.header')
@section('content')

          <div class="row">
          <div class="col-md-4">
              <div class="card ">
                <div class="card-header card-header-success card-header-text">
                  <div class="card-text">
                    <h4 class="card-title">New Savings Sub-Category</h4>
                  </div>
                </div>
                <div class="card-body ">
                  <form method="post" action="/save_saving_sub_category" class="form-horizontal">
                    @csrf
                    <div class="row">
                      <label class="col-sm-4 col-form-label">Select Category</label>
                      <div class="col-sm-8">
                        <div class="form-group">
                          <!-- <input type="number" class="form-control"> -->
                          <select name="category" id="" class="form-control">
                              <option value="">Select Category</option>
                              <?php
                                $j =0;
                                $cates = $auth::getAllsavingcategories();
                                foreach ($cates as $cat_key) {
                                  if ($cat_key['status']==7) {
                                    # code...
                                  
                                  $j++;
                              ?>
                              <option value="{{ $cat_key['cate_id'] }}">{{ $cat_key['lowerlimit'] }} - {{ $cat_key['upperlimit'] }}</option>
                              <?php
                                }}

                                if ($j<1) {
                                  # code...
                                
                              ?>
                                <option value="">Category List Is Empty</option>
                              <?php
                                }
                              ?>
                          </select>
                        </div>
                      </div>
                    </div>
</br>
                    <div class="row">
                      <label class="col-sm-4 col-form-label">Saving Period</label>
                      <div class="col-sm-8">
                        <div class="form-group">
                          <input type="number" name="saving_period" class="form-control">
                          <span class="bmd-help">Enter the valid Saving Period In Days</span>
                        </div>
                      </div>
                    </div>
</br>
                    <div class="row">
                      <label class="col-sm-4 col-form-label">Interest Amount</label>
                      <div class="col-sm-8">
                        <div class="form-group">
                          <input type="number" name="interest" class="form-control">
                          <span class="bmd-help">Enter the valid Interest (%)</span>
                        </div>
                      </div>
                    </div>
</br>
</br>
                    <?php
                      if ($j>0) {
                        # code...
                    ?>
                    <button type="submit" class="btn btn-rose">Save Sub Category</button>
                    <?php
                      }else {
                        # code...
                      
                    ?>
                        <span class="badge badge-danger">
                          <h6>There Is No Category Save Category First</h6>
                        </span>
                    <?php
                      }
                    ?>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-md-8">
              <div class="card">
                <div class="card-header card-header-primary card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">assignment</i>
                  </div>
                  
                  <div class="float-left">
                    <h4 class="card-title">Saving Sub Categories</h4>
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
                          <!-- <th>Category Id</th> -->
                          <th>Category</th>
                          <th>Period</th>
                          <th>Interest(%)</th>
                          <th>Savings (UGX)</th>
                          <th>Users</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tfoot>

                        <tr>
                            <th>Category</th>
                            <th>Period</th>
                            <th>Interest(%)</th>
                            <th>Savings (UGX)</th>
                            <th>Users</th>
                            <th>Status</th>
                        </tr>
                      </tfoot>
                      <tbody>
                        <?php
                          $cates = $auth::getAllSavingSubcategories();
                          foreach ($cates as $key) {
                            $categoryx = $auth::getSavingCatByCatId($key['cate_id']);
                            
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

                            //users on plann
                            $savings = 0;
                            $g = 0;
                            
                            $users_saves = $auth::getSavingsBetween( $categoryx[0]['lowerlimit'] ,$categoryx[0]['upperlimit']);

                            foreach ($users_saves as $usaves) {
                              # code... runnign savings 7, paid = 8, initiated savings 5, failed = 9, 
                              
                              if ($usaves['status']==7) {
                                # code...
                                $g++;
                                $savings = $savings+$usaves['amount'];
                              }
                              
                            }

                            if ($key['status']==7) {
                              # code...
                              $status = 'Active';
                              $but = 'badge-success';
                              $route = 'deactivate';
                            }elseif ($key['status']==5) {
                              # code...
                              $status = 'Not Active';
                              $but = 'badge-danger';
                              $route = 'activate';
                            }else {
                              # code...
                              $status = 'Deleted';
                              $but = 'badge-danger';
                              
                            }
                        ?>
                        <tr>
                          <!-- <td>CAT001</td> -->
                          <td>{{ $categoryx[0]['lowerlimit'] }} - {{ $categoryx[0]['upperlimit'] }} </td>
                          <td>{{ $value }} {{ $mea}} {{ $remainder ?? ' '}} {{ $mea2 }}</td>
                          <td>{{$key['Interest'] ?? ' '}}%</td>
                          <td>{{$savings}}</td>
                          <td>{{$g}}</td>
                          <td><span class="badge {{$but}}">{{$status}}</span></td>
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