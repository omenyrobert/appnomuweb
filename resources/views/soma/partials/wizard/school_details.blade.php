<div id="school_details" class="row" >
    <h5>School Details</h5>
    <div class="col-md-6 form-group">
           <label for="sch_name">School Name</label>
           <input type="text" name="sch_name" id="sch_name" placeholder="School name" >
    </div>
    <div class="col-md-6 form-group">
           <label for="hm_fname">Headteacher's First Name</label>
           <input type="text" name="hm_fname" id="hm_fname" placeholder="headteacher's first name" >
    </div>
    <div class="col-md-6 form-group">
           <label for="student_lname">Headtecher's Last Name</label>
           <input type="text" name="hm_lname" id="hm_lname" placeholder="Headteacher's last name" >
    </div>
    <div class="col-md-6 form-group">
           <label for="hm_contact">Headteacher's contact</label>
           <input type="phone" name="hm_contact" id="hm_contact" >
    </div>  
    <div class="col-md-6 form-group">
           <label for="student_class">School District</label>
           <select name="hm_district" id="hm_district">
                  <option value="">--Select District--</option>
                  @foreach($districts as $district)
                     <option value="{{$district->id}}">{{$district->name}}</option>
                  @endforeach
           </select>

    <div class="col-md-6" hidden>
           <button id="btn-nxt-parent" type="button" class="btn btn-success">Next</button>
    </div>
</div>