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

                typedef struct mitie_binary_relation_detector mitie_binary_relation_detector;
                typedef struct mitie_binary_relation mitie_binary_relation;

                mitie_binary_relation_detector* mitie_load_binary_relation_detector (
                    const char* filename
                );

                const char* mitie_binary_relation_detector_name_string (
                    const mitie_binary_relation_detector* detector
                );

                int mitie_entities_overlap (
                    unsigned long arg1_start,
                    unsigned long arg1_length,
                    unsigned long arg2_start,
                    unsigned long arg2_length
                );

                mitie_binary_relation* mitie_extract_binary_relation (
                    const mitie_named_entity_extractor* ner,
                    char** tokens,
                    unsigned long arg1_start,
                    unsigned long arg1_length,
                    unsigned long arg2_start,
                    unsigned long arg2_length
                );

                int mitie_classify_binary_relation (
                    const mitie_binary_relation_detector* detector,
                    const mitie_binary_relation* relation,
                    double* score
                );

                typedef struct mitie_text_categorizer  mitie_text_categorizer;
                typedef struct mitie_text_categorizer_trainer  mitie_text_categorizer_trainer;

                mitie_text_categorizer* mitie_load_text_categorizer (
                    const char* filename
                );

                mitie_text_categorizer* mitie_load_text_categorizer_pure_model (
                    const char* filename,
                    const char* fe_filename
                );

                int mitie_check_text_categorizer_pure_model(
                    const char* filename
                );

                mitie_text_categorizer* mitie_load_text_categorizer_pure_model_without_feature_extractor(
                    const char* filename
                );

                int mitie_categorize_text (
                    const mitie_text_categorizer* tcat,
                    const char** tokens,
                    char** text_tag,
                    double* text_score
                );

                int mitie_categorize_text_with_extractor (
                    const mitie_text_categorizer* tcat,
                    const char** tokens,
                    char** text_tag,
                    double* text_score,
                    const mitie_total_word_feature_extractor* fe
                );

                int mitie_save_named_entity_extractor (
                    const char* filename,
                    const mitie_named_entity_extractor* ner
                );

                int mitie_save_named_entity_extractor_pure_model (
                    const char* filename,
                    const mitie_named_entity_extractor* ner
                );

                int mitie_save_binary_relation_detector (
                    const char* filename,
                    const mitie_binary_relation_detector* detector
                );

                int mitie_save_text_categorizer (
                    const char* filename,
                    const mitie_text_categorizer* tcat
                );

                int mitie_save_text_categorizer_pure_model (
                    const char* filename,
                    const mitie_text_categorizer* tcat
                );

                typedef struct mitie_ner_trainer mitie_ner_trainer;
                typedef struct mitie_ner_training_instance mitie_ner_training_instance;

                mitie_ner_training_instance* mitie_create_ner_training_instance (
                    char** tokens
                );

                unsigned long mitie_ner_training_instance_num_tokens (
                    const mitie_ner_training_instance* instance
                );

                unsigned long mitie_ner_training_instance_num_entities (
                    const mitie_ner_training_instance* instance
                );

                int mitie_overlaps_any_entity (
                    mitie_ner_training_instance* instance,
                    unsigned long start,
                    unsigned long length
                );

                int mitie_add_ner_training_entity (
                    mitie_ner_training_instance* instance,
                    unsigned long start,
                    unsigned long length,
                    const char* label
                );

                mitie_ner_trainer* mitie_create_ner_trainer (
                    const char* filename
                );

                unsigned long mitie_ner_trainer_size (
                    const mitie_ner_trainer* trainer
                );

                int mitie_add_ner_training_instance(
                    mitie_ner_trainer* trainer,
                    const mitie_ner_training_instance* instance
                );

                void mitie_ner_trainer_set_beta (
                    mitie_ner_trainer* trainer,
                    double beta
                );

                double mitie_ner_trainer_get_beta (
                    const mitie_ner_trainer* trainer
                );

                void mitie_ner_trainer_set_num_threads (
                    mitie_ner_trainer* trainer,
                    unsigned long num_threads
                );

                unsigned long mitie_ner_trainer_get_num_threads (
                    const mitie_ner_trainer* trainer
                );

                mitie_named_entity_extractor* mitie_train_named_entity_extractor (
                    const mitie_ner_trainer* trainer
                );

                typedef struct mitie_binary_relation_trainer mitie_binary_relation_trainer;

                mitie_binary_relation_trainer* mitie_create_binary_relation_trainer (
                    const char* relation_name,
                    const mitie_named_entity_extractor* ner
                );

                unsigned long mitie_binary_relation_trainer_num_positive_examples (
                    const mitie_binary_relation_trainer* trainer
                );

                unsigned long mitie_binary_relation_trainer_num_negative_examples (
                    const mitie_binary_relation_trainer* trainer
                );

                int mitie_add_positive_binary_relation (
                    mitie_binary_relation_trainer* trainer,
                    char** tokens,
                    unsigned long arg1_start,
                    unsigned long arg1_length,
                    unsigned long arg2_start,
                    unsigned long arg2_length
                );

                int mitie_add_negative_binary_relation (
                    mitie_binary_relation_trainer* trainer,
                    char** tokens,
                    unsigned long arg1_start,
                    unsigned long arg1_length,
                    unsigned long arg2_start,
                    unsigned long arg2_length
                );

                void mitie_binary_relation_trainer_set_beta (
                    mitie_binary_relation_trainer* trainer,
                    double beta
                );

                double mitie_binary_relation_trainer_get_beta (
                    const mitie_binary_relation_trainer* trainer
                );

                void mitie_binary_relation_trainer_set_num_threads (
                    mitie_binary_relation_trainer* trainer,
                    unsigned long num_threads
                );

                unsigned long mitie_binary_relation_trainer_get_num_threads (
                    const mitie_binary_relation_trainer* trainer
                );

                mitie_binary_relation_detector* mitie_train_binary_relation_detector (
                    const mitie_binary_relation_trainer* trainer
                );

                mitie_text_categorizer_trainer* mitie_create_text_categorizer_trainer (
                    const char* filename
                );

                unsigned long mitie_text_categorizer_trainer_size (
                    const mitie_text_categorizer_trainer* trainer
                );

                void mitie_text_categorizer_trainer_set_beta (
                    mitie_text_categorizer_trainer* trainer,
                    double beta
                );

                double mitie_text_categorizer_trainer_get_beta (
                    const mitie_text_categorizer_trainer* trainer
                );

                void mitie_text_categorizer_trainer_set_num_threads (
                    mitie_text_categorizer_trainer* trainer,
                    unsigned long num_threads
                );

                unsigned long mitie_text_categorizer_trainer_get_num_threads (
                    const mitie_text_categorizer_trainer* trainer
                );

                int mitie_add_text_categorizer_labeled_text (
                    mitie_text_categorizer_trainer* trainer,
                    const char** tokens,
                    const char* label
                );

                mitie_text_categorizer* mitie_train_text_categorizer (
                    const mitie_text_categorizer_trainer* trainer
                );

                mitie_total_word_feature_extractor* mitie_load_total_word_feature_extractor (
                    const char* filename
                );

                unsigned long mitie_total_word_feature_extractor_fingerprint (
                    const mitie_total_word_feature_extractor* twfe
                );

                unsigned long mitie_total_word_feature_extractor_num_dimensions (
                    const mitie_total_word_feature_extractor* twfe
                );

                unsigned long mitie_total_word_feature_extractor_num_words_in_dictionary (
                    const mitie_total_word_feature_extractor* twfe
                );

                int mitie_total_word_feature_extractor_get_feature_vector (
                    const mitie_total_word_feature_extractor* twfe,
                    const char* word,
                    float* result
                );

                char** mitie_total_word_feature_extractor_get_words_in_dictionary (
                    const mitie_total_word_feature_extractor* twfe
                );
            ', self::$lib ?? Vendor::defaultLib());
        }

        return self::$instance;
    }

    public static function mitie_free($ptr)
    {
        if (!is_null($ptr)) {
            FFI::instance()->mitie_free($ptr);
        }
    }
}
