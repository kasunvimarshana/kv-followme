<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                @if( (isset($auth_user)) && ($auth_user->thumbnailphoto) )
                    <img src="data:image/jpeg;base64, {!! base64_encode( $auth_user->thumbnailphoto ) !!}" class="img-circle" alt="User Image"/>
                @else
                    <img src="{!! URL::asset('node_modules/admin-lte/dist/img/avatar5.png') !!}" class="img-circle" alt="User Image"/>
                @endif
            </div>
            <div class="pull-left info">
                @isset($auth_user)
                    <p> {{ $auth_user->mail }} </p>
                @endisset
                <!-- p>user</p -->
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form (Optional) -->
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            
            <!-- li class="header">ACTIVITIES</li -->
            <!-- Optionally, you can add icons to the links -->
            <!-- li class="{!! set_active(['home/']) !!}"><a href="{!! route('home.index') !!}"><i class="fa fa-edit"></i> <span>Home</span></a></li -->
            <li class="header">ACTIVITIES</li>
            <li class="treeview {!! set_active(['home', 'home/*']) !!}">
                <a href="#">
                    <i class="fa fa-edit"></i> <span>3W</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{!! set_active(['home/tws/create', 'home/tws/create/*']) !!}"><a href="{!! route('tw.create') !!}"> <i class="fa fa-arrow-circle-o-right"></i> Create New</a></li>
                    <li class="{!! set_active(['home/tws/show-owne-tws', 'home/tws/show-owne-tws/*']) !!}"><a href="{!! route('tw.showOwneTW') !!}"> <i class="fa fa-arrow-circle-o-right"></i> Assigned To Me</a></li>
                    <li class="{!! set_active(['home/tws/show-created-tws', 'home/tws/show-created-tws/*']) !!}"><a href="{!! route('tw.showCreatedTW') !!}"> <i class="fa fa-arrow-circle-o-right"></i> Assigned To Others</a></li>
                    <!-- li class="{!! set_active(['home/direct-reports/show-all', 'home/direct-reports/*']) !!}"><a href="{!! route('user.listDirectReports', [urlencode($auth_user->mail)]) !!}"> <i class="fa fa-arrow-circle-o-right"></i> Assigned To Subordinate</a></li -->
                </ul>
            </li>
            <li class="treeview {!! set_active(['team/direct-reports', 'team/direct-reports/*']) !!}">
                <a href="#">
                    <i class="fa fa-edit"></i> <span>My Team</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{!! set_active(['team/direct-reports/show-all', 'team/direct-reports/*']) !!}"><a href="{!! route('user.showDirectReports') !!}"> <i class="fa fa-arrow-circle-o-right"></i> Status</a></li>
                </ul>
            </li>
            <li class="treeview {!! set_active(['team/departments', 'team/departments/*']) !!}">
                <a href="#">
                    <i class="fa fa-edit"></i> <span>My Department</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{!! set_active(['team/departments/show', 'team/departments/*']) !!}"><a href="{!! route('department.show') !!}"> <i class="fa fa-arrow-circle-o-right"></i> Status</a></li>
                </ul>
            </li>
            <li class="treeview {!! set_active(['team/companies', 'team/companies/*']) !!}">
                <a href="#">
                    <i class="fa fa-edit"></i> <span>My SBU</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{!! set_active(['team/companies/departments', 'team/companies/departments/*']) !!}"><a href="{!! route('company.showDepartments') !!}"> <i class="fa fa-arrow-circle-o-right"></i> Status</a></li>
                    @isset($auth_user)
                        <li class="{!! set_active(['team/companies/show', 'team/companies/show/*']) !!}"><a href="{!! route('company.showCompanyTW', urlencode($auth_user->company)) !!}"> <i class="fa fa-arrow-circle-o-right"></i> View All</a></li>
                    @endisset
                </ul>
            </li>
            @superadmin
            <li class="treeview {!! set_active(['backstage', 'backstage/*']) !!}">
                <a href="#">
                    <i class="fa fa-edit"></i> <span>Backstage</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="treeview {!! set_active(['backstage/meeting-categories', 'backstage/meeting-categories/*']) !!}">
                        <a href="#">
                            <i class="fa fa-circle-o"></i> <span>Meeting Category</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{!! set_active(['backstage/meeting-categories/create', 'backstage/meeting-categories/create/*']) !!}"><a href="{!! route('meetingCategory.create') !!}"> <i class="fa fa-arrow-circle-o-right"></i> Control</a></li>
                        </ul>
                    </li>
                    
                    <li class="treeview {!! set_active(['backstage/user-roles', 'backstage/user-roles/*']) !!}">
                        <a href="#">
                            <i class="fa fa-circle-o"></i> <span>Admin User</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{!! set_active(['backstage/user-roles/create', 'backstage/user-roles/create/*']) !!}"><a href="{!! route('userRole.create') !!}"> <i class="fa fa-arrow-circle-o-right"></i> Control</a></li>
                        </ul>
                    </li>
                    
                    <li class="treeview {!! set_active(['backstage/user-escalate-offs', 'backstage/user-escalate-offs/*']) !!}">
                        <a href="#">
                            <i class="fa fa-circle-o"></i> <span>Escalate Off User</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{!! set_active(['backstage/user-escalate-offs/create', 'backstage/user-escalate-offs/create/*']) !!}"><a href="{!! route('userEscalateOff.create') !!}"> <i class="fa fa-arrow-circle-o-right"></i> Control</a></li>
                        </ul>
                    </li>
                    
                    <li class="treeview {!! set_active(['backstage/notification-schedules', 'backstage/notification-schedules/*']) !!}">
                        <a href="#">
                            <i class="fa fa-circle-o"></i> <span>Notification Schedule</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{!! set_active(['backstage/notification-schedules/create', 'backstage/notification-schedules/create/*']) !!}"><a href="{!! route('notificationSchedule.create') !!}"> <i class="fa fa-arrow-circle-o-right"></i> Control</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            @endsuperadmin
            
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
    
</aside>