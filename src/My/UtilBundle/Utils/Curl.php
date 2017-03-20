<?php
/**
 * Created by PhpStorm.
 * User: kuni
 * Date: 2017/03/15
 * Time: 15:58
 */
namespace My\UtilBundle\Utils;

class Curl
{
    /**
     * Curl:Json形式で結果を取得
     * @param $url
     * @param null $post_params
     * @return array
     */
    public static function execCurl2Json( $url, $post_params = null)
    {
        $additional_options = array(
            CURLOPT_RETURNTRANSFER => true,
        );

        $result = self::execCurl( $url, $post_params, $additional_options);
        $curl_info = $result['info'];

        $successful = false;
        $json_output = "";
        if( $curl_info['http_code'] >= 400 ){
        }else{
            $successful = true;
            $json_output = json_decode($result['body'], true);
        }
        return array(
            'successful' => $successful,
            'data'       => $json_output,
            'info'       => $curl_info,
        );
    }

    /**
     * Execute Curl
     * @param $url
     * @param null $post_params
     * @param array $additional_options
     * @return array
     */
    public static function execCurl( $url, $post_params = null, $additional_options = array() ){
        // ** Init
        $handle = curl_init($url);

        // ** Set Option
        // * Redirect
        curl_setopt($handle,CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($handle,CURLOPT_MAXREDIRS,      3);
        curl_setopt($handle,CURLOPT_AUTOREFERER,    true);

        // * post params
        if( $post_params ){
            curl_setopt($handle, CURLOPT_POST,      TRUE);
            curl_setopt($handle, CURLOPT_POSTFIELDS,$post_params);
        }
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST,FALSE);

        // * Additional Options
        foreach ($additional_options as $option => $value ){
            curl_setopt($handle, $option, $value);
        }

        // ** Execute
        $result = curl_exec($handle);

        // ** Get Result Info
        $info = curl_getinfo($handle);
        $err_no = curl_errno($handle);
        $error = curl_error($handle);

        curl_close($handle);
        if ( CURLE_OK !== $err_no ) {
            throw new \RuntimeException($error, $err_no);
        }

        return array(
            'body'   => $result,
            'info'   => $info,
        );
    }

}