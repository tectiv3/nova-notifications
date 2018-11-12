<?php

namespace Christophrumpel\NovaNotifications;

use Illuminate\Database\Eloquent\Model;

class NovaNotification extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    protected $fillable = [
        'type',
        'user_id',
        'notifiable_type',
        'notifiable_id',
        'channel',
        'data',
        'icon',
        'failed',
        'read_at',
        'action_text',
        'action_url'
    ];
}
