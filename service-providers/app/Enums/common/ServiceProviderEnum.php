<?php

namespace App\Enums\common;

use App\Traits\BaseEnumTrait;

enum ServiceProviderEnum: string
{
    use BaseEnumTrait;
    case CLAUDE = 'Claude';
    case RUNWAY_ML = 'RunwayML';
    case CANVA = 'Canva';
    case QWEN = 'Qwen';
    case DEEPSEEK = 'DeepSeek';
    case GEMINI = 'Gemini';
    case GETTY_IMAGES = 'Getty Images';
    case FREEPIK = 'Freepik';
    case PERPLEXITY = 'Perplexity';
    case PLACID = 'Placid';
    case SHUTTERSTOCK = 'Shutterstock';
    case ARTLIST = 'artlist';
    case FFMPEG = 'FFmpeg';
    case WHISPER_AI = 'Whisper AI';
    case ENVATO = 'Envato';
    case CHATGPT = 'ChatGPT';
    case PEXELS = 'Pexels';
    case GOOGLE_SHEETS = 'Google Sheets';
    case GOOGLE_SHEETS_API = 'Google Sheets API';
    case GOOGLE_SPREADSHEET = 'Google spreadsheet';
    case PREMIER_PRO = 'Premier Pro';
    case REACT_JS = 'ReactJS';
    case DESCRIPT_AI = 'DescriptAI';
    case ASSET = 'Asset';
    case USER = 'User';
    case SHOTSTACK = 'Shotstack';

}