@extends('layouts.header')
@section('content')

          <div class="row">
                <?php
                    $messages = $auth::getMessagesxd();
                    foreach ($messages as $key) {
                        # code...
                    
                ?>
            <div class="col-md-4">
              <div class="card">
                <div class="card-header card-header-primary card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">assignment</i>
                  </div>
                  <h4 class="card-title">Messages - {{$key['name'] ?? ' '}} - {{$key['email'] ?? ' '}} - {{date('l M d ,Y',strtotime($key['created_at'])) ?? ' '}}</h4>
                </div>
                <div class="card-body">
                  <div class="toolbar">
                    <!--        Here you can write extra buttons/actions for the toolbar              -->
                  </div>
                    <h3>{{$key['subject'] ?? ' '}}</h3>
                    <p>{{$key['text'] ?? ' '}}</p>
                </div>
                <!-- end content-->
              </div>
              <!--  end card  -->
            </div>
                <?php
                    }
                ?>
            <!-- end col-md-12 -->
          </div>
          <!-- end row -->
        </div>
      </div>
@endsection