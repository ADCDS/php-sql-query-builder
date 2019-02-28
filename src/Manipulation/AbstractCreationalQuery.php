<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/24/14
 * Time: 12:30 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Sql\QueryBuilder\Manipulation;

use NilPortugues\Sql\QueryBuilder\Syntax\SQLFunction;

/**
 * Class AbstractCreationalQuery.
 */
abstract class AbstractCreationalQuery extends AbstractBaseQuery
{
    /**
     * @var array
     */
    protected $values = [];

    /**
     * @param string $table
     * @param array  $values
     */
    public function __construct($table = null, array $values = null)
    {
        if (isset($table)) {
            $this->setTable($table);
        }

        if (!empty($values)) {
            $this->setValues($values);
        }
    }

    /**
     * @return array
     */
    public function getValues()
    {
        $res = [];
        //Filter out SQLFunctions
        foreach ($this->values as $key=>$value){
            if(!($value instanceof SQLFunction))
                $res[$key] = $value;
        }
        return $res;
    }

    /**
     * @return array
     */
    public function getValuesWithFunctions()
    {
        return $this->values;
    }

    /**
     * @param array $values
     *
     * @return $this
     */
    public function setValues(array $values)
    {
        $this->values = \array_filter($values, function($value) {
            return !is_null($value);
        });
        
        return $this;
    }
}
