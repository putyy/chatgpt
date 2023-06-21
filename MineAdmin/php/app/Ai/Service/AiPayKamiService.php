<?php
declare(strict_types=1);


namespace App\Ai\Service;

use App\Ai\Constants\ResponseCodeConst;
use App\Ai\Mapper\AiPayKamiMapper;
use App\Ai\Model\AiPayKami;
use Mine\Abstracts\AbstractService;
use Mine\Exception\NormalStatusException;

/**
 * 卡密服务类
 */
class AiPayKamiService extends AbstractService
{
    /**
     * @var AiPayKamiMapper
     */
    public $mapper;

    public function __construct(AiPayKamiMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function add(array $data){
        $num = $data['number'];
        $num = $num ?? 100;
        if ($num <= 0 || $num >= 100) {
            throw new NormalStatusException('数量必须小于或等于100', ResponseCodeConst::PARAM_FAILED);
        }
        $time     = date('Y-m-d H:i:s');
        $code_arr = HelperService::randStrArr(32, (int)$num);
        $saveAll  = [];
        $data['price']  = $data['price'] ? HelperService::encode100($data['price']) : 0;
        $data['uid']    = $data['uid'] ?: 0;
        $data['remark'] = $data['remark'] ?: '';
        foreach ($code_arr as $key=>$code){
            $saveAll[$key]['uid']        = $data['uid'];
            $saveAll[$key]['price']      = $data['price'];
            $saveAll[$key]['code']       = $code;
            $saveAll[$key]['status']     = 1;
            $saveAll[$key]['remark']     = $data['remark'];
            $saveAll[$key]['created_at'] = $time;
            $saveAll[$key]['updated_at'] = $time;
        }
        $model = new AiPayKami();
        $model->insert($saveAll);
    }
}