<?php

/**
 * FailsafeJsonTranslatorTest.php
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
 * @since 2020-08-07
 */

declare (strict_types=1);

namespace CoffeePhp\FailsafeJson\Test\Unit;


use CoffeePhp\FailsafeJson\FailsafeJsonTranslator;
use CoffeePhp\Json\Contract\JsonTranslatorInterface;
use CoffeePhp\Json\JsonTranslator;
use CoffeePhp\Json\Test\Unit\JsonTranslatorTest;

/**
 * Class FailsafeJsonTranslatorTest
 * @package coffeephp\failsafe-json
 * @since 2020-08-07
 * @author Danny Damsky <dannydamsky99@gmail.com>
 * @see FailsafeJsonTranslator
 */
final class FailsafeJsonTranslatorTest extends JsonTranslatorTest
{
    /**
     * @return JsonTranslatorInterface
     */
    protected function getJsonInstance(): JsonTranslatorInterface
    {
        return new FailsafeJsonTranslator(new JsonTranslator());
    }
}
