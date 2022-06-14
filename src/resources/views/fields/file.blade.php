@php
    $field['wrapper'] = $field['wrapper'] ?? $field['wrapperAttributes'] ?? [];
    $field['wrapper']['data-init-function'] = $field['wrapper']['data-init-function'] ?? 'bpFieldInitUploadElement';
    $field['wrapper']['data-field-name'] = $field['wrapper']['data-field-name'] ?? $field['name'];

    $url = null;
    if (!empty($field['value'])) {
        $url = $field['model']::find($field['value'])->getUrl();
    }
@endphp

<!-- text input -->
@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')
    
    @if ($url)
        @if ($field['has_preview'])
            <div class="preview-holder">
                <img src="{{ $url }}" alt="">
            </div>
        @endif
        <div class="uploaded-file {{ $field['has_preview'] ? 'has-preview' : '' }}">
            <a
                target="_blank"
                href="{{ $url }}"
            >
                {{ $url }}
            </a>
            <a 
                href="#"
                title="{{ __('different-core::file.clear') }}"
                data-value="{{ $field['value'] }}"
                class="file-clear"
            >
                <i class="la la-remove"></i>
            </a>
        </div>
    @endif

    <div class="new-file {{ isset($field['value']) ? 'has-file' : '' }}">
        <input
            type="file"
            name="upload_{{ $field['name'] }}"
        >
    </div>
    <input
        type="hidden"
        name="remove_{{ $field['name'] }}"
        value=""
        class="file-remove"
    >
    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')



{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->fieldTypeNotLoaded($field))
    @php
        $crud->markFieldTypeAsLoaded($field);
    @endphp

    @push('crud_fields_styles')
        <style type="text/css">
            .new-file.has-file {
                display: none;
            }

            .uploaded-file {
                display: flex;
                justify-content: space-between;
                align-items: center;
                width: 100%;
                color: #4f4f4f;
                border-radius: 0.5em;
                padding: 0.75em 1em;
                background-color: #f5f6f8;
            }

            .uploaded-file.has-preview {
                border-top-left-radius: 0;
                border-top-right-radius: 0;
            }

            .uploaded-file a {
                line-height: 1;
            }

            .preview-holder {
                padding: 0.75em 1em;
                display: flex;
                width: 100%;
                align-items: center;
                justify-content: center;
                background: #f5f6f8;
                border-top-left-radius: 0.5em;
                border-top-right-radius: 0.5em;
            }

            .preview-holder img {
                max-height: 250px;
                max-width: 100%;
            }
        </style>
        <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
        <link
            href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
            rel="stylesheet"
        />
    @endpush

    @push('crud_fields_scripts')
        <!-- no scripts -->
        <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
        <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
        <script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>
        <script>
            function bpFieldInitUploadElement(element) {
                const fileInput = element.find("input[type='file']");
                const uploadedFile = element.find(".uploaded-file");
                const previewFile = element.find(".preview-holder");
                const newFile = element.find(".new-file");
                const fileClear = element.find(".file-clear");
                const removeInput = element.find(".file-remove");

                $.fn.filepond.registerPlugin(FilePondPluginImagePreview);
                $(fileInput).filepond({
                    storeAsFile: true,
                    imagePreviewTransparencyIndicator: 'grid',
                    credits: false,
                    labelIdle: '{!! __("different-core::file.browse") !!}',
                });

                fileClear.click(function(e) {
                    e.preventDefault();
                    removeInput.attr("value", $(this).data('value'));
                    $(newFile).removeClass('has-file');
                    $(uploadedFile).addClass('d-none');
                    $(previewFile).addClass('d-none');
                });
            }
        </script>
    @endpush
@endif
