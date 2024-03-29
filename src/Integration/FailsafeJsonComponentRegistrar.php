<?php

/**
 * FailsafeJsonComponentRegistrar.php
 *
 * Copyright 2020 Danny Damsky
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package coffeephp\failsafe-json
 * @author Danny Damsky <dannydamsky99@gmail.com>
 * @since 2020-08-29
 */

declare(strict_types=1);

namespace CoffeePhp\FailsafeJson\Integration;

use CoffeePhp\ComponentRegistry\Contract\ComponentRegistrarInterface;
use CoffeePhp\Di\Contract\ContainerInterface;
use CoffeePhp\FailsafeJson\FailsafeJsonTranslator;

/**
 * Class FailsafeJsonComponentRegistrar
 * @package coffeephp\failsafe-json
 * @author Danny Damsky <dannydamsky99@gmail.com>
 * @since 2020-08-29
 */
final class FailsafeJsonComponentRegistrar implements ComponentRegistrarInterface
{
    /**
     * FailsafeJsonComponentRegistrar constructor.
     */
    public function __construct(private ContainerInterface $di)
    {
    }

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->di->bind(FailsafeJsonTranslator::class, FailsafeJsonTranslator::class);
    }
}
