<?php

declare(strict_types = 1);

namespace Graphpinator\QueryCost\Tests;

use \Infinityloop\Utils\Json;

final class MaxNodesModuleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return \Graphpinator\Typesystem\Type|\Graphpinator\QueryCost\Tests\__anonymous @260
     */
    public static function getQuery($type) : \Graphpinator\Typesystem\Type|__anonymous
    {
        return new class ($type) extends \Graphpinator\Typesystem\Type {
            public function __construct(
                private \Graphpinator\Typesystem\Type $type,
            )
            {
                parent::__construct();
            }

            public function validateNonNullValue(mixed $rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'field',
                        $this->type->notNull(),
                        static function ($parent) : int {
                            return 1;
                        },
                    ),
                ]);
            }
        };
    }

    /**
     * @return \Graphpinator\Typesystem\Type|\Graphpinator\QueryCost\Tests\__anonymous @1324
     */
    public static function getTestType() : \Graphpinator\Typesystem\Type|__anonymous
    {
        return new class extends \Graphpinator\Typesystem\Type {
            public function validateNonNullValue(mixed $rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'fieldA',
                        $this,
                        static function ($parent, $limit) : int {
                            return 1;
                        },
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'limit',
                            \Graphpinator\Container\Container::Int(),
                        ),
                    ])),
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'stringField',
                        $this,
                        static function ($parent, $limit) : string {
                            return 'stringValue';
                        },
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'limit',
                            \Graphpinator\Container\Container::String(),
                        ),
                    ])),
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'fieldB',
                        $this,
                        static function ($parent, $first) : int {
                            return 1;
                        },
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'first',
                            \Graphpinator\Container\Container::Int(),
                        ),
                    ])),
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'fieldC',
                        $this,
                        static function ($parent, $last) : int {
                            return $last;
                        },
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'last',
                            \Graphpinator\Container\Container::Int(),
                        ),
                    ])),
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'scalar',
                        \Graphpinator\Container\Container::Int()->notNull(),
                        static function ($parent, $arg) : int {
                            return 1;
                        },
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'arg',
                            \Graphpinator\Container\Container::Int(),
                        ),
                    ])),
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'fieldNoArgs',
                        \Graphpinator\Container\Container::Int()->notNull(),
                        static function ($parent) : int {
                            return 1;
                        },
                    ),
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'emptyField',
                        MaxNodesModuleTest::getOneFieldType(),
                        static function ($parent) : void {
                        },
                    ),
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'multipleArgumentField',
                        $this,
                        static function ($parent, $limit, $last, $first) : void {
                        },
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'limit',
                            \Graphpinator\Container\Container::Int(),
                        ),
                        \Graphpinator\Argument\Argument::create(
                            'last',
                            \Graphpinator\Container\Container::Int(),
                        ),
                        \Graphpinator\Argument\Argument::create(
                            'first',
                            \Graphpinator\Container\Container::Int(),
                        ),
                    ])),
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'lastField',
                        $this,
                        static function ($parent, $limit) : void {
                        },
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'limit',
                            \Graphpinator\Container\Container::Int(),
                        )->setDefaultValue(0),
                    ])),
                ]);
            }
        };
    }

    public static function getOneFieldType() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            public function validateNonNullValue(mixed $rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'name',
                        \Graphpinator\Container\Container::String(),
                        static function () : string {
                            return 'testName';
                        },
                    ),
                ]);
            }
        };
    }

    public function simpleDataProvider() : array
    {
        return [
            [
                \Infinityloop\Utils\Json::fromNative((object) [
                    'query' => '{ field { fieldA { fieldA(limit: 10) { fieldB(first: 5) { fieldC(last: 2) { scalar(arg: 5) } } } } } }',
                    ]),
                \Infinityloop\Utils\Json::fromNative(
                    (object) ['data' => ['field' => ['fieldA' => ['fieldA' => ['fieldB' => ['fieldC' => ['scalar' => 1]]]]]]],
                ),
            ],
            [
                \Infinityloop\Utils\Json::fromNative((object) [
                    'query' => '{ field { stringField(limit: "testVal") {fieldA(limit: 0) { scalar } } } }',
                ]),
                \Infinityloop\Utils\Json::fromNative(
                    (object) ['data' => ['field' => ['stringField' => ['fieldA' => ['scalar' => 1]]]]],
                ),
            ],
            [
                \Infinityloop\Utils\Json::fromNative((object) [
                    'query' => '{ field { fieldA(limit: -1) { stringField(limit: "test") { scalar emptyField { name } } } } }',
                ]),
                \Infinityloop\Utils\Json::fromNative(
                    (object) ['data' => ['field' => ['fieldA' => ['stringField' => ['scalar' => 1, 'emptyField' => null]]]]],
                ),
            ],
            [
                \Infinityloop\Utils\Json::fromNative((object) [
                    'query' => '{ field { lastField(limit: 0) { scalar } } }',
                ]),
                \Infinityloop\Utils\Json::fromNative(
                    (object) ['data' => ['field' => ['lastField' => null]]],
                ),
            ],
            [
                \Infinityloop\Utils\Json::fromNative((object) [
                    'query' => '{ field { multipleArgumentField(limit: 0, last: 5, first: 20) { scalar } } }',
                ]),
                \Infinityloop\Utils\Json::fromNative(
                    (object) ['data' => ['field' => ['multipleArgumentField' => null]]],
                ),
            ],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param \Infinityloop\Utils\Json $request
     * @param \Infinityloop\Utils\Json $expected
     */
    public function testSimple(Json $request, Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(
            $this->getSchema(),
            false,
            new \Graphpinator\Module\ModuleSet([new \Graphpinator\QueryCost\MaxNodesModule(262, ['limit', 'last', 'first'])]),
        );
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }

    public function testInvalid() : void
    {
        $exception = new \Graphpinator\QueryCost\Exception\MaximalQueryCostWasReached(10);

        self::assertSame('Maximal query cost 10 was reached.', $exception->getMessage());
        self::assertTrue($exception->isOutputable());

        $this->expectException(\Graphpinator\QueryCost\Exception\MaximalQueryCostWasReached::class);

        $graphpinator = new \Graphpinator\Graphpinator(
            self::getSchema(),
            false,
            new \Graphpinator\Module\ModuleSet([new \Graphpinator\QueryCost\MaxNodesModule(311)]),
        );
        $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory(\Infinityloop\Utils\Json::fromNative((object) [
            'query' => '{ field { fieldA { fieldA(limit: 10) { fieldB(first: 10) { fieldC { scalar } } } } } }',
        ])));
    }

    private function getContainer() : \Graphpinator\SimpleContainer
    {
        return new \Graphpinator\SimpleContainer([self::getQuery(self::getTestType())], []);
    }

    private function getSchema() : \Graphpinator\Typesystem\Schema
    {
        return new \Graphpinator\Typesystem\Schema(self::getContainer(), self::getQuery(self::getTestType()));
    }
}
