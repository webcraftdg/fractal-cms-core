<?php
/**
 * FractalCmsCoreInterface.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalcms\core\interfaces
 */

namespace fractalcms\core\interfaces;


interface FractalCmsCoreInterface
{
    public function getPermissions():array;
    public function getMenu():array;
    public function getContextId():string;
}
