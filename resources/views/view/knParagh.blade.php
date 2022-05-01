@extends('layouts.header')
@section('content')

          <div class="row">
            <div class="col-md-4">
              <div class="card ">
                <div class="card-header card-header-rose card-header-text">
                  <div class="card-text">
                    <h4 class="card-title">Enter Paragraph</h4>
                  </div>
                </div>
                <div class="card-body ">
                  <form method="post" action="/paragraph" class="form-horizontal" enctype="multipart/form-data">
                  @csrf
                    <div class="row">
                      <label class="col-sm-4 col-form-label">Heading</label>
                      <div class="col-sm-8">
                        <div class="form-group">
                          <input type="text" name="title" class="form-control">
                          <span class="bmd-help">Heading Here</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-4 col-form-label">Sub-Heading</label>
                      <div class="col-sm-8">
                        <div class="form-group">
                          <input type="text" name="stitle" class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-4 col-form-label">Paragraph</label>
                      <div class="col-sm-8">
                        <div class="form-group">
                          <textarea name="article" class="form-control" id="" ></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-4 col-form-label">Image</label>
                        <div class="col-sm-8">
                            <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                                <div class="fileinput-new thumbnail">
                                    <img src="/assets/img/banner.png" alt="...">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail"></div>
                                <div>
                                    <span class="btn btn-rose btn-round btn-file">
                                    <span class="fileinput-new">Select image</span>
                                    <span class="fileinput-exists">Change</span>
                                    <input type="file" name="image" required/>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-warning btn-round fileinput-exists">Submit Article</button>
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
                  <h4 class="card-title">Article Paragraphs</h4>
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
                            <th>Banner</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="disabled-sorting text-right">Actions</th>
                        </tr>
                      </tfoot>
                      <tbody>
                          <?php
                            foreach ($paras as $key) {
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
                          <td>
                             <img src="{{$key['url'] ?? '/assets/img/banner.png'}}" style="width:100px;" alt="...">
                          </td>
                          <td>{{date('l,d/M/Y',strtotime($key['created_at']))}}</td>
                          <td><span class="badge {{$bad}}">{{$key['status']}}</span></td>
                          <td class="text-right">
                            <a href="/manage-para?a={{$key['id']}}&s=Pending" class="btn btn-link btn-info btn-just-icon like"><i class="material-icons">favorite</i></a>
                            <a href="/manage-para?a={{$key['id']}}&s=Live" class="btn btn-link btn-warning btn-just-icon edit"><i class="material-icons">dvr</i></a>
                            <a href="/manage-para?a={{$key['id']}}&s=Deleted" class="btn btn-link btn-danger btn-just-icon "><i class="material-icons">close</i></a>
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