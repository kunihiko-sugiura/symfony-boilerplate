<?php
namespace My\UtilBundle\Utils;

/**
 * Created by PhpStorm.
 * User: kuni
 * Date: 2017/03/15
 * Time: 15:51
 */
class Path
{

    /**
     * 元のuriに対して、指定したPathを連結します。
     * @param string|null $uri
     * @param string|null $path
     * @return string
     */
    public static function combine($uri, $path)
    {
        return self::rtrim_end_slash($uri) . DIRECTORY_SEPARATOR . self::ltrim_end_slash($path);
    }
    /**
     * URIの最初のスラッシュまたはバックスラッシュを除去
     * @param string|null $uri
     * @return string
     */
    public static function ltrim_end_slash($uri)
    {
        return ltrim($uri, '\\/');
    }
    /**
     * URIの最後のスラッシュまたはバックスラッシュを除去
     * @param string|null $uri
     * @return string
     */
    public static function rtrim_end_slash($uri)
    {
        return rtrim($uri, '\\/');
    }
}