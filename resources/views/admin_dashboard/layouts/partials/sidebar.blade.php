<div class="app-sidebar">
     <!-- Sidebar Logo -->
     <div class="logo-box">
            <h1 style="font-size: 32px; font-weight: 600; letter-spacing: 0px; color: #ec7b34; padding-top:10px;">
                                    GoFlyHabibi
                                </h1>
     </div>

     <div class="scrollbar" data-simplebar>

          <ul class="navbar-nav" id="navbar-nav">

               <li class="menu-title">Menu...</li>

               <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.index') }}">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:widget-2-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Dashboard </span>
                         <span class="badge bg-primary badge-pill text-end">New</span>
                    </a>
               </li>

 <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.user')}}" role="button">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:user-circle-outline"></iconify-icon>
                         </span>    
                         <span class="nav-text"> Users</span>
                    </a>
                   
               </li>

               {{-- <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarAuthentication" data-bs-toggle="collapse" role="button"
                         aria-expanded="false" aria-controls="sidebarAuthentication">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:user-circle-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Authentication </span>
                    </a>
                    <div class="collapse" id="sidebarAuthentication">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['auth','signin']) }}">Sign In</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['auth','signup']) }}">Sign Up</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['auth','password']) }}">Reset Password</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['auth','lock-screen']) }}">Lock Screen</a>
                              </li>
                         </ul>
                    </div>
               </li> --}}
             
 <li class="nav-item">
                    <a class="nav-link" href="{{route('bookings.index')}}" role="button">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:chart-square-outline"></iconify-icon>
                         </span> 
                         <span class="nav-text">Bookings</span>
                    </a>
                   
               </li>

             <li class="nav-item">
                    <a class="nav-link" href="{{route('config')}}" role="button">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:chart-square-outline"></iconify-icon>
                         </span> 
                         <span class="nav-text">Configuration</span>
                    </a>
                   
               </li>

               <li class="menu-title">UI Kit...</li>

               {{-- <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarBaseUI" data-bs-toggle="collapse" role="button"
                         aria-expanded="false" aria-controls="sidebarBaseUI">
                         <span class="nav-icon"><iconify-icon icon="solar:leaf-outline"></iconify-icon></span>
                         <span class="nav-text"> Base UI </span>
                    </a>
                    <div class="collapse" id="sidebarBaseUI">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','accordion']) }}">Accordion</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','alerts']) }}">Alerts</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','avatar']) }}">Avatar</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','badge']) }}">Badge</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','breadcrumb']) }}">Breadcrumb</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','buttons']) }}">Buttons</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','card']) }}">Card</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','carousel']) }}">Carousel</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','collapse']) }}">Collapse</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','dropdown']) }}">Dropdown</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','list-group']) }}">List Group</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','modal']) }}">Modal</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','tabs']) }}">Tabs</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','offcanvas']) }}">Offcanvas</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','pagination']) }}">Pagination</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','placeholders']) }}">Placeholders</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','popovers']) }}">Popovers</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','progress']) }}">Progress</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','scrollspy']) }}">Scrollspy</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','spinners']) }}">Spinners</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','toasts']) }}">Toasts</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','tooltips']) }}">Tooltips</a>
                              </li>
                         </ul>
                    </div>
               </li> --}}

               {{-- <li class="nav-item">
                    <a class="nav-link" href="{{ route ('second' , ['pages','charts']) }}">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:chart-square-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Apex Charts </span>
                    </a>
               </li> --}}

               {{-- <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarForms" data-bs-toggle="collapse" role="button"
                         aria-expanded="false" aria-controls="sidebarForms">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:box-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Forms </span>
                    </a>
                    <div class="collapse" id="sidebarForms">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['forms','basic']) }}">Basic Elements</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['forms','flatpicker']) }}">Flatpicker</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['forms','validation']) }}">Validation</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['forms','fileuploads']) }}">File Upload</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['forms','editors']) }}">Editors</a>
                              </li>
                         </ul>
                    </div>
               </li> --}}

          </ul>
     </div>
</div>