<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard.index') }}">{{ setting('site_name') }}</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('admin.dashboard.index') }}">
                <?php
                    if(setting('site_name')) {
                        $sitenames = explode(' ', setting('site_name'));
                        if(count($sitenames) > 1) {
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
            
            {!! $backendMenus !!}

            @if(auth()->user()->hasRole('Admin') ||auth()->user()->hasRole('ocr')|| auth()->user()->hasPermissionTo('ocr_create') )
                <li  class="ocr">
                    <a class="nav-link" href="{{route('admin.OCR.index')}}">
                        <i class="fas fa-cocktail">
                            <span>OCR</span>
                        </i>
                    </a>
                </li>
            @endif
        </ul>
    </aside>
</div>
