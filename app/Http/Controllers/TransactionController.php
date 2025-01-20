<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class TransactionController extends Controller
{
    public function ReportTransaction(Request $request)
    {
        try {

            if (!session()->has('token')) {
                return redirect()->route('Login')->with('error', 'You must be logged in to access the menu.');
            }

            // Hit API Merchant
            $client = new Client();
            $responseTransaksi = $client->post(env('API_BASE_URL') .'/orders/result', [
                'headers' => [
                    'API_KEY' => session()->get('token'),
                ],
                'form_params' => [
                    'date_start' => $request->date_start,
                    'date_end' => $request->date_end
                ],
            ]);
            $dataTransaksi = json_decode($responseTransaksi->getBody()->getContents(), true);
            
            return view('sb-admin-2/mastertransaksi', [
                'data' => $dataTransaksi
            ]);

        } catch (\Exception $e) {
            Log::error('Gagal memuat data transaksi: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Gagal memuat data transaksi');
        }
    }

    public function ReportMerchantTransaction(Request $request)
    {
        try {

            if (!session()->has('token')) {
                return redirect()->route('Login')->with('error', 'You must be logged in to access the menu.');
            }

            // Hit API Merchant
            $client = new Client();
            $responseTransaksi = $client->post(env('API_BASE_URL') .'/orders/merchant/'.$request->input('merchant_id'), [
                'headers' => [
                    'API_KEY' => session()->get('token'),
                ],
                'form_params' => [
                    'date_start' => $request->input('date_start'),
                    'date_end' => $request->input('date_end')
                ],
            ]);
            $dataTransaksi = json_decode($responseTransaksi->getBody()->getContents(), true);
            
            return view('sb-admin-2/detailtransaksimerchant', [
                'data' => $dataTransaksi,
                'date_start' => $request->date_start,
                'date_end' => $request->date_end,
                'merchant_name' => $request->merchant_name,
                'total_transaksi' => $request->total_transaksi,
                'total_order' => $request->total_order,
                'total_ongkir' => $request->total_ongkir,
                'total_fee' => $request->total_fee,
            ]);

        } catch (\Exception $e) {
            Log::error('Gagal memuat data transaksi: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Gagal memuat data transaksi');
        }
    }

    public function MasterTransaksi()
    {
        try {

            if (!session()->has('token')) {
                return redirect()->route('Login')->with('error', 'You must be logged in to access the menu.');
            }

            // Hit API Merchant
            $client = new Client();
            $responseTransaksi = $client->request('GET', env('API_BASE_URL') .'/api/orders');
            $dataTransaksi = json_decode($responseTransaksi->getBody()->getContents(), true);
            
            return view('sb-admin-2/mastertransaksi', [
                'data' => $dataTransaksi
            ]);

        } catch (\Exception $e) {
            Log::error('Gagal memuat data transaksi: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Gagal memuat data transaksi');
        }
    }

    public function DetailTransaksi(Request $request)
    {
        try {
            if (!session()->has('token')) {
                return redirect()->route('Login')->with('error', 'You must be logged in to access the menu.');
            }

            // Hit API Merchant
            $client = new Client();
            $responseTransaksi = $client->request('GET', env('API_BASE_URL') .'/api/order/details/'.$request->input('id'));
            $dataTransaksi = json_decode($responseTransaksi->getBody()->getContents(), true);
            
            return view('sb-admin-2/detailtransaksi', [
                'data' => $dataTransaksi,
                'idTransaksi' => $request->id,
                'namaMerchant' => $request->nama_merchant,
                'namaPelanggan' => $request->nama_pelanggan,
                'nomorHandphone' => $request->nomor_handphone,
                'nominal' => $request->nominal,
            ]);

        } catch (\Exception $e) {
            Log::error('Gagal memuat data detail transaksi: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Gagal memuat data detail transaksi');
        }
    }
}
