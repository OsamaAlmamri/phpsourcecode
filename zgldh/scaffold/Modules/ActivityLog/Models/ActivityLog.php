<?php namespace Modules\ActivityLog\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class ActivityLog
 * @package Modules\ActivityLog\Models
 * @version December 26, 2016, 5:37 am UTC
 */
class ActivityLog extends \Spatie\Activitylog\Models\Activity
{
    const TABLE = 'z_activity_log';

    const ACTION_LOGIN = 'login';
    const ACTION_LOGOUT = 'logout';
    const ACTION_UPDATED_PASSWORD = 'updated-password';

    protected $table = self::TABLE;

    public function language($type)
    {
        return __($type);
    }

    /**
     * @deprecated
     * @return MorphTo
     */
    public function collector(): MorphTo
    {
        if (config('activitylog.subject_returns_soft_deleted_models')) {
            return $this->morphTo()->withTrashed();
        }
        return $this->morphTo();
    }
}
