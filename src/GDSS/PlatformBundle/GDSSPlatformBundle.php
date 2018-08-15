<?php

namespace GDSS\PlatformBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class GDSSPlatformBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
