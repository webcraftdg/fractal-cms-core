<?php
/**
 * FractalCmsCoreInterface.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package webcraftdg\fractalCms\core\interfaces
 */

namespace webcraftdg\fractalCms\core\interfaces;


interface FractalCmsCoreInterface
{
    public function getPermissions():array;
    public function getMenu():array;
    public function getContextId():string;
}
