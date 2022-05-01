@extends('layouts.header')
@section('content')

<div class="border border-success">
<hr>
 <form id="wizard-vertical" method="POST"
        action="{{ route('soma.store') }}"
                       enctype="multipart/form-data">
  @csrf
  
  <h4>Soma Loan Application</h4>
  @include('soma.partials.wizard.student_details')
  @include('soma.partials.wizard.school_details')
  @include('soma.partials.wizard.applicant_details')
  @include('soma.partials.wizard.loan_details')
</form>
@include('soma.partials.scripts.create_scripts')

@endsection