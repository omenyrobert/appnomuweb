@extends('layouts.header')
@section('content')

          <div class="row">
          <div class="col-md-4">
              <div class="card ">
                <div class="card-header card-header-success card-header-text">
                  <div class="card-text">
                    <h4 class="card-title">New Savings Category</h4>
                  </div>
                </div>
                <div class="card-body ">
                  <form method="post" action="/save_saving_category" class="form-horizontal">
                    @csrf
                    <div class="row">
                      <label class="col-sm-4 col-form-label">Amount Upper Limit</label>
                      <div class="col-sm-8">
                        <div class="form-group">
                          <input type="number" name="upper_limit" class="form-control">
                          <span class="bmd-help">Enter the valid loan Amount</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-4 col-form-label">Amount Lower Limit</label>
                      <div class="col-sm-8">
                        <div class="form-group">
                          <input type="number" name="lower_limit" class="form-control">
                          <span class="bmd-help">Enter the valid Saving Amount</span>
                        </div>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-rose">Save Category</button>
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
                    <h4 class="card-title">Saving Categories</h4>
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
                          <th>Category Id</th>
                          <th>Lower Limit</th>
                          <th>Upper Limit </th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                            <!-- <th>Category Id</th> -->
                            <th>Category Id</th>
                            <th>Lower Limit</th>
                            <th>Upper Limit </th>
                            <th>Status</th>
                            
                        </tr>
                      </tfoot>
                      <tbody>
                        <?php
                          $cates = $auth::getAllsavingcategories();
                          foreach ($cates as $key) {
                            # code...
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
                          <td>{{ $key['cate_id'] }}</td>
                          <td>{{ $key['lowerlimit'] }}</td>
                          <td>{{ $key['upperlimit'] }}</td>
                          <td><a href="/{{$route}}?c={{ $key['cate_id'] }}"><span class="badge {{ $but }}">{{ $status }}</span></a></td>
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