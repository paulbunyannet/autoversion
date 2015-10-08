<?php
/**
 * AutoVersionSingle
 *
 * Created 9/25/15 4:40 PM
 * Auto version a single file without creating a extra instance of AuthVersion
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package Pbc\AutoVersion
 */

namespace Pbc;


/**
 * Class AutoVersionSingle
 * @package Pbc\AutoVersion
 */
class AutoVersionSingle
{

    /**
     * Auto version a single file
     *
     * @param $file
     * @param string $method
     * @return mixed
     */
    public static function file($file, $method = 'regexp')
    {
        $autoVersion  = new AutoVersion(getenv('DOCUMENT_ROOT'));
        return $autoVersion->file($file, $method);
    }

}