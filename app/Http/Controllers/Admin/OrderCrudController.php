<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OrderRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class OrderCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class OrderCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Order::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/order');
        CRUD::setEntityNameStrings('order', 'orders');
      
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // set columns from db columns.
        $this->crud->removeButton('create');
        $this->crud->removeButton('update');
        $this->crud->removeColumn('appointment_id');
        $this->crud->removeColumn('services_with_price');
        $this->crud->removeColumn('customer_address');
        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
        $this->crud->modifyColumn('service_provider_id', [
            'label' => 'Service Provider',
            'type' => 'select',
            'name' => 'service_provider_id',
            'entity' => 'serviceProvider', // Relationship method name
            'attribute' => 'name', // Attribute to display (assuming 'name' is the column name in the users table)
        ]);
        $this->crud->modifyColumn('user_id', [
            'label' => 'User',
            'type' => 'select',
            'name' => 'user_id',
            'entity' => 'customerName', // Relationship method name
            'attribute' => 'name', // Attribute to display (assuming 'name' is the column name in the users table)
        ]);
        $this->crud->modifyColumn('total_price',[
            'name' => 'total_price',
            'label' => 'Order Total',
            'type' => 'text',
            'prefix' => '€'
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(OrderRequest::class);
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

        $this->crud->addField([
            'label' => 'User',
            'type' => 'select',
            'name' => 'user_id',
            'entity' => 'customerName', // Relationship method name
            'attribute' => 'name',  // Attribute to display (assuming 'name' is the column name in the users table)
        ]);
        $this->crud->removeColumn('appointment_id');
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
        $this->crud->removeColumn('appointment_id');
    }

    protected function setupShowOperation()
    {
        // Set up fields from the database
        CRUD::setFromDb();
        $this->crud->removeButton('update');
        $order = $this->crud->getCurrentEntry();
        $services_with_price = json_decode($order->services_with_price, true);

        // Customize the 'service_provider_id' field to display the service provider's name
        $this->crud->modifyColumn('service_provider_id', [
            'label' => 'Service Provider',
            'type' => 'select',
            'name' => 'service_provider_id',
            'entity' => 'serviceProvider', // Relationship method name
            'attribute' => 'name', // Attribute to display (assuming 'name' is the column name in the users table)
            'wrapper' => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('user/' . $entry->user_id . '/show');
                },
            ],
        ]);
        $this->crud->modifyColumn('user_id', [
            'label' => 'User',
            'type' => 'select',
            'name' => 'user_id',
            'entity' => 'customerName', // Relationship method name
            'attribute' => 'name', // Attribute to display (assuming 'name' is the column name in the users table)
            'wrapper' => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('user/' . $entry->service_provider_id . '/show');
                },
            ],
        ]);

        $this->crud->modifyColumn('user_id', [
            'label' => 'Cancellation Comment',
            'type' => 'textarea',
            'name' => 'cancellation_comment',
        ]);

        if($order->payment_info !== 'cod'){
            $this->crud->modifyColumn('total_price', [
                'label' => 'Order Total',
                'type' => 'text',
                'name' => 'total_price',
                'prefix' => '€'
            ]);
        }
        
        
        $this->crud->removeColumn('appointment_id');
        
    }

}
