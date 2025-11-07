<?php

namespace App\Enums\common;

enum ServiceTypeEnum: string
{
    case TEXT_GENERATION_SERVICE = 'Text Generation Service';
    case TEXT_SUMMARIZATION_SERVICE = 'Text Summarization Service';
    case QUESTION_ANSWERING_SERVICE = 'Question Answering Service';
    case TEXT_CLASSIFICATION_SERVICE = 'Text Classification Service';
    case TEXT_TRANSLATION_SERVICE = 'Text Translation Service';
    case CODE_GENERATION_SERVICE = 'Code Generation Service';
    case DATA_ANALYSIS_AND_INSIGHT_SERVICE = 'Data Analysis and Insights Service';
    case PERSONALIZATION_SERVICE = 'Personalization Service';
    case TEXT_GENERATION = 'Text Generation';
    case CODE_GENERATION = 'Code Generation';
    case IMAGE_ANALYSIS = 'Image Analysis';
    case DOCUMENT_SUMMARIZATION = 'Document Summarization';
    case SEARCH_PHOTOS = 'search photos';
    case GET_CURATED_PHOTOS = 'get curated photos';
    case GET_PHOTO = 'get photo';
    case SEARCH_VIDEOS = 'search videos';
    case GET_POPULAR_VIDEOS = 'get popular videos';
    case GET_VIDEO = 'get video';
    case GET_FEATURED_COLLECTIONS = 'get featured collections';
    case GET_COLLECTIONS = 'get collections';
    case GET_COLLECTION = 'get collection';
    case CREATE_ASSET = 'create asset';
    case CHECK_RENDER_STATUS = 'check render status';
    case GET_VIDEO_METADATA = 'get video metadata';
}
