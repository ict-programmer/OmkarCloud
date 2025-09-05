<?php

namespace App\Enums\Google;

use App\Traits\BaseEnumTrait;

enum LanguageEnum: string
{
    use BaseEnumTrait;

    case AR = 'lang_ar';
    case BG = 'lang_bg';
    case CA = 'lang_ca';
    case CS = 'lang_cs';
    case DA = 'lang_da';
    case DE = 'lang_de';
    case EL = 'lang_el';
    case EN = 'lang_en';
    case ES = 'lang_es';
    case ET = 'lang_et';
    case FI = 'lang_fi';
    case FR = 'lang_fr';
    case HR = 'lang_hr';
    case HU = 'lang_hu';
    case ID = 'lang_id';
    case IS = 'lang_is';
    case IT = 'lang_it';
    case IW = 'lang_iw';
    case JA = 'lang_ja';
    case KO = 'lang_ko';
    case LT = 'lang_lt';
    case LV = 'lang_lv';
    case NL = 'lang_nl';
    case NO = 'lang_no';
    case PL = 'lang_pl';
    case PT = 'lang_pt';
    case RO = 'lang_ro';
    case RU = 'lang_ru';
    case SK = 'lang_sk';
    case SL = 'lang_sl';
    case SR = 'lang_sr';
    case SV = 'lang_sv';
    case TR = 'lang_tr';
    case ZH_CN = 'lang_zh-CN';
    case ZH_TW = 'lang_zh-TW';
}
