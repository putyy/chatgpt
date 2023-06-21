<?php

declare(strict_types=1);

namespace App\Ai\Model;

use App\Ai\Service\HelperService;
use Hyperf\Database\Model\SoftDeletes;
use Mine\MineModel;

/**
 * @property int $id 主键
 * @property int $scene 使用场景
 * @property string $img_url 图片地址
 * @property string $url 跳转地址
 * @property string $remark 备注
 * @property int $sort 排序
 * @property int $created_by 创建者
 * @property int $updated_by 更新者
 * @property string $start_at 使用开始时间
 * @property string $end_at 使用结束时间
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 */
class AiImageMaterial extends MineModel
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'ai_image_material';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'scene', 'img_url', 'url', 'remark', 'sort', 'created_by', 'updated_by', 'start_at', 'end_at', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'scene' => 'integer', 'sort' => 'integer', 'created_by' => 'integer', 'updated_by' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function setImgUrlAttribute($value)
    {
        $this->attributes['img_url'] = HelperService::buildSavePath($value);
    }

    public function getImgUrlAttribute($value)
    {
        return HelperService::buildSourceUrl($value);
    }
}
