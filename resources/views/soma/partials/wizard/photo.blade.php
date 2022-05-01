<h3>Photo</h3>
<section>
    <div class="row">
   <div class="col-md-12">
        <div class="form-group">
            <div class="col-md-12">
                <input type="file" class="form-control {{$errors->has('photo')?'has-error':''}}" name="photo"
                placeholder="Upload Photo" value ="{!! old('photo')!!}" />
                @if($errors->has('logo'))
                <span class="label label-danger">{{ $errors->first('photo')}}
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