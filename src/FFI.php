<?php

namespace Mitie;

class FFI
{
    public static $lib;

    private static $instance;

    public static function instance()
    {
        if (!isset(self::$instance)) {
            // https://github.com/mit-nlp/MITIE/blob/master/mitielib/include/mitie.h
            self::$instance = \FFI::cdef('
                typedef struct mitie_named_entity_extractor  mitie_named_entity_extractor;
                typedef struct mitie_named_entity_detections mitie_named_entity_detections;

                void mitie_free (
                    void* object
                );

                char* mitie_load_entire_file (
                    const char* filename
                );

                char** mitie_tokenize (
                    const char* text
                );

                char** mitie_tokenize_file (
                    const char* filename
                );

                char** mitie_tokenize_with_offsets (
                    const char* text,
                    unsigned long** token_offsets
                );

                mitie_named_entity_extractor* mitie_load_named_entity_extractor (
                    const char* filename
                );

                mitie_named_entity_extractor* mitie_load_named_entity_extractor_pure_model (
                    const char* filename,
                    const char* fe_filename
                );

                typedef struct mitie_total_word_feature_extractor mitie_total_word_feature_extractor;

                int mitie_check_ner_pure_model(
                    const char* filename
                );

                mitie_named_entity_extractor* mitie_load_named_entity_extractor_pure_model_without_feature_extractor (
                    const char* filename
                );

                unsigned long mitie_get_num_possible_ner_tags (
                    const mitie_named_entity_extractor* ner
                );

                const char* mitie_get_named_entity_tagstr (
                    const mitie_named_entity_extractor* ner,
                    unsigned long idx
                );

                mitie_named_entity_detections* mitie_extract_entities (
                    const mitie_named_entity_extractor* ner,
                    char** tokens
                );

                mitie_named_entity_detections* mitie_extract_entities_with_extractor(
                    const mitie_named_entity_extractor* ner,
                    char** tokens,
                    const mitie_total_word_feature_extractor* fe
                );

                unsigned long mitie_ner_get_num_detections (
                    const mitie_named_entity_detections* dets
                );

                unsigned long mitie_ner_get_detection_position (
                    const mitie_named_entity_detections* dets,
                    unsigned long idx
                );

                unsigned long mitie_ner_get_detection_length (
                    const mitie_named_entity_detections* dets,
                    unsigned long idx
                );

                unsigned long mitie_ner_get_detection_tag (
                    const mitie_named_entity_detections* dets,
                    unsigned long idx
                );

                const char* mitie_ner_get_detection_tagstr (
                    const mitie_named_entity_detections* dets,
                    unsigned long idx
                );

                double mitie_ner_get_detection_score (
                    const mitie_named_entity_detections* dets,
                    unsigned long idx
                );
            ', self::$lib ?? Vendor::defaultLib());
        }

        return self::$instance;
    }
}
