<?php

/**
 * FailsafeJsonTranslator.php
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

declare(strict_types=1);

namespace CoffeePhp\FailsafeJson;

use CoffeePhp\Json\Contract\JsonTranslatorInterface;
use CoffeePhp\Json\Exception\JsonUnserializeException;
use CoffeePhp\Json\JsonTranslator;
use Throwable;

use function array_walk_recursive;
use function is_string;
use function mb_convert_encoding;
use function pack;
use function preg_replace_callback;
use function str_replace;
use function trim;

/**
 * Class FailsafeJsonTranslator
 * @package coffeephp\failsafe-json
 * @since 2020-08-07
 * @author Danny Damsky <dannydamsky99@gmail.com>
 */
final class FailsafeJsonTranslator implements JsonTranslatorInterface
{
    /**
     * Code used for escaping double quotes before parsing the JSON.
     *
     * @var string
     */
    private const DOUBLE_QUOTE_ESCAPE_KEY = '#DOUBLE_QUOTE_ESCAPE_KEY#';

    /**
     * Code used for escaping forward slashes before parsing the JSON.
     *
     * @var string
     */
    private const FORWARD_SLASH_ESCAPE_KEY = '#FORWARD_SLASH_ESCAPE_KEY#';

    /**
     * Code used for escaping backward slashes before parsing the JSON.
     *
     * @var string
     */
    private const BACKWARD_SLASH_ESCAPE_KEY = '#BACKWARD_SLASH_ESCAPE_KEY#';

    /**
     * Code used for escaping single backward slashes before parsing the JSON.
     *
     * @var string
     */
    private const SINGLE_BACKWARD_SLASH_ESCAPE_KEY = '#SINGLE_BACKWARD_SLASH_ESCAPE_KEY#';

    /**
     * Code used for escaping new lines before parsing the JSON.
     *
     * @var string
     */
    private const NEWLINE_ESCAPE_KEY = '#NEWLINE_ESCAPE_KEY#';

    /**
     * Code used for escaping unescaped new lines before parsing the JSON.
     *
     * @var string
     */
    private const UNESCAPED_NEWLINE_ESCAPE_KEY = '#UNESCAPED_NEWLINE_ESCAPE_KEY#';

    /**
     * Dangerous character searches.
     *
     * @var array<int, string>
     */
    private const DANGEROUS_SEARCHES = [
        '\\\\',
        '\\',
        '\n',
        "\n",
    ];

    /**
     * Dangerous character replacements.
     *
     * @var array<int, string>
     */
    private const DANGEROUS_REPLACEMENTS = [
        self::BACKWARD_SLASH_ESCAPE_KEY,
        self::SINGLE_BACKWARD_SLASH_ESCAPE_KEY,
        self::UNESCAPED_NEWLINE_ESCAPE_KEY,
        self::NEWLINE_ESCAPE_KEY,
    ];

    /**
     * Searches for escaped keys.
     *
     * @var array<int, string>
     */
    private const ESCAPE_SEARCHES = [
        self::DOUBLE_QUOTE_ESCAPE_KEY,
        self::FORWARD_SLASH_ESCAPE_KEY,
        self::BACKWARD_SLASH_ESCAPE_KEY,
        self::SINGLE_BACKWARD_SLASH_ESCAPE_KEY,
        self::NEWLINE_ESCAPE_KEY,
        self::UNESCAPED_NEWLINE_ESCAPE_KEY,
    ];

    /**
     * Replacements for escaped keys.
     *
     * @var array<int, string>
     */
    private const ESCAPE_REPLACEMENTS = [
        '"',
        '/',
        '\\\\',
        '\\',
        "\n",
        '\n',
    ];

    /**
     * FailsafeJsonTranslator constructor.
     */
    public function __construct(private JsonTranslator $jsonTranslator)
    {
    }

    /**
     * @inheritDoc
     */
    public function unserializeArray(string $string): array
    {
        $string = trim($string);
        try {
            return $this->jsonTranslator->unserializeArray($string);
        } catch (JsonUnserializeException) {
            return $this->handleUnserializeArrayFailsafeMethod($string);
        }
    }

    /**
     * @param string $string
     * @return array
     */
    private function handleUnserializeArrayFailsafeMethod(string $string): array
    {
        try {
            return $this->unserializeArrayFailsafeMethod($string);
        } catch (JsonUnserializeException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new JsonUnserializeException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    /**
     * @param string $string
     * @return array
     */
    private function unserializeArrayFailsafeMethod(string $string): array
    {
        $string = str_replace('\/', self::FORWARD_SLASH_ESCAPE_KEY, $string);
        $string = $this->escapeDoubleQuotesInJsonString($string, self::DOUBLE_QUOTE_ESCAPE_KEY);
        $string = $this->convertToUtf8($string);
        try {
            return $this->jsonTranslator->unserializeArray($string);
        } catch (Throwable) {
            $string = str_replace(self::DANGEROUS_SEARCHES, self::DANGEROUS_REPLACEMENTS, $string);
            $array = $this->jsonTranslator->unserializeArray($string);
            array_walk_recursive(
                $array,
                static function (mixed &$item): void {
                    if (is_string($item) && !empty($item)) {
                        $item = str_replace(self::ESCAPE_SEARCHES, self::ESCAPE_REPLACEMENTS, $item);
                    }
                }
            );
            return $array;
        }
    }

    /**
     * Fixes the double quotes issue in some JSONs.
     *
     * @param string $string
     * @param string $replacement
     * @return string
     */
    private function escapeDoubleQuotesInJsonString(string $string, string $replacement = "''"): string
    {
        return (string)preg_replace_callback(
            '/(?<!\\\\)(?:\\\\{2})*\\\\(?!\\\\)"/',
            static fn(array $match): string => str_replace('\\"', $replacement, (string)$match[0]),
            $string
        );
    }

    /**
     * Converts a given unicode string to UTF-8.
     *
     * @param string $string
     * @return string
     */
    private function convertToUtf8(string $string): string
    {
        return (string)preg_replace_callback(
            '/\\\\u([0-9a-fA-F]{4})/',
            static fn(array $match): string => mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE'),
            $string
        );
    }

    /**
     * @inheritDoc
     */
    public function serializeArray(array $array): string
    {
        return $this->jsonTranslator->serializeArray($array);
    }

    /**
     * @inheritDoc
     */
    public function serializeObject(object $object): string
    {
        return $this->jsonTranslator->serializeObject($object);
    }

    /**
     * @inheritDoc
     */
    public function unserializeObject(string $string): object
    {
        return $this->jsonTranslator->unserializeObject($string);
    }
}
