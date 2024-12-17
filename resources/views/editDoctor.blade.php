@extends('layouts.adminLayout')



@section('content')

<div class="d-flex1 doc-profile-wrapper" style="align-items: start" >
    <div class="doc-profile" >
        <div class="pro-img">
                <img src="{{asset('public/assets/images/doctor/doctor.jpg')}}" alt="Doctor image">
        </div>

        <div class="heading-div">
            <h2>Dr. Danny White</h2>
        </div>

        <form action="" method="post" class="content-div">
              <div class="flex-input-fields">
                    <div class="form-group1">
                       <label for="txtState"> <span><i class="fa-regular fa-envelope"></i> </span>First Name</label>
                        <div class="form-input">
                             <input class="form-control" type="text" id="txtState" name="txtState" value="Danny" >
                         </div>
                    </div>

                    <div class="form-group1">
                        <label for="txtState"> <span><i class="fa-solid fa-phone"></i> </span>Phone No</label>
                         <div class="form-input">
                              <input class="form-control" type="text" id="txtState" name="txtState"  value="+91 9008899809" >
                          </div>
                     </div>
                     
                     <!-- <div class="form-group1">
                        <label for="txtState"> <span><i class="fa-solid fa-user"></i> </span>Membership Id</label>
                         <div class="form-input">
                              <input class="form-control" type="text" id="txtState" name="txtState"  >
                          </div>
                     </div> -->

                     <div class="form-group1">
                        <label for="txtState"> <span><i class="fa-regular fa-clock"></i> </span>Last Login</label>
                         <div class="form-input">
                              <input class="form-control" type="text" id="txtState" name="txtState"  >
                          </div>
                     </div>


              </div>  

              <div class="button" >
                <button id="button-reset"><i class="fa-solid fa-rotate-right"></i> Reset Your Password</button>
              </div>
              

        </form>


    </div>

   

    <div class="form-card">
        <div class="heading-div">
            <h2>Edit Dr Profile</h2>
        </div>
        <form id="doctor-profile-form"  method="POST" class="content-div" action="">
            @csrf
            <div class="flex-input-fields d-flex1">
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-solid fa-user"></i></span>
                        <input class="form-control" type="text" id="firstName" name="firstName" maxlength="50" value="{{ old('firstName') }}">
                        <div class="text-danger">
                            @error('firstName')
                                * {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
    
                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-solid fa-user"></i></span>
                        <input class="form-control" type="text" id="lastName" name="lastName" value="{{ old('lastName') }}">
                        <div class="text-danger">
                            @error('lastName')
                                * {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
    
                <div class="form-group">
                    <label for="email">Email ID</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-regular fa-envelope"></i></span>
                        <input class="form-control" type="email" id="email" name="email" value="{{ old('email') }}">
                        <div class="text-danger">
                            @error('email')
                                * {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
    
                <div class="form-group">
                    <label for="mobileNo">Mobile No</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-solid fa-phone"></i></span>
                        <input class="form-control" type="text" id="mobileNo" name="mobileNo" value="{{ old('mobileNo') }}">
                        <div class="text-danger">
                            @error('mobileNo')
                                * {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
    
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <div class="form-input">
                        <input type="radio" id="male" name="gender" value="M" {{ old('gender') == 'M' ? 'checked' : '' }}>
                        <label for="male">Male</label>
    
                        <input type="radio" id="female" name="gender" value="F" {{ old('gender') == 'F' ? 'checked' : '' }}>
                        <label class="ml-5" for="female">Female</label>
                        <div class="text-danger">
                            @error('gender')
                                * {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
    
                <div class="form-group">
                    <label for="dob">Date of Birth</label>
                    <div class="form-input">
                        <span><i class="fa-regular fa-calendar-days"></i></span>
                        <input class="form-control" type="date" id="dob" name="dob" value="{{ old('dob') }}">
                        <div class="text-danger">
                            @error('dob')
                                * {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
    
                <div class="form-group con-all">
                    <label for="altEmail">Alternative Email ID</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-regular fa-envelope"></i></span>
                        <input class="form-control" type="email" id="altEmail" name="altEmail" value="{{ old('altEmail') }}">
                        <div class="text-danger">
                            @error('altEmail')
                                * {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
    
                <div class="form-group con-all">
                    <label for="altContact">Alternative Contact</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-solid fa-phone"></i></span>
                        <input class="form-control" type="text" id="altContact" name="altContact" value="{{ old('altContact') }}">
                        <div class="text-danger">
                            @error('altContact')
                                * {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
    
                <div class="form-group">
                    <label for="about">About Doctor</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-solid fa-user"></i></span>
                        <input class="form-control" type="text" id="about" name="about" value="{{ old('about') }}">
                        <div class="text-danger">
                            @error('about')
                                * {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
    
                <div class="form-group">
                    <label for="panNumber">Pan Number</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-solid fa-id-card"></i></span>
                        <input class="form-control" type="text" id="panNumber" name="panNumber" value="{{ old('panNumber') }}">
                        <div class="text-danger">
                            @error('panNumber')
                                * {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
    
    
    
                <div class="form-group">
                    <label for="txtAddress">Address</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-solid fa-location-pin"></i></span>
                        <input type="text" class="form-control" id="txtAddress" name="txtAddress" maxlength="60" value="{{old('txtAddress')}}">
                        <div class="text-danger">
                            @error( 'txtAddress')
                            * {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="txtPincode">Pin Code</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-solid fa-map-pin"></i></span>
                        <input class="form-control" type="text" id="txtPincode" name="txtPincode" maxlength="6" value="{{old('txtPincode')}}">
                        <div class="text-danger">
                            @error('txtPincode')
                            * {{ $message }}
                        @enderror
                        </div>
                    </div>
                </div>
    
                <div class="form-group">
                    <label for="txtCity">City</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-solid fa-building"></i></span>
                        <input class="form-control" type="text" id="txtCity" name="txtCity" readonly value="{{ old('txtCity') }}">
                        <div class="text-danger">
                            @error('txtCity')
                            * {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
    
                <div class="form-group">
                    <label for="txtDistrict">District</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-solid fa-city"></i></span>
                        <input class="form-control" type="text" id="txtDistrict" name="txtDistrict" readonly value="{{ old('txtDistrict') }}">
                        <div class="text-danger">
                            @error('txtDistrict')
                                * {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
    
                <div class="form-group">
                    <label for="txtState">State</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-solid fa-mountain-city"></i></span>
                        <input class="form-control" type="text" id="txtState" name="txtState" readonly value="{{ old('txtState') }}">
                        <div class="text-danger">
                            @error('txtState')
                            * {{ $message }}
                        @enderror
                        </div>
                    </div>
                </div>
    
                <div class="form-group">
                    <label for="txtCountry">Country</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-solid fa-globe"></i></span>
                        <input class="form-control" type="text" id="txtCountry" name="txtCountry" readonly value="{{ old('txtCountry') }}">
                        <div class="text-danger">
                            @error('txtCountry')
                                * {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
    
                <div class="form-group">
                    <label for="selLocality">Locality</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-solid fa-location-crosshairs"></i></span>
                        <select class="form-control" name="selLocality" id="localitySelect">
                            <option value="" selected disabled>Select Locality</option>
                        </select>
                        <div class="text-danger">
                            @error('selLocality')
                                * {{ $message }}
                            @enderror
                        </div>
                    </div>
                    {{-- <select class="form-control" name="selLocality" id="localitySelect">
                        <option value="" selected disabled>Select Locality</option>
                    </select> --}}
                </div>
            </div>
        </form>
        <div class="button">
             <button id="button-update">Update Your Profile</button>
        </div>
    </div>

    
</div>

@endsection


@section("javaScript")


<script src="{{asset('public/assets/js/doctorjs.js')}}"></script>
@endsection



