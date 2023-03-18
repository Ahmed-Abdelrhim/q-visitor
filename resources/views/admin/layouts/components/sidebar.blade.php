<div class="main-sidebar @if(app()->getLocale()== 'ar') direction @endif">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <!-- <a href="{{ route('admin.dashboard.index') }}">{{ setting('site_name') }}</a> -->
            <img src="{{asset('Q-visitor.png')}}" alt="not-found" class="logo"/>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('admin.dashboard.index') }}">
                <?php
                if (setting('site_name')) {
                    $sitenames = explode(' ', setting('site_name'));
                    if (count($sitenames) > 1) {
                        foreach ($sitenames as $sitename) {
                            echo $sitename[0];
                        }
                    } else {
                        echo substr(setting('site_name'), 0, 2);
                    }
                }
                ?>
            </a>
        </div>


        <ul class="sidebar-menu">

            {{--            {!! $backendMenus !!}--}}

            <!-- Start Dashboard -->
            @if(auth()->user()->hasRole(1) || auth()->user()->hasPermissionTo('dashboard') )
                <li class="dashboard">
                    <a class="nav-link" href="{{route('admin.dashboard.index')}}">
                        <i class="fa fa-home"></i>
                        <span>{{__('files.Dashboard')}}</span>
                    </a>
                </li>
            @endif
            <!-- End Dashboard -->


            <!-- Start Profile -->
            @if(auth()->user()->hasRole(1) || auth()->user()->hasPermissionTo('profile') )
                <li class="profile">
                    <a class="nav-link" href="{{route('admin.profile')}}">
                        <i class="far fa-user"></i>
                        <span>{{__('files.Profile')}}</span>
                    </a>
                </li>
            @endif
            <!-- End Profile -->

            <!-- Start Departments -->
            @if(auth()->user()->hasRole(1) ||
                auth()->user()->hasAnyPermission(['departments','departments_create','departments_edit','departments_delete','departments_show']))
                <li class="departments">
                    <a class="nav-link" href="{{route('admin.departments.index')}}">
                        <i class="fas fa-building"></i>
                        <span>{{__('files.Departments')}}</span>
                    </a>
                </li>
            @endif
            <!-- End Departments -->


            <!-- Start  Positions , designations -->
            @if(auth()->user()->hasRole(1) ||
                auth()->user()->hasAnyPermission(['designations','designations_create','designations_edit','designations_delete','designations_show']))
                <li class="positions">
                    <a class="nav-link" href="{{route('admin.designations.index')}}">
                        <i class="fas fa-layer-group"></i>
                        <span>{{__('files.Positions')}}</span>
                    </a>
                </li>
            @endif
            <!-- End Positions -->

            <!-- Start  Employees -->
            @if(auth()->user()->hasRole(1) ||
                auth()->user()->hasAnyPermission(['employees','employees_create','employees_edit','employees_delete','employees_show']))
                <li class="employees">
                    <a class="nav-link" href="{{route('admin.employees.index')}}">
                        <i class="fas fa-user-secret"></i>
                        <span>{{__('files.Employees')}}</span>
                    </a>
                </li>
            @endif
            <!-- End Employees -->


            <!-- Start  Visitors -->
            @if(auth()->user()->hasRole(1) ||
                auth()->user()->hasAnyPermission(['visitors','visitors_create','visitors_edit','visitors_delete','visitors_show']))
                <li class="visitors">
                    <a class="nav-link" href="{{route('admin.visitors.index')}}">
                        <!-- <i class="fas fa-walking"></i> -->
                        <i class="fa fa-info"></i>
                        <span>{{__('files.Visit Reservation')}}</span>
                    </a>
                </li>
            @endif
            <!-- End Visitors -->

            <!-- Start  Pre-Register -->
            @if(auth()->user()->hasRole(1) ||
                auth()->user()->hasAnyPermission(['pre-registers','pre-registers_create','pre-registers_edit','pre-registers_delete','pre-registers_show']))
                <li class="pre-register">
                    <a class="nav-link" href="{{route('admin.pre-registers.index')}}">
                        <i class="fas fa-laptop"></i>
                        <span>{{__('files.Future Visit')}}</span>
                    </a>
                </li>
            @endif
            <!-- End  Pre-Register -->

            <!-- Start  Administrators , adminusers -->
            @if(auth()->user()->hasRole(1) ||
                auth()->user()->hasAnyPermission(['adminusers','adminusers_create','adminusers_edit','adminusers_delete','adminusers_show']))
                <li class="administrators">
                    <a class="nav-link" href="{{route('admin.adminusers.index')}}">
                        <i class="fas fa-user-friends"></i>
                        <span>{{__('files.Administrators')}}</span>
                    </a>
                </li>
            @endif
            <!-- End  Administrators -->


            <!-- Start  Role -->
            @if(auth()->user()->hasRole('Admin') ||
                auth()->user()->hasAnyPermission(['role','role_create','role_edit','role_delete','role_show']))
                <li class="role">
                    <a class="nav-link" href="{{route('admin.role.index')}}">
                        <i class="fa fa-star"></i>
                        <span>{{__('files.Role')}}</span>
                    </a>
                </li>
            @endif
            <!-- End  Role -->

            <!-- Start  Types -->
            @if(auth()->user()->hasRole(1) ||
                auth()->user()->hasAnyPermission(['types','types_create','types_edit','types_delete','types_show']))
                <li class="role">
                    <a class="nav-link" href="{{route('admin.types.index')}}">
                        <i class="fas fa-layer-group"></i>
                        <span>{{__('files.Types')}}</span>
                    </a>
                </li>
            @endif
            <!-- End  Types -->

            <!-- Start  Shipments -->
            @if(auth()->user()->hasRole(1) ||
                auth()->user()->hasAnyPermission(['shipment','shipment_create','shipment_edit','shipment_delete','shipment_show']))
                <li class="role">
                    <a class="nav-link" href="{{route('admin.shipment.index')}}">
                        <i class="fas fa-layer-group"></i>
                        <span>{{__('files.Shipments')}}</span>
                    </a>
                </li>
            @endif
            <!-- End  Shipments -->


            <!-- Start  Settings -->
            @if(auth()->user()->hasRole(1) || auth()->user()->hasPermissionTo('setting'))
                <li class="settings">
                    <a class="nav-link" href="{{route('admin.setting.index')}}">
                        <!-- <i class="fa fa-star"></i> -->
                        <i class="fa fa-wrench"></i>
                        <span>{{__('files.Settings')}}</span>
                    </a>
                </li>
            @endif
            <!-- End  Settings -->

            @if(auth()->user()->hasRole(1) ||
                auth()->user()->hasAnyPermission(['ocr','ocr_create','ocr_edit','ocr_show','ocr_delete']))
                <li class="ocr">
                    <a class="nav-link" href="{{route('admin.OCR.index')}}">
                        <!-- <i class="fas fa-facebook"> -->
                        <i class="fa fa-camera"></i>
                        <!-- <i class="fa fa-scanner"></i> -->
                        <span>{{__('files.OCR')}}</span>
                    </a>
                </li>
            @endif
        </ul>
    </aside>
</div>
