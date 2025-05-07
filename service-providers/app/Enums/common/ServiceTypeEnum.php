<?php

namespace App\Enums\common;

enum ServiceTypeEnum: string
{
    case TEXT_GENERATION_SERVICE = 'Text Generation Service';
    case TEXT_SUMMERIZATION_SERVICE = 'Text Summarization Service';
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
}
