<?php
/**
 * Created by PhpStorm.
 * User: danie
 * Date: 3/12/2018
 * Time: 2:38 PM
 */

namespace Rodasnet\Portfolio\Interfaces;

use Rodasnet\Portfolio\Interfaces\IAccessModel;

interface IAccessService
{
    public function getOne(String $slug, string $field = 'id') : IAccessModel;

}
