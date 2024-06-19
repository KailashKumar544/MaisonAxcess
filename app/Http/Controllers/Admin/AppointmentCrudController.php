<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AppointmentRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AppointmentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AppointmentCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Appointment::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/appointment');
        CRUD::setEntityNameStrings('appointment', 'appointments');
        
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // Set up columns from the database
        CRUD::setFromDb();
        $this->crud->removeButton('create');
        $this->crud->removeButton('update');
        // Customize the 'service_provider_id' column to display the service provider's name
        $this->crud->modifyColumn('service_provider_id', [
            'label' => 'Service Provider',
            'type' => 'select',
            'name' => 'service_provider_id',
            'entity' => 'serviceProvider', // Relationship method name
            'attribute' => 'name', // Attribute to display (assuming 'name' is the column name in the users table)
        ]);

        $this->crud->addFilter([
            'type' => 'select2',
            'name' => 'service_provider_id',
            'label' => 'Service Provider',
            'placeholder' => 'Select Service Provider',
        ], function() {
            return \App\Models\User::pluck('name', 'id')->all();
        }, function($value) {
            $this->crud->addClause('where', 'service_provider_id', $value);
        });
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(AppointmentRequest::class);
        CRUD::setFromDb(); // set fields from db columns.

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
        $this->crud->addField([
            'name' => 'service_provider_id',
            'label' => 'Service Provider',
            'type' => 'select',
            'entity' => 'serviceProvider', // Relationship method name
            'attribute' => 'name', // Attribute to display (assuming 'name' is the column name in the users table)
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        // Set up fields from the database
        CRUD::setFromDb();
        $this->crud->removeButton('update');
        // Customize the 'service_provider_id' field to display the service provider's name
        $this->crud->modifyColumn('service_provider_id', [
            'label' => 'Service Provider',
            'type' => 'select',
            'name' => 'service_provider_id',
            'entity' => 'serviceProvider', // Relationship method name
            'attribute' => 'name', // Attribute to display (assuming 'name' is the column name in the users table)
        ]);
        
    }
}
