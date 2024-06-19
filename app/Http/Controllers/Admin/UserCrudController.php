<?php

namespace App\Http\Controllers\Admin;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\PermissionManager\app\Http\Controllers\UserCrudController as BackpackUserCrudController;
use App\Models\Category;

class UserCrudController extends BackpackUserCrudController
{

    public function setupListOperation()
    {
        // This takes care to add all fields from the package. If you need some field removed you could use CRUD::removeField
        parent::setupListOperation();

        // $this->crud->addColumn([
        //     'name' => 'category',
        //     'label' => 'Service Types',
        //     'type' => 'select',
        //     'entity' => 'category',
        //     'attribute' => 'name',
        //     'model' => "App\Models\Category",
        // ]);
        $this->crud->addColumn([
            'name' => 'category_names',
            'label' => 'Service Types',
            'type' => 'text', 
        ]);
        
        $this->crud->addColumn([
            'name' => 'phone_number',
            'label' => 'Phone Number',
            'type' => 'text',
            'sortable' => false, // Ensure it's not sortable
            'searchable' => false, // Ensure it's not searchable
        ]);

        // $this->crud->addFilter([
        //     'type' => 'select2',
        //     'name' => 'category_id',
        //     'label' => 'Service Types',
        //     'placeholder' => 'Select category',
        // ], function() {
        //     return Category::pluck('name', 'id')->all();
        // }, function($value) {
        //     $this->crud->addClause('where', 'category_id', $value);
        // });
    }

    public function setupCreateOperation()
    {
        // This takes care to add all fields from the package. If you need some field removed you could use CRUD::removeField
        parent::setupCreateOperation();
        CRUD::field('phone_number')->type('number');
        $categories = Category::all()->pluck('name', 'id')->toArray();

        $this->crud->addField([
            'name' => 'categories', 
            'label' => 'Service Types', 
            'type' => 'select_multiple', 
            'model' => "App\Models\Category",
            'options' => function () {
                return Category::all()->pluck('name', 'id')->toArray();
            }, 
        ]);
        // Any other fields that you need
        $this->crud->addField([
            'name' => 'profile_photo_path',
            'label' => 'Image',
            'type' => 'browse',
        ]);
        $this->crud->addField([
            'name' => 'description',
            'label' => 'Description',
            'type' => 'tinymce',
        ]);
    }

    public function setupUpdateOperation()
    {
        // This takes care to add all fields from the package. If you need some field removed you could use CRUD::removeField
        parent::setupUpdateOperation();
        CRUD::field('phone_number')->type('number');

        $entry = $this->crud->getCurrentEntry();
        $categories = $entry->category_id ? explode(',', $entry->category_id) : [];

        $this->crud->addField([
            'name' => 'categories', 
            'label' => 'Service Types', 
            'type' => 'select_multiple', 
            'model' => "App\Models\Category",
            'options' => function () {
                return Category::all()->pluck('name', 'id')->toArray();
            }, 
            'value' => $categories,
        ]);
        $this->crud->addField([
            'name' => 'profile_photo_path',
            'label' => 'Image',
            'type' => 'browse',
        ]);

        $this->crud->addField([
            'name' => 'description',
            'label' => 'Description',
            'type' => 'tinymce',
        ]);
    }

    public function setupShowOperation()
    {
        parent::setupShowOperation();
        $this->crud->addColumn([
            'name' => 'category_names',
            'label' => 'Service Types',
            'type' => 'closure',
            'function' => function ($entry) {
                $categoryIds = explode(',', $entry->category_id);
                $categoryNames = \App\Models\Category::whereIn('id', $categoryIds)->pluck('name')->toArray();
                return implode(', ', $categoryNames);
            },
        ]);
        

        $this->crud->addColumn([
            'name' => 'phone_number',
            'label' => 'Phone Number',
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'description',
            'label' => 'Description',
            'type' => 'textarea',
        ]);
        $this->crud->removeColumn('updated_at');
    }

    public function store()
    {
        // Validate the request
        $this->crud->setRequest($this->crud->validateRequest());

        // Handle any additional input processing (if required)
        $this->crud->setRequest($this->handlePasswordInput($this->crud->getRequest()));

        // Unset validation since it has already been run
        $this->crud->unsetValidation(); 

        // Retrieve the selected category IDs from the request
        $categoryIds = $this->crud->getRequest()->input('categories', []);

        // Convert the category IDs to a comma-separated string
        $categoryIdsString = implode(',', $categoryIds);

        // Add the category IDs to the request data
        $this->crud->getRequest()->merge(['category_id' => $categoryIdsString]);

        // Call traitStore() to perform the regular store operation
        $response = $this->traitStore();

        // Check if the store operation was successful
        if ($response->getStatusCode() === 302) {
            // Retrieve the newly created user
            $user = $this->crud->entry;

            // Update the user with the category IDs
            $user->update(['category_id' => $categoryIdsString]);
        }

        // Return the store operation response
        return $response;
    }
    
    public function update()
    {
        // Validate the request
        $this->crud->setRequest($this->crud->validateRequest());
        
        // Handle any additional input processing (if required)
        $this->crud->setRequest($this->handlePasswordInput($this->crud->getRequest()));

        // Unset validation since it has already been run
        $this->crud->unsetValidation(); 

        // Retrieve the user
        $user = $this->crud->getCurrentEntry();

        // Retrieve the selected category IDs from the request
        $categoryIds = $this->crud->getRequest()->input('categories', []);

        // Convert the category IDs to a comma-separated string
        $categoryIdsString = implode(',', $categoryIds);

        // Update the user with the category IDs
        $user->update([
            'category_id' => $categoryIdsString,
        ]);

        // Call traitUpdate() to perform the regular update operation
        return $this->traitUpdate();
    }
}
