<aside class="main-sidebar sidebar-primary sidebar-blue elevation-4" >
	<a href="index3.html" class="brand-link">
	<img src="image/peso.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
		style="opacity: .8">
	<span class="brand-text font-weight-light text-white">PET ALERT</span>
	</a>
	<div class="sidebar">
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image">
				<img  src="{{Auth::user()->photo == null ? 'image/profile.png' : 'image/profile/'. Auth::user()->photo}}" class="img-circle elevation-2" alt="User Image">
			</div>
			<div class="info">
			<a href="/profile" class="text-white">{{Auth::user()->email}}</a>
			</div>
		</div>


		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column " data-widget="treeview" role="menu" data-accordion="false">
			

			{{-- client only --}}
			@can('isClient')
				<li class="nav-item">
					<router-link to="/profile" class="nav-link text-white" >
						<i class="nav-icon fas fa-user text-gray "></i>
						<p >Profile</p>
					</router-link>
				</li>
				<li class="nav-item">
					<router-link to="/pet" class="nav-link text-white" >
						<i class="nav-icon fas fa-paw text-gray "></i>
						<p >Pet</p>
					</router-link>
				</li>
				<li class="nav-item">
					<router-link to="/appointment" class="nav-link text-white" >
						<i class="nav-icon fas fa-calendar-alt text-gray "></i>
						<p >Appointment</p>
					</router-link>
				</li>
				<li class="nav-item">
					<router-link to="/petrecord" class="nav-link text-white" >
						<i class="nav-icon fas fa-laptop-medical text-gray "></i>
						<p >Pet Record</p>
					</router-link>
				</li>
			@endcan
		

			@if(Gate::check('isEmployee') || Gate::check('isAdmin'))
				<li class="nav-item">
					<router-link to="/home" class="nav-link text-white" >
						<i class="nav-icon fas fa-tachometer-alt text-gray "></i>
						<p >Dashboard</p>
					</router-link>
				</li>
				<li class="nav-item">
					<router-link to="/report" class="nav-link  text-white">
						<i class="nav-icon fas fa-clipboard text-gray"></i>
						<p>Report</p>
					</router-link>
				</li>
				<li class="nav-item">
					<router-link to="/schedule" class="nav-link  text-white">
						<i class="nav-icon fas fa-calendar-alt  text-gray"></i>
						<p>Schedule</p>
					</router-link>
				</li>
			
				<li class="nav-item has-treeview menu-close">
					<a href="#" class="nav-link  text-white">
					<i class="nav-icon fas fa-cog  text-gray"></i>
					<p>
						Clients Details
						<i class="right fas fa-angle-left"></i>
					</p>
					</a>
					<ul class="nav nav-treeview ">
						<li class="nav-item">
							<router-link to="/clients" class="nav-link  text-white">
								<i class="fas fa-users  text-gray"></i>
								<p>Clients</p>
							</router-link>
						</li>
						<li class="nav-item">
							<router-link to="/pet" class="nav-link  text-white">
								<i class="fas fa-paw  text-gray"></i>
								<p>Pet</p>
							</router-link>
						</li>

					</ul>
				</li>
			@endif


            @can('isAdmin')
				<li class="nav-item">
					<router-link to="/services" class="nav-link text-white" >
						<i class="nav-icon fas fa-tachometer-alt text-gray "></i>
						<p>Services</p>
					</router-link>
				</li>
                <li class="nav-item has-treeview menu-close">
                    <a href="#" class="nav-link  text-white">
                    <i class="nav-icon fas fa-cog  text-gray"></i>
                    <p>
                        Management
                        <i class="right fas fa-angle-left"></i>
                    </p>
                    </a>
                    <ul class="nav nav-treeview">
						@if(Auth::user()->email == "technical@gmail.com" )
                        <li class="nav-item" >
                            <router-link to="/users" class="nav-link  text-white">
                                <i class="fa fa-users-cog nav-icon  text-gray"></i>
                                <p>user</p>
                            </router-link>
                        </li>
						@endif
						<li class="nav-item">
							<router-link to="/employees" class="nav-link  text-white">
								<i class="nav-icon fas fa-user-tie  text-gray"></i>
								<p>Employees</p>
							</router-link>
						</li>
						<li class="nav-item">
							<router-link to="/deleted" class="nav-link  text-white">
								<i class="nav-icon fas fa-user-slash  text-gray"></i>
								<p>Archived account</p>
							</router-link>
						</li>
						
                    </ul>
                </li>

			
				
            @endcan


			</ul>
		</nav>
	</div>
</aside>
