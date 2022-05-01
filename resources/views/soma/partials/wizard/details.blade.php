<h3>Student/Learner Details</h3>
<section>
  {!! csrf_field() !!}
<div class="row">
   <div class="col-md-6">
        <div class="form-group">
            <div class="col-md-12">
                <input type="text" class="form-control {{$errors->has('firstname')?'has-error':''}}" 
                 name="firstname"  placeholder="First Name*" value ="{!! old('firstname')!!}"  required/>
                @if($errors->has('firstname'))
                <span class="label label-danger">{{ $errors->first('firstname')}}
                </span>    
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <div class="col-md-12">
                <input type="text" class="form-control {{$errors->has('lastname')?'has-error':''}}" 
                 name="lastname"  placeholder="Last Name*" value ="{!! old('lastname')!!}"  required/>
                @if($errors->has('firstname'))
                <span class="label label-danger">{{ $errors->first('lastname')}}
                </span>    
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <div class="col-md-12">
            <select class="form-control select2 {{$errors->has('study_mode')?'has-error':''}}" name="study_mode" required>
                <option value="">--Study Mode--</option>
                @foreach($districts as $data)
                <option value="{{ $data->id }}">{{ $data->study_mode}}</option>
                @endforeach
            </select>
            @if($errors->has('study_mode'))
                <span class="label label-danger">{{ $errors->first('study_mode')}}
                </span>    
             @endif
            </div>
        </div>
    </div>
      <div class="col-md-6">
        <div class="form-group">
            <div class="col-md-12">
            <select class="form-control select2 {{$errors->has('gender')?'has-error':''}}" name="gender" required>
                <option value="">Select Gender*</option>
                <option value="Male">Male</option>
                 <option value="Female">Female</option>
            </select>
            @if($errors->has('gender'))
                <span class="label label-danger">{{ $errors->first('gender')}}
                </span>    
             @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <div class="col-md-12">
            <select class="form-control select2 {{$errors->has('current_class')?'has-error':''}}" name="current_class" required>
                <option value="">--Class/stream--</option>
                    
                      @foreach($grades as $data)
                                                      
                            <option value="{{ $data->name }}">{{ $data->name}}</option>
                                                           
                      @endforeach
            </select>
            @if($errors->has('current_class'))
                <span class="label label-danger">{{ $errors->first('current_class')}}
                </span>    
            @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <div class="col-md-12">
            <select class="form-control select2 {{$errors->has('current_stream')?'has-error':''}}" name="current_stream" >
                <option value="">--stream--</option>
                    
                      @foreach($districts as $stream)
                                                      
                            <option value="{{ $stream->id }}">{{ $stream->name}}</option>
                                                           
                      @endforeach
            </select>
            @if($errors->has('current_class'))
                <span class="label label-danger">{{ $errors->first('current_stream')}}
                </span>    
            @endif
            </div>
        </div>
    </div>

    


    <div class="col-md-6">
        <div class="form-group">
            <div class="col-md-12">
            <select class="form-control select2 {{$errors->has('religion')?'has-error':''}}" name="religion" required>
                <option value="">---Religion---</option>
                <option value="Roman Catholic">Roman Catholic</option>
                <option value="Protestant">Protestant</option>
                <option value="Islam">Islam</option>
                <option value="Pentecostal">Pentecostal</option>
                 <option value="Hinduism">Hinduism</option>
                <option value="Budduism">Budduism</option>
                 <option value="None">None</option>
            </select>
            @if($errors->has('religion'))
                <span class="label label-danger">{{ $errors->first('religion')}}
                </span>    
             @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
 <div class="col-md-6">
    <div class="form-group">
        <div class="col-md-12">
            <input type="text" class="form-control {{$errors->has('dob')?'has-error':''}} datepicker-autoclose"
            name="dob" placeholder="Date of Birth" value ="{!! old('dob') !!}"/>
            @if($errors->has('dob'))
                <span class="label label-danger">{{ $errors->first('dob')}}
                </span>    
            @endif
        </div>
     </div>
    </div>
   <div class="col-md-6">
    <div class="form-group">
        <div class="col-md-12">
            <input type="text" class="form-control {{$errors->has('date_joined')?'has-error':''}} datepicker-autoclose"
            name="date_joined" placeholder="Date of Joining" value ="{!! old('date_joined') !!}"/>
            @if($errors->has('date_joined'))
                <span class="label label-danger">{{ $errors->first('date_joined')}}
                </span>    
            @endif
        </div>
     </div>
    </div>
</div>
    <div class="form-group clearfix">
        <label class="col-lg-12 control-label ">(*) Mandatory</label>
    </div>
</section>