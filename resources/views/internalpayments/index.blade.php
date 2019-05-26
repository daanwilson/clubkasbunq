@extends('layouts.right.layout')

@section('title','Dashboard')

@section('content')
    @php
        $aantalY = [];
        $aantalL = [];
        $totals = [];
    @endphp

    <div>
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>&nbsp;</th>
                @foreach($teams as $team)
                    <th>{{ $team->name }}</th>
                @endforeach
                <th>TOTAAL</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Jeugdleden</td>
                @foreach($teams as $team)
                    @php
                        $aantalY[$team->id] = $team->YouthMembers()->count();
                    @endphp
                    <td>{{ $aantalY[$team->id] }}</td>
                @endforeach
                <td><strong><u>{{ array_sum($aantalY) }}</u></strong></td>
            </tr>
            <tr>
                <td>Kaderleden</td>
                @foreach($teams as $team)
                    @php
                        $aantalL[$team->id] = $team->LeaderMembers()->count(DB::raw('DISTINCT `members`.`member_id`'));
                    @endphp
                    <td>{{ $aantalL[$team->id] }}</td>
                @endforeach
                <td><strong><u>{{ array_sum($aantalL) }}</u></strong></td>
            </tr>
            <tr class="warning">
                <td>Scouting NL</td>
                @php
                    $total = array_sum($aantalY) + array_sum($aantalL);
                    $amount = (isset($amounts['contributie']) ? $amounts['contributie']->payment_amount : 0 );
                    $sum = 0;
                @endphp
                @foreach($teams as $team)
                    <td>
                        @if($amount>0)
                            @php
                                $a = ($amount/$total) * ($aantalY[$team->id] + $aantalL[$team->id]);
                                $totals[$team->id] = $a;
                                $sum+=$a;
                            @endphp
                            {{ MoneyFormat($a) }}
                        @else
                            &nbsp;
                        @endif
                    </td>
                @endforeach
                <td>
                    @if($sum>0)
                        <strong><u>{{ MoneyFormat($sum) }}</u></strong>
                    @else
                        &nbsp;
                    @endif
                </td>
            </tr>
            <tr class="warning">
                <td>Knutselfonds</td>
                @php
                    $total = array_sum($aantalY);
                    $amount = (isset($amounts['knutsel']) ? $amounts['knutsel']->payment_amount : 0 );
                    $amountPart = $amount;
                @endphp
                @foreach($teams as $team)
                    <td>
                        @if($amount>0)
                            @php
                                $totals[$team->id]+= ($amountPart * $aantalY[$team->id]);
                            @endphp
                            {{ MoneyFormat($amountPart * $aantalY[$team->id] ) }}
                        @else
                            &nbsp;
                        @endif
                    </td>
                @endforeach
                <td>
                    @if($amount>0)
                        <strong><u>{{ MoneyFormat($amountPart * $total ) }}</u></strong>
                    @else
                        &nbsp;
                    @endif
                </td>
            </tr>
            <tr class="warning">
                <td>Materiaalfonds</td>
                @php
                    $total = array_sum($aantalY);
                    $amount = 0;
                @endphp
                @foreach($teams as $team)
                    @php
                        $amountPart = (isset($amounts['materiaal_'.$team->id]) ? $amounts['materiaal_'.$team->id]->payment_amount : 0 );
                    @endphp
                    <td>
                        @php
                            if(isset($totals[$team->id])){
                                $totals[$team->id]+= ($amountPart * $aantalY[$team->id]);
                                $amount+= ($amountPart * $aantalY[$team->id]);
                            }
                        @endphp
                        {{ MoneyFormat($amountPart * $aantalY[$team->id]) }}
                    </td>
                @endforeach
                <td>
                    @if($amount>0)
                        <strong><u>{{ MoneyFormat($amount ) }}</u></strong>
                    @else
                        &nbsp;
                    @endif
                </td>
            </tr>

            <tr class="success">
                <td>Lenteactie</td>
                @php
                    $total = array_sum($aantalY);
                    $amount = (isset($amounts['lenteactie']) ? $amounts['lenteactie']->payment_amount : 0 );
                    $amountPart = $amount / $total;
                @endphp
                @foreach($teams as $team)
                    <td>
                        @if($amount>0)
                            @php
                                $totals[$team->id]-= ($amountPart * $aantalY[$team->id]);
                            @endphp
                            {{ MoneyFormat($amountPart * $aantalY[$team->id] ) }}
                        @else
                            &nbsp;
                        @endif
                    </td>
                @endforeach
                <td>
                    @if($amount>0)
                        <strong><u>{{ MoneyFormat($amountPart * $total ) }}</u></strong>
                    @else
                        &nbsp;
                    @endif
                </td>
            </tr>
            <tr class="info">
                <td>&nbsp;</td>
                @php
                    $total = array_sum($aantalY);
                    $amount = (isset($amounts['knutsel']) ? $amounts['knutsel']->payment_amount : 0 );
                    $amountPart = $amount;
                @endphp
                @foreach($teams as $team)
                    <td>
                        @if(isset($totals[$team->id]))
                            {{ MoneyFormat($totals[$team->id] ) }}
                        @else
                            &nbsp;
                        @endif
                    </td>
                @endforeach
                <td>
                    @if(count($totals)>0)
                        <strong><u>{{ MoneyFormat(array_sum($totals)) }}</u></strong>
                    @else
                        &nbsp;
                    @endif
                </td>
            </tr>

            </tbody>
        </table>
        <form action="" method="POST" class="form-horizontal">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-6">
                    <h3>Contributie Scouting NL</h3>
                    <p>Voer hiernaast het bedrag welke wordt afgeschreven door Scouting NL. Dit bedrag wordt vervolgens
                        verrekend en verdeeld over de leden en kaderleden.</p>
                </div>
                <div class="col-md-6">
                    <h3>Contributie Scouting NL</h3>
                    <input type="text" class="form-control" name="amounts[contributie]" placeholder="€ 0.00"
                           value="{{ isset($amounts['contributie']) ? $amounts['contributie']->payment_amount : '' }}"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h3>Knutselfonds</h3>
                    <p>Voer hiernaast het bedrag in wat gevraagd wordt voor de knutselfonds. Dit bedrag wordt
                        vermenigvuldigd met het aantal jeugdleden.</p>
                </div>
                <div class="col-md-6">
                    <h3>Knutselfonds</h3>
                    <input type="text" class="form-control" name="amounts[knutsel]" placeholder="€ 0.00"
                           value="{{ isset($amounts['knutsel']) ? $amounts['knutsel']->payment_amount : '' }}"/>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6">
                    <h3>Materiaalfonds</h3>
                    <p>Voer hiernaast het bedrag in wat gevraagd wordt voor de materiaal fonds. Dit bedrag wordt
                        vermenigvuldigd met het aantal jeugdleden.</p>
                </div>
                <div class="col-md-6">
                    <h3>Materiaalfonds</h3>
                    @foreach($teams as $team)
                        <div class="row">
                            <div class="col-xs-4">{{$team->name}}</div>
                            <div class="col-xs-8"><input type="text" class="form-control"
                                                         name="amounts[materiaal_{{$team->id}}]" placeholder="€ 0.00"
                                                         value="{{ isset($amounts['materiaal_'.$team->id]) ? $amounts['materiaal_'.$team->id]->payment_amount : '' }}"/>
                            </div>
                        </div>

                    @endforeach
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h3>Lenteactie</h3>
                    <p>Voer hiernaast het bedrag in wat opgehaald is met de lenteactie, minus de kosten. Oftewel, de
                        winst. Dit bedrag wordt
                        verdeeld over het aantal jeugdleden.</p>
                </div>
                <div class="col-md-6">
                    <h3>Lenteactie</h3>
                    <input type="text" class="form-control" name="amounts[lenteactie]" placeholder="€ 0.00"
                           value="{{ isset($amounts['lenteactie']) ? $amounts['lenteactie']->payment_amount : '' }}"/>
                </div>

            </div>
            <button type="submit" name="save" class="btn btn-primary">Opslaan</button>
        </form>
    </div>
@endsection
