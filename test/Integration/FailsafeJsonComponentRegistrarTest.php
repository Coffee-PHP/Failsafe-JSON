<?php

/**
 * FailsafeJsonComponentRegistrarTest.php
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
 * @since 2020-08-31
 */

declare(strict_types=1);

namespace CoffeePhp\FailsafeJson\Test\Integration;

use CoffeePhp\ComponentRegistry\ComponentRegistry;
use CoffeePhp\Di\Container;
use CoffeePhp\FailsafeJson\FailsafeJsonTranslator;
use CoffeePhp\FailsafeJson\Integration\FailsafeJsonComponentRegistrar;
use CoffeePhp\Json\Contract\JsonTranslatorInterface;
use CoffeePhp\Json\Integration\JsonComponentRegistrar;
use CoffeePhp\Json\JsonTranslator;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertNotInstanceOf;
use function PHPUnit\Framework\assertTrue;

/**
 * Class FailsafeJsonComponentRegistrarTest
 * @package coffeephp\failsafe-json
 * @since 2020-08-31
 * @author Danny Damsky <dannydamsky99@gmail.com>
 * @see FailsafeJsonComponentRegistrar
 */
final class FailsafeJsonComponentRegistrarTest extends TestCase
{
    /**
     * @see JsonComponentRegistrar::register()
     * @see FailsafeJsonComponentRegistrar::register()
     */
    public function testRegisterWithDependencies(): void
    {
        $di = new Container();
        $registry = new ComponentRegistry($di);
        $registry->register(JsonComponentRegistrar::class);
        $registry->register(FailsafeJsonComponentRegistrar::class);

        assertTrue(
            $di->has(JsonTranslatorInterface::class)
        );
        assertTrue(
            $di->has(JsonTranslator::class)
        );

        assertInstanceOf(
            FailsafeJsonTranslator::class,
            $di->get(FailsafeJsonTranslator::class)
        );

        assertInstanceOf(
            JsonTranslator::class,
            $di->get(JsonTranslator::class)
        );

        assertNotInstanceOf(
            FailsafeJsonTranslator::class,
            $di->get(JsonTranslator::class)
        );

        assertNotInstanceOf(
            FailsafeJsonTranslator::class,
            $di->get(JsonTranslatorInterface::class)
        );
    }
}
