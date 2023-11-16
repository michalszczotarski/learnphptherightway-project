<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Transaction;
use App\View;

class TransactionFileController
{
    public function index(): View
    {
        return View::make("upload");
    }

    public function transactions(): View
    {
        $transactionModel = new Transaction();

        $data = $transactionModel->getAllTransactions();

        if($data) {
            $totalPrices = $transactionModel->getTotalPrices($data);            
        }

        foreach($data as $key => $val) {
            $data[$key]['Date'] = $transactionModel->changeFormatDate($val['Date']);
            $data[$key]['Class'] = $transactionModel->getAmountClassColumn($val['Amount']);
        }
        
        return View::make("transactions", ["data"=> $data, 'totalPrices' => $totalPrices]);
    }

    public function upload() {
        $transactionModel = new Transaction();
        $transactionModel->upload();
    }
}
