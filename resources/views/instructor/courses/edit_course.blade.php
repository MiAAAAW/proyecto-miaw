@extends('instructor.instructor_dashboard')
@section('instructor')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Course</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">Edit Course</h5>
                <form method="post" action="{{route('update.course')}}" class="row g-3" id="myForm"
                      enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="course_id" value="{{$course->id}}">
                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">Course Name</label>
                        <input type="text" name="course_name" class="form-control" id="input1"
                               placeholder="Course Name" value="{{$course->course_name}}">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">Course Title</label>
                        <input type="text" name="course_title" class="form-control" id="input1"
                               placeholder="Course Title" value="{{$course->course_title}}">
                    </div>


                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">Course Category</label>
                        <select name="category_id" class="form-select mb-3" aria-label="Default select example">
                            <option selected="" disabled>Open this select menu</option>
                            @foreach($categories as $cat)
                                <option value="{{$cat->id}}" {{$cat->id == $course->category_id ? 'selected' : '' }}>{{$cat->category_name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">Course Subcategory</label>
                        <select name="subcategory_id" class="form-select mb-3" aria-label="Default select example">
                            <option>
                            @foreach($subcategories as $subcat)
                                <option value="{{$subcat->id}}" {{$subcat->id == $course->subcategory_id ? 'selected' : '' }}>{{$subcat->subcategory_name}}</option>
                                @endforeach
                            </option>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">Certificate Available</label>
                        <select name="certificate" class="form-select mb-3" aria-label="Default select example">
                            <option selected="" disabled>Open this select menu</option>
                            <option value="Yes" {{$course->certificate == 'Yes' ? 'selected' :''}}>Yes</option>
                            <option value="No" {{$course->certificate == 'No' ? 'selected' :''}}>No</option>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">Course Label</label>
                        <select name="label" class="form-select mb-3" aria-label="Default select example">
                            <option selected="" disabled>Open this select menu</option>
                            <option value="Beginner" {{$course->label == 'Beginner' ? 'selected' :''}}>Beginner</option>
                            <option value="Intermediate" {{$course->label == 'Intermediate' ? 'selected' :''}}>Intermediate</option>
                            <option value="Advance"{{$course->label == 'Advance' ? 'selected' :''}} >Advance</option>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="input1" class="form-label">Course Price</label>
                        <input type="text" name="selling_price" class="form-control" id="input1" value="{{$course->selling_price}}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="input1" class="form-label">Discount Price</label>
                        <input type="text" name="discount_price" class="form-control" id="input1" value="{{$course->discount_price}}"
                        >
                    </div>

                    <div class="form-group col-md-3">
                        <label for="input1" class="form-label">Duration</label>
                        <input type="text" name="duration" class="form-control" id="input1" value="{{$course->duration}}"
                        >
                    </div>

                    <div class="form-group col-md-3">
                        <label for="input1" class="form-label">Resources</label>
                        <input type="text" name="resources" class="form-control" id="input1" value="{{$course->resources}}"
                        >
                    </div>

                    <div class="form-group col-md-12">
                        <label for="input1" class="form-label">Course Prerequisites</label>
                        <textarea class="form-control" id="input11" name="prerequisites" rows="3">{{$course->prerequisites}}</textarea>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="input1" class="form-label">Course Description</label>
                        <textarea class="form-control" id="myeditorinstance" name="description">
                            {!!$course->description!!}
                        </textarea>
                    </div>

                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bestseller" value="1"
                                       id="flexCheckDefault" {{$course->bestseller == 1 ? 'checked' :''}}>
                                <label class="form-check-label" for="flexCheckDefault">Best Seller</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="featured" value="1"
                                       id="flexCheckDefault" {{$course->featured == 1 ? 'checked' :''}}>
                                <label class="form-check-label" for="flexCheckDefault">Featured</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="highestrated" value="1"
                                       id="flexCheckDefault" {{$course->highestrated == 1 ? 'checked' :''}}>
                                <label class="form-check-label" for="flexCheckDefault">Highest Rated</label>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

{{--    Start Image Edit --}}

    <div class="page-content">
        <div class="card p-4">

            <h5 class="mb-4">Edit Image</h5>
            <div class="card-body">
                <form action="{{ route('update.course.image') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $course->id }}">
                    <input type="hidden" name="old_img" value="{{ $course->course_image }}">


                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="input2" class="form-label">Course Image </label>
                            <input class="form-control" name="course_image" type="file" id="image">
                        </div>

                        <div class="col-md-6">
                            <img id="showImage" src="{{ asset($course->course_image) }}" alt="Admin" class="rounded-circle p-1 bg-primary" width="100">
                        </div>
                    </div>

                    <br><br>
                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>



    {{-- //// Start Main Course Video Update /// --}}

    <div class="page-content">
        <div class="card p-4">
            <h5>Edit Video</h5>
            <div class="card-body">

                <form action="{{ route('update.course.video') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="vid" value="{{ $course->id }}">
                    <input type="hidden" name="old_vid" value="{{ $course->video }}">


                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="input2" class="form-label">Course Intro Video </label>
                            <input type="file" name="video" class="form-control"  accept="video/mp4, video/webm" >
                        </div>

                        <div class="col-md-6">
                            <video width="300" height="130" controls>
                                <source src="{{ asset( $course->video ) }}" type="video/mp4">
                            </video>
                        </div>
                    </div>

                    <br><br>
                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">Save Changes</button>

                        </div>
                    </div>

                </form>


            </div>
        </div>

    </div>

    {{-- //// End Main Course Video Update /// --}}

    {{-- //// Start Goal Course Update /// --}}

    <div class="page-content">
        <div class="card p-4">
            <h5>Edit Goal Course</h5>
            <div class="card-body">

                <form action="{{ route('update.course.goal') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="id" value="{{ $course->id }}">
                    <!--   //////////// Goal Option /////////////// -->
                    @foreach ($goals as $item)
                        <div class="row add_item">
                            <div class="whole_extra_item_delete" id="whole_extra_item_delete">
                                <div class="container mt-2">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="goals" class="form-label"> Goals </label>
                                                <input type="text" name="course_goals[]" id="goals" class="form-control" value="{{ $item->goal_name }}" >
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6" style="padding-top: 30px;">
                                            <a class="btn btn-success addeventmore"><i class="fa fa-plus-circle"></i> Add More..</a>

                                            <span class="btn btn-danger btn-sm removeeventmore"><i class="fa fa-minus-circle">Remove</i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!---end row-->

                    @endforeach

                    <!--   //////////// End Goal Option /////////////// -->


                    <br><br>
                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">Save Changes</button>

                        </div>
                    </div>

                </form>


            </div>
        </div>

    </div>

    {{-- //// End Main Goal Course Update /// --}}

    {{--    End Start Edit--}}
    <div class="switcher-wrapper">
        <div class="switcher-btn"><i class='bx bx-cog bx-spin'></i>
        </div>
        <div class="switcher-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-uppercase">Theme Customizer</h5>
                <button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
            </div>
            <hr/>
            <h6 class="mb-0">Theme Styles</h6>
            <hr/>
            <div class="d-flex align-items-center justify-content-between">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="lightmode" checked>
                    <label class="form-check-label" for="lightmode">Light</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="darkmode">
                    <label class="form-check-label" for="darkmode">Dark</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="semidark">
                    <label class="form-check-label" for="semidark">Semi Dark</label>
                </div>
            </div>
            <hr/>
            <div class="form-check">
                <input class="form-check-input" type="radio" id="minimaltheme" name="flexRadioDefault">
                <label class="form-check-label" for="minimaltheme">Minimal Theme</label>
            </div>
            <hr/>
            <h6 class="mb-0">Header Colors</h6>
            <hr/>
            <div class="header-colors-indigators">
                <div class="row row-cols-auto g-3">
                    <div class="col">
                        <div class="indigator headercolor1" id="headercolor1"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor2" id="headercolor2"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor3" id="headercolor3"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor4" id="headercolor4"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor5" id="headercolor5"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor6" id="headercolor6"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor7" id="headercolor7"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor8" id="headercolor8"></div>
                    </div>
                </div>
            </div>
            <hr/>
            <h6 class="mb-0">Sidebar Colors</h6>
            <hr/>
            <div class="header-colors-indigators">
                <div class="row row-cols-auto g-3">
                    <div class="col">
                        <div class="indigator sidebarcolor1" id="sidebarcolor1"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor2" id="sidebarcolor2"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor3" id="sidebarcolor3"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor4" id="sidebarcolor4"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor5" id="sidebarcolor5"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor6" id="sidebarcolor6"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor7" id="sidebarcolor7"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor8" id="sidebarcolor8"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--========== Start of add multiple class with ajax ==============-->
    <div style="visibility: hidden">
        <div class="whole_extra_item_add" id="whole_extra_item_add">
            <div class="whole_extra_item_delete" id="whole_extra_item_delete">
                <div class="container mt-2">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="goals">sub goals</label>
                            <input type="text" name="course_goals[]" id="goals" class="form-control" placeholder="Goals  ">
                        </div>
                        <div class="form-group col-md-6" style="padding-top: 20px">
                            <span class="btn btn-success btn-sm addeventmore"><i class="fa fa-plus-circle">Add</i></span>
                            <span class="btn btn-danger btn-sm removeeventmore"><i class="fa fa-minus-circle">Remove</i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!----For Section-------->
    <script type="text/javascript">
        $(document).ready(function(){
            var counter = 0;
            $(document).on("click",".addeventmore",function(){
                var whole_extra_item_add = $("#whole_extra_item_add").html();
                $(this).closest(".add_item").append(whole_extra_item_add);
                counter++;
            });
            $(document).on("click",".removeeventmore",function(event){
                $(this).closest("#whole_extra_item_delete").remove();
                counter -= 1
            });
        });
    </script>
    <!--========== End of add multiple class with ajax ==============-->
    {{--change subcategory based on category--}}
    <script type="text/javascript">
        //It waits for the document to be fully loaded ($(document).ready()).
        $(document).ready(function () {
            //It attaches an event handler to the select element with the name "category_id" using $('select[name="category_id"]').on('change', function(){...}).
            $('select[name="category_id"]').on('change', function () {
                //When the value of the "category_id" select element changes, it retrieves the selected value using var category_id = $(this).val();
                var category_id = $(this).val();
                //If a category is selected (if (category_id)), it sends an AJAX request to the server using $.ajax({...}).
                if (category_id) {
                    $.ajax({
                        {{--//The URL for the AJAX request is constructed dynamically using {{ url('/subcategory/ajax') }} with the selected category ID appended to it.--}}
                        //It's specified as a GET request (type: "GET").
                        url: "{{ url('/subcategory/ajax') }}/" + category_id,
                        type: "GET",
                        //The expected dataType of the response is JSON (dataType:"json").
                        dataType: "json",
                        //Upon successful retrieval of data from the server, the success callback function is executed.
                        success: function (data) {
                            //Inside the success callback function, it first clears any existing options in the select element with the name "subcategory_id" using $('select[name="subcategory_id"]').html('');.
                            $('select[name="subcategory_id"]').html('');
                            var d = $('select[name="subcategory_id"]').empty();
                            //Then, it iterates over the received data using $.each(data, function(key, value){...}) and appends options to the "subcategory_id" select element based on the received data.
                            $.each(data, function (key, value) {
                                $('select[name="subcategory_id"]').append('<option value="' + value.id + '">' + value.subcategory_name + '</option>');
                            });
                        },

                    });
                } else {
                    alert('danger');
                }
            });
        });

    </script>



    <script type="text/javascript">
        $(document).ready(function () {
            $('#image').change(function (e) {
                var reader = new FileReader();
                //setting before run
                reader.onload = function (e) {
                    $('#showImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            })
        })
    </script>

    <!--validation message-->
    <script type="text/javascript">
        $(document).ready(function () {
            $('#myForm').validate({
                rules: {
                    category_name: {
                        required: true,
                    },

                    image: {
                        required: true,
                    },

                    course_name: {
                        required: true,
                    },

                    course_title: {
                        required: true,
                    },

                    course_image: {
                        required: true,
                    },

                },
                messages: {
                    category_name: {
                        required: 'Please Enter Category Name',
                    },

                    image: {
                        required: 'Please upload category image',
                    },

                    course_name: {
                        required: 'Please enter course name',
                    },

                    course_title: {
                        required: 'Please enter course title',
                    },

                    course_image: {
                        required: 'Please upload course image',
                    },
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
            });
        });

    </script>

    <!--end validation message-->

@endsection
