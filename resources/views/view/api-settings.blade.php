@extends('layouts.header')
@section('content')

          <div class="row">
            <div class="col-md-6">
              <div class="card ">
                <div class="card-header card-header-rose card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">mail_outline</i>
                  </div>
                  <h4 class="card-title">SMS API</h4>
                </div>
                <div class="card-body ">
                  <form method="#" action="#">
                    <div class="form-group">
                      <label for="exampleEmail" class="bmd-label-floating">Email Address</label>
                      <input type="email" class="form-control" id="exampleEmail">
                    </div>
                    <div class="form-group">
                      <label for="examplePass" class="bmd-label-floating">Password</label>
                      <input type="password" class="form-control" id="examplePass">
                    </div>
                    <div class="form-check">
                      <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" value=""> Subscribe to newsletter
                        <span class="form-check-sign">
                          <span class="check"></span>
                        </span>
                      </label>
                    </div>
                  </form>
                </div>
                <div class="card-footer ">
                  <button type="submit" class="btn btn-fill btn-rose">Submit</button>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card ">
                <div class="card-header card-header-rose card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">contacts</i>
                  </div>
                  <h4 class="card-title">Flutterwave Settings</h4>
                </div>
                <div class="card-body ">
                  <form class="form-horizontal">
                    <div class="row">
                      <label class="col-md-3 col-form-label">Email</label>
                      <div class="col-md-9">
                        <div class="form-group has-default">
                          <input type="email" class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-md-3 col-form-label">Password</label>
                      <div class="col-md-9">
                        <div class="form-group">
                          <input type="password" class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-md-3"></label>
                      <div class="col-md-9">
                        <div class="form-check">
                          <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" value=""> Remember me
                            <span class="form-check-sign">
                              <span class="check"></span>
                            </span>
                          </label>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="card-footer ">
                  <div class="row">
                    <div class="col-md-9">
                      <button type="submit" class="btn btn-fill btn-rose">Sign in</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card ">
                <div class="card-header card-header-rose card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">contacts</i>
                  </div>
                  <h4 class="card-title">Google Settings</h4>
                </div>
                <div class="card-body ">
                  <form class="form-horizontal">
                    <div class="row">
                      <label class="col-md-3 col-form-label">Email</label>
                      <div class="col-md-9">
                        <div class="form-group has-default">
                          <input type="email" class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-md-3 col-form-label">Password</label>
                      <div class="col-md-9">
                        <div class="form-group">
                          <input type="password" class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-md-3"></label>
                      <div class="col-md-9">
                        <div class="form-check">
                          <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" value=""> Remember me
                            <span class="form-check-sign">
                              <span class="check"></span>
                            </span>
                          </label>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="card-footer ">
                  <div class="row">
                    <div class="col-md-9">
                      <button type="submit" class="btn btn-fill btn-rose">Sign in</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card ">
                <div class="card-header card-header-rose card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">contacts</i>
                  </div>
                  <h4 class="card-title">SMTP Settings</h4>
                </div>
                <div class="card-body ">
                  <form class="form-horizontal">
                    <div class="row">
                      <label class="col-md-3 col-form-label">Email</label>
                      <div class="col-md-9">
                        <div class="form-group has-default">
                          <input type="email" class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-md-3 col-form-label">Password</label>
                      <div class="col-md-9">
                        <div class="form-group">
                          <input type="password" class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-md-3"></label>
                      <div class="col-md-9">
                        <div class="form-check">
                          <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" value=""> Remember me
                            <span class="form-check-sign">
                              <span class="check"></span>
                            </span>
                          </label>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="card-footer ">
                  <div class="row">
                    <div class="col-md-9">
                      <button type="submit" class="btn btn-fill btn-rose">Sign in</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
@endsection