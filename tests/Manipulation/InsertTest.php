<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 6/3/14
 * Time: 1:37 AM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Sql\QueryBuilder\Manipulation;

use NilPortugues\Sql\QueryBuilder\Manipulation\Insert;
use NilPortugues\Sql\QueryBuilder\Syntax\SQLFunction;

/**
 * Class InsertTest.
 */
class InsertTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Insert
     */
    private $query;
    /**
     *
     */
    protected function setUp()
    {
        $this->query = new Insert();
    }

    /**
     * @test
     */
    public function itShouldGetPartName()
    {
        $this->assertSame('INSERT', $this->query->partName());
    }

    /**
     * @test
     */
    public function itShouldSetValues()
    {
        $values = ['user_id' => 1, 'username' => 'nilportugues'];

        $this->query->setValues($values);

        $this->assertSame($values, $this->query->getValues());
    }

    /**
     * @test
     */
    public function itShouldGetColumns()
    {
        $values = ['user_id' => 1, 'username' => 'nilportugues'];

        $this->query->setValues($values);

        $columns = $this->query->getColumns();

        $this->assertInstanceOf('NilPortugues\Sql\QueryBuilder\Syntax\Column', $columns[0]);
    }

    public function testSQLFunction(){
        $values = ['id' => 1,
            'created_at' => new SQLFunction("NOW", ""),
            'updated_at' => new SQLFunction("NOW", ""),
            'is_admin' => true
        ];

        $this->query->setValues($values);

        unset($values['created_at']);
        unset($values['updated_at']);
        $valueGotten = $this->query->getValues();
        $this->assertSame($values, $valueGotten);
    }

    public function testValuesOnDuplicateKeyUpdate(){
        $values = ['id' => 1,
            'created_at' => new SQLFunction("NOW", ""),
            'updated_at' => new SQLFunction("NOW", ""),
            'is_admin' => true
        ];

        $this->query->setValues($values);

        $onDuplicateValues = ['updated_at' => new SQLFunction("NOW", "")];
        $this->query->onDuplicateKeyUpdate($onDuplicateValues);

        unset($values['created_at']);
        unset($values['updated_at']);

        $valueGotten = $this->query->getValues();
        $onDuplicateValuesGotten = $this->query->getOnDuplicateKeyUpdateValuesWithFunctions();
        $this->assertSame($values, $valueGotten);
        $this->assertSame($onDuplicateValues, $onDuplicateValuesGotten);
    }

    public function testValuesOnDuplicateKeyUpdateMixedValues(){
        $values = ['id' => 1,
            'created_at' => new SQLFunction("NOW", ""),
            'updated_at' => new SQLFunction("NOW", ""),
            'is_admin' => true,
            'count' => 1
        ];

        $this->query->setValues($values);

        $onDuplicateValues = ['updated_at' => new SQLFunction("NOW", ""), 'count' => -1];
        $this->query->onDuplicateKeyUpdate($onDuplicateValues);

        unset($values['created_at']);
        unset($values['updated_at']);

        $valueGotten = $this->query->getValues();
        $onDuplicateValuesGotten = $this->query->getOnDuplicateKeyUpdateValuesWithFunctions();
        $this->assertSame($values, $valueGotten);
        $this->assertSame($onDuplicateValues, $onDuplicateValuesGotten);
    }
}
