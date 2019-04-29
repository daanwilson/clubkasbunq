<ul id="side-menu" class="nav in bankaccount-list">
    @foreach ($bankaccounts->getValue() as $account)
    <li>
        <a href="#" title="{{ $account->getMonetaryAccountBank()->getDescription() }}" >
            <span class='bullit' style='background-color:{{ $account->getMonetaryAccountBank()->getSetting()->getColor() }}'></span>
            {{ $account->getMonetaryAccountBank()->getDescription() }}
            
            <small class="pull-right">{{ $account->getMonetaryAccountBank()->getBalance()->getValue().'&nbsp;'.$account->getMonetaryAccountBank()->getBalance()->getCurrency() }}</small>
            @foreach ($account->getMonetaryAccountBank()->getAlias() as $alias)
                @if($alias->getType()=='IBAN')
                    <div class="small">{{ $alias->getValue() }}</div>
                @endif                
            @endforeach
            
        </a>
    </li>
    @endforeach
</ul>