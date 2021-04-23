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
 * @noinspection StaticInvocationViaThisInspection
 */

declare(strict_types=1);

namespace CoffeePhp\FailsafeJson\Test\Unit;

use CoffeePhp\FailsafeJson\FailsafeJsonTranslator;
use CoffeePhp\Json\Exception\JsonUnserializeException;
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
    protected function getJsonInstance(): FailsafeJsonTranslator
    {
        return new FailsafeJsonTranslator(new JsonTranslator());
    }

    public function testExtremeJsonCase(): void
    {
        $translator = new JsonTranslator();
        $failsafeTranslator = $this->getJsonInstance();

        $json = '\u007b\u000a\u0020\u0020\u0020\u0020\u0022\u05d8\u0027\u05e1\u005c\u0022\u05d8\u0022\u003a\u0020\u0022\u05e9\u05d3\u05d2\u05da\u05dc\u05d7\u05d7\u05e9\u0020\u05ea\u0027\u05d2\u05e9\u05d3\u05d2\u05d7\u05d9\u05e9\u0020\u05d1\u05e2\u005c\u0022\u05de\u0022\u000a\u007d';
        $this->assertSame(["ט'ס\"ט" => "שדגךלחחש ת'גשדגחיש בע\"מ"], $failsafeTranslator->unserializeArray($json));

        $this->expectException(JsonUnserializeException::class);
        $translator->unserializeArray($json);
    }
}
