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

use CoffeePhp\Di\Contract\ContainerInterface;
use CoffeePhp\FailsafeJson\FailsafeJsonTranslator;
use CoffeePhp\Json\Contract\JsonTranslatorInterface;
use CoffeePhp\ComponentRegistry\Contract\ComponentRegistrarInterface;

/**
 * Class FailsafeJsonComponentRegistrar
 * @package coffeephp\failsafe-json
 * @author Danny Damsky <dannydamsky99@gmail.com>
 * @since 2020-08-29
 */
final class FailsafeJsonComponentRegistrar implements ComponentRegistrarInterface
{

    /**
     * @inheritDoc
     */
    public function register(ContainerInterface $di): void
    {
        $di->bind(JsonTranslatorInterface::class, FailsafeJsonTranslator::class);
        $di->bind(FailsafeJsonTranslator::class, FailsafeJsonTranslator::class);
    }
}
