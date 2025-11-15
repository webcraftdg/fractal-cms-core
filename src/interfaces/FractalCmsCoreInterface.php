<?php
/**
 * FractalCmsCoreInterface.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package FractalCMS\Core\interfaces
 */

namespace FractalCMS\Core\interfaces;


interface FractalCmsCoreInterface
{
    public function getPermissions():array;
    public function getMenu():array;
    public function getContextId():string;
}
