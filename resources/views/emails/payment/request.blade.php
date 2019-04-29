@component('mail::message')
# Betaalverzoek

Beste {{ $salutation }},

Hierbij ontvangt u een betaalverzoek van {{ env('APP_ASSOCIATION_NAME') }} met een totaalbedrag van
<strong style="font-size:24px;line-height: 30px;">{{ $request->getAmountClean() }}</strong>
@if($surcharge>0)
    <div style="font-size:12px;line-height: 15px;">(+ {{ MoneyFormat($surcharge) }} iDEAL toeslag.)</div>
@endif
&nbsp;
@if($request->description!='')
    <div>Omschrijving: <strong>{{ $request->description }}</strong></div>
@endif
<br/>
Gelieve dit verzoek binnen 14 dagen te betalen op rekening {{ getChunked($bankaccount->IBAN,4,"&nbsp;")." (".$bankaccount->description.")" }}
U kunt onderstaande knop gebruiken om deze eenvoudig met iDEAL te voldoen.
<hr/>

@component('mail::button', ['url' => $request->getShareUrl()])
Open iDEAL link
@endcomponent
<hr style='border:none;border-top:1px solid silver;height:0;'/>

Alvast bedankt namens {{
    (strtolower($bankaccount->description)=='bestuur'?'het':'de')." ".$bankaccount->description
}}.

<div>
    <hr/>
    <small style='font-size:11px;'>
        Deze link verloopt op: {{ DateTimeFormat($request->getTimeExpiry()) }}<br/>
        Mocht de knop niet werken dan kunt u onderstaande link kopi&euml;ren en plakken in uw browser:<br/>
        {{ $request->getShareUrl() }}
        @if($surcharge>0)
        <hr/>
        Het bedrag bevat ook {{ MoneyFormat($surcharge) }} iDEAL toeslag.
        @endif
    </small>

</div>

@endcomponent

