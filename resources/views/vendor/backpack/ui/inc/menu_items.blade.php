{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title="Users" icon="la la-user" :link="backpack_url('user')" />
<x-backpack::menu-dropdown-item title="Roles" icon="la la-group" :link="backpack_url('role')" />
<x-backpack::menu-dropdown-item title="Permissions" icon="la la-key" :link="backpack_url('permission')" />
<x-backpack::menu-item title='Menu' icon='la la-list' :link="backpack_url('menu-item')" />
<x-backpack::menu-item title='Pages' icon='la la-file-o' :link="backpack_url('page')" />
<x-backpack::menu-dropdown-item title="Services" icon="la la-newspaper-o" :link="backpack_url('service')" />
<x-backpack::menu-dropdown-item title="Service Types" icon="la la-list" :link="backpack_url('service_type')" />
<!-- <x-backpack::menu-dropdown-item title="Tags" icon="la la-tag" :link="backpack_url('tag')" /> -->
<x-backpack::menu-item :title="trans('backpack::crud.file_manager')" icon="la la-files-o" :link="backpack_url('elfinder')" />
<x-backpack::menu-item title="Orders" icon="la la-store" :link="backpack_url('order')" />
<x-backpack::menu-item title="Appointments" icon="la la-calendar-check" :link="backpack_url('appointment')" />
<!-- <x-backpack::menu-item title="Contacts" icon="la la-question" :link="backpack_url('contact')" /> -->