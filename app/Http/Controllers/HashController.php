<?php
/**
 * HashController file contains HashController class only.
 * 
 * @author Mukarram <mukarramishaq189@gmail.com>
 */

namespace App\Http\Controllers;

use \Mukarramishaq\TestHash\Helpers\TestHash;
use Illuminate\Http\Request;

/**
 * HashController class controls routes relating to hash
 * 
 * @author Mukarram <mukarramishaq@gmail.com>
 */
class HashController extends Controller
{
    /**
     * controller of /hash endpoint. it returns a random hash of 
     * size 128 of random string in json response
     * 
     * @param string $stringToBeHashed [optional] [default is empty string]
     * @return Illuminate\Http\Response
     */
    public function hash($stringToBeHashed='')
    {
        $stringToBeHashed = empty($stringToBeHashed) ? $this->generateRandomString(rand(20, 50)) : $stringToBeHashed;
        $hash = TestHash::hash($stringToBeHashed, 128);
        \Log::info("MK DEBUG: Random Generated String: $stringToBeHashed");
        \Log::info("MK DEBUG: Hash: $hash");
        return response()->json(['hash' => $hash]);
    }

    /**
     * generates random string of given length
     *
     * @param integer $stringLength length of required string
     * @return string
     */
    public function generateRandomString($stringLength)
    {
        $string = "";
        $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $len = strlen($domain);
        for ($i = 0; $i < $stringLength; $i++) {
            $index = rand(0, $len - 1);
            $string .= $domain[$index];
        }
        return $string;
    }
}
