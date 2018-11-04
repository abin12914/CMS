<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/images/users/default_user.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                @if(!empty($loggedUser))
                    <p>{{ $loggedUser->name }}</p>
                    <a href="{{ route('user.profile') }}"><i class="fa  fa-hand-o-right"></i> View Profile</a>
                @else
                    <p>Login</p>
                    <a href="{{ route('user.profile') }}"><i class="fa  fa-hand-o-right"></i> To continue</a>
                @endif
            </div>
        </div>
        @if(!empty($loggedUser))
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">MAIN NAVIGATION</li>
                <li class="{{ Request::is('dashboard')? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>
                {{-- @if($loggedUser->isSuperAdmin())
                @endif
                @if($loggedUser->isSuperAdmin() || $loggedUser->isAdmin())
                @endif --}}
                @if($loggedUser->isSuperAdmin() || $loggedUser->isAdmin() || $loggedUser->isUser())
                    <li class="treeview {{ Request::is('certificate/*') || Request::is('certificate') ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-clipboard"></i>
                            <span>Certificates</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ Request::is('certificate/create')? 'active' : '' }}">
                                <a href="{{route('certificate.create') }}">
                                    <i class="fa fa-circle-o text-yellow"></i> Issue
                                </a>
                            </li>
                            <li class="{{ Request::is('certificate/create')? 'active' : '' }}">
                                <a href="{{route('certificate.create') }}">
                                    <i class="fa fa-circle-o text-yellow"></i> Register
                                </a>
                            </li>
                            <li class="{{ Request::is('certificate')? 'active' : '' }}">
                                <a href="{{route('certificate.index') }}">
                                    <i class="fa fa-circle-o text-aqua"></i> List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview {{ Request::is('course/*') || Request::is('course') ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-graduation-cap"></i>
                            <span>Courses</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ Request::is('course/create')? 'active' : '' }}">
                                <a href="{{route('course.create') }}">
                                    <i class="fa fa-circle-o text-yellow"></i> Register
                                </a>
                            </li>
                            <li class="{{ Request::is('course')? 'active' : '' }}">
                                <a href="{{route('course.index') }}">
                                    <i class="fa fa-circle-o text-aqua"></i> List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview {{ Request::is('batch/*') || Request::is('batch') ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-building-o"></i>
                            <span>Batches</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ Request::is('batch/create')? 'active' : '' }}">
                                <a href="{{route('batch.create') }}">
                                    <i class="fa fa-circle-o text-yellow"></i> Register
                                </a>
                            </li>
                            <li class="{{ Request::is('batch')? 'active' : '' }}">
                                <a href="{{route('batch.index') }}">
                                    <i class="fa fa-circle-o text-aqua"></i> List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview {{ Request::is('student/*') || Request::is('student') ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-users"></i>
                            <span>Students</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ Request::is('student/create')? 'active' : '' }}">
                                <a href="{{route('student.create') }}">
                                    <i class="fa fa-circle-o text-yellow"></i> Register
                                </a>
                            </li>
                            <li class="{{ Request::is('student')? 'active' : '' }}">
                                <a href="{{route('student.index') }}">
                                    <i class="fa fa-circle-o text-aqua"></i> List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview {{ Request::is('certification/*') || Request::is('certification') ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-file-text-o"></i>
                            <span>Certification</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ Request::is('certification/create')? 'active' : '' }}">
                                <a href="{{route('certification.create') }}">
                                    <i class="fa fa-circle-o text-yellow"></i> Register
                                </a>
                            </li>
                            <li class="{{ Request::is('certification')? 'active' : '' }}">
                                <a href="{{route('certification.index') }}">
                                    <i class="fa fa-circle-o text-aqua"></i> List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview {{ Request::is('address/*') || Request::is('address') ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-newspaper-o"></i>
                            <span>Addresses</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ Request::is('address/create')? 'active' : '' }}">
                                <a href="{{route('address.create') }}">
                                    <i class="fa fa-circle-o text-yellow"></i> Register
                                </a>
                            </li>
                            <li class="{{ Request::is('address')? 'active' : '' }}">
                                <a href="{{route('address.index') }}">
                                    <i class="fa fa-circle-o text-aqua"></i> List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview {{ Request::is('authority/*') || Request::is('authority') ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-user-secret"></i>
                            <span>Authorities</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ Request::is('authority/create')? 'active' : '' }}">
                                <a href="{{route('authority.create') }}">
                                    <i class="fa fa-circle-o text-yellow"></i> Register
                                </a>
                            </li>
                            <li class="{{ Request::is('authority')? 'active' : '' }}">
                                <a href="{{route('authority.index') }}">
                                    <i class="fa fa-circle-o text-aqua"></i> List
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        @endif
    </section>
    <!-- /.sidebar -->
</aside>