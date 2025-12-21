<?php
/**
 * FractalCmsCoreUserInterface.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms\core\interfaces
 */

namespace fractalCms\core\interfaces;


interface FractalCmsCoreUserInterface
{
    /**
     * @return string
     */
    public function getInitials() : string;

}
