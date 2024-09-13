<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile</title>

    {{-- Tab Logo --}}
    <link rel="shortcut icon" href="{{ asset('images/tab-logo.ico') }}" type="image/x-icon">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />

    {{-- CSS file under Public Folder --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />

    {{-- Sweet Alert --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</head>

<body>




    <div class="container-fluid custom-shadow">

        @include('header_and_footer.header')

        {{-- Cover Photo --}}
            <div class="container-fluid custom-shadow-profile">
                <div class="container cover">
                    <div class="header__wrapper d-flex justify-content-center position-relative">
                        <img id="coverImage"
                            src="{{ asset($user->cover_photo ? 'storage/cover_photos/' . $user->id . '/' . basename($user->cover_photo) : 'images/finalcover.png') }}"
                            alt="Cover Photo" class="cover-photo" />
                        <div class="upload-overlay d-flex flex-column align-items-center">
                            <input type="file" id="coverUpload" accept="image/*" style="display: none;" />
                            <button type="button" class="btn btn-light mt-2" id="uploadButton"><i
                                    class="fas fa-upload"></i> Change Cover
                                Photo</button>
                            <button type="button" class="btn btn-primary mt-2" id="saveButton"
                                style="display: none;">Save</button>
                            <button type="button" class="btn btn-danger mt-2" id="deleteButton"><i
                                    class="fas fa-trash"></i> Delete</button>
                        </div>
                    </div>
                </div>

                <script>
                    // Uploading of photo preview and action confirmation
                    let originalCoverImageSrc = document.getElementById('coverImage').src;

                    document.getElementById('uploadButton').addEventListener('click', function() {
                        document.getElementById('coverUpload').click();
                    });

                    document.getElementById('coverUpload').addEventListener('change', function(event) {
                        const file = event.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                document.getElementById('coverImage').src = e.target.result;
                                document.getElementById('saveButton').style.display = 'block'; // Show save button
                            };
                            reader.readAsDataURL(file);
                        }
                    });

                    document.getElementById('saveButton').addEventListener('click', function() {
                        if (confirm('Are you sure you want to save this cover photo?')) {
                            const fileInput = document.getElementById('coverUpload');
                            const formData = new FormData();
                            formData.append('cover_photo', fileInput.files[0]);

                            fetch('{{ route('seller.cover-photo') }}', {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        originalCoverImageSrc = data.image_path; // Update original image source
                                        document.getElementById('saveButton').style.display =
                                            'none'; // Hide save button after saving
                                        alert('Cover photo saved successfully!');
                                    } else {
                                        alert('Failed to save cover photo.');
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        } else {
                            // Revert to original image if user cancels saving
                            document.getElementById('coverImage').src = originalCoverImageSrc;
                            document.getElementById('saveButton').style.display = 'none'; // Hide save button
                        }
                    });

                    document.getElementById('deleteButton').addEventListener('click', function() {
                        if (confirm('Are you sure you want to delete your cover photo?')) {
                            fetch('{{ route('seller.cover-photo.delete') }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json',
                                    },
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        window.location.reload(); // Refresh the page to show default picture
                                    } else {
                                        alert('Failed to delete cover photo.');
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        }
                    });
                </script>


                {{-- Profile Picture --}}
                <div class="container d-flex justify-content-center align-items-center">
                    <div class="img__container position-relative thick-border-container">
                        <img id="profileImage"
                            src="{{ asset($user->profile_photo ? 'storage/profile_photos/' . $user->id . '/' . basename($user->profile_photo) : 'images/defuser.png') }}"
                            alt="Profile Picture" class="thick-border profile-picture" />

                        <!-- Hover Overlay for Uploading a New Profile Picture -->
                        <div
                            class="profile-upload-overlay d-flex flex-column align-items-center justify-content-center">
                            <input type="file" id="profileUpload" accept="image/*" style="display: none;" />
                            <button type="button" class="btn btn-light" id="profileUploadButton"><i
                                    class="fas fa-upload"></i> Change</button>
                            <button type="button" class="btn btn-success mt-2" id="profileSaveButton"
                                style="display: none;">Save</button>
                            <button type="button" class="btn btn-danger mt-2" id="profileDeleteButton"> <i
                                    class="fas fa-trash"></i> Delete</button>
                        </div>
                    </div>
                </div>

                <script>
                    // Uploading photo preview
                    let originalProfileImageSrc = document.getElementById('profileImage').src;

                    document.getElementById('profileUploadButton').addEventListener('click', function() {
                        document.getElementById('profileUpload').click();
                    });

                    document.getElementById('profileUpload').addEventListener('change', function(event) {
                        const file = event.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                document.getElementById('profileImage').src = e.target.result;
                                document.getElementById('profileSaveButton').style.display = 'block'; // Show save button
                            };
                            reader.readAsDataURL(file);
                        }
                    });

                    document.getElementById('profileSaveButton').addEventListener('click', function() {
                        if (confirm('Are you sure you want to save this profile picture?')) {
                            const fileInput = document.getElementById('profileUpload');
                            const formData = new FormData();
                            formData.append('profile_photo', fileInput.files[0]);

                            fetch('{{ route('seller.profile-photo') }}', {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        originalProfileImageSrc = data.image_path; // Update original image source
                                        document.getElementById('profileSaveButton').style.display =
                                            'none'; // Hide save button after saving
                                    } else {
                                        alert('Failed to save profile photo.');
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        } else {
                            // Revert to original image if user cancels saving
                            document.getElementById('profileImage').src = originalProfileImageSrc;
                            document.getElementById('profileSaveButton').style.display = 'none'; // Hide save button
                        }
                    });

                    document.getElementById('profileDeleteButton').addEventListener('click', function() {
                        if (confirm('Are you sure you want to delete your profile picture?')) {
                            fetch('{{ route('seller.profile-photo.delete') }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json',
                                    },
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        window.location.reload(); // Refresh the page to show default picture
                                    } else {
                                        alert('Failed to delete profile photo.');
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        }
                    });
                </script>



            {{-- Tags --}}
            <div class="name text-center">
                <h2 class="card-title text-center mb-3 p-4" style="color:#343434;">{{ $user->fname }}
                    {{ $user->lname }} </h2>

                <div class="pb-5">

                    <button type="button" class="btn btn-lg btn-outline-success btn-rounded me-1"
                        data-mdb-ripple-init><i class="fas fa-message"></i> Inbox</button>

                </div>

            </div>

        </div>



        <div class="container mt-5 mb-5 " style="padding: 2rem;">

            <div class="row">
                <!-- Left Side Container -->
                <div class="col-md-4 mb-3">
                    <div class="custom-shadow p-4 d-flex flex-wrap justify-content-center rounded-2">
                        <div class="text-muted small text-center align-self-center m-2">
                            <h2>427</h2>
                            <span class=" d-sm-inline-block">
                                <h5>Commendations</h5>
                            </span>
                        </div>
                        <div class="text-muted small text-center align-self-center m-2">
                            <h2>{{ $feedbackCount }}</h2>
                            <span class=" d-sm-inline-block">
                                <h5>Feedbacks</h5>
                            </span>
                        </div>
                        <!-- Content for the left side -->
                        <div class="col-12 text-center d-flex justify-content-start">

                            <form action="{{ route('seller.storeTags') }}" method="POST">
                                @csrf
                                <div class="dropdown">
                                    <button type="button"
                                        class="btn btn-outline-secondary btn-rounded mb-3 ms-4 mt-3 dropdown-toggle"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-edit"></i> Edit Tags
                                    </button>
                                    <ul class="dropdown-menu">
                                        @foreach($tags as $tag)
                                            <li>
                                                <a class="dropdown-item" href="#">
                                                    <input type="checkbox" name="tags[]" value="{{ $tag->name }}"
                                                        class="form-check-input me-2">
                                                    {{ $tag->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                        <button type="submit" class="btn btn-primary">Save Tags</button>
                                    </ul>
                                    
                                </div>
                                
                            </form>
                            
                            

                        </div>

                        <div class="d-flex flex-wrap justify-content-center">
                            @foreach($selectedTags as $tag)
                                <button type="button" class="btn btn-sm btn-primary mb-2 me-2" disabled>{{ $tag }}</button>
                            @endforeach
                        </div>
                        
                    </div>
                </div>

                 <div class="container mt-3 custom shadow p-3">
                            <h4 class="p-2"> &#128204 Upcoming Events</h4>

                            @php
                                // Filter the events before the loop to include only Approved and OnGoing events and exclude events where the date has passed
                                $filteredEvents = $events->filter(function ($event) {
                                    return in_array($event->Status, ['Approved', 'OnGoing']) &&
                                        \Carbon\Carbon::parse($event->Date)->isFuture();
                                });
                            @endphp

                            @if ($filteredEvents->isEmpty())
                                @if (!empty($search))
                                    <h1 class="text-center text-muted">No search results found</h1>
                                @else
                                    <h1 class="text-center text-muted">No events yet</h1>
                                @endif
                            @else
                                <!-- Display Events Based on Status -->
                                @foreach ($filteredEvents as $event)
                                    @if ($event->Status == 'Approved' || $event->Status == 'Ongoing')
                                        <div class="col-12 mb-3">
                                            <div class="event-side">
                                                <div class="card-body d-flex align-items-center">
                                                    <!-- Calendar-like Date Display -->
                                                    <div
                                                        class="date-card d-flex flex-column align-items-center justify-content-center me-4">
                                                        <div class="day"
                                                            style="font-size: 2rem; font-weight: bold;">
                                                            {{ \Carbon\Carbon::parse($event->Date)->format('j') }}
                                                        </div>
                                                        <div class="date-month text-muted me-5 ms-5">
                                                            {{ \Carbon\Carbon::parse($event->Date)->format('M Y') }}
                                                        </div>
                                                    </div>

                                                    <!-- Event Details -->
                                                    <div>
                                                        <h5 class="card-title m-0">{{ $event->EventName }}</h5>
                                                        <p class="card-text text-muted m-0 truncate-description">
                                                            {{ $event->EventDescription }}</p>
                                                        <p class="modal-text m-0"><i class="far fa-clock"></i>
                                                            {{ \Carbon\Carbon::parse($event->StartTime)->format('g:i A') }}
                                                            -
                                                            {{ \Carbon\Carbon::parse($event->EndTime)->format('g:i A') }}
                                                        </p>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <p class="card-text"><i
                                                                    class="fas fa-map-marker-alt text-primary"></i>
                                                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($event->Location) }}"
                                                                    class="location-icon" target="_blank">
                                                                    {{ $event->Location }}
                                                                </a>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>




                <div class="col-md-8 mb-3">
                    <div class="custom-shadow p-4 rounded-2">

                        {{-- Tabs --}}
                        <ul class="nav nav-pills user-profile-tab justify-content-start mt-2 bg-light-info rounded-2"
                            id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button
                                    class="nav-link position-relative rounded-0 active d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                                    id="pills-products-tab" data-bs-toggle="pill" data-bs-target="#pills-products"
                                    type="button" role="tab" aria-controls="pills-products" aria-selected="true">
                                    <i class="fa fa-image me-2 fs-6"></i>
                                    <span class="d-none d-md-block">Products</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button
                                    class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                                    id="pills-events-tab" data-bs-toggle="pill" data-bs-target="#pills-events"
                                    type="button" role="tab" aria-controls="pills-events" aria-selected="false"
                                    tabindex="-1">
                                    <i class="fa fa-calendar me-2 fs-6"></i>
                                    <span class="d-none d-md-block">Events</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button
                                    class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                                    id="pills-archives-tab" data-bs-toggle="pill" data-bs-target="#pills-archives"
                                    type="button" role="tab" aria-controls="pills-archives"
                                    aria-selected="false" tabindex="-1">
                                    <i class="fa fa-box me-2 fs-6"></i>
                                    <span class="d-none d-md-block">Archives</span>
                                </button>
                            </li>
                        </ul>

                        {{-- Add new product Modal --}}
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <!-- Left column -->
                                                <form method="post" action="{{ route('products-seller.store') }}"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @method('post')
                                                    <input type="hidden" name="user_id"
                                                        value="{{ auth()->user()->id }}">
                                                    <div class="mb-3">
                                                        <label for="productName" class="form-label">Product
                                                            Name</label>
                                                        <input type="text" class="form-control" id="productName"
                                                            name="name" placeholder="Input Product Name" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="description"
                                                            class="form-label">Description</label>
                                                        <textarea class="form-control" id="description" name="description" placeholder="Input Product Description" required></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="quantity" class="form-label">Quantity</label>
                                                        <input type="number" class="form-control" id="quantity"
                                                            name="qty" placeholder="Input Product Quantity"
                                                            required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="price" class="form-label">Price</label>
                                                        <input type="number" class="form-control" id="price"
                                                            name="price" placeholder="0" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="prodimg" class="form-label">Product
                                                            Images</label>
                                                        <input type="file" class="form-control" id="prodimg"
                                                            name="prodimg[]" accept="image/*" multiple
                                                            onchange="handleFiles(event)" required>
                                                        <p class="text-muted">Accepted image formats: .jpeg, .jpg,
                                                            .png, with a maximum
                                                            size of 2MB per image.</p>
                                                    </div>

                                                    <script>
                                                        function handleFiles(event) {
                                                            const files = event.target.files;
                                                            const previewContainer = document.getElementById('preview-container');

                                                            // Clear previous previews and input if more than 4 files selected
                                                            if (files.length > 4) {
                                                                alert("You can only upload a maximum of 4 images.");
                                                                event.target.value = "";
                                                                previewContainer.innerHTML = "";
                                                                return;
                                                            }

                                                            // Clear previous previews
                                                            previewContainer.innerHTML = "";

                                                            // Loop through the files and create image previews
                                                            for (let i = 0; i < files.length; i++) {
                                                                const file = files[i];
                                                                const fileReader = new FileReader();

                                                                fileReader.onload = function(e) {
                                                                    const img = document.createElement('img');
                                                                    img.src = e.target.result;
                                                                    img.style.width = "200px";
                                                                    img.style.height = "200px"; // Set a fixed height
                                                                    img.style.objectFit = "cover"; // Ensure the image covers the area without distorting
                                                                    img.style.border = "1px solid #ccc"; // Light border
                                                                    img.style.margin = "10px";
                                                                    img.style.boxSizing = "border-box"; // Include border in the width/height calculation
                                                                    previewContainer.appendChild(img);
                                                                };

                                                                fileReader.readAsDataURL(file);
                                                            }
                                                        }
                                                    </script>


                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save a new
                                                            Product</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <div
                                                class="col-md-6 d-flex align-items-center justify-content-center mb-5">
                                                <!-- Right column -->
                                                <div class="mb-3">
                                                    <label for="preview-container" class="form-label">Image
                                                        Preview</label>
                                                    <div id="preview-container"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Products --}}
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-products" role="tabpanel"
                                aria-labelledby="pills-products-tab" tabindex="0">

                                <div class="row">
                                    <div class="col-md-6 mt-5">
                                        <button type="button"
                                            class="btn btn-warning btn-add-buttons border-0 text-white"
                                            data-bs-toggle="modal" data-bs-target="#exampleModal">Add new
                                            product</button>
                                        {{-- <button type="button" class="btn btn-primary btn-add-buttons" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">Create an event</button> --}}
                                    </div>

                                    {{-- Search bar --}}
                                    <div class="col-md-6 mt-5">
                                        <form method="post" action="{{ route('products-search') }}">
                                            <div class="input-group rounded-pill overflow-hidden">
                                                @csrf
                                                <input type="search" name="search" class="form-control border-0"
                                                    placeholder="Search crafts or products" aria-label="Search"
                                                    value="{{ isset($search) ? $search : '' }}">
                                                <button class="btn border-0 bg-white" type="submit">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>


                                <!-- Product Cards -->
                                <div class="row justify-content-left" style="margin-top: 3rem;">
                                    @foreach ($products->reverse() as $product)
                                        @php
                                            // Assuming 'created_at' is the date the product was uploaded
                                            $isNew = \Carbon\Carbon::parse($product->created_at)->isToday();
                                        @endphp

                                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                                            <div class="card" data-bs-toggle="modal"
                                                data-bs-target="#productModal{{ $product->ProductID }}">
                                                <div id="carouselProduct{{ $product->ProductID }}"
                                                    class="carousel slide" data-bs-ride="false">
                                                    <div class="carousel-inner">
                                                        @foreach (explode(',', $product->ProductImage) as $image)
                                                            <div
                                                                class="carousel-item @if ($loop->first) active @endif">
                                                                <img src="{{ asset('storage/products/' . $user->id . '/' . basename($image)) }}"
                                                                    class="d-block w-100 card-img-top"
                                                                    alt="Product Image">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <h5 class="card-title"
                                                        style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-transform: capitalize;">
                                                        @if ($isNew)
                                                            <span class="badge badge-gradient me-1">NEW!</span>
                                                        @endif

                                                        {{ $product->ProductName }}
                                                    </h5>
                                                    <p class="card-text text-muted"
                                                        style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                        {{ $product->ProductDescription }}</p>
                                                    <p class="card-text text-muted">
                                                        Price:
                                                        @if ($product->old_price && $product->old_price != $product->Price)
                                                            <span
                                                                style="text-decoration: line-through; color: {{ $product->Price > $product->old_price ? 'green' : 'red' }};">
                                                                ₱ {{ $product->old_price }}
                                                            </span>
                                                        @endif
                                                        <span>
                                                            ₱ {{ $product->Price }}
                                                        </span>
                                                    </p>
                                                    <div class="text-muted small text-end align-self-end">
                                                        <span class="d-none d-sm-inline-block"><i
                                                                class="far fa-eye"></i> 18</span>
                                                        <span><i class="far fa-heart ml-2"></i> 7</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>


                                        <!-- Sweet alert -->
                                        @if (Session::has('message'))
                                            @if (Session::get('type') === 'success')
                                                <script>
                                                    swal("Success", "{{ Session::get('message') }}", 'success', {
                                                        button: "OK",
                                                        timer: 3000,
                                                    });
                                                </script>
                                            @elseif (Session::get('type') === 'error')
                                                <script>
                                                    swal("Error", "{{ Session::get('message') }}", 'error', {
                                                        button: "OK",
                                                    });
                                                </script>
                                            @endif
                                        @endif


                                        {{-- View Details Modal --}}
                                        <div class="modal fade" id="productModal{{ $product->ProductID }}"
                                            tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <p class="card-text text-muted mb-0 d-flex align-items-center">
                                                            <img src="{{ asset('images/defuser.png') }}"
                                                                alt="User Image" class="user-image ms-1 me-2">
                                                            {{ $user->fname }} {{ $user->lname }} •
                                                        </p>
                                                        <p
                                                            class="card-text text-muted mb-0 d-flex align-items-center ms-2">
                                                            @if ($product->created_at->diffInHours() < 24)
                                                                Posted {{ $product->created_at->diffForHumans() }}
                                                            @else
                                                                Posted on
                                                                {{ $product->created_at->format('g:iA • m/d/Y') }}
                                                            @endif


                                                        </p>

                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <!-- Left column -->
                                                            <div class="col-md-6">
                                                                <div id="carouselProductModal{{ $product->ProductID }}"
                                                                    class="carousel slide" data-bs-interval="false">
                                                                    <div class="carousel-inner">
                                                                        @foreach (explode(',', $product->ProductImage) as $image)
                                                                            <div
                                                                                class="carousel-item @if ($loop->first) active @endif">
                                                                                <div class="zoom-container">
                                                                                    <img src="{{ asset('storage/products/' . $user->id . '/' . basename($image)) }}"
                                                                                        class="d-block w-100 card-img-top"
                                                                                        alt="Product Image"
                                                                                        style="height: 400px;">
                                                                                </div>

                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                    {{-- <button
                                                    class="carousel-control-prev @if (count(explode(',', $product->ProductImage)) <= 1) d-none @endif"
                                                    type="button"
                                                    data-bs-target="#carouselProductModal{{ $product->ProductID }}"
                                                    data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon"
                                                        aria-hidden="true"></span>
                                                    <span class="visually-hidden">Previous</span>
                                                </button>
                                                <button
                                                    class="carousel-control-next @if (count(explode(',', $product->ProductImage)) <= 1) d-none @endif"
                                                    type="button"
                                                    data-bs-target="#carouselProductModal{{ $product->ProductID }}"
                                                    data-bs-slide="next">
                                                    <span class="carousel-control-next-icon"
                                                        aria-hidden="true"></span>
                                                    <span class="visually-hidden">Next</span>
                                                </button> --}}
                                                                </div>

                                                                <!-- Thumbnails -->
                                                                <div class="mt-3">
                                                                    <div class="row">
                                                                        @foreach (explode(',', $product->ProductImage) as $index => $image)
                                                                            <div class="col-3">
                                                                                <img src="{{ asset('storage/products/' . $user->id . '/' . basename($image)) }}"
                                                                                    class="img-thumbnaill"
                                                                                    alt="Product Thumbnail"
                                                                                    data-bs-target="#carouselProductModal{{ $product->ProductID }}"
                                                                                    data-bs-slide-to="{{ $index }}"
                                                                                    onclick="selectThumbnail(this)">
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>

                                                                <script>
                                                                    function selectThumbnail(element) {
                                                                        // Remove 'selected' class from all thumbnails
                                                                        document.querySelectorAll('.img-thumbnaill').forEach(img => img.classList.remove('selected'));

                                                                        // Add 'selected' class to the clicked thumbnail
                                                                        element.classList.add('selected');
                                                                    }
                                                                </script>

                                                            </div>
                                                            <!-- Right column -->
                                                            <div class="col-md-6">
                                                                <h2 class="modal-title fs-5 mb-3 fw-bold"
                                                                    id="productModalLabel"
                                                                    style="text-transform: capitalize;">
                                                                    {{ $product->ProductName }}</h2>
                                                                <p class="card-text text-muted">
                                                                    {{ $product->ProductDescription }}</p>
                                                                <p class="card-text text-muted">
                                                                    Price:
                                                                    @if ($product->old_price && $product->old_price != $product->Price)
                                                                        <span
                                                                            style="text-decoration: line-through; color: {{ $product->Price > $product->old_price ? 'green' : 'red' }};">
                                                                            ₱ {{ $product->old_price }}
                                                                        </span>
                                                                    @endif
                                                                    <span>
                                                                        ₱ {{ $product->Price }}
                                                                    </span>
                                                                </p>



                                                                <p class="card-text text-muted">Quantity:
                                                                    {{ $product->Quantity }}</p>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="modal-footer d-flex justify-content-between align-items-center">
                                                        <p
                                                            class="card-text text-muted mb-0 d-flex align-items-start ms-2">
                                                            @if ($product->updated_at->diffInHours() < 24)
                                                            Updated {{ $product->updated_at->diffForHumans() }}
                                                        @else
                                                            Updated on {{ $product->updated_at->format('g:iA m/d/Y') }}
                                                        @endif
                                                                                                                </p>

                                                        <div>
                                                            <button type="button" class="btn btn-danger me-2"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#deleteModal{{ $product->ProductID }}"><i
                                                                    class="fas fa-trash"></i></button>
                                                            <button type="button" class="btn btn-primary me-2"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editModal{{ $product->ProductID }}"><i
                                                                    class="fas fa-edit"></i></button>
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <script>
                                            document.querySelectorAll('.zoom-container').forEach(container => {
                                                const img = container.querySelector('img');

                                                container.addEventListener('mousemove', e => {
                                                    const rect = container.getBoundingClientRect();
                                                    const x = e.clientX - rect.left;
                                                    const y = e.clientY - rect.top;

                                                    img.style.transformOrigin =
                                                        `${(x / container.offsetWidth) * 100}% ${(y / container.offsetHeight) * 100}%`;
                                                });

                                                container.addEventListener('mouseleave', () => {
                                                    img.style.transformOrigin = 'center center'; // Reset zoom origin when not hovering
                                                });
                                            });
                                        </script>



                                        <!-- Delete modal -->
                                        <div class="modal fade" id="deleteModal{{ $product->ProductID }}"
                                            tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title fs-5" id="deleteModalLabel">Delete
                                                            item?</h2>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to delete? This action cannot be undone.
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form id="delete-form-{{ $product->ProductID }}"
                                                            action="{{ route('products-seller.destroy', ['product' => $product->ProductID]) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Yes</button>
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">No</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <script>
                                            function deleteProduct(productId) {
                                                // Submit the form corresponding to the productId
                                                document.getElementById('delete-form-' + productId).submit();
                                            }
                                        </script>



                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editModal{{ $product->ProductID }}"
                                            tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editModalLabel">Edit Product</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Form for editing product content -->
                                                        <form method="post"
                                                            action="{{ route('products-seller.update', ['product' => $product->ProductID]) }}"
                                                            enctype="multipart/form-data">
                                                            @csrf
                                                            @method('put')
                                                            <!-- Input fields for editing product details -->
                                                            <div class="mb-3">
                                                                <label for="productImage" class="form-label">Product
                                                                    Image</label>
                                                                <input type="file" class="form-control"
                                                                    id="productImage" accept="image/*"
                                                                    name="PImage[]" multiple>

                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="productName" class="form-label">Product
                                                                    Name</label>
                                                                <input type="text" name="Pname"
                                                                    class="form-control" id="productName"
                                                                    value="{{ $product->ProductName }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="productDescription"
                                                                    class="form-label">Product
                                                                    Description</label>
                                                                <input type="text" class="form-control"
                                                                    id="productName"
                                                                    value="{{ $product->ProductDescription }}"
                                                                    name="Pdescription">

                                                                {{-- <textarea class="form-control" id="productDescription" value="{{$product->ProductDescription}}" name="Pdescription"></textarea> --}}
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="productPrice" class="form-label">Product
                                                                    Price</label>
                                                                <input type="text" class="form-control"
                                                                    id="productName" value="{{ $product->Price }}"
                                                                    name="Pprice">
                                                                {{-- <textarea class="form-control" id="productPrice" value="{{$product->Price}}"></textarea> --}}
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="productPrice" class="form-label">Product
                                                                    Quantity</label>
                                                                <input type="text" class="form-control"
                                                                    id="productName" value="{{ $product->Quantity }}"
                                                                    name="Pqty">
                                                                {{-- <textarea class="form-control" id="productPrice" value="{{$product->ProductQuantity}}"></textarea> --}}
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="productCategory"
                                                                    class="form-label">Product
                                                                    Category</label>
                                                                <select class="form-control" id="productCategory">
                                                                    <option value="electronics">Painting</option>
                                                                    <option value="clothing">Crochet</option>
                                                                    <option value="accessories">Bracelets</option>
                                                                    <!-- Add more options for other categories -->
                                                                </select>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button"
                                                                    class="btn btn-secondary mr-auto"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#unsavedEditModal">Cancel</button>

                                                                <!-- ADDED: class - para sa styling ng update button -->
                                                                <input type="submit" value="Update"
                                                                    class="btn btn-primary">
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Pagination --}}
                                <nav aria-label="..." class="mt-3">
                                    <ul class="pagination justify-content-end">
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" tabindex="-1"
                                                aria-disabled="true">Previous</a>
                                        </li>
                                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item" aria-current="page">
                                            <a class="page-link" href="#">2</a>
                                        </li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">Next</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>

 {{-- Events Section --}}
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show" id="pills-events" role="tabpanel"
                                    aria-labelledby="pills-events-tab" tabindex="0" data-bs-backdrop="static"
                                    data-bs-keyboard="false">

                                    <div class="row">
                                        <div class="col-md-6 mt-5">
                                            <button type="button" class="btn btn-warning btn-add-buttons text-white"
                                                data-bs-toggle="modal" data-bs-target="#eventModal">Create an
                                                event</button>
                                            {{-- <button type="button" class="btn btn-primary btn-add-buttons" data-bs-toggle="modal"
                                         data-bs-target="#exampleModal">Create an event</button> --}}
                                        </div>

                                        <!-- Create Event Modal -->
                                        <div class="modal fade" id="eventModal" tabindex="-1"
                                            aria-labelledby="eventModalLabel" aria-hidden="true"
                                            data-bs-backdrop="static" data-bs-keyboard="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="eventModalLabel">Create an Event
                                                        </h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form id="eventForm" method="POST"
                                                            action="{{ route('create.event.submit') }}"
                                                            enctype="multipart/form-data" novalidate>
                                                            @csrf
                                                            <div class="mb-3">
                                                                <label for="image" class="form-label">Upload Event
                                                                    Banner</label>
                                                                <input type="file" class="form-control"
                                                                    id="EventImage" name="EventImage"
                                                                    accept="image/*" required>
                                                                <div class="invalid-feedback">Please upload a banner
                                                                    for the event.</div>
                                                                <p class="text-muted">Accepted image formats: .jpeg,
                                                                    .jpg, .png, with a maximum size of 2MB per image.
                                                                    <br>Recommended image size: 1905px x 600px
                                                                </p>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="EventName" class="form-label">Event
                                                                    Name</label>
                                                                <input type="text" class="form-control"
                                                                    id="EventName" name="EventName"
                                                                    placeholder="Event Name" required>
                                                                <div class="invalid-feedback">Please provide the event
                                                                    name.</div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="Description"
                                                                    class="form-label">Description</label>
                                                                <input type="text" class="form-control"
                                                                    id="Description" name="EventDescription"
                                                                    placeholder="Event Description" required>
                                                                <div class="invalid-feedback">Please provide a
                                                                    description for the event.</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <label for="datetime" class="form-label">Event
                                                                        Date</label>
                                                                    <input style="margin-bottom:10px;" type="date"
                                                                        class="form-control" id="Date"
                                                                        name="Date" required
                                                                        min="<?php echo date('Y-m-d'); ?>">
                                                                    <div class="invalid-feedback">Please provide the
                                                                        event date.</div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label for="start-time" class="form-label">Start
                                                                        Time</label>
                                                                    <input type="time" class="form-control"
                                                                        id="StartTime" name="StartTime" required>
                                                                    <div class="invalid-feedback">Please provide the
                                                                        start time.</div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label for="end-time" class="form-label">End
                                                                        Time</label>
                                                                    <input type="time" class="form-control"
                                                                        id="EndTime" name="EndTime" required>
                                                                    <div class="invalid-feedback">Please provide the
                                                                        end time.</div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label for="location"
                                                                        class="form-label">Location</label>
                                                                    <input type="text" class="form-control"
                                                                        id="Location" name="Location" required
                                                                        placeholder="Location">
                                                                    <div class="invalid-feedback">Please provide the
                                                                        event location.</div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="link" class="form-label">Link for
                                                                        Registration</label>
                                                                    <input type="url" class="form-control"
                                                                        id="Link" name="Link" required
                                                                        placeholder="Link">
                                                                    <div class="invalid-feedback">Please provide a link
                                                                        for registration.</div>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                                                                <button type="submit"
                                                                    class="btn btn-primary me-md-2">Create</button>
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Cancel</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const form = document.getElementById('eventForm');

                                                form.addEventListener('submit', function(event) {
                                                    if (!form.checkValidity()) {
                                                        event.preventDefault();
                                                        form.classList.add('was-validated');
                                                    }
                                                });
                                            });
                                        </script>



                                        {{-- Search bar --}}
                                        <div class="col-md-6 mt-5">
                                            <form method="post" action="{{ route('products-search') }}">
                                                <div class="input-group rounded-pill overflow-hidden">
                                                    @csrf
                                                    <input type="search" name="search"
                                                        class="form-control border-0" placeholder="Search events"
                                                        aria-label="Search"
                                                        value="{{ isset($search) ? $search : '' }}">
                                                    <button class="btn border-0 bg-white" type="submit">
                                                        <i class="fa fa-search"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    {{-- Events Content --}}
                                    <div class="events-section mt-5">
                                        <div class="row">
                                            @forelse ($events as $event)
                                                @php
                                                    $isEnded =
                                                        \Carbon\Carbon::parse($event->Date)->isPast() ||
                                                        $event->Status === 'Ended';
                                                @endphp
                                                <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                                                    <!-- Display event details -->
                                                    <div class="card position-relative {{ $isEnded ? 'ended-event' : '' }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#eventModal{{ $event->EventID }}">
                                                        @if ($isEnded)
                                                            <div class="badge ended-badge bg-danger position-absolute">
                                                                Ended
                                                            </div>
                                                        @endif
                                                        <img src="{{ $event->EventImage ? asset('storage/' . $event->EventImage) : 'default-image.jpg' }}"
                                                            class="card-img-top" alt="{{ $event->EventName }}">
                                                        <div class="card-body">
                                                            <h5 class="card-title">{{ $event->EventName }}</h5>
                                                            <p class="card-text text-muted truncate-description-card">
                                                                {{ $event->EventDescription }}
                                                            </p>
                                                            <strong>Status:</strong>
                                                            @if ($event->Status == 'Approved')
                                                                <span class="badge bg-success">Approved</span>
                                                            @elseif($event->Status == 'Rejected')
                                                                <span class="badge bg-danger">Rejected</span>
                                                            @else
                                                                <span class="badge bg-warning">Pending</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="col-12 text-center mt-5">
                                                    <p class="text-muted">No events created yet. Start by creating your
                                                        first event!</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>

                                    <!-- Modal for View Details -->
                                    @foreach ($events as $event)
                                        <div class="modal fade dynamic-modal" id="eventModal{{ $event->EventID }}"
                                            tabindex="-1" aria-labelledby="eventModalLabel{{ $event->EventID }}"
                                            aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <!-- Full Image on Top with Overlay for Edit/Delete Menu -->
                                                    <div class="modal-body p-0 position-relative">
                                                        <img src="{{ asset('storage/' . $event->EventImage) }}"
                                                            class="img-fluid w-100" alt="Event Image"
                                                            style="object-fit: cover; height: 200px;">



                                                        <!-- Edit/Delete Menu Overlay -->
                                                        @if (Auth::id() == $event->UserID)
                                                            @php
                                                                $eventDate = \Carbon\Carbon::parse($event->Date);
                                                                $currentDate = \Carbon\Carbon::now();
                                                            @endphp
                                                            @if (Auth::id() == $event->UserID)
                                                                @php
                                                                    $eventDate = \Carbon\Carbon::parse($event->Date);
                                                                    $currentDate = \Carbon\Carbon::now();
                                                                @endphp

                                                                <div class="position-absolute top-0 end-0 p-3">
                                                                    <div class="dropdown-bar">
                                                                        <button class="btn btn-option dropdown-bar"
                                                                            type="button"
                                                                            id="dropdownMenuButton{{ $event->EventID }}"
                                                                            data-bs-toggle="dropdown"
                                                                            aria-expanded="false">
                                                                            <i class="fas fa-ellipsis-v"></i>
                                                                        </button>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>

                                                                        <ul class="dropdown-menu dropdown-menu-end"
                                                                            aria-labelledby="dropdownMenuButton{{ $event->EventID }}">
                                                                            @if ($eventDate->isFuture())
                                                                                <!-- Options for Future Events -->
                                                                                <li>
                                                                                    <button
                                                                                        class="dropdown-item edit-event"
                                                                                        data-bs-dismiss="modal"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#editEventModal{{ $event->EventID }}">
                                                                                        <i class="fas fa-edit"></i>
                                                                                        Edit
                                                                                    </button>
                                                                                </li>
                                                                            @endif

                                                                            <!-- Common option for Delete -->
                                                                            <li>
                                                                                <button
                                                                                    class="dropdown-item delete-event"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#confirmDeleteModal{{ $event->EventID }}">
                                                                                    <i class="fas fa-trash"></i> Delete
                                                                                </button>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <!-- Event Details Below -->
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <!-- Event Date and Time -->
                                                                <div
                                                                    class="d-flex justify-content-between align-items-center mb-3">
                                                                    <!-- Event Date and Time -->
                                                                    <p class="event-date-time text-muted mb-0">
                                                                        {{ \Carbon\Carbon::parse($event->Date)->format('l F j, Y') }}
                                                                        |
                                                                        {{ \Carbon\Carbon::parse($event->StartTime)->format('g:i A') }}
                                                                        -
                                                                        {{ \Carbon\Carbon::parse($event->EndTime)->format('g:i A') }}
                                                                    </p>

                                                                    <!-- Link Button -->
                                                                    @if ($event->Link)
                                                                        <a href="{{ $event->Link }}"
                                                                            class="btn btn-primary-link"
                                                                            target="_blank">
                                                                            <i class="fas fa-link me-2"></i> Link to
                                                                            Join
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                                <!-- Event Name -->
                                                                <h5 class="modal-title fw-bold mt-3">
                                                                    {{ $event->EventName }}</h5>
                                                                <!-- Event Description -->
                                                                <p class="modal-text mb-3 text-muted">
                                                                    {{ $event->EventDescription }}</p>
                                                                <!-- Location with Icon -->
                                                                <div class="d-flex align-items-center mb-3">
                                                                    @if ($event->Link)
                                                                        @php
                                                                            $statusColor = '';
                                                                            switch ($event->Status) {
                                                                                case 'Approved':
                                                                                    $statusColor = 'btn-success';
                                                                                    break;
                                                                                case 'Pending':
                                                                                    $statusColor = 'btn-warning';
                                                                                    break;
                                                                                case 'OnGoing':
                                                                                    $statusColor = 'btn-info';
                                                                                    break;
                                                                                case 'Rejected':
                                                                                    $statusColor = 'btn-danger';
                                                                                    break;
                                                                                case 'Ended':
                                                                                    $statusColor = 'btn-secondary';
                                                                                    break;
                                                                            }
                                                                        @endphp

                                                                        @if (Auth::id() == $event->UserID)
                                                                            <button type="button"
                                                                                class="btn {{ $statusColor }} me-3"
                                                                                disabled>
                                                                                {{ $event->Status }}
                                                                            </button>
                                                                        @endif
                                                                    @endif

                                                                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($event->Location) }}"
                                                                        class="btn btn-primary" target="_blank">
                                                                        <i class="fas fa-map-marker-alt"></i>
                                                                        {{ $event->Location }}
                                                                    </a>
                                                                </div>


                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach


                                    <!-- Modal for Editing Events -->
                                    @foreach ($events as $event)
                                        <div class="modal fade" id="editEventModal{{ $event->EventID }}"
                                            tabindex="-1"
                                            aria-labelledby="editEventModalLabel{{ $event->EventID }}"
                                            aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <div style="text-align: center;">
                                                            <h2 class="form-name fs-5">Edit Event</h2>
                                                        </div>

                                                        @if ($errors->any())
                                                            <div class="alert alert-danger">
                                                                <ul>
                                                                    @foreach ($errors->all() as $error)
                                                                        <li>{{ $error }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif

                                                        <!-- Event edit form -->
                                                        <form action="{{ route('events.update', $event->EventID) }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')

                                                            <!-- Image Upload and Preview -->
                                                            <div class="mb-3">
                                                                <label for="eventImage" class="form-label">Event
                                                                    Image</label>
                                                                <input type="file" class="form-control"
                                                                    id="eventImage" name="EImage"
                                                                    accept="image/png, image/jpeg">
                                                                <img id="imagePreview" src=""
                                                                    alt="Image Preview"
                                                                    style="display:none; margin-top:10px; max-width: 100%;">
                                                            </div>

                                                            <!-- Event Name -->
                                                            <div class="mb-3">
                                                                <label for="eventName" class="form-label">Event
                                                                    Name</label>
                                                                <input type="text" class="form-control"
                                                                    id="eventName" name="EName"
                                                                    value="{{ $event->EventName }}">
                                                            </div>

                                                            <!-- Description -->
                                                            <div class="mb-3">
                                                                <label for="eventDescription"
                                                                    class="form-label">Description</label>
                                                                <input class="form-control" id="eventDescription"
                                                                    name="EDescription"
                                                                    value="{{ $event->EventDescription }}">
                                                            </div>

                                                            <!-- Date, Start Time, and End Time -->
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <label for="eventDate"
                                                                        class="form-label">Date</label>
                                                                    <input type="date" class="form-control"
                                                                        id="eventDate" name="EDate"
                                                                        value="{{ $event->Date }}"
                                                                        min="<?php echo date('Y-m-d'); ?>">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label for="eventStartTime"
                                                                        class="form-label">Start Time</label>
                                                                    <input type="time" class="form-control"
                                                                        id="eventStartTime" name="EStartTime"
                                                                        value="{{ $event->StartTime }}">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label for="eventEndTime" class="form-label">End
                                                                        Time</label>
                                                                    <input type="time" class="form-control"
                                                                        id="eventEndTime" name="EEndTime"
                                                                        value="{{ $event->EndTime }}">
                                                                </div>
                                                            </div>

                                                            <!-- Location and Link -->
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label for="eventLocation"
                                                                        class="form-label">Location</label>
                                                                    <input type="text" class="form-control"
                                                                        id="eventLocation" name="ELocation"
                                                                        value="{{ $event->Location }}">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="eventLink"
                                                                        class="form-label">Link</label>
                                                                    <input type="url" class="form-control"
                                                                        id="eventLink" name="ELink"
                                                                        value="{{ $event->Link }}">
                                                                </div>
                                                            </div>

                                                            <!-- Submit Button -->
                                                            <div class="d-flex justify-content-end"
                                                                style="margin-top: 9px;">
                                                                <button style="margin-right:9px;" type="submit"
                                                                    class="btn button-primary">Update</button>

                                                                <!-- Close Button -->
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Image Preview and Validation Script -->
                                        <script>
                                            document.getElementById('eventImage').addEventListener('change', function() {
                                                const file = this.files[0];
                                                const imagePreview = document.getElementById('imagePreview');

                                                if (file) {
                                                    const fileType = file['type'];
                                                    const validImageTypes = ['image/jpeg', 'image/png'];

                                                    // Validate file type
                                                    if (!validImageTypes.includes(fileType)) {
                                                        alert('Only JPG and PNG image formats are allowed.');
                                                        this.value = ''; // Clear the input
                                                        imagePreview.style.display = 'none'; // Hide the preview
                                                    } else {
                                                        // Show preview
                                                        const reader = new FileReader();
                                                        reader.onload = function(e) {
                                                            imagePreview.src = e.target.result;
                                                            imagePreview.style.display = 'block';
                                                        };
                                                        reader.readAsDataURL(file);
                                                    }
                                                } else {
                                                    imagePreview.style.display = 'none'; // Hide preview if no file selected
                                                }
                                            });

                                            // Handle modal close confirmation
                                            const modal = document.getElementById('editEventModal{{ $event->EventID }}');
                                            modal.addEventListener('hide.bs.modal', function(event) {
                                                if (isFormDirty) {
                                                    event.preventDefault();
                                                    if (confirm('You have unsaved changes. Are you sure you want to close without saving?')) {
                                                        isFormDirty = false;
                                                        // Manually hide the modal
                                                        $(modal).modal('hide');
                                                    }
                                                }
                                            });
                                        </script>


                                        <!-- Confirmation Modal for Deleting Event -->
                                        <div class="modal fade" id="confirmDeleteModal{{ $event->EventID }}"
                                            tabindex="-1"
                                            aria-labelledby="confirmDeleteModalLabel{{ $event->EventID }}"
                                            aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="confirmDeleteModalLabel{{ $event->EventID }}">Confirm
                                                            Delete
                                                        </h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">Are you sure you want to delete this event?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form action="{{ route('events.destroy', $event->EventID) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-danger">Delete</button>
                                                        </form>
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        {{-- Archives --}}
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show" id="pills-archives" role="tabpanel"
                                aria-labelledby="pills-archives-tab" tabindex="0">

                                <h1>Archives</h1>

                            </div>
                        </div>
                    </div>



                    <div class="container custom-shadow mt-5">

                        {{-- <h1 class="text-start pt-3">My Events</h1> --}}
                        {{-- padisplay dito yung mga created events ng user na to --}}

                    </div>




                    <div class="container">
                        <div class="feedbacks mt-5">
                            <h2 style="color: #145DA0;">Feedbacks</h2>
                        </div>
                    </div>

                    <div class="container custom-shadow">
                        <div id="feedbackCarousel" class="carousel slide mt-5" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @if($feedbacks->isEmpty())
                                    <div class="carousel-item active">
                                        <div class="container custom-shadow p-3">
                                            <div class="row">
                                                <div class="col-12">
                                                    <p class="mb-1">No feedbacks available yet.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    @foreach ($feedbacks as $key => $feedback)
                                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                            <div class="container custom-shadow p-3">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <!-- Star Rating -->
                                                        <div class="mb-2">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="fa fa-star{{ $i <= $feedback->rating ? '' : '-o' }}" style="color: #ffc107; font-size: 20px;"></i>
                                                            @endfor
                                                        </div>
                                                        <!-- Feedback Content -->
                                                        <p class="mb-1">{{ $feedback->feedback }}</p>
                                                        <!-- User and Timestamp -->
                                                        <p class="text-muted mb-0">- {{ $feedback->user->name ?? 'Anonymous' }}</p>
                                                        <p class="text-muted small">Posted on: {{ $feedback->created_at->format('F d, Y') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
    
                            <!-- Carousel Controls -->
                            <button class="carousel-control-prev" type="button" data-bs-target="#feedbackCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#feedbackCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- UI: Back to top button --}}
        <button type="button" class="btn btn-primary btn-floating btn-lg" id="btn-back-to-top">
            <i class="fas fa-arrow-up"></i>
        </button>



        {{-- JS: Back to top button --}}
        <script>
            //Get the button
            let mybutton = document.getElementById("btn-back-to-top");

            // When the user scrolls down 20px from the top of the document, show the button
            window.onscroll = function() {
                scrollFunction();
            };

            function scrollFunction() {
                if (
                    document.body.scrollTop > 20 ||
                    document.documentElement.scrollTop > 20
                ) {
                    mybutton.style.display = "block";
                } else {
                    mybutton.style.display = "none";
                }
            }
            // When the user clicks on the button, scroll to the top of the document
            mybutton.addEventListener("click", backToTop);

            function backToTop() {
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            }
        </script>


        <footer>
            @include('header_and_footer.footer')
        </footer>

        <!-- multiple upload of image -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
            crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>
</body>

</html>
