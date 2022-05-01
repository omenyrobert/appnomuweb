


<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
  <meta charset="utf-8" />
  <link rel="icon" type="image/png" href="/assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    {{ env('APP_NAME','APPNOMU SAVINGS & LOANS') }} || {{ $page}}
  </title>
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  <!-- Extra details for Live View on GitHub Pages -->
  <!-- Canonical SEO -->
  <link rel="canonical" href="#" />
  <!--  Social tags      -->
  <meta name="keywords" content="APPNOMU BUSINESS SERVICES, SACCO, FINANCIAL MANAGEMENT">
  <meta name="description" content="This is a an online Savings and Loans System">
  <!-- Schema.org markup for Google+ -->
  <meta itemprop="name" content="APPNOMU">
  <meta itemprop="description" content="This is a an online Savings and Loans System">
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="/assets/demo/demo.css" />
  <link rel="stylesheet" type="text/css" href="/assets/css/icons.css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="/maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="/assets/css/material-dashboard.min6c54.css?v=2.2.2" rel="stylesheet" />
  <!-- <link href="/assets/demo/demo.css" rel="stylesheet" /> -->
  <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
  
  <!-- <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" /> -->
  <!-- Google Tag Manager -->
  <!-- End Google Tag Manager -->
</head>

<body class="">

  <div class="wrapper ">
    <div class="sidebar" data-color="rose" data-background-color="black" data-image="assets/img/sidebar-1.jpg">
      <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
      <div class="logo"><a href="#" class="simple-text logo-mini">
          CT
        </a>
        <a href="#" class="simple-text logo-normal">
          {{ env('APP_NAME','APPNOMU SAVINGS & LOANS') }}
        </a></div>
      <div class="sidebar-wrapper">
        <div class="user">
          <div class="photo">
            <img src="{{session('dashboard')['user-identification']['passport'] ?? '../../uploads/faces/img.png'}}" />
          </div>
          <div class="user-info">
            <a data-toggle="collapse" href="#collapseExample" class="username">
             
              <span>
                {{$user->name}}</br>
                {{$user->user_id }}
                <b class="caret"></b>
              </span>
            </a>
            <div class="collapse" id="collapseExample">
              <ul class="nav">
                <li class="nav-item">
                  <a class="nav-link" href="/profile">
                    <span class="sidebar-mini"> MP </span>
                    <span class="sidebar-normal"> My Profile </span>
                  </a>
                </li>
                <!-- <li class="nav-item">
                  <a class="nav-link" href="/my-settings">
                    <span class="sidebar-mini"> S </span>
                    <span class="sidebar-normal"> Settings </span>
                  </a>
                </li> -->
              </ul>
            </div>
          </div>
        </div>
        <ul class="nav">
          <li class="nav-item active ">
            <a class="nav-link" href="{{route('dashboard')}}">
              <i class="material-icons">dashboard</i>
              <p> Dashboard </p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#pagesExamples">
              <i class="material-icons">savings</i>
              <p> Savings
                <b class="caret"></b>
              </p>
            </a>
            <div class="collapse" id="pagesExamples">
              <ul class="nav">
                
                  @if($user->role != 'admin') 
                  
                
                <li class="nav-item ">
                  <a class="nav-link" href="/savings">
                    <span class="sidebar-mini"> AS </span>
                    <span class="sidebar-normal"> My Savings </span>
                  </a>
                </li>
                @else
                <li class="nav-item ">
                  <a class="nav-link" href="/allSavings">
                    <span class="sidebar-mini"> AS </span>
                    <span class="sidebar-normal"> All Savings </span>
                  </a>
                </li>
                <li class="nav-item ">
                  <a class="nav-link" href="/saving-categories">
                    <span class="sidebar-mini"> SC </span>
                    <span class="sidebar-normal"> Savings Categories </span>
                  </a>
                </li>
                <li class="nav-item ">
                  <a class="nav-link" href="/sub-categories">
                    <span class="sidebar-mini"> SSC </span>
                    <span class="sidebar-normal"> Sub Categories </span>
                  </a>
                </li>
                @endif
              </ul>
            </div>
          </li>
          <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#componentsExamples">
              <i class="material-icons">payments</i>
              <p> Withdraws
                <b class="caret"></b>
              </p>
            </a>
            <div class="collapse" id="componentsExamples">
              <ul class="nav">
                @if($user->role != 'admin')
                <li class="nav-item ">
                  <a class="nav-link" href="/withdraws">
                    <span class="sidebar-mini"> AW </span>
                    <span class="sidebar-normal"> Withdraw  </span>
                  </a>
                </li>
                
                 @else
                <li class="nav-item ">
                  <a class="nav-link" href="/all-withdraws">
                    <span class="sidebar-mini"> SW </span>
                    <span class="sidebar-normal">All Withdraws  </span>
                  </a>
                </li>
               @endif
                <!-- <li class="nav-item ">
                  <a class="nav-link" href="/withdraw">
                    <span class="sidebar-mini"> WC </span>
                    <span class="sidebar-normal"> Withdraw Cash </span>
                  </a>
                </li> -->
              </ul>
            </div>
          </li>
          <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#formsExamples">
              <i class="material-icons">credit_score</i>
              <p> Loans
                <b class="caret"></b>
              </p>
            </a>
            <div class="collapse" id="formsExamples">
              <ul class="nav">
                @if($user->role != 'admin')
                <li class="nav-item ">
                  <a class="nav-link" href="/loans">
                    <span class="sidebar-mini"> ML </span>
                    <span class="sidebar-normal"> My Loans </span>
                  </a>
                </li>
                @endif
                <li class="nav-item ">
                  <a class="nav-link" href="{{route('soma.dashboard')}}">
                    <span class="sidebar-mini"> ML </span>
                    <span class="sidebar-normal"> Soma Loans </span>
                  </a>
                </li>
                @if($user->role != 'admin')
                <li class="nav-item ">
                  <a class="nav-link" href="/loan-installments">
                    <span class="sidebar-mini"> ML </span>
                    <span class="sidebar-normal"> My Loans Installements </span>
                  </a>
                </li>
              @else
                <li class="nav-item ">
                  <a class="nav-link" href="/loan-chart">
                    <span class="sidebar-mini"> RL </span>
                    <span class="sidebar-normal"> Loan Chart</span>
                  </a>
                </li>
                <li class="nav-item ">
                  <a class="nav-link" href="/user-loans">
                    <span class="sidebar-mini"> UL </span>
                    <span class="sidebar-normal"> User Loans</span>
                  </a>
                </li>
                @endif
              </ul>
            </div>
          </li>
          @if($user->role != 'admin')
          <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#reffer">
              <i class="material-icons">group_add</i>
              <p> Refferals
                <b class="caret"></b>
              </p>
            </a>
            <div class="collapse" id="reffer">
              <ul class="nav">
                <li class="nav-item ">
                  <a class="nav-link" href="/my-refferals">
                    <span class="sidebar-mini"> MR </span>
                    <span class="sidebar-normal"> My Refferals </span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
          <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#alias">
              <i class="material-icons">group_add</i>
              <p> Alliances
                <b class="caret"></b>
              </p>
            </a>
            <div class="collapse" id="alias">
              <ul class="nav">
                <li class="nav-item ">
                  <a class="nav-link" href="/alliases">
                    <span class="sidebar-mini"> MR </span>
                    <span class="sidebar-normal"> Add Alliance/GUARATOR </span>
                  </a>
                </li>
                <li class="nav-item ">
                  <a class="nav-link" href="/my-alliances">
                    <span class="sidebar-mini"> MR </span>
                    <span class="sidebar-normal">  GUARANTOR  List</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
         @else
          <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#users">
              <i class="material-icons">people</i>
              <p> Users
                <b class="caret"></b>
              </p>
            </a>
            <div class="collapse" id="users">
              <ul class="nav">
                <li class="nav-item ">
                  <a class="nav-link" href="/users">
                    <span class="sidebar-mini"> MR </span>
                    <span class="sidebar-normal"> All Users </span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
          @endif
          <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#knowledge">
              <i class="material-icons">assignment</i>
              <p> knowledge Base
                <b class="caret"></b>
              </p>
            </a>
            <div class="collapse" id="knowledge">
              <ul class="nav">
                <li class="nav-item ">
                  <a class="nav-link" href="/knowledge-base">
                    <span class="sidebar-mini"> KBP </span>
                    <span class="sidebar-normal"> knowledge Base Preview </span>
                  </a>
                </li>
                <li class="nav-item ">
                  <a class="nav-link" href="/make-article">
                    <span class="sidebar-mini"> MA </span>
                    <span class="sidebar-normal"> Make Article </span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
          <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#sms">
              <i class="material-icons">chat</i>
              <p> Messaging
                <b class="caret"></b>
              </p>
            </a>
            <div class="collapse" id="sms">
              <ul class="nav">
                <li class="nav-item ">
                  <a class="nav-link" href="/allSms">
                    <span class="sidebar-mini"> MR </span>
                    <span class="sidebar-normal"> All Sent Sms </span>
                  </a>
                </li>
                <li class="nav-item ">
                  <a class="nav-link" href="/sendSms">
                    <span class="sidebar-mini"> BS </span>
                    <span class="sidebar-normal"> Send Bulk SMS </span>
                  </a>
                </li>
                <li class="nav-item ">
                  <a class="nav-link" href="/messages-contact">
                    <span class="sidebar-mini"> CFMs  </span>
                    <span class="sidebar-normal"> Contact US Messages </span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
          <!-- <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#sets">
              <i class="material-icons">settings_suggest</i>
              <p> System Settings
                <b class="caret"></b>
              </p>
            </a>
            <div class="collapse" id="sets">
              <ul class="nav">
                <li class="nav-item ">
                  <a class="nav-link" href="/general">
                    <span class="sidebar-mini"> GS </span>
                    <span class="sidebar-normal"> General Settings </span>
                  </a>
                </li>
                <li class="nav-item ">
                  <a class="nav-link" href="/api-settings">
                    <span class="sidebar-mini"> APS </span>
                    <span class="sidebar-normal">API Settings</span>
                  </a>
                </li>
                <li class="nav-item ">
                  <a class="nav-link" href="/loan-settings">
                    <span class="sidebar-mini"> LS </span>
                    <span class="sidebar-normal"> Loan Settings </span>
                  </a>
                </li>
                <li class="nav-item ">
                  <a class="nav-link" href="/loan-settings">
                    <span class="sidebar-mini"> ES </span>
                    <span class="sidebar-normal"> Email Settings </span>
                  </a>
                </li>
                <li class="nav-item ">
                  <a class="nav-link" href="/loan-settings">
                    <span class="sidebar-mini"> SMS </span>
                    <span class="sidebar-normal"> SMS Settings </span>
                  </a>
                </li>
                <li class="nav-item ">
                  <a class="nav-link" href="/loan-settings">
                    <span class="sidebar-mini"> PS </span>
                    <span class="sidebar-normal"> Payment Settings </span>
                  </a>
                </li>
              </ul>
            </div>
          </li> -->

        
        </ul>
      </div>
      <div class="sidebar-background"></div>
    </div>
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <div class="navbar-minimize">
              <button id="minimizeSidebar" class="btn btn-just-icon btn-white btn-fab btn-round">
                <i class="material-icons text_align-center visible-on-sidebar-regular">more_vert</i>
                <i class="material-icons design_bullet-list-67 visible-on-sidebar-mini">view_list</i>
              </button>
            </div>
            <a class="navbar-brand" href="javascript:;">{{$page}}</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end">
            <form class="navbar-form">
              <div class="input-group no-border">
                <input type="text" value="" class="form-control" placeholder="Search...">
                <button type="submit" class="btn btn-white btn-round btn-just-icon">
                  <i class="material-icons">search</i>
                  <div class="ripple-container"></div>
                </button>
              </div>
            </form>
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="/">
                  <i class="material-icons">dashboard</i>
                  <p class="d-lg-none d-md-block">
                    Stats
                  </p>
                </a>
              </li>
              <!-- <li class="nav-item dropdown">
                <a class="nav-link" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="material-icons">notifications</i>
                  <span class="notification">5</span>
                  <p class="d-lg-none d-md-block">
                    Some Actions
                  </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                  <a class="dropdown-item" href="#">Mike John responded to your email</a>
                  <a class="dropdown-item" href="#">You have 5 new tasks</a>
                  <a class="dropdown-item" href="#">You're now friend with Andrew</a>
                  <a class="dropdown-item" href="#">Another Notification</a>
                  <a class="dropdown-item" href="#">Another One</a>
                </div>
              </li> -->
              <li class="nav-item dropdown">
                <a class="nav-link" href="javascript:;" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="material-icons">person</i>
                  <p class="d-lg-none d-md-block">
                    Account
                  </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                  <a class="dropdown-item" href="/profile">Profile</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="/logout">Log out</a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <div class="content">
        <div class="container-fluid">
      <!-- End Navbar -->
      @if ($errors->any())
          <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
      @endif
      @if (session('Success'))
          <div class="alert alert-success">
              <ul>
                  <li>{!! session('Success') !!}</li>
              </ul>
          </div>
      @endif
            <h4>
        <marquee>Dear Valued Client, Please note these;
         , All new clients start with a loan of UGX20,000, Please upload your national ID both sides,Upload selfie/passport photos, Add alliances before requesting for a loan, Repay your loan on time to qualify for bigger loans. We are open for support from Monday to Friday 8.30 AM to 5.30 PM, Email help@appnou.net, Whatsapp +256775383963, join our telegram channel for news and updates, self guides >> blog.appnomu.com.Thank You for choosing us !</marquee>
      </h4>
      @yield('content')
     
    </div>
  </div>
  <div class="fixed-plugin">
    <div class="dropdown show-dropdown">
      <a href="#" data-toggle="dropdown">
        <i class="fa fa-cog fa-2x"> </i>
      </a>
      <ul class="dropdown-menu">
        <li class="header-title"> Sidebar Filters</li>
        <li class="adjustments-line">
          <a href="javascript:void(0)" class="switch-trigger active-color">
            <div class="badge-colors ml-auto mr-auto">
              <span class="badge filter badge-purple" data-color="purple"></span>
              <span class="badge filter badge-azure" data-color="azure"></span>
              <span class="badge filter badge-green" data-color="green"></span>
              <span class="badge filter badge-warning" data-color="orange"></span>
              <span class="badge filter badge-danger" data-color="danger"></span>
              <span class="badge filter badge-rose active" data-color="rose"></span>
            </div>
            <div class="clearfix"></div>
          </a>
        </li>
        <li class="header-title">Sidebar Background</li>
        <li class="adjustments-line">
          <a href="javascript:void(0)" class="switch-trigger background-color">
            <div class="ml-auto mr-auto">
              <span class="badge filter badge-black active" data-background-color="black"></span>
              <span class="badge filter badge-white" data-background-color="white"></span>
              <span class="badge filter badge-red" data-background-color="red"></span>
            </div>
            <div class="clearfix"></div>
          </a>
        </li>
        <li class="adjustments-line">
          <a href="javascript:void(0)" class="switch-trigger">
            <p>Sidebar Mini</p>
            <label class="ml-auto">
              <div class="togglebutton switch-sidebar-mini">
                <label>
                  <input type="checkbox">
                  <span class="toggle"></span>
                </label>
              </div>
            </label>
            <div class="clearfix"></div>
          </a>
        </li>
        <li class="adjustments-line">
          <a href="javascript:void(0)" class="switch-trigger">
            <p>Sidebar Images</p>
            <label class="switch-mini ml-auto">
              <div class="togglebutton switch-sidebar-image">
                <label>
                  <input type="checkbox" checked="">
                  <span class="toggle"></span>
                </label>
              </div>
            </label>
            <div class="clearfix"></div>
          </a>
        </li>
        <li class="header-title">Images</li>
        <li class="active">
          <a class="img-holder switch-trigger" href="javascript:void(0)">
            <img src="/assets/img/sidebar-1.jpg" alt="">
          </a>
        </li>
        <li>
          <a class="img-holder switch-trigger" href="javascript:void(0)">
            <img src="/assets/img/sidebar-2.jpg" alt="">
          </a>
        </li>
        <li>
          <a class="img-holder switch-trigger" href="javascript:void(0)">
            <img src="/assets/img/sidebar-3.jpg" alt="">
          </a>
        </li>
        <li>
          <a class="img-holder switch-trigger" href="javascript:void(0)">
            <img src="/assets/img/sidebar-4.jpg" alt="">
          </a>
        </li>
      </ul>
    </div>
  </div>
  
<script src="//code.tidio.co/yww8gmcwm9pkc71l3jmlcahw1oqk0boa.js" async></script>
  <!--   Core JS Files   -->
  <script src="assets/js/core/jquery.min.js"></script>
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap-material-design.min.js"></script>
  <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="assets/js/plugins/moment.min.js"></script>
  <script src="assets/js/plugins/sweetalert2.js"></script>
  <script src="assets/js/plugins/jquery.validate.min.js"></script>
  <script src="assets/js/plugins/jquery.bootstrap-wizard.js"></script>
  <script src="assets/js/plugins/bootstrap-selectpicker.js"></script>
  <script src="assets/js/plugins/bootstrap-datetimepicker.min.js"></script>
  <script src="assets/js/plugins/jquery.dataTables.min.js"></script>
  
  <script src="assets/js/plugins/bootstrap-tagsinput.js"></script>
  <script src="assets/js/plugins/jasny-bootstrap.min.js"></script>
  <script src="assets/js/plugins/fullcalendar.min.js"></script>
  <script src="assets/js/plugins/jquery-jvectormap.js"></script>
  <script src="assets/js/plugins/nouislider.min.js"></script>
  <script src="cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
  <script src="assets/js/plugins/arrive.min.js"></script>
  <script async defer src="buttons.github.io/buttons.js"></script>
  <script>
    $(document).ready(function() {
      $('#datatables').DataTable({
        "pagingType": "full_numbers",
        "lengthMenu": [
          [10, 25, 50, -1],
          [10, 25, 50, "All"]
        ],
        responsive: true,
        language: {
        //   search: "INPUT",
          searchPlaceholder: "Search records",
        }
      });

      var table = $('#datatables').DataTable();

      // Edit record

      table.on('click', '.edit', function() {
        $tr = $(this).closest('tr');

        if ($($tr).hasClass('child')) {
          $tr = $tr.prev('.parent');
        }

        var data = table.row($tr).data();
        alert('You press on Row: ' + data[0] + ' ' + data[1] + ' ' + data[2] + '\'s row.');
      });

      // Delete a record

      table.on('click', '.remove', function(e) {
        $tr = $(this).closest('tr');

        if ($($tr).hasClass('child')) {
          $tr = $tr.prev('.parent');
        }

        table.row($tr).remove().draw();
        e.preventDefault();
      });

      //Like record

      table.on('click', '.like', function() {
        alert('You clicked on Like button');
      });
    });
  </script>
  <!-- Chartist JS -->
  <script src="assets/js/plugins/chartist.min.js"></script>
  <!--  Notifications Plugin    -->
  <script src="assets/js/plugins/bootstrap-notify.js"></script>
  <script src="assets/js/material-dashboard.min6c54.js?v=2.2.2" type="text/javascript"></script>
  <script>
    $(document).ready(function() {
      $().ready(function() {
        $sidebar = $('.sidebar');

        $sidebar_img_container = $sidebar.find('.sidebar-background');

        $full_page = $('.full-page');

        $sidebar_responsive = $('body > .navbar-collapse');

        window_width = $(window).width();

        fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();

        if (window_width > 767 && fixed_plugin_open == 'Dashboard') {
          if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
            $('.fixed-plugin .dropdown').addClass('open');
          }

        }

        $('.fixed-plugin a').click(function(event) {
          // Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
          if ($(this).hasClass('switch-trigger')) {
            if (event.stopPropagation) {
              event.stopPropagation();
            } else if (window.event) {
              window.event.cancelBubble = true;
            }
          }
        });

        $('.fixed-plugin .active-color span').click(function() {
          $full_page_background = $('.full-page-background');

          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-color', new_color);
          }

          if ($full_page.length != 0) {
            $full_page.attr('filter-color', new_color);
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.attr('data-color', new_color);
          }
        });

        $('.fixed-plugin .background-color .badge').click(function() {
          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('background-color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-background-color', new_color);
          }
        });

        $('.fixed-plugin .img-holder').click(function() {
          $full_page_background = $('.full-page-background');

          $(this).parent('li').siblings().removeClass('active');
          $(this).parent('li').addClass('active');


          var new_image = $(this).find("img").attr('src');

          if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
            $sidebar_img_container.fadeOut('fast', function() {
              $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
              $sidebar_img_container.fadeIn('fast');
            });
          }

          if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

            $full_page_background.fadeOut('fast', function() {
              $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
              $full_page_background.fadeIn('fast');
            });
          }

          if ($('.switch-sidebar-image input:checked').length == 0) {
            var new_image = $('.fixed-plugin li.active .img-holder').find("img").attr('src');
            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

            $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
            $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.css('background-image', 'url("' + new_image + '")');
          }
        });

        $('.switch-sidebar-image input').change(function() {
          $full_page_background = $('.full-page-background');

          $input = $(this);

          if ($input.is(':checked')) {
            if ($sidebar_img_container.length != 0) {
              $sidebar_img_container.fadeIn('fast');
              $sidebar.attr('data-image', '#');
            }

            if ($full_page_background.length != 0) {
              $full_page_background.fadeIn('fast');
              $full_page.attr('data-image', '#');
            }

            background_image = true;
          } else {
            if ($sidebar_img_container.length != 0) {
              $sidebar.removeAttr('data-image');
              $sidebar_img_container.fadeOut('fast');
            }

            if ($full_page_background.length != 0) {
              $full_page.removeAttr('data-image', '#');
              $full_page_background.fadeOut('fast');
            }

            background_image = false;
          }
        });

        $('.switch-sidebar-mini input').change(function() {
          $body = $('body');

          $input = $(this);

          if (md.misc.sidebar_mini_active == true) {
            $('body').removeClass('sidebar-mini');
            md.misc.sidebar_mini_active = false;

            if ($(".sidebar").length != 0) {
              var ps = new PerfectScrollbar('.sidebar');
            }
            if ($(".sidebar-wrapper").length != 0) {
              var ps1 = new PerfectScrollbar('.sidebar-wrapper');
            }
            if ($(".main-panel").length != 0) {
              var ps2 = new PerfectScrollbar('.main-panel');
            }
            if ($(".main").length != 0) {
              var ps3 = new PerfectScrollbar('main');
            }

          } else {

            if ($(".sidebar").length != 0) {
              var ps = new PerfectScrollbar('.sidebar');
              ps.destroy();
            }
            if ($(".sidebar-wrapper").length != 0) {
              var ps1 = new PerfectScrollbar('.sidebar-wrapper');
              ps1.destroy();
            }
            if ($(".main-panel").length != 0) {
              var ps2 = new PerfectScrollbar('.main-panel');
              ps2.destroy();
            }
            if ($(".main").length != 0) {
              var ps3 = new PerfectScrollbar('main');
              ps3.destroy();
            }


            setTimeout(function() {
              $('body').addClass('sidebar-mini');

              md.misc.sidebar_mini_active = true;
            }, 300);
          }

          // we simulate the window Resize so the charts will get updated in realtime.
          var simulateWindowResize = setInterval(function() {
            window.dispatchEvent(new Event('resize'));
          }, 180);

          // we stop the simulation of Window Resize after the animations are completed
          setTimeout(function() {
            clearInterval(simulateWindowResize);
          }, 1000);

        });
      });
    });
  </script>
  <script src="assets/demo/jquery.sharrre.js"></script>
  <script>
    $(document).ready(function() {
      md.initDashboardPageCharts();
      md.initVectorMap();
    });
  </script>

  <script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/assets-for-demo/js/demo.js
      demo.initCharts();
    });
  </script>
</body>
</html>
  <!-- Global site tag (gtag.js) - Google Ads: 10816766033 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-10816766033"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-10816766033');
</script>
