<?php
/**
 * FractalCmsCoreInterface.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms\core\interfaces
 */

namespace fractalCms\core\interfaces;


interface FractalCmsCoreInterface
{
    /**
     * @return string
     */
    public function getName() : string;

    /**
     * Get permissions Rbac
     *
     * @return array
     */
    public function getPermissions():array;

    /**
     * Get menu structure
     *
     * @return array
     */
    public function getMenu():array;

    /**
     * Get routes
     *
     * @return array
     */
    public function getRoutes():array;

    /**
     * Get context Id
     *
     * @return string
     */
    public function getContextId():string;

    /**
     * Set context Id
     *
     * @return string
     */
    public function setContextId(string $id):void;

    /**
     * get informations from other Modules
     *
     * @return array
     */
    public function getInformations() : array;
}
