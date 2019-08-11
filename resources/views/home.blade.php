@extends('layouts.right.layout')

@section('title','Dashboard')

@section('content')
<div id="dashboard">
    <div class="row">
        <div class="col-xs-12">
            {!! $chart->container() !!}
            {!! $chart->script() !!}
        </div>
    </div>
    <div class="row">
        @foreach($teams as $team)
        
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary" style="border-color:{{ $team->color }};">
                        <div class="panel-heading" style="border-color:{{ $team->color }};background-color:{{ $team->color }};cursor:pointer;" onclick="window.location='{{ route('members.index','f=Teams:'.$team->id) }}'">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-users fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div>{{$team->name}}</div>
                                    <span class="huge">{{ $team->YouthMembers()->count() }}</span>&nbsp;<span>Leden</span>
                                    <div>{{ $team->LeaderMembers()->count(DB::raw('DISTINCT `members`.`member_id`')) }} &nbsp;Leiding</div>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('members.index','f=Teams:'.$team->id) }}" style="color:{{ $team->color }}">
                            <div class="panel-footer">
                                <span class="pull-left">Bekijk leden {{$team->name}}</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
        @endforeach
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading" style="cursor:pointer;" onclick="window.location='{{ route('accounts.index') }}'">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="far fa-credit-card fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right"  title="{!! MoneyFormat($bankamount) !!}">
                                    <div>Bankrekeningen</div>
                                    <div class="huge ellipsis">{!! MoneyFormat($bankamount) !!}</div>
                                    <div>Geld op {{ $bankcount }} rekeningen</div>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('accounts.index') }}">
                            <div class="panel-footer">
                                <span class="pull-left">Bekijk bankrekeningen</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading" onclick="window.location='{{ route('cash.index') }}'">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-wallet fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right" title="{!! MoneyFormat($cash_total) !!}">
                                    <div>Kleine kas</div>
                                    <div class="huge ellipsis">{!! MoneyFormat($cash_total) !!}</div>
                                    <div>Geld in {{ count($cash) }} kleine kas{{(count($cash) > 1 ? 'sen' : '')}}</div>
                                </div>
                            </div>
                        </div>
                        <a href="{{route('cash.index')}}">
                            <div class="panel-footer">
                                <span class="pull-left">Bekijke kleine kas</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
    <div class="row">
        <div class="col-md-12">
            <h1>Checklist</h1>

            <h3>September</h3>
            <ol>
                <li>Start Seizoen: Clubloten afleveren op het hescohonk inclusief instructie boekje.</li>
                <li>Kascontrole plannen tijdens eerste groepsraad. Verzoek aan de penningmeesters om tijdig seizoen/kamp af te ronden.</li>
            </ol>
            <h3>Oktober</h3>
            <ol>
                <li>Overvliegen. Vaak krijg je de vraag wat het budget hiervoor is. Onder de 100,- is dit geen probleem, daarboven is overleg wel handig.</li>
                <li>Kascontrole.</li>
                <li>Invoeren clubloten in systeem door speltakken.</li>
                <li>Speltakken vragen of ze de ledenlijst in SOL bijwerken met nieuwe leden/overvliegers.</li>
            </ol>
            <h3>November</h3>
            <ol>
                <li>Clubloten controleren fase 1. Eventueel mislukte incasso's nabellen, of opnieuw indienen.</li>
                <li>Clubloten controleren fase 2.
                    <ol>
                        <li>Actuele ledenlijst uit SOL importeren in dit systeem. <a href="{{ route('members.import') }}" target="_blank">Leden -> Leden importeren</a></li>
                        <li>Clubloten lijst downloaden uit het systeem van de clubactie. Deze inlezen in dit systeem. <a href="{{ route('members.clubloten') }}" target="_blank">Leden -> Clubloten importeren</a> </li>
                        <li>De verkochten loten worden nu ingevuld bij de leden. Soms kan het systeem de leden er niet bij vinden. Hier krijg je een overzichtje van. Doe deze handmatig.</li>
                        <li>Je kan nu in het leden overzicht filteren in de kolom 'Clubloten' op bijvoorbeeld 'Minder dan 5 loten verkocht'. Filter ook op functie: 'Jeugdlid'.</li>
                        <li>Je heb nu een overzicht van leden die niet genoeg verkocht hebben. Neem dit op met de betreffende speltakken. Sommige leden hebben een goede reden. Voor de overige leden verwacht je dan het afkoopbedrag die de speltak aan de groep moet betalen.</li>
                    </ol>
                </li>
            </ol>
            <h3>December</h3>
            <ol>
                <li>Clubloten. Controleer of alle speltakken het bedrag hebben overgemaakt. (Stuur eventueel een betaalverzoek/request met de Bunq App)</li>
                <li>Clubloten. Uitslag is meestal rond 10 december. Eventueel kan je dit medelen op facebook/mailing naar ouders.</li>
            </ol>
            <h3>Januari</h3>
            <ol>
                <li>Nieuwjaarsviering. Vaak krijg je de vraag wat het budget is. Onder de 100,- is geen probleem. Daarboven is overleg handig. Als overvliegen ook niet duur was heb je wat meer ruimte.</li>
            </ol>
            <h3>Februari</h3>
            <ol>
                <li>Grote clubactie betaald uit.</li>
            </ol>
            <h3>Maart</h3>
            <ol>
                <li>Jantje beton. Dit wordt vaak geregeld door de Bosgeest.</li>
                <li>Lente actie. Wordt geregeld door bestuur. Inkoop betalen vanuit groepsrekening. Ontvangen geld storten op groepsrekening.</li>
            </ol>
            <h3>April</h3>
            <ol>
                <li>Scouting NL incasseert contributie van de rekening.</li>
                <li>Bestellen clubloten. Hier krijg je een mailtje van. 100 boekjes is over het algemeen voldoende.</li>
                <li>Verekenen van diverse posten op <a href="{{ route('internalpayments.index') }}" target="_blank">Interne betalingen</a>. Dit spreekt redelijk voor zich. Vul de bedragen in onderaan de pagina en er rolt een bedrag per speltak per onderdeel uit. Stuur een betaalverzoek via de bunq app naar de betreffende speltakken voor de contributie, materiaalfonds en knutselfonds. En maak de bedragen van de lenteactie over. Combineer deze bedragen niet in 1 transactie per speltak, want dat maakt het onoverzichtelijk.</li>
            </ol>
            <h3>Mei</h3>
            <ol>
                <li>Opbrengst jantje beton wordt gestort.</li>
                <li>Facturen voor cursussen doorsturen naar de penningmeester van de Jeugdraad. Die worden vergoed vanuit de subsidie die we krijgen van de gemeente.</li>
                <li>Factuur vanuit de maasgaarde volgt voor de huur van het clubgebouw. Deze kan betaald worden vanuit de opbrengeten van de clubactie.</li>
            </ol>
            <h3>Juni</h3>
            <ol>
                <li>Afsluiting seizoen. Hier komen wellicht nog kosten voor een BBQ.</li>
            </ol>
            <h3>Juli</h3>
            <ol>
                <li>Zomerkamp. Wellicht kosten voor een bedankje voor afscheid leiding.</li>
            </ol>
        </div>
    </div>
</div>
@endsection
