<?php
declare(strict_types=1);

namespace App\Ai\Api;

use Hyperf\DbConnection\Db;
use Mine\MineController;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;

class BaseApi extends MineController
{
    public function checkParameter(array $params, array $checkData = [], bool $is_set = false): bool
    {
        if (empty($checkData)) {
            return true;
        }

        if ($is_set) {
            foreach ($checkData as $v) {
                if (!isset($params[$v])) {
                    return false;
                }
            }
        } else {
            foreach ($checkData as $v) {
                if (empty($params[$v])) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws \Throwable
     * @throws NotFoundExceptionInterface
     */
    public function funCallbackRes(callable $callback): ResponseInterface
    {
        return $this->success($this->funCallback($callback));
    }

    public function funCallback(callable $callback)
    {
        Db::beginTransaction();
        try {
            $res = $callback();
            Db::commit();
        } catch (\Throwable $throwable) {
            Db::rollBack();
            throw $throwable;
        }
        return $res;
    }
}