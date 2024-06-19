@include('components.header', ['menu_items' => App\Models\Menu::all()])
<div class="services-sec services-form">
	<div class="services-detial">
	<div class="img-client">
    <img src="{{ env('APP_URL') }}/{{$articles->image}}" alt="service-image">
	</div>
	
	<div class="despti">
	<h1>{{$articles->title}}</h1>
    <button id="addServicesBtn">Ajouter des services</button>
    <form action="" method="post" id="service_form">
        @csrf
        <div id="selectedServices">
            <!-- Selected services will be added here -->
        </div>
        <div id="orderTotal">Total de la commande: $0.00</div>
		
        <div class="appintment_section">    
            <label for="appointment_date">Date de réalisation souhaitée:</label>
            <input type="date" id="appointment_date" name="appointment_date" value="" required>
        </div>
        <h2>Customer Address</h2>
        <div class="address_section">
		   <div class="address-grid-box">	
            @if(!empty($user))
                <div>
                <label for="name">Nom</label>
                <input type="text" name="name" value="{{$user->name}}" readonly required>
                </div>
                <div>
                <label for="email">E-mail</label>
                <input type="email" name="email" value="{{$user->email}}" readonly required>
                </div>
                <div>
                <label for="phone">Téléphone</label>
                <input type="tel" name="phone_number" value="{{$user->phone_number}}" readonly required>
                </div>
            @else
                <div>
                <label for="name">Nom</label>
                <input type="text" name="name" value="" required>
                </div>
                <div>
                <label for="email">E-mail</label>
                <input type="email" name="email" value="" required>
                </div>
                <div>
                <label for="phone">Téléphone</label>
                <input type="tel" name="phone_number" value="" required>
                </div>
            @endif
            <div>
            <label for="state">Ville</label>
                <input type="text" name="state" value="" required>
            </div>
            <div>
            <label for="country">Pays</label>
            <input type="text" name="country" value="" required>
            </div>
			<div>
            <label for="postal_code">Code Postal</label>
            <input type="text" name="postal_code" value="" required>
            </div>
			
			
			
			</div>
			
			<div class="address-grid">
            
			
			<div>
            <label for="address">Adresse</label>
                <textarea name="address" rows="2" required></textarea>
            </div>  
			</div>
            @if(!empty($articles->payment_mode) && $articles->payment_mode == 'online')
			<div class="payment_method"><label for="payment_method">Mode de paiement</label> 
                <div id="card-element"></div>	
            </div>
            @else
            <div class="location-grid">
                <label for="location_home"><b>Lieu de réservation du service?</b></label>
                <input type="radio" name="location" id="location_home" value="Maison" required>
                <label for="location_home">Maison</label>
                
                <input type="radio" name="location" id="location_office" value="Bureau" required>
                <label for="location_office">Bureau</label>
            </div>

            <div class="address-grid">
            <div>
                <label for="cancellation_hour"><b>Délai d'annulation de la commande:<b></label>
                <label for="cancellation_comment"><b>Commentaire:</b></label>
                
            </div>
            <div>
            <p>48 heures</p>
                <textarea name="cancellation" rows="2" required></textarea>
            </div>
            
            </div> 
			@endif
        </div>
        <input type="hidden" name="main_service_id" value="{{$articles->id}}">
        <input type="hidden" name="service_image" value="{{$articles->image}}">
        <div class="error_message">

        </div>
        <input type="hidden" name="payment_mode" value="{{ $articles->payment_mode }}">
        @if(!empty($articles->payment_mode) && $articles->payment_mode == 'online')
        <button type="submit" id="book_appointment">Payez maintenant</button>
        @else
        <button type="submit" id="book_appointment">Demander un devis</button>
        @endif
        <div class="loader" style="display:none;"><img src="{{asset('uploads/loader.gif')}}" alt="loader"></div>
    </form>    
	</div>
	</div>
	
    
    <div id="servicesModal" class="modal" style="display:block;">
        <div class="modal-content">
            <span class="close">ok</span>
            <h2>Prestations de service</h2>
            <ul class="list-box-set">
                @foreach($articles->services as $service)
                @if(!empty($service['name']) && !empty($service['price']))
                    <li data-name="{{$service['name']}}" data-price="{{$service['price']}}">
                        <span>{{$service['name']}} €{{$service['price']}}</span>
                        
                        <label class="switch">
                            <input type="checkbox" name="enableDisableBtn" class="enableDisableBtn" value="0">
                            <span class="slider round"></span>
                        </label>
                    </li>
                    @endif
                @endforeach
           		 </ul>
        	
    	</div>
	</div>
</div>
<!-- jQuery library -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://js.stripe.com/v3/"></script>
<!-- jQuery UI library -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<!-- Bootstrap Datepicker library -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script> -->

<script>
var paymentMode = "{{ $articles->payment_mode }}";
    
if (paymentMode && paymentMode === 'online') {
    var stripe = Stripe('pk_live_51PCHatAE9pFe5El974mJawg9t1dacHAYkBgf5hjJVSZRbRaxGR34xG1pKGDXnVw1O5YAUgigXYBAZg4783h88OGI00jjvGDVsv');
    var elements = stripe.elements();
    var cardElement = elements.create('card');
    cardElement.mount('#card-element');
}
$(document).ready(function() {
  $(document).on('click', '.decrement', function(event) {
    event.preventDefault(); // Prevent default form submission behavior
    console.log('Minus button clicked'); // Check if event listener is triggered
    let quantityInput = $(this).closest('#quantityContainer').find('.quantityInput');
    let currentValue = parseInt(quantityInput.val());
    if (currentValue > 1) {
      quantityInput.val(currentValue - 1);
    }
    calculateTotal();
  });

  $(document).on('click', '.increment', function(event) {
    event.preventDefault(); // Prevent default form submission behavior
    console.log('Plus button clicked'); // Check if event listener is triggered
    let quantityInput = $(this).closest('#quantityContainer').find('.quantityInput');
    let currentValue = parseInt(quantityInput.val());
    quantityInput.val(currentValue + 1);
    calculateTotal();
  });
    // Update total on page load
    calculateTotal();
});
function calculateTotal() {
        var paymentMode = "{{ $articles->payment_mode }}";
        var total = 0;
        // Loop through each selected service
        $('#selectedServices li').each(function() {
            var dataValue = $(this).attr('data-value');
            var parts = dataValue.split('-');
            var price = parseFloat(parts[parts.length - 1]);
            var quantity = parseFloat($(this).find('.quantityInput').val());
            
            total += price * quantity;
        });
        
        // Display the total
        if (!paymentMode || paymentMode === 'offline') {
            $('#orderTotal').text('Order Total: Sur devis');
        }else{
            $('#orderTotal').text('Order Total: €' + total.toFixed(2));
        }
    }
</script>




    <script>
        $(document).ready(function(){
            // var bookedDates = @json($bookedDates);

            // $('#appointment_date').datepicker({
            //     format: 'yyyy-mm-dd',
            //     autoclose: true,
            //     minDate: 0,
            //     beforeShowDay: function(date){
            //         var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
            //         return [bookedDates.indexOf(string) == -1]
            //     },
            //     onSelect: function(dateText, inst) {
            //         // Parse the selected date string
            //         var selectedDate = new Date(dateText);
            //         // Format the selected date as 'mm/dd/yyyy'
            //         var formattedDate = selectedDate.getFullYear() + '-' + ('0' + (selectedDate.getMonth() + 1)).slice(-2) + '-' + ('0' + selectedDate.getDate()).slice(-2);
            //         // Set the formatted date back to the input field
            //         $(this).val(formattedDate);
            //     }
            // });
            var bookedDates = @json($bookedDates);

            var datePicker = $('#appointment_date');

            function enableManualInput() {
                datePicker.prop('readonly', false);
            }

            function disableManualInput() {
                datePicker.prop('readonly', true);
            }

            // Initialize datepicker with booking functionality
            datePicker.datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                minDate: 0,
                beforeShowDay: function(date) {
                    var string = $.datepicker.formatDate('yy-mm-dd', date);
                    return [bookedDates.indexOf(string) === -1];
                },
                onSelect: function(dateText, inst) {
                    // Parse the selected date string
                    var selectedDate = new Date(dateText);
                    // Format the selected date as 'yyyy-mm-dd'
                    var formattedDate = selectedDate.getFullYear() + '-' + ('0' + (selectedDate.getMonth() + 1)).slice(-2) + '-' + ('0' + selectedDate.getDate()).slice(-2);
                    // Set the formatted date back to the input field
                    $(this).val(formattedDate);
                    // Enable manual input after a date is selected
                    enableManualInput();
                }
            });

            // Enable manual input when the input field is clicked
            datePicker.on('click', function() {
                enableManualInput();
            });

            // Disable manual input when the datepicker is opened
            datePicker.on('focus', function() {
                disableManualInput();
            });

            // Enable manual input when the datepicker is closed
            datePicker.on('blur', function() {
                enableManualInput();
            });

            // Validate manually entered date
            datePicker.on('change', function() {
                var enteredDate = $(this).val();
                var isValidDate = bookedDates.indexOf(enteredDate) === -1;
                if (!isValidDate) {
                    alert('This date is already booked. Please select another date.');
                    $(this).val(''); // Clear the input field
                }
            });



            $('#service_form').submit(function(event){
                event.preventDefault(); // Prevent the default form submission

                // Get all <li> elements within the 'selectedServices' div
                var liElements = document.querySelectorAll('#selectedServices li');

                // Create an array to store the data-values
                var dataValues = [];

                // Loop through each <li> element and extract its data-value attribute
                liElements.forEach(function(li) {
                    var dataValue = li.getAttribute('data-value');
                    dataValues.push(dataValue);
                });
                if(liElements.length == 0){
                    $('.error_message').html('<p style="color:#D63638">Please select atleast one service to proceed.</p>')
                }else{
                    var form_data = $(this).serialize();
                    var paymentMode = "{{ $articles->payment_mode }}";
                    if (!paymentMode || paymentMode === 'offline') {
                        $.ajax({
                            url: '{{ route('insert.appointment_data') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                dataValues: dataValues,
                                form_data: form_data
                            },
                            beforeSend: function() {
                                $('.loader').show();
                            },
                            success: function(response) {
                                // Handle success, if needed
                                if(response.success == true){
                                    window.location.href = '{{ route('success.appointment_data', ['order_id' => ':order_id']) }}'
                                        .replace(':order_id', response.orderid);
                                }
                                else{
                                    $('.error_message').html('<p style="color:#D63638">'+response.message+'</p>');
                                    $('.loader').hide();
                                }
                            },
                            error: function(xhr, status, error) {
                                // Handle error, if needed
                            }
                        });
                    }else{
                        stripe.createToken(cardElement).then(function(result) {
                        
                            if (result.error) {
                                // Inform the user if there was an error
                                alert(result.error.message);
                            } else {
                                // Insert the token ID into the form so it gets submitted to the server
                                var token = result.token.id;
                                var formDataWithToken = form_data + '&stripeToken=' + token;

                                // Send form data and token to server
                                $.ajax({
                                    url: '{{ route('insert.appointment_data') }}',
                                    type: 'POST',
                                    data: {
                                        _token: '{{ csrf_token() }}',
                                        dataValues: dataValues,
                                        form_data: formDataWithToken
                                    },
                                    beforeSend: function() {
                                        $('.loader').show();
                                    },
                                    success: function(response) {
                                        // Handle success, if needed
                                        if(response.success == true){
                                            window.location.href = '{{ route('success.appointment_data', ['order_id' => ':order_id']) }}'
                                                .replace(':order_id', response.orderid);
                                        }
                                        else{
                                            $('.error_message').html('<p style="color:#D63638">'+response.message+'</p>');
                                            $('.loader').hide();
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        // Handle error, if needed
                                    }
                                });
                            }
                        });
                    }
                }
            })
        });
    </script>
    <script>
    document.getElementById("addServicesBtn").addEventListener("click", function() {
        document.getElementById("servicesModal").style.display = "block";
    });

    // When the user clicks on <span> (x), close the modal
    document.querySelector(".close").addEventListener("click", function() {
        document.getElementById("servicesModal").style.display = "none";
    });

    // When the user clicks anywhere outside of the modal, close it
    window.addEventListener("click", function(event) {
        if (event.target == document.getElementById("servicesModal")) {
            document.getElementById("servicesModal").style.display = "none";
        }
    });

    // Enable/disable button functionality
    document.querySelectorAll(".enableDisableBtn").forEach(function(btn) {
        btn.addEventListener("click", function() {
            var li = this.closest('li[data-name][data-price]');
            var serviceName = li.getAttribute("data-name");
            var servicePrice = li.getAttribute("data-price");
            // var quantity = li.querySelector(".quantityInput").value;
            var serviceID = serviceName.replace(/\s+/g, '_').replace(/[^a-zA-Z0-9_]/g, '') + "-" + servicePrice.toString().replace(/\./g, '_'); // Unique identifier for each service
          
            if (this.value === "0") {
                var serviceList = document.createElement("ul");
                var serviceItem = document.createElement("li");
				serviceList.id=serviceID;
                serviceList.setAttribute("class",'quantity-container');
                serviceItem.setAttribute("data-value", serviceName+ "-" + servicePrice);  // Set the unique ID as the element's ID
                serviceItem.innerHTML = '<div class="title-quantity">'+serviceName + " - €" + servicePrice + '</div><div class="quantity-control" id="quantityContainer"><button class="decrement">-</button><input type="number" min="1" value="1" name="'+serviceID+'" class="quantityInput" style="width: 50px;"><button class="increment">+</button></div>';
                serviceList.appendChild(serviceItem);
                document.getElementById("selectedServices").appendChild(serviceList);
                this.value = "1";
            } else {
                // Remove the service from the selected services list using its ID
                var serviceItemToRemove = document.getElementById(serviceID);
                if (serviceItemToRemove) {
                    serviceItemToRemove.parentNode.removeChild(serviceItemToRemove);
                }
                this.value = "0";
            }
            calculateTotal();
        });
    });


</script>
@include('components.footer')


