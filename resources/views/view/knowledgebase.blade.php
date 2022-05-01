@extends('layouts.header')
@section('content')

          <div class="row">
            <div class="col-md-12">
              <div class="card ">
                <div class="card-header card-header-rose card-header-text">
                  <div class="card-text">
                    <h4 class="card-title">Enter Article</h4>
                  </div>
                </div>
                <div class="card-body ">
                  <form method="post" action="/article" class="form-horizontal" enctype="multipart/form-data">
                  @csrf
                    <div class="row">
                      <label class="col-sm-2 col-form-label">Article Heading</label>
                      <div class="col-sm-10">
                        <div class="form-group">
                          <input type="text" name="header" class="form-control">
                          <span class="bmd-help">Article Heading Here</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">Article Sub-Heading</label>
                      <div class="col-sm-10">
                        <div class="form-group">
                          <input type="text" name="sub_heading" class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">Article</label>
                      <div class="col-sm-10">
                        <div class="form-group">
                          <textarea name="article" class="form-control" id="" ></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Image</label>
                        <div class="col-sm-10">
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
          </div>
        </div>
    </div>
@endsection