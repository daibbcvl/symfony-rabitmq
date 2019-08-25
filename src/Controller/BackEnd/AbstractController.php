<?php

namespace App\Controller\BackEnd;

use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseController;

abstract class AbstractController extends BaseController
{
    /**
     * @param mixed $obj
     *
     * @return mixed
     */
    protected function toJsonSerializable($obj)
    {
        switch (true) {
            case $obj instanceof \DateTimeInterface:
                return $obj->format('Y-m-d');

            case $obj instanceof \JsonSerializable:
                return $obj->jsonSerialize();

            case $obj instanceof Pagerfanta:
                return $this->pagerToArray($obj);

            case \is_array($obj) || $obj instanceof \Traversable:
                $result = [];
                foreach ($obj as $key => $value) {
                    $result[$key] = $this->toJsonSerializable($value);
                }

                return $result;

            case \is_object($obj):
                return $this->objectToArray($obj);

            default:
                return $obj;
        }
    }

    /**
     * @param object $obj
     *
     * @return array
     */
    protected function objectToArray($obj): array
    {
        $result = [];
        try {
            $reflection = new \ReflectionClass($obj);
            $methods = $reflection->getMethods();
            foreach ($methods as $method) {
                if (0 === mb_strpos($method->getName(), 'get')) {
                    $key = $this->toSnakeCase(mb_substr($method->getName(), 3));
                    $result[$key] = $this->toJsonSerializable($method->invoke($obj));
                }
            }

            $properties = $reflection->getProperties();
            foreach ($properties as $property) {
                $key = $this->toSnakeCase($property->getName());
                $result[$key] = $this->toJsonSerializable($property->getValue($obj));
            }
        } catch (\ReflectionException $ex) {
        }

        return $result;
    }

    /**
     * @param Pagerfanta $pager
     *
     * @return array
     */
    protected function pagerToArray(Pagerfanta $pager): array
    {
        return [
            'results' => $this->toJsonSerializable($pager->getCurrentPageResults()),
            'page' => $pager->getCurrentPage(),
            'size' => $pager->getMaxPerPage(),
            'from' => $pager->getCurrentPageOffsetStart(),
            'to' => $pager->getCurrentPageOffsetEnd(),
            'total_results' => $pager->getNbResults(),
            'total_pages' => $pager->getNbPages(),
            'has_previous' => $pager->hasPreviousPage(),
            'has_next' => $pager->hasNextPage(),
        ];
    }

    /**
     * @param string $s
     *
     * @return string
     */
    private function toSnakeCase(string $s): string
    {
        return mb_strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $s));
    }
}
