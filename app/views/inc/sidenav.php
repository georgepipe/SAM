<nav class="bg-gray-800 flex flex-col min-h-10"">

		<button 
			class="absolute top-2 left-2 z-[1] sm:hidden hover:bg-gray-700" 
			data-target-action="nav" 
			aria-expanded="false"
		>
			<span class="absolute -inset-0.5"></span>
			<span class="sr-only">Open main menu</span>
			<svg 
					class="block h-6 w-6 nav-closed" 
					fill="none" 
					viewBox="0 0 24 24" 
					stroke-width="1.5" 
					stroke="white" 
					aria-hidden="true" >
							<path 
									stroke-linecap="round" 
									stroke-linejoin="round" 
									d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
			</svg>
			<svg 
					class="hidden h-6 w-6 nav-open" 
					fill="none" viewBox="0 0 24 24" 
					stroke-width="1.5" 
					stroke="white" 
					aria-hidden="true">
							<path 
									stroke-linecap="round" 
									stroke-linejoin="round" 
									d="M6 18L18 6M6 6l12 12" />
			</svg>
		</button>

	<ul 
			class="hidden w-full py-5 px-5 flex-row sm:flex sm:w-fit gap-3" 
			type="menu" 
			data-target="nav" 
	>
			<li class="py-3 px-2 rounded-md group relative">
				<a 
					class="text-white p-2 transition hover:text-sky-400" 
					href="<?php echo URLROOT.'pages/index';?>">
				Overview</a>
				<span class="absolute -bottom-1 left-0 w-0 transition-all h-0.5 bg-indigo-600 group-hover:w-full"></span>
			</li>
			<li class="py-3 px-2 rounded-md group relative">
				<a 
					class="text-white p-2 transition hover:text-sky-400" 
					href="<?php echo URLROOT.'workorders/index';?>">Work Orders
				</a>
				<span class="absolute -bottom-1 right-0 w-0 transition-all h-0.5 bg-indigo-600 group-hover:w-full"></span>
			</li >
			<li class="py-3 px-2 rounded-md group relative">
				<a 
					class="text-white p-2 transition hover:text-sky-400" 
					href="<?php echo URLROOT.'inventory/index';?>">Inventory
				</a>
				<span class="absolute -bottom-1 left-0 w-0 transition-all h-0.5 bg-indigo-600 group-hover:w-full"></span>
			</li>
			<li class="py-3 px-2 rounded-md group relative">
				<a 
					class="text-white p-2 transition hover:text-sky-400" 
					href="<?php echo URLROOT.'users/login';?>">Sign In
				</a>
				<span class="absolute -bottom-1 right-0 w-0 transition-all h-0.5 bg-indigo-600 group-hover:w-full"></span>
			</li>
	</ul>
</nav>