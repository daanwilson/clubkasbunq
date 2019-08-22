<?php

namespace App\Charts;

use App\BankAccount;
use App\Season;
use App\User;
use Balping\JsonRaw\Encoder;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class accountBalanceChart extends Chart
{
    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->options['responsive']=true;
        //$this->options['legend']=['display'=>false];
        $this->options['tooltips']=[
            'callbacks'=> [
                'label'=>$this->rawObject('function(tooltipItem, data) {
                    var label = data.datasets[tooltipItem.datasetIndex].label || "";
                    if (label) {
                        label += ": ";
                    }
                    var parts = Number(tooltipItem.value).toFixed(2).toString().split(".");
                    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    return label+"€ "+parts.join(",");
                }')
            ]
        ];
        $this->options['onClick'] = $this->rawObject('function(evt,item){
            var chart = window[$(evt.target).attr("id")];
            //console.log(chart);
            var activeElement = chart.getElementAtEvent(evt);
            //console.log(activeElement);
            var firstPoint = chart.getElementAtEvent(evt)[0];

            if (firstPoint) {
                //var label = chart.data.labels[firstPoint._index];
                //var value = chart.data.datasets[firstPoint._datasetIndex].data[firstPoint._index];
                //console.log(label,value,chart.data.datasets[firstPoint._datasetIndex]);
                window.location = "/statistics/"+chart.data.datasets[firstPoint._datasetIndex].account
            }
            }');
    }

    public function loadData(){
        $bankaccounts = auth()->user()->BankAccounts();
        $current_season = Season::current();
        $seasons = Season::allCachedByKey('id');
        $key = array_search($current_season->id, array_keys($seasons), true);
        if ($key !== false) {
            $start = max($key-4,0);
            $seasons = array_slice($seasons, $start, 4, true);
        }
        $labels = [];
        foreach($seasons as $season){
            $labels[] = $season->season_name;
        }

        $this->labels($labels);

        foreach($bankaccounts as $account){
            /* @var $account BankAccount */
            $amounts = [];
            foreach($seasons as $season){
                $amounts[] = $account->getBalanceBySeason($season);
            }

            $this->dataset($account->description,'bar',$amounts)->options(
                [
                    'backgroundColor'=>$account->color,
                    'borderColor'=>$account->color,
                    'account'=>$account->id,
                ]);
        }

    }


}
