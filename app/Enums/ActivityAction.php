<?php
namespace App\Enums;

use App\Traits\ArrayableEnum;

enum ActivityAction: string
{
    use ArrayableEnum;
    // === FEED & NAVIGATION ===
    case FEED_VIEW = 'feed_view';
    case FEED_SCROLL = 'feed_scroll';
    case FEED_TIME_SPENT = 'feed_time_spent';

    // === QUESTION INTERACTIONS ===
    case QUESTION_IMPRESSION = 'question_impression';     // 70%+ in viewport
    case QUESTION_VIEW = 'question_view';                 // full page open
    case QUESTION_TIME_SPENT = 'question_time_spent';     // seconds
    case QUESTION_FULLY_WATCHED = 'question_fully_watched'; // if long content

    // === PREDICTION (your core action) ===
    case PREDICT = 'predict';
    case PREDICTION_CHANGE = 'prediction_change';
    case PREDICTION_CONFIDENCE = 'prediction_confidence';

    // === ENGAGEMENT ===
    case LIKE = 'like';
    case UNLIKE = 'unlike';
    case COMMENT = 'comment';
    case COMMENT_REPLY = 'comment_reply';
    case COMMENT_LIKE = 'comment_like';

    // === SOCIAL ===
    case SHARE = 'share';
    case FOLLOW_USER = 'follow_user';
    case UNFOLLOW_USER = 'unfollow_user';
    case PROFILE_VISIT = 'profile_visit';

    // === DISCOVERY ===
    case TAG_CLICK = 'tag_click';
    case TAG_SEARCH = 'tag_search';
    case SEARCH_QUERY = 'search_query';

    // === CONTENT CREATION ===
    case QUESTION_CREATE = 'question_create';
    case QUESTION_EDIT = 'question_edit';
    case QUESTION_PUBLISH = 'question_publish';
    case QUESTION_DELETE = 'question_delete';

    // === NOTIFICATIONS ===
    case NOTIFICATION_VIEW = 'notification_view';
    case NOTIFICATION_CLICK = 'notification_click';

    // === APP EVENTS ===
    case APP_OPEN = 'app_open';
    case APP_BACKGROUND = 'app_background';
    case APP_CLOSE = 'app_close';

    // === ERRORS / DEBUG (optional) ===
    case ERROR_FRONTEND = 'error_frontend';
    case ERROR_API = 'error_api';

    // === CUSTOM / FUTURE ===
    case CUSTOM = 'custom'; // fallback with metadata

    // Human-readable labels (optional)
    public function label(): string
    {
        return match ($this) {
            self::FEED_VIEW => 'Feed Viewed',
            self::QUESTION_IMPRESSION => 'Question Shown',
            self::PREDICT => 'Made a Prediction',
            self::LIKE => 'Liked',
            self::SHARE => 'Shared',
            self::TAG_CLICK => 'Clicked Tag',
            default => ucfirst(str_replace('_', ' ', $this->value)),
        };
    }

    public static function stacks(): array
    {
        return [
            self::LIKE,
            self::UNLIKE,
            self::COMMENT,//
            self::COMMENT_REPLY,
            self::COMMENT_LIKE,
            self::PREDICT,//
            self::PREDICTION_CHANGE,//
            self::SHARE,//
            self::QUESTION_CREATE,//
            self::QUESTION_PUBLISH,
            self::QUESTION_EDIT,//
            self::QUESTION_DELETE,//
            self::FOLLOW_USER,
            self::UNFOLLOW_USER,
        ];
    }
    public static function notStacks(): array
    {
        return [
            self::FEED_VIEW,
            self::FEED_SCROLL,
            self::FEED_TIME_SPENT,
            self::QUESTION_IMPRESSION,
            self::QUESTION_VIEW,
            self::QUESTION_TIME_SPENT,
            self::QUESTION_FULLY_WATCHED,
            self::PREDICTION_CONFIDENCE,
            self::PROFILE_VISIT,
            self::TAG_CLICK,
            self::TAG_SEARCH,
            self::SEARCH_QUERY,
            self::NOTIFICATION_VIEW,
            self::NOTIFICATION_CLICK,
            self::APP_OPEN,
            self::APP_BACKGROUND,
            self::APP_CLOSE,
            self::ERROR_FRONTEND,
            self::ERROR_API,
            self::CUSTOM,
        ];
    }


    // Group actions for analytics
    public function group(): string
    {
        return match ($this) {
            self::FEED_VIEW,
            self::FEED_SCROLL,
            self::FEED_TIME_SPENT => 'feed',

            self::QUESTION_IMPRESSION,
            self::QUESTION_VIEW,
            self::QUESTION_TIME_SPENT,
            self::QUESTION_FULLY_WATCHED => 'question_view',

            self::PREDICT,
            self::PREDICTION_CHANGE,
            self::PREDICTION_CONFIDENCE => 'prediction',

            self::LIKE,
            self::UNLIKE,
            self::COMMENT,
            self::COMMENT_REPLY,
            self::COMMENT_LIKE => 'engagement',

            self::SHARE,
            self::FOLLOW_USER,
            self::UNFOLLOW_USER,
            self::PROFILE_VISIT => 'social',

            self::TAG_CLICK,
            self::TAG_SEARCH,
            self::SEARCH_QUERY => 'discovery',

            self::QUESTION_CREATE,
            self::QUESTION_EDIT,
            self::QUESTION_PUBLISH,
            self::QUESTION_DELETE => 'content_creation',

            self::NOTIFICATION_VIEW,
            self::NOTIFICATION_CLICK => 'notification',

            self::APP_OPEN,
            self::APP_BACKGROUND,
            self::APP_CLOSE => 'app_lifecycle',

            default => 'other',
        };
    }
}
