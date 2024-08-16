<?php

namespace App\Http\Controllers;

use App\Services\CryptoDataService;
use App\Models\InvestmentAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;


class CryptoController extends Controller
{
    protected CryptoDataService $cryptoDataService;


    public function __construct(CryptoDataService $cryptoDataService)
    {
        $this->cryptoDataService = $cryptoDataService;
    }

    public function index(): View
    {
        $data = $this->cryptoDataService->getTopCryptos();
        return view('cryptos.index', ['data' => $data]);
    }

    public function show(string $symbol): View
    {
        $data = $this->cryptoDataService->getCryptoData($symbol);

        $user = Auth::user();
        $accounts = InvestmentAccount::whereHas('users', function($query) use ($user) {
            $query->where('user_id', $user->id)
                ->whereIn('access_type', ['invest', 'full']);
        })->get();

        return view('cryptos.show', ['data' => $data, 'accounts' => $accounts]);
    }
}
