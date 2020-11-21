<?php

namespace Logitini;

/**
 * Logitini API services
 * This is the main class for API integration into Logitini.com
 *
 * These API's include all dashboard integration
 * Version 1.0.5
 *
 *  @author Armon Kolaei
 */
class Logitini
{

    protected $domain = "https://api.logitini.com/";
    protected $app_key = "";
    protected $app_secret = "";

    function __construct($appKey, $appSecret, $env = "prod")
    {
        switch ($env) {
            case 'dev':
                $this->domain = "https://apidev.logitini.com/";
                break;
        }
        $this->app_key = $appKey;
        $this->app_secret = $appSecret;
    }

    /**
     * will record document history
     *
     * @param $currentUser
     * @param $type
     * @param $id
     * @param $data
     * @return bool
     */
    public function recordDocHistory($currentUser, $type, $id, $data)
    {
        try {
            //prepare extra data
            $userInfo = array(
                'user_id' => $currentUser['id'],
                'first_name' => isset($currentUser['first_name']) ? $currentUser['first_name'] : "",
                'last_name' => isset($currentUser['last_name']) ? $currentUser['last_name'] : "",
            );

            $dataToSent = array(
                'data' => $data,
                'lg_extra' => $userInfo
            );

            $url = "document/v1/write?";

            $postPeram = array(
                'app_key' => $this->app_key,
                'app_secret' => $this->app_secret,
                'doc_type' => $type,
                'doc_id' => $id,
            );

            $urlPeram = http_build_query($postPeram);

            $data_string = $data_string = json_encode($dataToSent);

            $ch = curl_init($this->domain . $url . $urlPeram);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

            // close the connection, release resources used
            $server_output = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

            if ($httpcode == 200) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * will get document history based on type
     *
     * @param $type
     * @param $id
     * @return bool|mixed
     */
    public function getDocHistory($type, $id)
    {
        try {
            $url = "document/v1/by_id?";

            $postPeram = array(
                'app_key' => $this->app_key,
                'app_secret' => $this->app_secret,
                'doc_type' => $type,
                'doc_id' => $id,
            );

            $urlPeram = http_build_query($postPeram);

            $ch = curl_init($this->domain . $url . $urlPeram);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
            ));

            // close the connection, release resources used
            $server_output = curl_exec($ch);

            curl_close($ch);

            return json_decode($server_output);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * will record document history
     *
     * @param $currentUser
     * @param $data
     * @return bool
     */
    public function recordOpenLog($currentUser = array(), $data)
    {
        try {
            //prepare extra data
            $userInfo = array(
                'user_id' => isset($currentUser['id']) ? $currentUser['id'] : 0,
                'first_name' => isset($currentUser['first_name']) ? $currentUser['first_name'] : "system",
                'last_name' => isset($currentUser['last_name']) ? $currentUser['last_name'] : "admin",
            );

            $dataToSent = array(
                'data' => $data,
                'lg_extra' => $userInfo
            );

            $url = "open_logs/v1/write?";

            $postPeram = array(
                'app_key' => $this->app_key,
                'app_secret' => $this->app_secret,
            );

            $urlPeram = http_build_query($postPeram);

            $data_string = $data_string = json_encode($dataToSent);

            $ch = curl_init($this->domain . $url . $urlPeram);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

            // close the connection, release resources used
            $server_output = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

            if ($httpcode == 200) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * will record document history
     *
     * @param $currentUser
     * @param $data
     * @return bool
     */
    public function recordAuditTrail($currentUser, $data)
    {
        try {
            //prepare user data
            $userInfo = array(
                'id' => isset($currentUser['user_id']) ? $currentUser['user_id'] : 0,
                'name' => $currentUser['first_name'] . " " .  $currentUser['last_name'],
                'user_id' => isset($currentUser['user_id']) ? $currentUser['user_id'] : 0,
                'first_name' => isset($currentUser['first_name']) ? $currentUser['first_name'] : "system",
                'last_name' => isset($currentUser['last_name']) ? $currentUser['last_name'] : "admin",
            );

            if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']) {
                $clientIpAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $clientIpAddress = $_SERVER['REMOTE_ADDR'];
            }

            $ua_info = parse_user_agent();

            $deviceName = $ua_info['platform'];

            $dataToSent = array(
                'user' => $userInfo,
                'action' => $data['action'],
                'device' => array(
                    'name' => $deviceName,
                ),
                'location' => array(
                    'name' => $clientIpAddress,
                ),
            );

            $url = "audit_trail/v1/write?";

            $postPeram = array(
                'app_key' => $this->app_key,
                'app_secret' => $this->app_secret,
            );

            $urlPeram = http_build_query($postPeram);

            $data_string = $data_string = json_encode($dataToSent);

            $ch = curl_init($this->domain . $url . $urlPeram);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

            // close the connection, release resources used
            $server_output = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

            if ($httpcode == 200) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * will create a new currency
     *
     * @param $data
     * @return bool
     */
    public function fintech_create_currency($data)
    {
        try {
            //currency prams
            $currencyName =  isset($data['name']) ? $data['name'] : "";
            $currencyNameShort =  isset($data['name_short']) ? $data['name_short'] : "";

            $dataToSent = array(
                'name' => $currencyName,
                'name_short' => $currencyNameShort,
            );

            $url = "fintech/bank/create_currency/" . $this->app_key;

            $data_string = $data_string = json_encode($dataToSent);

            $ch = curl_init($this->domain . $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'app_secret:' . $this->app_secret,
            ));

            // close the connection, release resources used
            $server_output = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

            if ($httpcode == 200) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * will retrieve all FinTech app currencies
     *
     * @return bool|mixed
     */
    public function fintech_get_currencies()
    {
        try {
            $url = "fintech/bank/currencies/" . $this->app_key;

            $ch = curl_init($this->domain . $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'app_secret:' . $this->app_secret,
            ));

            // close the connection, release resources used
            $server_output = curl_exec($ch);

            curl_close($ch);

            return json_decode($server_output);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * will create a new wallet by email
     *
     * @param $email
     * @return bool
     */
    public function fintech_create_wallet($email, $isMaster)
    {
        try {
            //currency prams
            $dataToSent = array(
                'email' => $email,
            );

            $url = "fintech/bank/create_wallet/" . $this->app_key;

            $data_string = $data_string = json_encode($dataToSent);
        
            $masterPerm = "false";
            if ($isMaster) {
                $masterPerm = "true";
            }

            $postPeram = array(
                'master' => $masterPerm,
            );

            $urlPeram = http_build_query($postPeram);

            $ch = curl_init($this->domain . $url . "?" . $urlPeram);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'app_secret:' . $this->app_secret,
            ));

            // close the connection, release resources used
            $server_output = curl_exec($ch);

            curl_close($ch);

            return json_decode($server_output);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * will retrieve all wallets or search by one passed
     *
     * @return bool|mixed
     */
    public function fintech_get_wallet($email = "")
    {
        try {
            $url = "fintech/bank/get_wallets/" . $this->app_key;

            if ($email == "") {
                $postPeram = array();
            }
            else {
                $postPeram = array(
                    'email' => $email,
                );
            }
            $urlPeram = http_build_query($postPeram);

            $ch = curl_init($this->domain . $url . "?" . $urlPeram);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'app_secret:' . $this->app_secret,
            ));

            
            // close the connection, release resources used
            $server_output = curl_exec($ch);

            curl_close($ch);

            return json_decode($server_output);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * will retrieve wallet by id
     *
     * @return bool|mixed
     */
    public function fintech_get_wallet_by_id($walletId)
    {
        try {
            $url = "fintech/bank/wallet/" . $this->app_key . "/" . $walletId;

            $ch = curl_init($this->domain . $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'app_secret:' . $this->app_secret,
            ));

            // close the connection, release resources used
            $server_output = curl_exec($ch);

            curl_close($ch);

            return json_decode($server_output);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * will retrieve wallet summary by id
     *
     * @return bool|mixed
     */
    public function fintech_get_wallet_summary($walletId)
    {
        try {
            $url = "fintech/bank/wallet_summary/" . $this->app_key . "/" . $walletId;

            $ch = curl_init($this->domain . $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'app_secret:' . $this->app_secret,
            ));

            // close the connection, release resources used
            $server_output = curl_exec($ch);

            curl_close($ch);

            return json_decode($server_output);
        } catch (\Exception $e) {
            return false;
        }
    }

     /**
     * will create a new wallet by email
     *
     * @param $email
     * @return bool
     */
    public function fintech_create_account($walletId, $currencyId)
    {
        try {
            $url = "fintech/bank/create_account/" . $this->app_key . "/" . $walletId . "/" . $currencyId;

            $ch = curl_init($this->domain . $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'app_secret:' . $this->app_secret,
            ));

            // close the connection, release resources used
            $server_output = curl_exec($ch);

            curl_close($ch);

            return json_decode($server_output);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * will post transaction for master wallet
     *
     * @param $data
     * @return bool
     */
    public function fintech_master_deposit($data)
    {
        try {
            $url = "fintech/account/master_deposit/" . $this->app_key;

            $data_string = $data_string = json_encode($data);

            $ch = curl_init($this->domain . $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'app_secret:' . $this->app_secret,
            ));

            // close the connection, release resources used
            $server_output = curl_exec($ch);

            curl_close($ch);

            return json_decode($server_output);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * will post transaction for wallet
     *
     * @param $data
     * @return bool
     */
    public function fintech_post_transaction($data)
    {
        try {
            $url = "fintech/account/transaction/" . $this->app_key;

            $data_string = $data_string = json_encode($data);

            $ch = curl_init($this->domain . $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'app_secret:' . $this->app_secret,
            ));

            // close the connection, release resources used
            $server_output = curl_exec($ch);

            curl_close($ch);

            return json_decode($server_output);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * will retrieve wallet transactions
     *
     * @return bool|mixed
     */
    public function fintech_get_wallet_transactions($walletId)
    {
        try {
            $url = "fintech/bank/account_posted_transactions/" . $this->app_key . "/" . $walletId;

            $ch = curl_init($this->domain . $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'app_secret:' . $this->app_secret,
            ));

            // close the connection, release resources used
            $server_output = curl_exec($ch);

            curl_close($ch);

            return json_decode($server_output);
        } catch (\Exception $e) {
            return false;
        }
    }
}