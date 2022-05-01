@extends('layouts.header')
@section('content')

          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-primary card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">assignment</i>
                  </div>
                  <h4 class="card-title">Articles</h4>
                </div>
                <div class="card-body">
                  <div class="toolbar">
                    <!--        Here you can write extra buttons/actions for the toolbar              -->
                  </div>
                  <div class="material-datatables">
                    <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                      <thead>
                        <tr>
                          <th>Heading</th>
                          <th>Sub Heading </th>
                          <th>Text</th>
                          <th>Banner</th>
                          <th>Date</th>
                          <th>Status</th>
                          <th class="disabled-sorting text-right">Actions</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                            <th>Heading</th>
                            <th>Sub Heading </th>
                            <th>Text</th>
                            <th>Banner</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="disabled-sorting text-right">Actions</th>
                        </tr>
                      </tfoot>
                      <tbody>
                          <?php
                            $articles = $auth::getknowlegebase();
                            foreach ($articles as $key) {
                                # code...
                                if ($key['status']=='Pending') {
                                    $bad = 'badge-warning';
                                } elseif ($key['status']=='Live') {
                                    $bad = 'badge-success';
                                } else {
                                    $bad = 'badge-rose';
                                }
                                
                          ?>
                        <tr>
                          <td>{{wordwrap($key['title'],15,"<br>\n") ?? ' '}}</td>
                          <td>{{wordwrap($key['sub-title'],15,"<br>\n") ?? ' '}}</td>
                          <td>{{substr($key['article'],0,20) ?? ' '}}....</td>
                          <td>
                             <img src="{{$key['url'] ?? '/assets/img/banner.png'}}" style="width:100px;" alt="...">
                          </td>
                          <td>{{date('l,d/M/Y',strtotime($key['created_at']))}}</td>
                          <td><span class="badge {{$bad}}">{{$key['status']}}</span></td>
                          <td class="text-right">
                            <a href="/manage-knowledge?a={{$key['id']}}&s=Pending" class="btn btn-link btn-info btn-just-icon like"><i class="material-icons">favorite</i></a>
                            <a href="/knbase?id={{$key['id']}}" class="btn btn-link btn-success btn-just-icon like"><i class="material-icons">assignment</i></a>
                            <a href="/manage-knowledge?a={{$key['id']}}&s=Live" class="btn btn-link btn-warning btn-just-icon edit"><i class="material-icons">dvr</i></a>
                            <a href="/manage-knowledge?a={{$key['id']}}&s=Deleted" class="btn btn-link btn-danger btn-just-icon "><i class="material-icons">close</i></a>
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