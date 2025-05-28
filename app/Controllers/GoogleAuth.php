<?php namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CustomerModel;

class GoogleAuth extends BaseController
{
    public function redirect()
    {
        $client = new \Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $client->addScope('email');
        $client->addScope('profile');

        // Buat URL OAuth dan redirect ke Google
        return redirect()->to($client->createAuthUrl());
    }

    public function callback()
    {
        $client = new \Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));

        $code = $this->request->getVar('code');
        if ($code) {
            $token = $client->fetchAccessTokenWithAuthCode($code);

            if (!isset($token['error'])) {
                $client->setAccessToken($token['access_token']);

                $google_oauth = new \Google_Service_Oauth2($client);
                $google_user = $google_oauth->userinfo->get();

                $userModel = new UserModel();
                $customerModel = new CustomerModel();

                $existingUser = $userModel->where('email', $google_user->email)->first();

                if (!$existingUser) {
                    // Simpan user baru dari Google
                    $userData = [
                        'nama' => $google_user->name,
                        'email' => $google_user->email,
                        'password' => null, // password null karena login pakai Google
                        'role' => 'customer',
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                    $userModel->save($userData);

                    $userId = $userModel->getInsertID();

                    // Simpan juga data customer
                    $customerModel->save([
                        'user_id' => $userId,
                        'nama' => $google_user->name,
                        'email' => $google_user->email,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);

                    $existingUser = $userModel->find($userId);
                }

                // Simpan session user
                session()->set('user', $existingUser);

                // Jika user belum punya password, arahkan ke halaman atur password
                if (empty($existingUser['password'])) {
                    return redirect()->to('/atur-password');
                }

                // Jika sudah punya password, langsung ke dashboard
                return redirect()->to('/dashboard');
            }
        }

        // Jika gagal, kembali ke login
        return redirect()->to('/login')->with('error', 'Login dengan Google gagal.');
    }
}
