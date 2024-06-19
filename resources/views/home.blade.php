@include('components.header', ['menu_items' => App\Models\Menu::all()])

<div id="content" class="site-content">

<div class="hero-slider">
	<div class="home-demo">
  		<div class="owl-carousel owl-theme">
			<div class="item"><img src="{{ asset('uploads/Banner 1.jpg') }}"/></div>
			<div class="item"><img src="{{ asset('uploads/Banner 2.jpg') }}"/></div>
			<div class="item"><img src="{{ asset('uploads/banner 3.jpg') }}"/></div>
  		</div>
	</div>
</div>
<div class="body-content" style="max-width: 1300px; margin: auto; display: block;">
      
<div class="services-list">
<h2 class="green-title">Les prestations de conciergerie sur site :</h2>
<div class="services-box">
<div>
<div class="s-image"><a href="https://maisonaxcess.com/service_types/pressing-et-blanchisserie" style="text-decoration: unset; color: black;"><img src="https://maisonaxcess.com/uploads/services%20icons/pressing%20et%20blanchisserie.png" alt="pressing et blanchisserie.png" width="100"></div>
<p>Pressing et blanchisserie</p>
</a></div>
<div>
<div class="s-image"><img src="https://maisonaxcess.com/uploads/services%20icons/reparation%20et%20entretien%20ve%CC%81lo.png" alt="pressing et blanchisserie.png" width="100"></div>
<p>Réparation et entretien vélo</p>
</div>
<div>
<div class="s-image"><a href="https://maisonaxcess.com/service_types/retouche"><img src="https://maisonaxcess.com/uploads/services%20icons/retouche%20et%20couture.png" alt="pressing et blanchisserie.png" width="100"></a></div>
<a style="text-decoration: unset; color: black;" href="https://maisonaxcess.com/service_types/retouche">
<p>Retouche et couture</p>
</a></div>
<div>
<div class="s-image"><img src="https://maisonaxcess.com/uploads/services%20icons/course%20de%20proximite%CC%81.png" alt="pressing et blanchisserie.png" width="100"></div>
<p>Courses de proximité</p>
</div>
<div>
<div class="s-image"><img src="https://maisonaxcess.com/uploads/services%20icons/envoi%20et%20re%CC%81ception%20de%20colis.png" alt="pressing et blanchisserie.png" width="100"></div>
<p>Envoi et réception de colis</p>
</div>
<div>
<div class="s-image"><img src="https://maisonaxcess.com/uploads/services%20icons/administratif.png" alt="pressing et blanchisserie.png" width="100"></div>
<p>Administratif</p>
</div>
<div>
<div class="s-image"><img src="https://maisonaxcess.com/uploads/services%20icons/IT%20la.png" alt="pressing et blanchisserie.png" width="100"></div>
<p>IT La.</p>
</div>
<div>
<div class="s-image"><a href="https://maisonaxcess.com/service_types/cles-de-serrures"><img src="https://maisonaxcess.com/uploads/services%20icons/serrurerie.png" alt="pressing et blanchisserie.png" width="100"></a></div>
<a style="text-decoration: unset; color: black;" href="https://maisonaxcess.com/service_types/cles-de-serrures">
<p>Serrurerie</p>
</a></div>
<div>
<div class="s-image"><a href="https://maisonaxcess.com/service_types/coordonnerie"><img src="https://maisonaxcess.com/uploads/services%20icons/coordonnerie.png" alt="pressing et blanchisserie.png" width="100"></a></div>
<a style="text-decoration: unset; color: black;" href="https://maisonaxcess.com/service_types/coordonnerie">
<p>Cordonnerie</p>
</a></div>
<div>
<div class="s-image"><img src="https://maisonaxcess.com/uploads/services%20icons/maroquinerie.png" alt="pressing et blanchisserie.png" width="100"></div>
<p>Maroquinerie</p>
</div>
</div>
<div class="services-list2">
<h2 class="green-title">Conciergerie digitale</h2>
<p>L’offre de conciergerie <strong>Axcess</strong> transcende les limites de votre lieu de travail. Grâce à notre application mobile dédiée, vos collaborateurs peuvent facilement accéder à toute notre gamme de produits et services, ainsi qu’à des informations pratiques, où qu’ils soient et à tout moment, en quelques touches seulement.</p>
<div class="services-box2">
<div>
<div class="s-image"><img src="https://maisonaxcess.com/uploads/services%20icons2/plane.png" alt="pressing et blanchisserie.png" width="100"></div>
<p>Reservations</p>
</div>
<div>
<div class="s-image"><img src="https://maisonaxcess.com/uploads/services%20icons2/faces.png" alt="pressing et blanchisserie.png" width="100"></div>
<p>Loisirs et activités</p>
</div>
<div>
<div class="s-image"><img src="https://maisonaxcess.com/uploads/services%20icons2/feet.png" alt="pressing et blanchisserie.png" width="100"></div>
<p>Garde d'animaux</p>
</div>
<div>
<div class="s-image"><img src="https://maisonaxcess.com/uploads/services%20icons2/home.png" alt="pressing et blanchisserie.png" width="100"></div>
<p>Services à domicile</p>
</div>
<div>
<div class="s-image"><img src="https://maisonaxcess.com/uploads/services%20icons2/scotter.png" alt="pressing et blanchisserie.png" width="100"></div>
<p>Loisirs et activités.</p>
</div>
<div>
<div class="s-image"><img src="https://maisonaxcess.com/uploads/services%20icons2/chat.png" alt="pressing et blanchisserie.png" width="100"></div>
<p>Cours particuliers</p>
</div>
</div>
</div>

</div> <!-- Render HTML content -->
    </div>
<div class="welcome-section">
<div class="container">
	<div class="service-box1 box">
		<h3>MA CONCIERGERIE</h3>
			<div class="services-logo">
				<img src="{{ asset('uploads/urbanlogo.JPEG') }}"/>
			</div>
			<div class="address">	
				Horaires d'ouverture
                Du lundi au vendredi
				7h30- 18h30
			</div>
			<div class="number">
			<svg fill="var(--pink)" xmlns="http://www.w3.org/2000/svg" style="width:1em;height:1em" viewBox="0 0 24 24"><path d="M19.425 20.45q-2.9 0-5.762-1.388-2.863-1.387-5.113-3.637-2.25-2.25-3.637-5.1-1.388-2.85-1.388-5.775 0-.45.3-.75t.75-.3h3.25q.4 0 .687.237.288.238.363.613L9.45 7.3q.05.375-.025.675-.075.3-.325.525l-2.3 2.25q1.2 2 2.837 3.625Q11.275 16 13.35 17.2l2.225-2.25q.25-.25.588-.35.337-.1.687-.05l2.775.575q.375.075.613.35.237.275.237.675v3.25q0 .45-.3.75t-.75.3ZM6.1 9.325l1.775-1.7q.05-.05.063-.113.012-.062-.013-.112L7.5 5.15q-.025-.075-.075-.113Q7.375 5 7.3 5H5.175q-.075 0-.113.037-.037.038-.037.088.075 1.025.338 2.087.262 1.063.737 2.113Zm8.65 8.575q1 .475 2.075.725 1.075.25 2.025.275.05 0 .088-.038.037-.037.037-.087v-2.1q0-.075-.037-.125-.038-.05-.113-.075l-2.1-.425q-.05-.025-.1 0l-.1.05ZM6.1 9.325Zm8.65 8.575Z"></path></svg>
			<a href="tel:0618030036">06 18 03 00 36</a>
			</div>
			<div class="number">
			<svg fill="var(--pink)" xmlns="http://www.w3.org/2000/svg" style="width:1em;height:1em" viewBox="0 0 24 24"><path d="M4.3 19.5q-.75 0-1.275-.525Q2.5 18.45 2.5 17.7V6.3q0-.75.525-1.275Q3.55 4.5 4.3 4.5h15.4q.75 0 1.275.525.525.525.525 1.275v11.4q0 .75-.525 1.275-.525.525-1.275.525Zm7.7-6.95-8-5.1V17.7q0 .125.088.213.087.087.212.087h15.4q.125 0 .213-.087.087-.088.087-.213V7.45ZM12 11l7.85-5H4.15ZM4 7.45V6v11.7q0 .125.088.213.087.087.212.087H4v-.3Z"></path></svg>
			<a href="mailto:pierre@agence-axcess.com">conciergerie-vb@urbanstation.fr</a>
			</div>
			
			

	</div>
<div class="service-box2 box">
<h3>COMMANDES EN COURS (0)</h3>
<div class="service-two-massage">Aucune commande en cours</div>
</div>

</div>
</div>

</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
<!-- Include jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Include Owl Carousel JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<script>
    $(document).ready(function(){
        $('.owl-carousel').owlCarousel({
            loop:true,
            margin:10,
            nav:false,
            autoplay:true,
            autoplayTimeout:5000,
            autoplayHoverPause:true,
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:1
                },
                1000:{
                    items:1
                }
            }
        });
    });
</script>

@include('components.footer')