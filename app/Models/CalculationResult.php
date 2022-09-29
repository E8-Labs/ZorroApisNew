<?php

namespace App\Models;


class CalculationResult
{
	public $ltv;
	public $creditScore;
	public $rates;
	public $lowestRate;
	public $annualLowestRate;
	public $monthlyPaymentsRate;
	public $requiredMonthlyPayment;
	public $totalAmountToPay;
	public $totalInterestToPay;
	public $calculationDetail;
	public $data;

	public $optimal_costOrCredit;
	public $low_rate_costOrCredit;
	public $low_cost_costOrCredit;

	public $optimal_rate;
	public $low_rate_rate;
	public $low_cost_rate;
}