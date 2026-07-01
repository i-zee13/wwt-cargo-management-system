 <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
     <div class="card-header"> 
         <div class="row">
             <div class="col-md-10 col-lg-10 mt-auto mb-auto">
                 <h2 class="heading02">Notifications</h2>
             </div>
             <div class="col-md-2 col-lg-2 mt-auto mb-auto">
                 <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
             </div>
         </div>
     </div>
     <div class="offcanvas-body">
     </div>
 </div>
 <div class="sidebar close">
     <div class="logo-details bx-menu">
         <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 41.5 41.7"
             style="enable-background:new 0 0 41.5 41.7;" xml:space="preserve">
             <g>
                 <path class="st0" d="M10.7,0.8h20c5.5,0,10,4.5,10,10v20c0,5.5-4.5,10-10,10h-20c-5.5,0-10-4.5-10-10v-20C0.7,5.3,5.2,0.8,10.7,0.8
                z" />
             </g>
             <path class="st1" d="M11.8,26.2h17.9 M11.8,20.8h17.9 M11.8,15.4h17.9" />
         </svg>
          <a href="/" class="logo_name">
             <img src="{{getOrganizationData()->logo}}" alt="">
         </a>  

     </div>
     <ul class="nav-links mt-2">
        @if (GetActiveGuardDetail()->is_web == 1)
        <li>
             <a href="/admin/home">
                 <img src="/images/dashboard.svg" alt="">
                 <span class="link_name">Dashboard</span>
             </a>
             <ul class="sub-menu blank">
                 <li><a class="link_name" href="/admin/home">Dashboard</a></li>
             </ul>
         </li>  
        @endif
       
         <div class="new_nav  ">
             <li class="arrow">
                <div class="iocn-link">
                    <a href="#">
                        <svg width="18" height="20" viewBox="0 0 18 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M9 0C3.95625 0 0 2.30625 0 5.25V14.25C0 17.1938 3.95625 19.5 9 19.5C14.0437 19.5 18 17.1938 18 14.25V5.25C18 2.30625 14.0437 0 9 0ZM16.5 9.75C16.5 10.65 15.7594 11.5687 14.475 12.2719C13.0219 13.0687 11.0813 13.5 9 13.5C6.91875 13.5 4.97812 13.0687 3.525 12.2719C2.24062 11.5687 1.5 10.65 1.5 9.75V8.19375C3.10313 9.59062 5.83125 10.5 9 10.5C12.1687 10.5 14.8969 9.59062 16.5 8.19375V9.75ZM14.475 16.7719C13.0219 17.5687 11.0813 18 9 18C6.91875 18 4.97812 17.5687 3.525 16.7719C2.24062 16.0687 1.5 15.15 1.5 14.25V12.6937C3.10313 14.0906 5.83125 15 9 15C12.1687 15 14.8969 14.0906 16.5 12.6937V14.25C16.5 15.15 15.7594 16.0687 14.475 16.7719Z"
                                fill="#8F96A1" />
                        </svg>
                        <span class="link_name">Generals</span>
                    </a>
                </div>
                <ul class="sub-menu"> 
                    <li><a href="javascript:void(0);">Manage Access Rights</a></li>
                    <li><a href="javascript:void(0);">Clients</a></li>
                </ul>
            </li>
         </div>
         
         @if(Auth::user()->super == 1)
         <li>
             <div class="help-support">
    
                 <a href="/admin/manage_settings">
                     <img src="/images/setting-bottom.svg" alt="">
    
                     <span class="link_name" style="color: #eb973c;">{{__('fields.settings')}}</span>
                 </a>
                 <ul class="sub-menu blank">
                     <li><a class="link_name" href="/manage_settings">{{__('fields.settings')}}</a></li>
                 </ul>
             </div>
         </li> 
       
             

     </ul>
   
     @endif
 </div>
