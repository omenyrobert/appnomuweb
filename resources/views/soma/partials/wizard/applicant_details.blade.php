<div id="applicant_detailss" class="row" >
    <h5>Parent/Applicant Details</h5>
    @if($user->identification && $user->identification->front_face)
    <div class="form-group">
           <h6>applicant same as Next Of Kin</h6>
           <input type="checkbox" name="chk_parent_applicant" value="true" id="chk_parent_applicant">
           <label for="chk_parent_applicant">check if your student's next of Kin</label>
    /div>
    
    @else
       <div class="col-md-6 form-group">
              <label for="sch_name">Parents FullName</label>
              <input type="text" name="parent_name" id="parent_name" placeholder="parents full name" >
       </div>
       
       <div class="col-md-6 form-group">
              <label for="hm_contact">Parent/Next of Kin contact</label>
              <input type="phone" name="nok_contact" id="nok_contact" >
       </div>
       <div class="col-md-6 form-group">
              <label for="parent_id">Upload National Id Front Face</label>
              <input type="file" name="parent_id_card" id="parent_id_card"  class="border border-primary"placeholder="national id">
       </div>
    @endif
    <div class="col-md-6">
            <select  name="relationship" >
                 <option value="">---Relationship---</option>
                 <option value="Parent">Parent</option>
                 <option value="Auntie/Uncle">Auntie/Uncle</option>
                 <option value="GrandParent">GrandParent</option>
                 <option value="Sibling">Brother/Sister</option>
                 <option value="Guardian">Guardian</option>
                 <option value="Friend">Friend</option>
                 <option value="Neighbour">Neighbour</option>
                 <option value="Sponsor">Sponsor</option>
              </select>
    </div>
    <div class="col-md-6 form-group">
              <label for="student_id_card">Upload Student ID</label>
              <input type="file" name="student_id_card" id="student_id_card" class="border border-primary" placeholder="student id">
       </div>
       <div class="col-md-6 form-group">
              <label for="receipt_report">Upload Student's past receipt or School Report Card</label>
              <input type="file" name="receipt_report" id="receipt_report" class="border border-primary" placeholder="report or receipt">
              <div>
              <label for="file_type">check the type of document</label>
              <input type="radio" name="radio_receipt_report" id="report" value="Report">
              <label for="report">Report Card</label>
              <input type="radio" name="radio_receipt_report" id="receipt" value="Receipt">
              <label for="report">Receipt/Payslip</label>
              </div>
       </div>
    <div class="col-md-6" hidden>
           <button type="button"id="btn-nxt-loan " class="btn btn-success">Next</button>
    </div>
                  </div>