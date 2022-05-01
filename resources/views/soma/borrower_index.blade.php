@extends('layouts.header')
@section('content')
<div class="row">
    <a href="{{route('soma.create')}}" class="btn btn-primary col-md-4" style="background-color: blue;">Request A Loan</a>
</div>
    <div class="col-md-12 border border-dark card">
        <h5>Soma Loans</h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Loan Id </th>
                    <th>Loan Amount</th>
                    <th>Amount Paid</th>
                    <th>Outstanding Balance</th>
                    <th>Interest Rate</th>
                    <th>Loan Period</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if(count($loans))
                    @foreach($loans as $loan)
                        <tr>
                            <td>{{$loan->SLN_id}}</td>
                            <td>{{$loan->principal}}</td>
                            <td>{{$loan->paid_amount}}</td>
                            <td>{{$loan->principle - $loan->paid_amount}}</td>
                            <td>{{$loan->interest_rate}}</td>
                            <td>{{$loan->status}}</td>
                            @if($loan->status == 'pending' || $loan->status =='late')
                            <td>
                                <form action="">
                                    <a href="" class="btn btn-primary">Pay off Balance</a>
                                </form>
                            </td>
                            @else
                            <td></td>
                            @endif
                        </tr>
                    @endforeach
                @else
                    <tr>You Have No Soma Loan Yet, Click the request Button and Get One</tr>
                @endif

            </tbody>
        </table>
    </div>
    <div class="col-md-12 border border-dark card">
        <h5>Upcoming Installments</h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>SomaLoan</th>
                    <th>Amount</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if(count($installments) != 0)
                @foreach($installments as $installment)
                <tr>
                    <td>{{$installment->repaymentable->SLN_id}}</td>
                    <td>{{$installment->amount}}</td>
                    <td>{{$installment->due_date->diffForHumans()}}</td>
                    <td>{{$installment->status}}</td>
                    @if($installment->status == 'pending' || $installment->status == 'late')
                        <td>
                            <form action="">
                                <a href="" class="btn btn-primary">Pay Installment</a>
                            </form>
                        </td>
                    @else
                        <td></td>
                    @endif
                </tr>
                @endforeach
                @else
                <tr>No Outstanding Repayments</tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection