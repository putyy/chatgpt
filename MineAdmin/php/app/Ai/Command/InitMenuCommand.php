<?php
declare(strict_types=1);

namespace App\Ai\Command;

use App\System\Model\SystemMenu;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Hyperf\DbConnection\Db;

#[Command]
class InitMenuCommand extends HyperfCommand
{

    /**
     * 执行的命令行
     */
    protected ?string $name = 'ai:init-menu';

    public function handle()
    {
        // TODO: Implement handle() method.
        $isInit = SystemMenu::where(['parent_id' => 0, 'level' => 0, 'code' => 'aiapp',])->first();
        if (!empty($isInit)) {
            $this->line('已经执行过了', 'info');
            return;
        }
        $tableName = env('DB_PREFIX') . SystemMenu::getModel()->getTable();

        $sql = "INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (0, '0', 'AI系统', 'aiapp', 'IconFire', 'ai', NULL, NULL, 2, 'M', 1, 96, 1, 1, now(), now(), NULL, NULL);
SET @topid := LAST_INSERT_ID();
SET @toplevel := CONCAT('0', ',', @topid);

-- chatgptPrompts --
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@topid, @toplevel, 'chatgpt角色', 'ai:chatgptPrompts', 'IconUser', 'ai/chatgptPrompts', 'ai/chatgptPrompts/index', NULL, 2, 'M', 1, 0, 1, 1, now(), now(), NULL, NULL);
SET @id := LAST_INSERT_ID();
SET @level := CONCAT('0',',', @topid, ',', @id);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, 'chatgpt角色列表', 'ai:chatgptPrompts:index', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, 'chatgpt角色更新', 'ai:chatgptPrompts:update', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, 'chatgpt角色保存', 'ai:chatgptPrompts:save', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, 'chatgpt角色读取', 'ai:chatgptPrompts:read', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, 'chatgpt角色删除', 'ai:chatgptPrompts:delete', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);

-- chatMessage --
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@topid, @toplevel, '聊天数据', 'ai:chatMessage', 'IconNotification', 'ai/chatMessage', 'ai/chatMessage/index', NULL, 2, 'M', 1, 0, 1, 1, now(), now(), NULL, NULL);
SET @id := LAST_INSERT_ID();
SET @level := CONCAT('0',',', @topid, ',', @id);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '聊天数据列表', 'ai:chatMessage:index', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '聊天数据读取', 'ai:chatMessage:read', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '聊天数据删除', 'ai:chatMessage:delete', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);

-- chatSession -- 
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@topid, @toplevel, '问答会话', 'ai:chatSession', 'IconSend', 'ai/chatSession', 'ai/chatSession/index', NULL, 2, 'M', 1, 0, 1, 1, now(), now(), NULL, NULL);
SET @id := LAST_INSERT_ID();
SET @level := CONCAT('0',',', @topid, ',', @id);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '问答会话列表', 'ai:chatSession:index', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '问答会话读取', 'ai:chatSession:read', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '问答会话删除', 'ai:chatSession:delete', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);

-- mineMenuGroup --
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@topid, @toplevel, 'Mine菜单分组', 'ai:mineMenuGroup', 'IconMenuUnfold', 'ai/mineMenuGroup', 'ai/mineMenuGroup/index', NULL, 2, 'M', 1, 0, 1, 1, now(), now(), NULL, NULL);
SET @id := LAST_INSERT_ID();
SET @level := CONCAT('0',',', @topid, ',', @id);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '个人中心菜单分组列表', 'ai:mineMenuGroup:index', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '个人中心菜单分组保存', 'ai:mineMenuGroup:save', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '个人中心菜单分组更新', 'ai:mineMenuGroup:update', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '个人中心菜单分组读取', 'ai:mineMenuGroup:read', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '个人中心菜单分组删除', 'ai:mineMenuGroup:delete', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);

-- mineMenu --
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@topid, @toplevel, 'Mine菜单', 'ai:mineMenu', 'IconSelectAll', 'ai/mineMenu', 'ai/mineMenu/index', NULL, 2, 'M', 1, 0, 1, 1, now(), now(), NULL, NULL);
SET @id := LAST_INSERT_ID();
SET @level := CONCAT('0',',', @topid, ',', @id);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '个人中心菜单列表', 'ai:mineMenu:index', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '个人中心菜单保存', 'ai:mineMenu:save', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '个人中心菜单更新', 'ai:mineMenu:update', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '个人中心菜单读取', 'ai:mineMenu:read', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '个人中心菜单删除', 'ai:mineMenu:delete', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);

-- order --
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@topid, @toplevel, '订单列表', 'ai:order', 'icon-home', 'ai/order', 'ai/order/index', NULL, 2, 'M', 1, 0, 1, 1, now(), now(), NULL, NULL);
SET @id := LAST_INSERT_ID();
SET @level := CONCAT('0',',', @topid, ',', @id);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '订单表列表', 'ai:order:index', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '订单表读取', 'ai:order:read', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '订单表删除', 'ai:order:delete', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);

-- quickIssue --
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@topid, @toplevel, '快捷问题', 'ai:quickIssue', 'IconExclamationCircle', 'ai/quickIssue', 'ai/quickIssue/index', NULL, 2, 'M', 1, 0, 1, 1, now(), now(), NULL, NULL);
SET @id := LAST_INSERT_ID();
SET @level := CONCAT('0',',', @topid, ',', @id);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '快捷问题列表', 'ai:quickIssue:index', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '快捷问题更新', 'ai:quickIssue:update', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '快捷问题保存', 'ai:quickIssue:save', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '快捷问题读取', 'ai:quickIssue:read', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '快捷问题删除', 'ai:quickIssue:delete', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);

-- user --
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@topid, @toplevel, '用户列表', 'ai:user', 'IconUserGroup', 'ai/user', 'ai/user/index', NULL, 2, 'M', 1, 0, 1, 1, now(), now(), NULL, NULL);
SET @id := LAST_INSERT_ID();
SET @level := CONCAT('0',',', @topid, ',', @id);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '用户主表列表', 'ai:user:index', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '用户主表更新', 'ai:user:update', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '用户主表读取', 'ai:user:read', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '用户主表删除', 'ai:user:delete', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '用户开通VIP', 'ai:user:open-vip', NULL, NULL, NULL, NULL, 2, 'B', 1, 1, 1, 1, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '用户锁定', 'ai:user:lock', NULL, NULL, NULL, NULL, 2, 'B', 1, 1, 1, 1, now(), now(), NULL, NULL);

-- payKami --
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@topid, @toplevel, '卡密', 'ai:payKami', 'icon-home', 'ai/payKami', 'ai/payKami/index', NULL, 2, 'M', 1, 0, 1, 1, now(), now(), NULL, NULL);
SET @id := LAST_INSERT_ID();
SET @level := CONCAT('0',',', @topid, ',', @id);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '卡密列表', 'ai:payKami:index', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '卡密读取', 'ai:payKami:read', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '卡密更新', 'ai:payKami:update', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '卡密删除', 'ai:payKami:delete', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, '创建卡密', 'ai:payKami:add', NULL, NULL, NULL, NULL, 2, 'B', 1, 1, 1, 1, now(), now(), NULL, NULL);

-- setting --
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@topid, @toplevel, '设置', 'ai:setting', 'IconSettings', 'ai/setting', 'ai/setting/index', NULL, 2, 'M', 1, 0, 1, 1, now(), now(), NULL, NULL);

-- openKey --
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@topid, @toplevel, 'openai_key', 'ai:openKey', 'icon-home', 'ai/openKey', 'ai/openKey/index', NULL, 2, 'M', 1, 0, 1, NULL, now(), now(), NULL, NULL);
SET @id := LAST_INSERT_ID();
SET @level := CONCAT('0',',', @topid, ',', @id);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, 'openai_key列表', 'ai:openKey:index', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, 'openai_key保存', 'ai:openKey:save', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, 'openai_key更新', 'ai:openKey:update', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, 'openai_key读取', 'ai:openKey:read', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, 'openai_key删除', 'ai:openKey:delete', NULL, NULL, NULL, NULL, 2, 'B', 1, 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, 'openai_key刷新缓存', 'ai:openKey:refresh-cache-list', NULL, NULL, NULL, NULL, 2, 'B', 1, 1, 1, 1, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, 'openai_key批量添加', 'ai:openKey:batch-add', NULL, NULL, NULL, NULL, 2, 'B', 1, 1, 1, 1, now(), now(), NULL, NULL);

-- imageMaterial --
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@topid, @toplevel, '图片素材', 'ai:imageMaterial', 'icon-home', 'ai/imageMaterial', 'ai/imageMaterial/index', NULL, '2', 'M', '1', 0, 1, NULL, now(), now(), NULL, NULL);
SET @id := LAST_INSERT_ID();
SET @level := CONCAT('0',',', @topid, ',', @id);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, CONCAT('图片素材', '列表'), CONCAT('ai:imageMaterial',':index'), NULL, NULL, NULL, NULL, '2', 'B', '1', 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, CONCAT('图片素材', '保存'), CONCAT('ai:imageMaterial',':save'), NULL, NULL, NULL, NULL, '2', 'B', '1', 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, CONCAT('图片素材', '更新'), CONCAT('ai:imageMaterial',':update'), NULL, NULL, NULL, NULL, '2', 'B', '1', 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, CONCAT('图片素材', '读取'), CONCAT('ai:imageMaterial',':read'), NULL, NULL, NULL, NULL, '2', 'B', '1', 0, 1, NULL, now(), now(), NULL, NULL);
INSERT INTO `{$tableName}`(`parent_id`, `level`, `name`, `code`, `icon`, `route`, `component`, `redirect`, `is_hidden`, `type`, `status`, `sort`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`, `remark`) VALUES (@id, @level, CONCAT('图片素材', '删除'), CONCAT('ai:imageMaterial',':delete'), NULL, NULL, NULL, NULL, '2', 'B', '1', 0, 1, NULL, now(), now(), NULL, NULL);
";
        Db::unprepared($sql);
        $this->line('大功告成', 'info');
    }
}