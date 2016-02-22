<?php
namespace SilverStripe\Framework\Test;

/**
 * @author Luís Otávio Cobucci Oblonczyk <luis@my-clay.com>
 */
trait ModelMocker
{
    /**
     * @param string $className
     * @param array $properties
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getModelMock($className, array $properties = array())
    {
        $map = array();

        foreach ($properties as $name => $value) {
            $map[] = array($name, $value);
        }

        $mock = $this->getMockBuilder($className)
                     ->disableOriginalConstructor()
                     ->getMock();

        $mock->method('__get')
             ->willReturnMap($map);

        return $mock;
    }

    /**
     * Returns a builder object to create mock objects using a fluent interface.
     *
     * @param string $className
     *
     * @return \PHPUnit_Framework_MockObject_MockBuilder
     *
     * @since  Method available since Release 3.5.0
     */
    public abstract function getMockBuilder($className);
}
