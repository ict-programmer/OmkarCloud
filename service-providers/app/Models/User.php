<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use MongoDB\Laravel\Auth\User as Authenticatable;
use MongoDB\Laravel\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $connection = 'mongodb_clusters_identities';

    protected $fillable = [
        'name',
        'restaurant_name',
        'email',
        'email_verified_at',
        'is_approved',
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'country_code',
        'mobile',
        'whatsapp',
        'telegram_group_id',
        'send_daily_report',
        'send_weekly_report',
        'send_monthly_report',
        'user_type',
        'parent_id',
        'approved',
        'referral_code',
        'company_name',
        'company_title',
        'orderific_client',
        'refer_leads',
        'business_tenure',
        'remember_token',
        'profile',
        'product_service',
        'zoom_info',
        'company_website',
        'industry',
        'location',
        'about',
        'expertskill',
        'expert_level',
        'copy',
        'graphics',
        'video',
        'audio',
        'email_otp',
        'email_otp_expired_at',
        'forgot_otp',
        'forgot_otp_expired_at',
        'otp_attempt',
        'forgot_otp_attempt',
        'creator_api_key',
        'creator_secure_key',
        'brand_key',
        'referred_by',
        'currency_code',
        'sub_domain_brand_id',
        'profile_cid',
        'profile_image_name',
        'user_code',
        'is_testing',
        'user_auth_token',
        'driver_currency_id',
        'first_name',
        'middle_name',
        'last_name',
        'login_pin',
        'bio',
        'last_login_at',
        'status',
        'share_mode',
        'delete_reason',
        'default_address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
