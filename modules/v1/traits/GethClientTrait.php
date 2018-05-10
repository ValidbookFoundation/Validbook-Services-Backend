<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\traits;

use Ethereum\Ethereum;
use Ethereum\EthereumStatic;

trait GethClientTrait
{
    public function hexMessage($message)
    {
        $hex = EthereumStatic::strToHex($message);

        return $hex;
    }

    public function formatBaseCardMessage($message, $search, $replaceMessage)
    {
        $result = str_replace($search, $replaceMessage, $message);
        return $result;
    }

    public function verifySig($hexMessage, $signature, $address)
    {
        $client = new Ethereum(getenv("GETH_URL"));

        $ver_address = $client->request("personal_ecRecover", [$hexMessage, $signature]);

        if (is_array($address)) {
            if (in_array($ver_address, $address)) {
                return true;
            }
        } else {
            $address = strtolower($address);
            if ($ver_address == $address) {
                return true;
            }
        }

        return false;
    }

    public function toSign($data, $privateKey)
    {
        $client = new Ethereum(getenv("GETH_URL"));
        $data = $client->request("eth_sign", [$data, $privateKey]);

        return $data;

    }

    public function hashMessage($message)
    {
        $message = "\x19Ethereum Signed Message:" . strlen($message) . $message;

        $client = new Ethereum(getenv("GETH_URL"));

        $hex = EthereumStatic::strToHex($message);
        $hash = $client->request("web3_sha3", [$hex]);

        return $hash;
    }

    public function hashFile($filepath)
    {
        return hash_file('sha3-256', $filepath);
    }

    public function checkTransaction($sender, $receiver, $amount, $pass)
    {
        $client = new Ethereum(getenv("GETH_URL"));

        //unlock account
        $unlockAccount = $client->request("personal_unlockAccount", [$sender, $pass]);

        if (!$unlockAccount) {
            return false;
        }

        $hotBalanceHex = $client->request("eth_getBalance", [$sender, "latest"]);

        $hotBalance = $this->bigHexToBigDec($hotBalanceHex);

        if (bccomp($hotBalance, (string)$amount, 0) == 1) {
            $transParams = [
                [
                    'from' => $sender,
                    'to' => $receiver,
                    'value' => EthereumStatic::strToHex((string)$amount)
                ]
            ];
            $trans = $client->request("eth_sendTransaction", $transParams);

            if (!empty($trans)) {
                if (!empty($client->request("eth_getTransactionReceipt", [$trans]))) {
                    return true;
                }
            }
        }
        return false;
    }


    public function bigHexToBigDec($hex)
    {
        $dec = '0';
        $len = strlen($hex);
        for ($i = 1; $i <= $len; $i++) {
            $dec = bcadd($dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
        }

        return $dec;
    }
}