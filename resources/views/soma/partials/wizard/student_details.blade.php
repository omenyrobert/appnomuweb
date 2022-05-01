<div id="student_details" class="row">
    <h5>Student Details</h5>
    <div class="col-md-6 form-group">
           <label for="student_fname">First Name</label>
           <input type="text" name="student_fname" id="student_fname" placeholder="students first name" required>
    </div>
    <div class="col-md-6 form-group">
           <label for="student_lname">Last Name</label>
           <input type="text" name="student_lname" id="student_lname" placeholder="students last name" required>
    </div>
    <div class="col-md-6 form-group">
           <label for="student_lname">Date Of Birth</label>
           <input type="date" name="student_dob" id="student_dob" required>
    </div>
    <div class="col-md-6 form-group">
           <h6>select Gender</h6>
           <input type="radio" name="student_gender" id="student_gender" value="Male" >
           <label for="student_gender">Male</label>
           <input type="radio" name="student_gender" id="student_gender" value="Female" >
           <label for="student_gender">Female</label>
    </div>
    <div class="col-md-6 form-group">
           <label for="student_contact">Student contact</label>
           <input type="phone" name="student_phone" id="student_contact" >
    </div>
    <div class="col-md-6 form-group">
           <label for="sch_admin_num">School admission number</label>
           <input type="phone" name="sch_admin_num" id="sch_admin_num" >
    </div>
    <div class="col-md-6 form-group">
           <label for="student_class">Class</label>
           <select name="student_class" id="student_class">
                  <option value="">--Select Class--</option>
                  @foreach($grades as $grade)
                     <option value="{{$grade->id}}">{{$grade->name}}</option>
                  @endforeach
           </select>
    </div>
    <div class="col-md-6" hidden>
           <button type="button" id="btn-nxt-school" class="btn btn-success">Next</button>
    </div>
</div>
