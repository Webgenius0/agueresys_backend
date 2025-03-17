@extends('backend.app')
@section('title', 'Banner')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
@endpush

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">Create God</h3>
        </div>

        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">

                <div class="mb-4">
                    <h4 class="fs-20 mb-1">Create God</h4>
                    <p class="fs-15">Create new god and more details here.</p>
                </div>

                <form action="{{ route('gods.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- Title Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Title<span class="text-danger">*</span></label>
                                <div class="form-group position-relative">
                                    <input type="text"
                                        class="form-control text-dark ps-5 h-55 @error('title') is-invalid @enderror"
                                        name="title" value="{{ old('title') }}" required placeholder="Enter Title here">
                                </div>
                                @error('title')
                                    <div id="title-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <!-- 2nd Subtitle Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Sub Title <span class="text-danger">*</span></label>
                                <div class="form-group position-relative">
                                    <input type="text"
                                        class="form-control text-dark ps-5 h-55 @error('sub_title') is-invalid @enderror"
                                        name="sub_title" value="{{ old('sub_title') }}" placeholder="Enter sub title here">
                                </div>
                                @error('sub_title')
                                    <div id="sub_title-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- 3rd description_title Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Description Title<span
                                        class="text-danger">*</span></label>
                                <div class="form-group position-relative">
                                    <input type="text"
                                        class="form-control text-dark ps-5 h-55 @error('description_title') is-invalid @enderror"
                                        name="description_title" value="{{ old('description_title') }}"
                                        placeholder="Enter description title here">
                                </div>
                                @error('description_title')
                                    <div id="description_title-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- 4th Description Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Description<span class="text-danger">*</span></label>
                                <div class="form-group position-relative">
                                    <textarea class="form-control text-dark ps-5 h-55 @error('description') is-invalid @enderror" name="description"
                                        placeholder="Enter description here">{{ old('description') }}</textarea>
                                </div>
                                @error('description')
                                    <div id="description-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- 5th aspect_description Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Aspect Description<span
                                        class="text-danger">*</span></label>
                                <div class="form-group position-relative">
                                    <textarea class="form-control text-dark ps-5 h-55 @error('aspect_description') is-invalid @enderror"
                                        name="aspect_description" placeholder="Enter aspect description here">{{ old('aspect_description') }}</textarea>
                                </div>
                                @error('aspect_description')
                                    <div id="aspect_description-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- 6th Thumbnail Field -->
                        <div class="col-lg-6">
                            <div class="form-group ">
                                <label class="label text-secondary mb-1">Thumbnail<span class="text-danger">*</span></label>
                                <input class="dropify form-control @error('thumbnail') is-invalid @enderror" type="file"
                                    name="thumbnail" accept="image/*">
                                @error('thumbnail')
                                    <div id="thumbnail-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <!-- Aspects Image Upload -->
                    <div class="col-md-12">
                        <div class="form-group mb-4">
                            <label class="label text-secondary">Aspects Images<span style="color: red">*</span></label>
                            <div id="gallery-dropzone"
                                class="dropzone border rounded p-4 d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <i class="ri-upload-cloud-2-line fs-40 text-primary"></i>
                                    <p class="text-secondary m-0">Drag & Drop or Click to Upload</p>
                                </div>
                            </div>

                            <input type="file" hidden class="form-control @error('cover_image') is-invalid @enderror"
                                name="aspect_images[]" accept="image/jpeg,image/png,image/jpg" multiple id="gallery-images">
                            @error('aspect_images')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Preview Container -->
                    <div class="col-md-12 mt-3">
                        <div id="preview-container" class="d-flex flex-wrap gap-3"></div>
                    </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('gods.index') }}"
                            class="btn btn-danger py-2 px-4 fw-medium fs-16 text-white">Cancle</a>
                        <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16"> <i
                                class="ri-check-line text-white fw-medium"></i> Submit </button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>



@endsection

@push('scripts')
    <script src="{{ asset('frontend/assets/js/plugins/jquery-3.7.1.min.js') }}"></script>
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
        })
    </script>
    <script>
        // This JavaScript code is used in the service create page to enable and filter
        // subcategories based on the category selected. It also handles the file upload
        // for the gallery images.
        $(document).ready(function() {
            let uploadedImages = [];
            const dropzone = $('#gallery-dropzone');
            const previewContainer = $('#preview-container');
            const galleryImagesInput = $('#gallery-images')[0];
            const maxFiles = 20; // Maximum allowed file uploads

            dropzone.on('dragover', function(event) {
                event.preventDefault();
                dropzone.addClass('border-primary');
            });

            dropzone.on('dragleave', function() {
                dropzone.removeClass('border-primary');
            });

            dropzone.on('drop', function(event) {
                event.preventDefault();
                dropzone.removeClass('border-primary');
                let files = event.originalEvent.dataTransfer.files;
                handleFiles(files);
            });

            dropzone.on('click', function() {
                let fileInput = $('<input>', {
                    type: 'file',
                    accept: 'image/jpeg,image/png,image/jpg',
                    multiple: true
                }).on('change', function(event) {
                    handleFiles(event.target.files);
                });
                fileInput.trigger('click');
            });

            function handleFiles(files) {
                // console.log(files.length)
                if (uploadedImages.length >= maxFiles || files.length >= maxFiles) {
                    flasher.warning(`You can only upload up to ${maxFiles} images.`);
                    // alert(`You can only upload up to ${maxFiles} images.`);
                    return;
                }
                $.each(files, function(index, file) {
                    const maxSize = 2 * 1024 * 1024; // 2MB limit

                    if (file.size > maxSize) {
                        // alert(`File "${file.name}" size exceeds the maximum allowed size (2MB).`);
                        // console.log(`File "${file.name}" exceeds 2MB and will not be uploaded.`);
                        flasher.error(`"${file.name}" exceeds 2MB and will not be uploaded.`);
                        return;
                    }
                    // Check if file is already uploaded
                    if (uploadedImages.some(img => img.name === file.name && img.size === file.size)) {
                        flasher.warning(`"${file.name}" is already added.`);
                        return;
                    }

                    if (file.type.startsWith('image/')) {
                        let reader = new FileReader();
                        reader.onload = function(event) {
                            let imgUrl = event.target.result;
                            uploadedImages.push(file);
                            updateGalleryImagesInput();

                            // Create preview
                            let previewCard = $('<div>', {
                                class: 'position-relative rounded overflow-hidden shadow-sm m-2'
                            });

                            let imgElement = $('<img>', {
                                src: imgUrl,
                                class: 'img-thumbnail rounded',
                                css: {
                                    height: '200px',
                                    width: '200px'
                                }
                            });

                            let deleteButton = $('<button>', {
                                html: '&times;',
                                class: 'position-absolute top-0 end-0 btn-sm btn-danger rounded-circle'
                            }).on('click', function() {
                                uploadedImages = uploadedImages.filter(item => item !== file);
                                previewCard.remove();
                                updateGalleryImagesInput();
                            });

                            previewCard.append(imgElement, deleteButton);
                            previewContainer.append(previewCard);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            function updateGalleryImagesInput() {
                let dataTransfer = new DataTransfer();
                $.each(uploadedImages, function(index, file) {
                    dataTransfer.items.add(file);
                });
                galleryImagesInput.files = dataTransfer.files;
                // console.log(galleryImagesInput.files);
            }
        });
    </script>
@endpush
