<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!session()->has('user_id')) {
            return redirect()->route('Login')->with('error', 'You must be logged in to access the menu.');
        }

        return view('sb-admin-2/dashboard');
    }

    public function Login()
    {
        return view('sb-admin-2/login');
    }

    public function postlogin(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $client = new Client();
        $response = $client->post(env('API_BASE_URL') . '/login', [
            'form_params' => [
                'username' => $validated['username'],
                'password' => $validated['password'],
            ],
        ]);
        $responseBody = json_decode($response->getBody(), true);
        
        if($responseBody['message'] == 'sucess'){
            $request->session()->put('user_id', 'admin');
            $request->session()->put([
                'user_id' => $responseBody['user'][0]['id_user'],
                'token' => $responseBody['token'],
                'role' => $responseBody['user'][0]['role'],
            ]);
            return redirect()->route('dashboard');
        }
        else{
            return redirect()->back()->with('error', $responseBody['message']);
        }
    }

    public function logout(Request $request)
    {
        // Menghapus semua data dari sesi
        $request->session()->flush();
        return redirect()->route('Login');
    }

    public function MasterMerchant()
    {
        try {

            if (!session()->has('token')) {
                return redirect()->route('Login')->with('error', 'You must be logged in to access the menu.');
            }

            // Hit API Merchant
            $client = new Client();
            $responseMerchant = $client->request('GET', env('API_BASE_URL') . '/merchants');
            $dataMerchant = json_decode($responseMerchant->getBody()->getContents(), true);
            
            return view('sb-admin-2/mastermerchant', [
                'data' => $dataMerchant
            ]);

        } catch (\Exception $e) {
            Log::error('Gagal memuat data merchant: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Gagal memuat data merchant');
        }
    }
    
    public function postmerchant(Request $request)
    {
        try {

            if($request->input('proses') == 'save'){

                if ($request->hasFile('img_merchant')) {
                    $file = $request->file('img_merchant');
                    $fileName = $file->getClientOriginalName();
                    
                    $client = new Client();
                    $response = $client->post(env('API_BASE_URL') . '/merchants', [
                        'headers' => [
                            'API_KEY' => session()->get('token'),
                        ],
                        'form_params' => [
                            'nama' => $request->nama,
                            'deskripsi' => $request->deskripsi,
                            'image' => $fileName,
                            'latitude' => $request->latitude,
                            'longitude' => $request->longitude,
                        ],
                    ]);
                    $responseBody = json_decode($response->getBody(), true);

                    if($responseBody['message'] == 'Merchant Di tambahkan'){

                        $merchantID = $responseBody['merchant_id'];
                        //upload foto
                        $response = $client->post(env('API_BASE_URL') . '/merchant/upload', [
                            'headers' => [
                                'API_KEY' => session()->get('token'),
                            ],
                            'multipart' => [
                                [
                                    'name' => 'image',
                                    'contents' => fopen($file->getRealPath(), 'r'),
                                    'filename' => $fileName,
                                ],
                                [
                                    'name' => 'merchant_id',
                                    'contents' => $merchantID,
                                ],
                            ],
                        ]);
                        $responseBody = json_decode($response->getBody(), true);
                        
                        if($responseBody['data']['message'] == 'Foto merchant berhasil di unggah'){

                            $imageNameUpdate = $responseBody['data']['image_name'];
                            //update imagename
                            $client = new Client();
                            $response = $client->put(env('API_BASE_URL') . '/merchants/update/'. $merchantID, [
                                'headers' => [
                                    'API_KEY' => session()->get('token'),
                                ],
                                'form_params' => [
                                    'image' => $imageNameUpdate,
                                ],
                            ]);
                            
                            return redirect()->route('MasterMerchant')->with('success', 'Tambah merchant sukses');
                        }else{
                            return redirect()->route('MasterMerchant')->with('success', 'Gagal tambah foto merchant ' . $responseBody['data']['message']);
                        }
                    }else{

                        return redirect()->route('MasterMerchant')->with('success', $responseBody['message']);
                    }
                }else{
                    return redirect()->route('MasterMerchant')->with('success', 'image merchant tidak valid');
                }
            }
            else if($request->input('proses') == 'edit'){

                if ($request->hasFile('img_merchant')){
                    $file = $request->file('img_merchant');
                    $fileName = $file->getClientOriginalName();

                    //upload foto
                    $client = new Client();
                    $responseUpload = $client->post(env('API_BASE_URL') . '/merchant/upload', [
                        'headers' => [
                            'API_KEY' => session()->get('token'),
                        ],
                        'multipart' => [
                            [
                                'name' => 'image',
                                'contents' => fopen($file->getRealPath(), 'r'),
                                'filename' => $fileName,
                            ],
                            [
                                'name' => 'merchant_id',
                                'contents' => $request->merchant_id,
                            ],
                        ],
                    ]);
                    $responseBody = json_decode($responseUpload->getBody(), true);
                    
                    if($responseBody['data']['message'] == 'Foto merchant berhasil di unggah'){
                        $imageNameUpdate = $responseBody['data']['image_name'];
                        $response = $client->put(env('API_BASE_URL') . '/merchants/update/'. $request->merchant_id, [
                            'headers' => [
                                'API_KEY' => session()->get('token'),
                            ],
                            'form_params' => [
                                'nama' => $request->nama,
                                'deskripsi' => $request->deskripsi,
                                'latitude' => $request->latitude,
                                'longitude' => $request->longitude,
                                'image' => $imageNameUpdate,
                            ],
                        ]);
                        $responseBody = json_decode($response->getBody(), true);
                        return redirect()->route('MasterMerchant')->with('success', $responseBody['message']);
                    }else{
                        return redirect()->route('MasterMerchant')->with('success', 'Gagal edit merchant ' . $responseBody['data']['message']);
                    }

                }else{
                    //tanpa foto
                    $client = new Client();
                    $response = $client->put(env('API_BASE_URL') . '/merchants/update/'. $request->merchant_id, [
                        'headers' => [
                            'API_KEY' => session()->get('token'),
                        ],
                        'form_params' => [
                            'nama' => $request->nama,
                            'deskripsi' => $request->deskripsi,
                            'latitude' => $request->latitude,
                            'longitude' => $request->longitude,
                        ],
                    ]);
                    $responseBody = json_decode($response->getBody(), true);
                    return redirect()->route('MasterMerchant')->with('success', $responseBody['message']);
                }
            }
            else if($request->input('proses') == 'delete'){
                
                $client = new Client();
                $response = $client->put(env('API_BASE_URL') . '/merchants/delete/'.$request->merchant_id, [
                    'headers' => [
                        'API_KEY' => session()->get('token'),
                    ],
                ]);
                $responseBody = json_decode($response->getBody(), true);
                
                return redirect()->route('MasterMerchant')->with('success', $responseBody['message']);
            }
            else{
                return redirect()->route('MasterMerchant')->with('success', 'gagal');
            }

        } catch (\Exception $e) {
            Log::error('Gagal proses data: ' . $e->getMessage());
            return redirect()->route('MasterMerchant')->with('error', 'gagal save merchant');
        }
    }

    public function MasterMenu(Request $request)
    {
        try {
            $client = new Client();

            //kategori
            $responseKategori= $client->request('GET', env('API_BASE_URL') . '/category/'.$request->input('id'));
            $dataKategori = json_decode($responseKategori->getBody()->getContents(), true);

            //menu
            $responseMenu = $client->request('GET', env('API_BASE_URL') . '/menus/'.$request->input('id'));
            $dataMenu = json_decode($responseMenu->getBody()->getContents(), true);
            //dd($dataMenu);
            return view('sb-admin-2/mastermenu', [
                'data' => $dataMenu,
                'datakategori' => $dataKategori,
                'namaMerchant' => $request->nama,
                'deskripsiMerchant' => $request->deskripsi,
                'imgMerchant' => $request->img,
                'merchantId' => $request->input('id'),
            ]);

        } catch (\Exception $e) {
            Log::error('Gagal memuat data menu: ' . $e->getMessage());
            return redirect()->route('MasterMerchant')->with('error', 'gagal load menu');
        }
    }

    public function postmenu(Request $request)
    {
        try {

            if (!session()->has('token')) {
                return redirect()->route('Login')->with('error', 'You must be logged in to access the menu.');
            }

            if($request->input('proses') == 'save'){

                // Periksa apakah file ada
                if ($request->hasFile('img_menu')) {
                    $file = $request->file('img_menu');
                    $fileName = $file->getClientOriginalName();
                    
                    $client = new Client();

                    //upload foto
                    $response = $client->post(env('API_BASE_URL') . '/menu/upload', [
                        'headers' => [
                            'API_KEY' => session()->get('token'),
                        ],
                        'multipart' => [
                            [
                                'name' => 'image',
                                'contents' => fopen($file->getRealPath(), 'r'),
                                'filename' => $fileName,
                            ],
                            [
                                'name' => 'merchant_id',
                                'contents' => $request->merchant_id,
                            ],
                            [
                                'name' => 'sku',
                                'contents' => $request->sku,
                            ],
                        ],
                    ]);
                    
                    $responseBody = json_decode($response->getBody(), true);
                    
                    if($responseBody['data']['message'] == 'Foto menu berhasil di unggah'){
                        $response = $client->post(env('API_BASE_URL') . '/menus', [
                            'headers' => [
                                'API_KEY' => session()->get('token'),
                            ],
                            'form_params' => [
                                'sku' => $request->sku,
                                'nama' => $request->nama,
                                'harga' => $request->harga,
                                'image' => $responseBody['data']['image_name'],
                                'merchant_id' => $request->merchant_id,
                                'kategori' => $request->kategori,
                            ],
                        ]);
                        $responseBody = json_decode($response->getBody(), true);
                        if($responseBody['message'] == 'Menu Di tambahkan'){
                            return redirect()->route('MasterMerchant')->with('success', 'Tambah menu sukses');
                        }else{
                            return redirect()->route('MasterMerchant')->with('success', $responseBody['message']);
                        }
                    }else{
                        return redirect()->route('MasterMerchant')->with('success', 'Gagal tambah menu ' . $responseBody['data']['message']);
                    }
                }else{
                    return redirect()->route('MasterMerchant')->with('success', 'image menu tidak valid');
                }
            }
            else if($request->input('proses') == 'edit'){
                if ($request->hasFile('img_menu')){
                    $file = $request->file('img_menu');
                    $fileName = $file->getClientOriginalName();
                    
                    //upload foto
                    $client = new Client();
                    $response = $client->post(env('API_BASE_URL') . '/menu/upload', [
                        'headers' => [
                            'API_KEY' => session()->get('token'),
                        ],
                        'multipart' => [
                            [
                                'name' => 'image',
                                'contents' => fopen($file->getRealPath(), 'r'),
                                'filename' => $fileName,
                            ],
                            [
                                'name' => 'merchant_id',
                                'contents' => $request->merchant_id,
                            ],
                            [
                                'name' => 'sku',
                                'contents' => $request->sku,
                            ],
                        ],
                    ]);
                    $responseBody = json_decode($response->getBody(), true);
                    if($responseBody['data']['message'] == 'Foto menu berhasil di unggah'){
                        $response = $client->put(env('API_BASE_URL') . '/menu/update/'.$request->menu_id, [
                            'headers' => [
                                'API_KEY' => session()->get('token'),
                            ],
                            'form_params' => [
                                'sku' => $request->sku,
                                'nama' => $request->nama,
                                'harga' => $request->harga,
                                'kategori' => $request->kategori,
                                'image' => $responseBody['data']['image_name'],
                            ],
                        ]);
                        return redirect()->route('MasterMerchant')->with('success', 'Edit menu sukses');
                    }else{
                        return redirect()->route('MasterMerchant')->with('success', 'Gagal tambah menu ' . $responseBody['data']['message']);
                    }
                }else{
                    //tanpa foto
                    $client = new Client();
                    $response = $client->put(env('API_BASE_URL') . '/menu/update/'.$request->menu_id, [
                        'headers' => [
                            'API_KEY' => session()->get('token'),
                        ],
                        'form_params' => [
                            'sku' => $request->sku,
                            'nama' => $request->nama,
                            'harga' => $request->harga,
                            'kategori' => $request->kategori,
                        ],
                    ]);
                    $responseBody = json_decode($response->getBody(), true);
                    
                    return redirect()->route('MasterMerchant')->with('success', $responseBody['message']);
                }
            }
            else if($request->input('proses') == 'delete'){

                $client = new Client();
                $response = $client->put(env('API_BASE_URL') . '/menu/delete/'.$request->menu_id, [
                    'headers' => [
                        'API_KEY' => session()->get('token'),
                    ],
                ]);
                $responseBody = json_decode($response->getBody(), true);
                
                return redirect()->route('MasterMerchant')->with('success', $responseBody['message']);
            }else{
                return redirect()->route('MasterMerchant')->with('success', 'gagal');
            }

        } catch (\Exception $e) {
            Log::error('Gagal proses data: ' . $e->getMessage());
            return redirect()->route('MasterMerchant')->with('error', 'gagal save menu');
        }
    }

    public function MasterKategori(Request $request)
    {
        try {

            if (!session()->has('token')) {
                return redirect()->route('Login')->with('error', 'You must be logged in to access the menu.');
            }

            // Hit API Merchant
            $client = new Client();
            $responseKategori= $client->request('GET', env('API_BASE_URL') . '/category/'.$request->input('id'));
            $dataKategori = json_decode($responseKategori->getBody()->getContents(), true);
           //dd($dataKategori);
            return view('sb-admin-2/masterkategori', [
                'data' => $dataKategori,
                'namaMerchant' => $request->nama,
                'deskripsiMerchant' => $request->deskripsi,
                'imgMerchant' => $request->img,
                'merchantId' => $request->input('id'),
            ]);

        } catch (\Exception $e) {
            Log::error('Gagal memuat data kategori: ' . $e->getMessage());
            return redirect()->route('MasterMerchant')->with('error', 'gagal load kategori');
        }
    }

    public function postkategori(Request $request)
    {
        try {

            if (!session()->has('token')) {
                return redirect()->route('Login')->with('error', 'You must be logged in to access the menu.');
            }
            //dd($request);
            if($request->input('proses') == 'save'){
                $client = new Client();
                $response = $client->post(env('API_BASE_URL') . '/category', [
                    'headers' => [
                        'API_KEY' => session()->get('token'),
                    ],
                    'form_params' => [
                        'nama' => $request->nama,
                        'merchant_id' => $request->merchant_id,
                    ],
                ]);
                $responseBody = json_decode($response->getBody(), true);
        
                return redirect()->route('MasterMerchant')->with('success', $responseBody['message']);
            }
            else if($request->input('proses') == 'edit'){
                $client = new Client();
                $response = $client->put(env('API_BASE_URL') . '/category/update/'.$request->kategori_id, [
                    'headers' => [
                        'API_KEY' => session()->get('token'),
                    ],
                    'form_params' => [
                        'nama' => $request->nama,
                        'merchant_id' => $request->merchant_id,
                    ],
                ]);
                $responseBody = json_decode($response->getBody(), true);
                
                return redirect()->route('MasterMerchant')->with('success', $responseBody['message']);
            }
            else if($request->input('proses') == 'delete'){
                $client = new Client();
                $response = $client->put(env('API_BASE_URL') . '/category/delete/'.$request->kategori_id, [
                    'headers' => [
                        'API_KEY' => session()->get('token'),
                    ],
                    'form_params' => [
                        'nama' => $request->nama,
                    ],
                ]);
                $responseBody = json_decode($response->getBody(), true);
                //dd($responseBody);
                return redirect()->route('MasterMerchant')->with('success', $responseBody['message']);
            }else{
                return redirect()->route('MasterMerchant')->with('success', 'gagal');
            }

        } catch (\Exception $e) {
            Log::error('Gagal proses data: ' . $e->getMessage());
            return redirect()->route('MasterMerchant')->with('error', 'gagal save kategori');
        }
    }

}
