<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>FCCC Registration Form</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
      * {
        box-sizing: border-box;
        font-family: Arial, sans-serif !important;
      }

      body {
        font-family: Arial, sans-serif !important;
        margin: 0;
        padding: 40px;
         background: linear-gradient(135deg, #4c1130, #f1edec);
      }

      .container {
        max-width: 900px;
        margin: 0 auto;
        background: #fff;
        padding: 30px 40px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      }

      h1 {
        font-size: 35px;
        margin-bottom: 10px;
        color: black;
        text-align: center;
      }

      .form-title {
        font-size: 25px;
        margin-bottom: 25px;
        color: #900;
        text-align: center;
      }

      .form-title span {
        color: #900;
      }

      .form-section {
        margin-bottom: 30px;
      }

      .section-label {
        display: block;
        font-weight: bold;
        font-size: 20px;
        margin-bottom: 30px;
      }

      input[type="text"],
      input[type="email"],
      select {
        width: 100%;
        padding: 7px;
        margin-bottom: 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
      }

      input[type="checkbox"],
      input[type="radio"] {
        margin-right: 6px;
      }

      .radio-group {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
      }

      .field-group {
        margin-top: 15px;
      }

      .form-actions {
        text-align: center;
        margin-top: 30px;
      }

      button[type="submit"] {
        background: #0A5C45;
        color: #fff;
        border: none;
        padding: 14px 24px;
        border-radius: 6px;
        font-size: 15px;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.3s ease;
      }

      button[type="submit"]:hover {
        background: #0066cc;
      }

      .required {
        color: red;
      }

      .add-btn{
        color: white;
        background-color: #0A5C45;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
      }

      .upload-btn {
        background-color: #00695c;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
      }

      /* input[type="file"] {
        display: none;
      } */
      
      .upload-btn:hover{
       background-color: #0066cc !important;
      }
      
      .hidden {
        display: none;
      }
      
      .error-text {
        color: red;
        font-size: 12px;
        margin-top: 4px;
        display: block;
      }
      
      .is-invalid {
        border-color: red !important;
      }
      
      .validation-success {
        color: green;
        font-size: 12px;
        margin-top: 4px;
        display: block;
      }

      .form-label {
        font-weight: bold;
        margin-bottom: 0.5rem;
      }
      
      .align-radio-group {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: center;
      }
      
      .form-check-inline {
        display: inline-flex;
        align-items: center;
        margin-right: 1rem;
      }
      
      .form-check-label {
        margin-left: 0.5rem;
        font-weight: normal !important;
      }
      
      label {
        font-weight: bold;
        color: #000 !important;
      }
      
      .director-row, .wireman-row {
        margin-bottom: 10px;
      }
      
      .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
      }
      
      .alert-danger {
        color: #a94442;
        background-color: #f2dede;
        border-color: #ebccd1;
      }
      
      .alert-success {
        color: #3c763d;
        background-color: #dff0d8;
        border-color: #d6e9c6;
      }

      /* Success Modal styles */
      .success-modal .top-bar {
        height: 6px;
        background-color: #2eaf65;
        border-top-left-radius: .3rem;
        border-top-right-radius: .3rem;
      }
      .success-modal .icon-circle {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background-color: #58c173;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        margin-top: -35px;
        box-shadow: 0 2px 8px rgba(0,0,0,.1);
      }
      .success-modal .ok-btn {
        background-color: #0A5C45;
        color: #fff;
        border: none;
      }
      .success-modal .ok-btn:hover {
        background-color: #0066cc;
      }
      .success-modal .modal-content {
        border-radius: 8px;
      }
      .success-modal .modal-body {
        padding: 40px 30px;
      }
    </style>
</head>
<body>
  <!-- <div id="message-container" class="container mb-3"></div> -->
  
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif
  
  <form id="FCCCRegistrationForm" action="{{ route('registration.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
    @csrf
    <h1 style="color: white;" class="mb-3">Register for Energy Generation License Portal</h1>
    <div class="container">
      @if(session('success'))
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content success-modal">
          <div class="top-bar"></div>
          <div class="modal-body text-center">
            <div class="icon-circle mx-auto">
              <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
            </div>
            <h5 class="fw-bold mt-3">THANK YOU</h5>
            <p class="mb-4">Form has been successfully submitted</p>
            <button type="button" class="btn ok-btn px-4" id="successOkBtn" data-bs-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
    </div>
  @endif
      

      <!-- Type of Account -->
      <div class="row mb-4 mt-4">
        <div class="col-md-3">
          <label class="form-label">Type of Account <span class="required">*</span></label>
        </div>
        <div class="col-md-9">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="account_type" id="individualAccount" value="individual" checked>
            <label class="form-check-label" for="individualAccount">Individual</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="account_type" id="companyAccount" value="company">
            <label class="form-check-label" for="companyAccount">Company</label>
          </div>
        </div>
      </div>

      <!-- Company Form -->
      <div id="Company" class="hidden">
        <h2 class="form-title">Registration Form - <span>Company</span></h2>
        
        <!-- Organization Name -->
        <div class="row mb-3">
          <div class="col-md-3">
            <label class="form-label">Organization Name <span class="required">*</span></label>
          </div>
          <div class="col-md-9">
            <input type="text" class="form-control" placeholder="Organization name" id="organization" name="organization_name">
            <span id="organization_error" class="error-text"></span>
          </div>
        </div>

        <!-- Type of Organization -->
        <div class="row mb-3">
          <div class="col-md-3">
            <label class="form-label">Type of Organization <span class="required">*</span></label>
          </div>
          <div class="col-md-9">
            <div class="row">
              <div class="col-md-6">
                <div class="form-check">
                  <input class="form-check-input" type="radio" id="sole" name="organization_type" value="Sole Proprietor">
                  <label class="form-check-label" for="sole">Sole Proprietor</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" id="partnership" name="organization_type" value="Partnership">
                  <label class="form-check-label" for="partnership">Partnership</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" id="limited" name="organization_type" value="Limited Liability">
                  <label class="form-check-label" for="limited">Limited Liability</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-check">
                  <input class="form-check-input" type="radio" id="government" name="organization_type" value="Government Entity">
                  <label class="form-check-label" for="government">Government Entity</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" id="ngo" name="organization_type" value="NGO">
                  <label class="form-check-label" for="ngo">Non Government Organization</label>
                </div>
              </div>
            </div>
            <span id="organization_type_error" class="error-text"></span>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-3">
            <label class="form-label">Designation Business <span class="required">*</span></label>
          </div>
          <div class="col-md-9">
            <input type="text" class="form-control" placeholder="Designation Business" name="designation_business">
            <span id="designation_business_error" class="error-text"></span>
          </div>
        </div>

        <!-- Portal Account Holder -->
        <div class="form-section">
          <label class="section-label">Portal Account Holder <span class="required">*</span></label>

          <!-- Title -->
          <div class="row mb-3">
            <div class="col-md-3">
              <label class="form-label">Title <span class="required">*</span></label>
            </div>
            <div class="col-md-9">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="title" id="titleMr" value="Mr">
                <label class="form-check-label" for="titleMr">Mr.</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="title" id="titleMrs" value="Mrs">
                <label class="form-check-label" for="titleMrs">Mrs.</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="title" id="titleMs" value="Ms">
                <label class="form-check-label" for="titleMs">Ms</label>
              </div>
              <span id="title_error" class="error-text"></span>
            </div>
          </div>

          <!-- Names -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Name <span class="required">*</span></label>
              <input type="text" class="form-control" placeholder="First name" name="first_name" id="first_name">
              <span id="first_name_error" class="error-text"></span>
            </div>
            <div class="col-md-6">
              <label class="form-label">Surname <span class="required">*</span></label>
              <input type="text" class="form-control" placeholder="Surname" name="surname" id="surname">
              <span id="surname_error" class="error-text"></span>
            </div>
          </div>

          <!-- Address -->
          <div class="row mb-3">
            <div class="col-md-3">
              <label class="form-label">Address <span class="required">*</span></label>
            </div>
            <div class="col-md-9">
              <div class="mb-2">
                <input type="text" class="form-control" placeholder="Building Name or Lot" name="address" id="address">
                <span id="address_error" class="error-text"></span>
              </div>
              
              <div class="row mb-2">
                <div class="col-md-6">
                  <input type="text" class="form-control" placeholder="Street Address" name="street" id="street">
                  <span id="street_error" class="error-text"></span>
                </div>
                <div class="col-md-6">
                  <input type="text" class="form-control" placeholder="Suburb" name="suburb" id="suburb">
                  <span id="suburb_error" class="error-text"></span>
                </div>
              </div>
              
              <div class="row align-items-center">
                <div class="col-md-5">
                  <select class="form-select" name="city" id="city">
                    <option value="">Select City</option>
                    <option value="1">Suva</option>
                    <option value="2">Navua</option>
                    <option value="3">Lautoka</option>
                    <option value="4">Ba</option>
                    <option value="5">Sigatoka</option>
                    <option value="6">Labasa</option>
                    <option value="7">Rakiraki</option>
                    <option value="8">Nadi</option>
                    <option value="9">Savusavu</option>
                    <option value="10">Tavua</option>
                  </select>
                  <span id="city_error" class="error-text"></span>
                </div>
                <div class="col-md-2">
                  <label class="form-label">Region <span class="required">*</span></label>
                </div>
                <div class="col-md-5">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="region" id="regionCentral" value="1">
                    <label class="form-check-label" for="regionCentral">Central</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="region" id="regionEastern" value="2">
                    <label class="form-check-label" for="regionEastern">Eastern</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="region" id="regionWestern" value="3">
                    <label class="form-check-label" for="regionWestern">Western</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="region" id="regionNorthern" value="4">
                    <label class="form-check-label" for="regionNorthern">Northern</label>
                  </div>
                  <span id="region_error" class="error-text"></span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Contact -->
        <div class="row mb-3">
          <div class="col-md-3">
            <label class="form-label">Contact <span class="required">*</span></label>
          </div>
          <div class="col-md-9">
            <div class="row mb-2">
              <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Office Phone" maxlength="7" name="office_phone" id="office_phone">
                <span id="office_phone_error" class="error-text"></span>
              </div>
              <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Mobile Contact" maxlength="7" name="mobile_phone" id="mobile_phone">
                <span id="mobile_phone_error" class="error-text"></span>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-2">
                <input type="email" class="form-control" placeholder="Email this account will be used to register with" name="email" id="email">
                <span id="email_error" class="error-text"></span>
              </div>
              <div class="col-md-6">
                <input type="email" class="form-control" placeholder="Alternate email" name="alt_email" id="alt_email">
                <span id="alt_email_error" class="error-text"></span>
              </div>
            </div>
          </div>
        </div>

        <!-- Company Details -->
        <div class="form-section" id="company-details">
          <label class="section-label">Company Details <span class="required">*</span></label>

          <!-- List of Directors -->
          <div class="row mb-3">
            <div class="col-md-3">
              <label class="form-label">List of Directors <span class="required">*</span></label>
            </div>
            <div class="col-md-9">
              <div id="directors-wrapper" class="row g-2 mb-2">
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="Director" name="directors[]" id="director_0"/>
                  <span id="director_0_error" class="error-text"></span>
                </div>
                <div class="col-sm-4">
                  <button type="button" class="btn add-btn w-100" id="addDirector">Add More</button>
                </div>
              </div>
              <span id="directors_error" class="error-text"></span>
            </div>
          </div>

          <!-- Company Registration Number + ROC Upload -->
          <div class="row mb-3">
            <div class="col-md-3">
              <label class="form-label">Enter Company Registration Number <span class="required">*</span></label>
            </div>
            <div class="col-md-5">
              <input type="text" class="form-control" placeholder="Enter Company Registration Number" name="com_reg_num" id="com_reg_num"/>
              <span id="com_reg_num_error" class="error-text"></span>
            </div>
            <div class="col-md-4">
              <input type="file" id="rocFile" class="d-none" name="roc_file">
<label for="rocFile" class="btn upload-btn w-100">Upload ROC Certificate</label>
              <span class="file-name text-muted ms-2" id="rocFile_name"></span>
              <span id="roc_file_error" class="error-text"></span>
            </div>
          </div>

          <!-- Company TIN Number + TIN Letter Upload -->
          <div class="row mb-3">
            <div class="col-md-3">
              <label class="form-label">Enter Company TIN Number <span class="required">*</span></label>
            </div>
            <div class="col-md-5">
              <input type="text" class="form-control" placeholder="Enter Company TIN Number" name="tin_number" id="tin_number"/>
              <span id="tin_number_error" class="error-text"></span>
            </div>
            <div class="col-md-4">
              <input type="file" id="tinLetter" class="d-none" name="tin_letter"/>
<label for="tinLetter" class="btn upload-btn w-100">Upload TIN Letter</label>
              <span class="file-name text-muted ms-2" id="tinLetter_name"></span>
              <span id="tin_letter_error" class="error-text"></span>
            </div>
          </div>

          <!-- Wireman License Number + Upload + (+) -->
          <div id="wireman-licenses">
            <div class="row mb-3 wireman-row">
              <div class="col-md-3">
                <label class="form-label">Wireman License Number <span class="required">*</span></label>
              </div>
              <div class="col-md-5">
                <input type="text" class="form-control" placeholder="Wireman License Number" name="wireman_l_num[]" id="wireman_l_num_0" />
                <span id="wireman_l_num_0_error" class="error-text"></span>
              </div>
              <div class="col-md-3">
                <input type="file" id="wiremanFile0" class="d-none" name="wireman_license[]">
<label for="wiremanFile0" class="btn upload-btn w-100">Upload Wireman's Licence Copy</label>
                <span class="file-name text-muted ms-2" id="wiremanFile0_name"></span>
                <span id="wireman_license_0_error" class="error-text"></span>
              </div>
              <div class="col-md-1 d-grid">
                <button type="button" class="btn btn-outline-secondary" id="addWireman">+</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Submit -->
        <div class="form-actions">
          <button type="submit">Submit to Request for Portal Registration</button>
        </div>
      </div>

      <!-- Individual Form -->
      <div id="Individual">
        <h2 class="form-title">Registration Form - <span>Individual</span></h2>

        <!-- Portal Account Holder -->
        <div class="form-section">
          <label class="section-label">Portal Account Holder <span class="required">*</span></label>

          <!-- Title -->
          <div class="row mb-3">
            <div class="col-md-3">
              <label class="form-label">Title <span class="required">*</span></label>
            </div>
            <div class="col-md-9">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="title_ind" id="titleMrInd" value="Mr">
                <label class="form-check-label" for="titleMrInd">Mr.</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="title_ind" id="titleMrsInd" value="Mrs">
                <label class="form-check-label" for="titleMrsInd">Mrs.</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="title_ind" id="titleMsInd" value="Ms">
                <label class="form-check-label" for="titleMsInd">Ms</label>
              </div>
              <span id="title_ind_error" class="error-text"></span>
            </div>
          </div>

          <!-- Names -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Name <span class="required">*</span></label>
              <input type="text" class="form-control" placeholder="First name" name="first_name_ind" id="first_name_ind">
              <span id="first_name_ind_error" class="error-text"></span>
            </div>
            <div class="col-md-6">
              <label class="form-label">Surname <span class="required">*</span></label>
              <input type="text" class="form-control" placeholder="Surname" name="surname_ind" id="surname_ind">
              <span id="surname_ind_error" class="error-text"></span>
            </div>
          </div>

          <!-- Address -->
          <div class="row mb-3">
            <div class="col-md-3">
              <label class="form-label">Address <span class="required">*</span></label>
            </div>
            <div class="col-md-9">
              <div class="mb-2">
                <input type="text" class="form-control" placeholder="Building Name or Lot" name="address_ind" id="address_ind">
                <span id="address_ind_error" class="error-text"></span>
              </div>
              
              <div class="row mb-2">
                <div class="col-md-6">
                  <input type="text" class="form-control" placeholder="Street Address" name="street_ind" id="street_ind">
                  <span id="street_ind_error" class="error-text"></span>
                </div>
                <div class="col-md-6">
                  <input type="text" class="form-control" placeholder="Suburb" name="suburb_ind" id="suburb_ind">
                  <span id="suburb_ind_error" class="error-text"></span>
                </div>
              </div>
              
              <div class="row align-items-center">
                <div class="col-md-5">
                  <select class="form-select" name="city_ind" id="city_ind">
                    <option value="">Select City</option>
                    <option value="1">Suva</option>
                    <option value="2">Navua</option>
                    <option value="3">Lautoka</option>
                    <option value="4">Ba</option>
                    <option value="5">Sigatoka</option>
                    <option value="6">Labasa</option>
                    <option value="7">Rakiraki</option>
                    <option value="8">Nadi</option>
                    <option value="9">Savusavu</option>
                    <option value="10">Tavua</option>
                  </select>
                  <span id="city_ind_error" class="error-text"></span>
                </div>
                <div class="col-md-2">
                  <label class="form-label">Region <span class="required">*</span></label>
                </div>
                <div class="col-md-5">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="region_ind" id="regionCentralInd" value="1">
                    <label class="form-check-label" for="regionCentralInd">Central</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="region_ind" id="regionEasternInd" value="2">
                    <label class="form-check-label" for="regionEasternInd">Eastern</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="region_ind" id="regionWesternInd" value="3">
                    <label class="form-check-label" for="regionWesternInd">Western</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="region_ind" id="regionNorthernInd" value="4">
                    <label class="form-check-label" for="regionNorthernInd">Northern</label>
                  </div>
                  <span id="region_ind_error" class="error-text"></span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Contact -->
        <div class="row mb-3">
          <div class="col-md-3">
            <label class="form-label">Contact <span class="required">*</span></label>
          </div>
          <div class="col-md-9">
            <div class="row mb-2">
              <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Office Phone" maxlength="7" name="office_phone_ind" id="office_phone_ind">
                <span id="office_phone_ind_error" class="error-text"></span>
              </div>
              <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Mobile Contact" maxlength="7" name="mobile_phone_ind" id="mobile_phone_ind">
                <span id="mobile_phone_ind_error" class="error-text"></span>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-2">
                <input type="email" class="form-control" placeholder="Email this account will be used to register with" id="individualEmail" name="individualEmail">
                <span id="email_ind_error" class="error-text"></span>
              </div>
              <div class="col-md-6">
                <input type="email" class="form-control" placeholder="Alternate email" name="alt_email_ind" id="alt_email_ind">
                <span id="alt_email_ind_error" class="error-text"></span>
              </div>
            </div>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-3">
            <label class="form-label">Additional Information <span class="required">*</span></label>
          </div>
          <div class="col-md-9">
            <div class="row mb-3">
              <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Enter TIN number" name="tin_number_ind" id="tin_number_ind">
                <span id="tin_number_ind_error" class="error-text"></span>
              </div>
              <div class="col-md-6">
                <input type="file" id="tinFileInd" class="d-none" name="tin_letter_ind">
<label for="tinFileInd" class="btn upload-btn w-100">Upload TIN Letter</label>
                <span class="file-name text-muted ms-2" id="tinFileInd_name"></span>
                <span id="tin_letter_ind_error" class="error-text"></span>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Wireman License Number" name="wireman_l_num_ind" id="wireman_l_num_ind">
                <span id="wireman_l_num_ind_error" class="error-text"></span>
              </div>
              <div class="col-md-6">
                <input type="file" id="wiremanFileInd" class="d-none" name="wireman_license_ind">
<label for="wiremanFileInd" class="btn upload-btn w-100">Upload Wireman's Licence Copy</label>
                <span class="file-name text-muted ms-2" id="wiremanFileInd_name"></span>
                <span id="wireman_license_ind_error" class="error-text"></span>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Submit -->
        <div class="form-actions">
          <button type="submit">Submit to Request for Portal Registration</button>
        </div>
      </div>
    </div>
  </form>

  <!-- Bootstrap 5 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
function displaySelectedFileName(input) {
      const display = document.getElementById(input.id + "_name");
      if (display) {
        display.textContent = (input.files && input.files.length > 0) ? input.files[0].name : "";
      }
    }

    document.addEventListener("DOMContentLoaded", function() {
      const individualForm = document.getElementById("Individual");
      const companyForm = document.getElementById("Company");
      const radios = document.querySelectorAll("input[name='account_type']");

      function toggleForms() {
        if (document.querySelector("input[name='account_type']:checked").value === "individual") {
          individualForm.classList.remove("hidden");
          companyForm.classList.add("hidden");
          
          // Remove required attributes from company fields
          document.querySelectorAll("#Company input, #Company select").forEach(field => {
            field.removeAttribute("required");
          });
          
          // Add required attributes to individual fields
          document.querySelectorAll("#Individual input, #Individual select").forEach(field => {
            if (field.type !== 'radio' && field.type !== 'checkbox' && field.type !== 'file') {
              field.setAttribute("required", "required");
            }
          });
          
          // Handle radio buttons
          document.querySelectorAll("#Individual input[type='radio']").forEach(radio => {
            radio.setAttribute("required", "required");
          });
          
          // Handle file inputs
          document.querySelectorAll("#Individual input[type='file']").forEach(file => {
            file.setAttribute("required", "required");
          });
        } else {
          individualForm.classList.add("hidden");
          companyForm.classList.remove("hidden");
          
          // Remove required attributes from individual fields
          document.querySelectorAll("#Individual input, #Individual select").forEach(field => {
            field.removeAttribute("required");
          });
          
          // Add required attributes to company fields
          document.querySelectorAll("#Company input, #Company select").forEach(field => {
            if (field.type !== 'radio' && field.type !== 'checkbox' && field.type !== 'file') {
              field.setAttribute("required", "required");
            }
          });
          
          // Handle radio buttons
          document.querySelectorAll("#Company input[type='radio']").forEach(radio => {
            radio.setAttribute("required", "required");
          });
          
          // Handle file inputs
          document.querySelectorAll("#Company input[type='file']").forEach(file => {
            file.setAttribute("required", "required");
          });
        }
      }

      // Add event listeners to all radio buttons
      radios.forEach(radio => {
        radio.addEventListener("change", toggleForms);
      });
      
      // Initialize the form state
      toggleForms();
      
      // Add validation for all inputs
      Array.from(document.getElementById("FCCCRegistrationForm").elements).forEach(element => {
        if (element.tagName === "INPUT" || element.tagName === "SELECT") {
          if (element.type !== "file" && element.type !== "radio" && element.type !== "checkbox") {
            element.addEventListener("input", () => validateField(element));
            element.addEventListener("blur", () => validateField(element));
          }
        }
      });

      // Show selected file names and validate on change
      document.querySelectorAll('input[type="file"]').forEach(fileInput => {
        fileInput.addEventListener('change', function() {
          displaySelectedFileName(fileInput);
          validateField(fileInput);
        });
      });

      // Server-side rehydration of form state on backend validation errors:
      const serverHasErrors = @json($errors->any());
      const serverOld = @json(old());
      const serverErrorBag = @json($errors->toArray());

      if (serverHasErrors) {
        // Restore account type selection and toggle forms
        if (serverOld && serverOld.account_type) {
          const acctRadio = document.querySelector(`input[name="account_type"][value="${serverOld.account_type}"]`);
          if (acctRadio) {
            acctRadio.checked = true;
          }
          toggleForms();
        }

        function hasErrorFor(name) {
          if (!serverErrorBag) return false;
          if (serverErrorBag[name]) return true;
          // for arrays and dot notations
          const keys = Object.keys(serverErrorBag);
          return keys.some(k => k === name || k.startsWith(name + '.'));
        }

        // Rehydrate simple inputs/selects (non-file, non-array)
        document.querySelectorAll('#FCCCRegistrationForm input[name]:not([type="file"]), #FCCCRegistrationForm select[name]').forEach(el => {
          const name = el.name;
          // Skip arrays here; handled later
          if (name.endsWith('[]')) return;

          if (el.type === 'radio' || el.type === 'checkbox') {
            if (hasErrorFor(name)) {
              document.querySelectorAll(`input[name="${name}"]`).forEach(g => g.checked = false);
            } else {
              const val = serverOld ? serverOld[name] : undefined;
              if (val !== undefined && el.value == val) {
                el.checked = true;
              }
            }
            return;
          }

          if (hasErrorFor(name)) {
            el.value = '';
          } else {
            const val = serverOld ? serverOld[name] : undefined;
            if (val !== undefined) {
              el.value = val;
            }
          }
        });

        // Helper to rebuild array inputs using existing "Add" buttons
        function rebuildArrayInputs(baseName, containerSelector, addButtonSelector) {
          const values = (serverOld && serverOld[baseName]) ? serverOld[baseName] : [];
          const container = document.querySelector(containerSelector);
          if (!container) return;
          const addBtn = document.querySelector(addButtonSelector);

          // Ensure there are enough rows
          const existing = container.querySelectorAll(`input[name="${baseName}[]"]`).length;
          const target = Math.max(values.length, 1);
          for (let i = existing; i < target; i++) {
            if (addBtn) addBtn.click();
          }

          // Fill/clear each row based on errors
          const inputs = container.querySelectorAll(`input[name="${baseName}[]"]`);
          inputs.forEach((inp, index) => {
            const dotKey = `${baseName}.${index}`;
            if (serverErrorBag && serverErrorBag[dotKey]) {
              inp.value = '';
            } else if (values[index] !== undefined) {
              inp.value = values[index];
            }
          });
        }

        // Company arrays
        rebuildArrayInputs('directors', '#directors-wrapper', '#addDirector');
        rebuildArrayInputs('wireman_l_num', '#wireman-licenses', '#addWireman');

        // Note: file inputs cannot be repopulated by the browser for security reasons; leaving them blank is expected.
      }
    });

    function validateField(input) {
      let value = input.value;
      if (input.type === 'file') {
        value = input.files.length > 0 ? input.files[0].name : '';
      }
      
      const errorSpan = document.getElementById(input.id + "_error");
      const urlRegex = /(https?:\/\/[^\s]+)/; 
      const script = /<script.*?>.*?<\/script>/; 
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      const numbers = /\d/;
      const forbiddenChars = /[<>\/?]/;  
      const imageExtensions = /\.(jpg|jpeg|png|gif|bmp|webp|tiff)$/i;

      let error = "";

      // Handle both company and individual field names
      const fieldType = input.name.replace("_ind", "");
      
      // Check if field is required
      const isRequired = input.hasAttribute('required');
      
      if (isRequired && !value) {
        error = "This field is required.";
      } else if (value) {
        switch (fieldType) {
          case "organization_name":
          case "first_name":
          case "surname":
          case "directors[]":
            if (numbers.test(value)) {
              error = "Numbers are not allowed.";
            } else if (urlRegex.test(value)) {
              error = "Links are not allowed.";
            } else if (forbiddenChars.test(value)) {
              error = "Special characters are not allowed.";
            }
            break;

          case "email":
          case "individualEmail":
          case "alt_email":
            if (!emailPattern.test(value)) {
              error = "Invalid email address.";
            }
            break;

          case "office_phone":
          case "mobile_phone":
          case "office_phone_ind":
          case "mobile_phone_ind":
            if (!/^\d{7}$/.test(value)) {
              error = "Phone number must be exactly 7 digits.";
            }
            break;

          case "tin_number":
          case "com_reg_num":
          case "tin_number_ind":
            if (!/^\d{9}$/.test(value)) {
              error = "This number must be exactly 9 digits.";
            } else if (urlRegex.test(value)) {
              error = "Links are not allowed.";
            } else if (forbiddenChars.test(value)) {
              error = "Special characters are not allowed.";
            }
            break;

          case "wireman_l_num[]":
          case "wireman_l_num_ind":
          case "designation_business":
            if (!/^[a-zA-Z0-9]+$/.test(value)) {
              error = "Must be alphanumeric.";
            } else if (urlRegex.test(value)) {
              error = "Links are not allowed.";
            } else if (forbiddenChars.test(value)) {
              error = "Special characters are not allowed.";
            }
            break;

         
          case "address":
          case "street":
          case "suburb":
          case "address_ind":
          case "street_ind":
          case "suburb_ind":
            if (script.test(value)) {
              error = "Script tags are not allowed.";
            } else if (forbiddenChars.test(value)) {
              error = "Special characters are not allowed.";
            } else if (urlRegex.test(value)) {
              error = "Links are not allowed.";
            }
            break;
            
          case "roc_file":
          case "tin_letter":
          case "wireman_license[]":
          case "tin_letter_ind":
          case "wireman_license_ind":
            if (input.files.length === 0 && input.hasAttribute('required')) {
              error = "This file upload is required.";
            } else if (input.files.length > 0 && imageExtensions.test(input.files[0].name)) {
              error = "Image files are not allowed for this document.";
            }
            break;
            
          case "organization_type":
          case "title":
          case "region":
          case "city":
          case "title_ind":
          case "region_ind":
          case "city_ind":
            // Radio buttons and select validation
            if (input.hasAttribute('required') && !value) {
              error = "This selection is required.";
            }
            break;

          default:
            break;
        }
      }

      if (errorSpan) {
        errorSpan.textContent = error;
        if (error) {
          input.classList.add("is-invalid");
        } else {
          input.classList.remove("is-invalid");
        }
      }
    }

    function validateForm() {
      let hasErrors = false;
      const form = document.getElementById("FCCCRegistrationForm");
      
      // Validate all fields
      Array.from(form.elements).forEach(element => {
        if (element.tagName === "INPUT" || element.tagName === "SELECT") {
          validateField(element);
          const errorSpan = document.getElementById(element.id + "_error");
          if (errorSpan && errorSpan.textContent !== "") {
            hasErrors = true;
          }
        }
      });

      if (hasErrors) {
        // Show error message
        showMessage("Please fix the errors in the form before submitting.", "error");
        return false;
      }
      
      // Allow submission to the server
      return true;
    }

    function showMessage(message, type) {
      const messageContainer = document.getElementById("message-container");
      messageContainer.innerHTML = `
        <div class="alert alert-${type === 'error' ? 'danger' : 'success'}">
          ${message}
        </div>
      `;
      
      // Scroll to the message
      messageContainer.scrollIntoView({ behavior: 'smooth' });
    }

    // --- Add more Directors ---
    const addDirectorBtn = document.getElementById('addDirector');
    if (addDirectorBtn) {
      addDirectorBtn.addEventListener('click', function () {
        const wrapper = document.getElementById('directors-wrapper');
        const row = document.createElement('div');
        row.className = 'col-12 d-flex align-items-center mt-2 director-row';
        const newId = 'director_' + Date.now();
        row.innerHTML = `
          <input type="text" class="form-control me-2" placeholder="Director" name="directors[]" id="${newId}"/>
          <span id="${newId}_error" class="error-text"></span>
          <button type="button" class="btn btn-outline-danger remove-director">–</button>
        `;
        wrapper.appendChild(row);

        const newInput = row.querySelector("input");
        if (document.querySelector("input[name='account_type']:checked").value === "company") {
          newInput.setAttribute("required", "required");
        }
        newInput.addEventListener("input", () => validateField(newInput));
        newInput.addEventListener("blur", () => validateField(newInput));
      });
    }

    // Remove director row
    document.addEventListener('click', function (e) {
      if (e.target && e.target.classList.contains('remove-director')) {
        const row = e.target.closest('.director-row');
        if (row) row.remove();
      }
    });

    // --- Add more Wireman License rows ---
    let wiremanIdx = 1;
    const addWiremanBtn = document.getElementById('addWireman');
    if (addWiremanBtn) {
      addWiremanBtn.addEventListener('click', function () {
        const container = document.getElementById('wireman-licenses');
        const row = document.createElement('div');
        row.className = 'row mb-3 wireman-row';
        const id = 'wiremanFile' + wiremanIdx;
        const fieldId = 'wireman_l_num_' + Date.now();
        row.innerHTML = `
          <div class="col-md-3">
            <label class="form-label">Wireman License Number <span class="required">*</span></label>
          </div>
          <div class="col-md-5">
            <input type="text" class="form-control" placeholder="Wireman License Number" name="wireman_l_num[]" id="${fieldId}"/>
            <span id="${fieldId}_error" class="error-text"></span>
          </div>
          <div class="col-md-3">
            <input type="file" id="${id}" class="d-none" name="wireman_license[]">
<label for="${id}" class="btn upload-btn w-100">Upload Wireman's Licence Copy</label>
            <span class="file-name text-muted ms-2" id="${id}_name"></span>
            <span id="${id}_error" class="error-text"></span>
          </div>
          <div class="col-md-1 d-grid">
            <button type="button" class="btn btn-outline-danger remove-wireman">–</button>
          </div>
        `;
        container.appendChild(row);
        wiremanIdx++;

        const newInput = row.querySelector("input[type='text']");
        const newFileInput = row.querySelector("input[type='file']");
        
        if (document.querySelector("input[name='account_type']:checked").value === "company") {
          newInput.setAttribute("required", "required");
          newFileInput.setAttribute("required", "required");
        }
        
        newInput.addEventListener("input", () => validateField(newInput));
        newInput.addEventListener("blur", () => validateField(newInput));
        
        newFileInput.addEventListener('change', function() {
          displaySelectedFileName(newFileInput);
          validateField(newFileInput);
        });
      });
    }

    // Remove wireman row
    document.addEventListener('click', function (e) {
      if (e.target && e.target.classList.contains('remove-wireman')) {
        const row = e.target.closest('.wireman-row');
        if (row) row.remove();
      }
    });
  // Success modal: auto-show and OK button handling
  document.addEventListener('DOMContentLoaded', function () {
    @if(session('success'))
      const successModalEl = document.getElementById('successModal');
      if (successModalEl) {
        const successModal = new bootstrap.Modal(successModalEl);
        successModal.show();
      }
    @endif

    const okBtn = document.getElementById('successOkBtn');
    if (okBtn) {
      okBtn.addEventListener('click', function () {
        const form = document.getElementById('FCCCRegistrationForm');
        if (form) {
          form.reset();
        }
        // Clear validation UI and file name displays
        document.querySelectorAll('.error-text').forEach(e => e.textContent = '');
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.file-name').forEach(el => { el.textContent = ''; });
        // Re-apply required attributes based on default radio selection
        const checked = document.querySelector("input[name='account_type']:checked");
        if (checked) {
          checked.dispatchEvent(new Event('change'));
        }
      });
    }
  });
  </script>
</body>
</html>
