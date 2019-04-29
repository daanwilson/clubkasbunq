<?php

namespace App\Http\Middleware;

use Closure;
use \Illuminate\Support\Facades\Auth;

class GenerateMenus {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $this->generateTopmenu();
        $this->generateSidemenu();
        return $next($request);
    }
    
    public function generateTopmenu(){
        \Menu::make('topMenu', function (\Lavary\Menu\Builder $menu) {
            /* @var $menu \Lavary\Menu\Builder */
            if (auth()->check()) {
                $menu->add('Uitloggen', ['route' => 'logout']);
            }
        });
    }
    public function generateSidemenu(){
        \Menu::make('sideMenu', function (\Lavary\Menu\Builder $menu) {
            /* @var $menu \Lavary\Menu\Builder */
            if (auth()->check()) {
                $menu->add(_('Home'), ['route' => 'home'])->prepend('<i class="fa fa-tachometer-alt fa-fw"></i>&nbsp;');
                if(Auth::User()->can('team-management')){
                    $menu->add(_('Teams'), ['route' => 'teams.index'])->prepend('<i class="fa fa-flag fa-fw"></i>&nbsp;');
                }
                if(Auth::User()->can(['member-listing','member-management','member-functions'])){
                    $menu->add(_('Leden'), ['route' => 'members.index'])->nickname('leden')->prepend('<i class="fas fa-users fa-fw"></i>&nbsp;');
                    if(Auth::User()->can('member-management')){
                        $menu->get('leden')->add(_('Leden'), ['route' => 'members.index'])->prepend('<i class="fas fa-users fa-fw"></i>&nbsp;');
                        $menu->get('leden')->add(_('Leden importeren'), ['route' => 'members.import'])->prepend('<i class="fas fa-file-import"></i>&nbsp;');
                        $menu->get('leden')->add(_('Clobloten importeren'), ['route' => 'members.clubloten'])->prepend('<i class="fas fa-donate"></i>&nbsp;');
                    }
                }
                if(Auth::User()->can(['account-listing'])){
                    $menu->add(_('Bankrekeningen'), ['route' => 'accounts.index'])->nickname('bankaccounts')->prepend('<i class="fa fa-credit-card fa-fw"></i>&nbsp;');
                    $accounts = \Auth::User()->BankAccounts();
                    foreach ($accounts as $account){
                        $menu->get('bankaccounts')->add($account->description.'<span class="label label-'.($account->amount>0?'success':'danger').' pull-right">'.$account->getAmountFormated().'</span>', ['route' => ['account.payments.index','id'=>$account->id ]]);
                    }
                    
                }
                if(Auth::User()->can(['cash-management'])){
                    $menu->add(_('Kleine kas'), ['route' => 'cash.index'])->nickname('cash')->prepend('<i class="fa fa-wallet fa-fw"></i>&nbsp;');
                    $cashaccounts = \Auth::User()->CashAccounts();
                    foreach ($cashaccounts as $cashaccount){
                        $menu->get('cash')->add($cashaccount->getName().'<span class="label label-'.($cashaccount->getAmount()>0?'success':'danger').' pull-right">'.$cashaccount->getAmountFormated().'</span>', ['route' => ['cash.edit','id'=>$cashaccount->team->id ]]);
                    }

                }
                if(Auth::User()->can(['user-management','role-management','permissions-management'])){
                    $menu->add(_('Gebruikers'), ['route' => 'users.index'])->nickname('users')->prepend('<i class="far fa-user-circle"></i>&nbsp;');
                    if(Auth::User()->can('user-management')){
                        $menu->get('users')->add(_('Gebruikers'), ['route' => 'users.index'])->prepend('<i class="fas fa-user-lock"></i>&nbsp;');
                    }
                    if(Auth::User()->can('role-management')){
                        $menu->get('users')->add(_('Gebruikers rollen'), ['route' => 'roles.index'])->prepend('<i class="fas fa-user-tag"></i>&nbsp;');
                    }
                    if(Auth::User()->can('permission-management')){
                        $menu->get('users')->add(_('Toegangsrechten'), ['route' => 'permissions.index'])->prepend('<i class="far fa-check-circle"></i>&nbsp;');
                    }
                    //$menu->users->add(_('Gebruikersrechten'), ['route' => 'permissions.index']);
                }

                if(Auth::User()->can(['settings-edit','settings-management','season-management','money-purpose-management','money-items-management'])) {
                    $menu->add(_('Instellingen'), ['route' => 'settings.index'])->nickname('settings')->prepend('<i class="fa fa-cogs fa-fw"></i>&nbsp;');
                    if(Auth::User()->can(['settings-edit','settings-management'])){
                        $menu->get('settings')->add(_('Instellingen'), ['route' => 'settings.index'])->prepend('<i class="fa fa-cogs fa-fw"></i>&nbsp;');
                    }
                    if(Auth::User()->can('season-management')) {
                        $menu->get('settings')->add(_('Seizoenen'), ['route' => 'season.index'])->prepend('<i class="fas fa-layer-group"></i>&nbsp;');
                    }
                    if(Auth::User()->can('money-purpose-management')) {
                        $menu->get('settings')->add(_('Doelen'), ['route' => 'moneypurpose.index'])->prepend('<i class="fas fa-book-open"></i>&nbsp;');
                    }
                    if(Auth::User()->can('money-items-management')) {
                        $menu->get('settings')->add(_('Posten'), ['route' => 'moneyitem.index'])->prepend('<i class="fas fa-sitemap"></i>&nbsp;');
                    }
                }
                if(Auth::User()->can('bunqtab-payment-requests')) {
                    $menu->add(_("Bunq Tabs (Betaalverzoeken)"), ['route' => 'bunqtabs.index'])->nickname('bunqtabs')->prepend('<i class="fas fa-cash-register"></i>&nbsp;');
                }

            }
        });
    }

}
