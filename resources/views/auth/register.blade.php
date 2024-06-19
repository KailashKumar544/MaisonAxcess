<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>
        <style>
            .tabs {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
            text-align:center;
            }

            /* Style the buttons inside the tab */
            .tabs button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
            font-size: 17px;
            width: 50%;
            }
            .tabs button:hover {
            background-color: #ddd;
            }

            /* Create an active/current tablink class */
            .tabs button.active {
            background-color: #ccc;
            }
            /* Style the tab content */
            .tab-content {
                display: none;
                padding: 20px;
                border: 1px solid #ccc;
            }

            /* Style the active tab content */
            .tab-content.active {
                display: block;
            }
        </style>
        <x-validation-errors class="mb-4 gggg" />
        <div class="tabs">
            <button class="tablink active" onclick="openTab(event, 'customer')">Client</button>
            <button class="tablink" onclick="openTab(event, 'service-provider')">Prestataire</button>
        </div>
        <div id="customer" class="tab-content active">

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf

                <div class="colum-grid">
                <div class="mt-4">
                    <x-label for="name" value="{{ __('Nom') }}" />
                    <x-input class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                </div>
                
                <div class="mt-4">
                    <x-label for="email" value="{{ __('E-mail') }}" />
                    <x-input class="block mt-1 w-full form-control @error('email') is-invalid @enderror" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                </div>
                

                <div class="mt-4">
                    <x-label for="phone" value="{{ __('Portable') }}" />
                    <x-input class="block mt-1 w-full" type="tel" name="phone_number" :value="old('phone')" required autocomplete="phone" />
                </div>
                
                
                <input type="hidden" name="user_role" value="3">

               
            <div class="colum-grid">
                <div class="mt-4">
                    <x-label for="password" value="{{ __('Mot de passe') }}" />
                    <x-input class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                </div>

                <div class="mt-4">
                    <x-label for="password_confirmation" value="{{ __('Confirmer le mot de passe') }}" />
                    <x-input class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>
            </div>	
              
                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="mt-4">
                        <x-label for="terms">
                            <div class="flex items-center">
                                <x-checkbox name="terms" id="terms" required />

                                <div class="ms-2">
                                    {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">'.__('Terms of Service').'</a>',
                                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">'.__('Privacy Policy').'</a>',
                                    ]) !!}
                                </div>
                            </div>
                        </x-label>
                    </div>
                @endif

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                        {{ __('Déjà inscrit?') }}
                    </a>

                    <x-button class="ms-4 btn-register">
                        {{ __('Inscription') }}
                    </x-button>
                </div>
                

            </form>
        </div>
        <div id="service-provider" class="tab-content">

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf

                <div class="mt-4">
                    <x-label for="profile_photo" value="{{ __('Photo de profil') }}" />
                    <x-input class="block mt-1 w-full" type="file" name="photo" required />
                </div>
                
                <div class="colum-grid">
                <div class="mt-4">
                    <x-label for="name" value="{{ __('Nom') }}" />
                    <x-input class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                </div>
                
                <div class="mt-4">
                    <x-label for="email" value="{{ __('E-mail') }}" />
                    <x-input class="block mt-1 w-full form-control @error('email') is-invalid @enderror" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                </div>
                

                <div class="mt-4">
                    <x-label for="phone" value="{{ __('Portable') }}" />
                    <x-input class="block mt-1 w-full" type="tel" name="phone_number" :value="old('phone')" required autocomplete="phone" />
                </div>
                
                
                <input type="hidden" name="user_role" value="4">

                <div class="mt-4 category_for_service">
                    <x-label for="category" value="{{ __('Principaux services') }}" />
                        <select name="mainServiceTypes" id="parentCategory" onchange="populateChildCategories()" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                            <option value="">Sélectionnez les services principaux</option>
                            @foreach($categories as $category)
                                @if($category->parent_id == null)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endif
                            @endforeach
                        </select>
                </div>

                <div class="mt-4 child_category_for_service" style="display:none;">
                    <x-label for="category" value="{{ __('Sous-services') }}" />
                    <select name="childCategory" id="childCategory" onchange="populateSubChildCategories()" style="display: none;" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                        <option value="">Sélectionnez les sous-services</option>
                    </select>
                </div>

                <div class="mt-4 sub_child_category_for_service" style="display:none;">
                    <x-label for="category" value="{{ __('Sous-services pour enfants') }}" />
                    <select name="subChildCategory" id="subChildCategory" style="display: none;" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                        <option value="">Sélectionnez les sous-services enfants</option>
                    </select>
                </div>

            <div class="colum-grid">
                <div class="mt-4">
                    <x-label for="password" value="{{ __('Mot de passe') }}" />
                    <x-input class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                </div>

                <div class="mt-4">
                    <x-label for="password_confirmation" value="{{ __('Confirmer le mot de passe') }}" />
                    <x-input class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>
            </div>	
                
                
                <div class="mt-4 description_service_provider">
                    <x-label for="description" value="{{ __('Description') }}" />
                    <x-input class="block mt-1 w-full" type="textarea" name="description" :value="old('description')" autocomplete="description" />
                </div>
                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="mt-4">
                        <x-label for="terms">
                            <div class="flex items-center">
                                <x-checkbox name="terms" id="terms" required />

                                <div class="ms-2">
                                    {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">'.__('Terms of Service').'</a>',
                                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">'.__('Privacy Policy').'</a>',
                                    ]) !!}
                                </div>
                            </div>
                        </x-label>
                    </div>
                @endif

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                        {{ __('Déjà inscrit?') }}
                    </a>

                    <x-button class="ms-4 btn-register">
                        {{ __('Inscription') }}
                    </x-button>
                </div>
                

            </form>
        </div>
    </x-authentication-card>
</x-guest-layout>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    function populateChildCategories() {
        var parentCategoryId = document.getElementById("parentCategory").value;
        var childCategoryDropdown = document.getElementById("childCategory");

        // Clear existing options
        childCategoryDropdown.innerHTML = '<option value="">Select Child Service Types</option>';

        // Check if a parent category is selected
        if (parentCategoryId !== "") {
            // Iterate through categories to find children of selected parent
            @foreach($categories as $category)
                @if(count($category->children) > 0)
                    @foreach($category->children as $child)
                        if ("{{ $category->id }}" === parentCategoryId) {
                            var option = document.createElement("option");
                            option.text = "{{ $child->name }}";
                            option.value = "{{ $child->id }}";
                            childCategoryDropdown.appendChild(option);
                        }
                    @endforeach
                @endif
            @endforeach

            // Show the child category dropdown
            jQuery('.child_category_for_service').show();
            childCategoryDropdown.style.display = "block";
        } else {
            // Hide the child category dropdown if no parent category is selected
            jQuery('.child_category_for_service').hide();
            childCategoryDropdown.style.display = "none";
        }
    }

    function populateSubChildCategories() {
        var parentCategoryId = document.getElementById("childCategory").value;
        var childCategoryDropdown = document.getElementById("subChildCategory");
        childCategoryDropdown.innerHTML = '<option value="">Select Sub Child Service Types</option>';
        if (parentCategoryId !== "") {
            @foreach($categories as $category)
                @if(count($category->children) > 0)
                    @foreach($category->children as $child)
                        if ("{{ $category->id }}" === parentCategoryId) {
                            var option = document.createElement("option");
                            option.text = "{{ $child->name }}";
                            option.value = "{{ $child->id }}";
                            childCategoryDropdown.appendChild(option);
                        }
                    @endforeach
                @endif
            @endforeach

            jQuery('.sub_child_category_for_service').show();
            childCategoryDropdown.style.display = "block";
        } else {
            jQuery('.sub_child_category_for_service').hide();
            childCategoryDropdown.style.display = "none";
        }
    }
    function openTab(evt, tabName) {
        var tabContents = document.getElementsByClassName("tab-content");
        for (var i = 0; i < tabContents.length; i++) {
            tabContents[i].classList.remove("active");
        }
        var tablinks = document.getElementsByClassName("tablink");
        for (var i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }

        document.getElementById(tabName).classList.add("active");
        evt.currentTarget.classList.add("active");
    }
    </script>
