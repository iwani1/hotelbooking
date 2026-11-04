/* -----------------------------------------------------------------------------



File:           JS Core
Version:        1.0
Last change:    00/00/00 
-------------------------------------------------------------------------------- */
;(function($) {

	"use strict";
	gsap.config({
		nullTargetWarn: false,
	});

// lenis-smooth-scroll
	const lenis = new Lenis({
		duration: .8, 
		easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)), 
		direction: 'vertical', 
		smooth: true, 
		smoothTouch: false, 
	});

	function raf(time) {
		lenis.raf(time);
		requestAnimationFrame(raf);
	}
	requestAnimationFrame(raf);


	function TXTheaderSticky() {
		var $window = $(window);
		var lastScrollTop = 0;
		var $header = $('.txa_sticky_header');
		var headerHeight = $header.outerHeight() + 30;

		$window.scroll(function () {
			var windowTop = $window.scrollTop();

			if (windowTop >= headerHeight) {
				$header.addClass('txa_sticky');
			} else {
				$header.removeClass('txa_sticky');
				$header.removeClass('txa_sticky_show');
			}

			if ($header.hasClass('txa_sticky')) {
				if (windowTop < lastScrollTop) {
					$header.addClass('txa_sticky_show');
				} else {
					$header.removeClass('txa_sticky_show');
				}
			}

			lastScrollTop = windowTop;
		});
	}
	TXTheaderSticky();

	$('.open_mobile_menu').on("click", function() {
		$('.mobile_menu_wrap').toggleClass("mobile_menu_on");
	});
	$('.open_mobile_menu').on('click', function () {
		$('body').toggleClass('mobile_menu_overlay_on');
	});
	jQuery(".mobile-main-navigation li.dropdown").append('<span class="dropdown-btn"><i class="fas fa-angle-down"></i></span>'),
	jQuery(".mobile-main-navigation li .dropdown-btn").on("click", function () {
		jQuery(this).hasClass("active")
		? (jQuery(this).closest("ul").find(".dropdown-btn.active").toggleClass("active"), jQuery(this).closest("ul").find(".dropdown-menu.active").toggleClass("active").slideToggle())
		: (jQuery(this).closest("ul").find(".dropdown-btn.active").toggleClass("active"),
			jQuery(this).closest("ul").find(".dropdown-menu.active").toggleClass("active").slideToggle(),
			jQuery(this).toggleClass("active"),
			jQuery(this).parent().find("> .dropdown-menu").toggleClass("active"),
			jQuery(this).parent().find("> .dropdown-menu").slideToggle());
	});
	// Background Image
	$('[data-background]').each(function() {
		$(this).css('background-image', 'url('+ $(this).attr('data-background') + ')');
	});

	$('.marquee-left').marquee({
		gap: 0,
		speed: 60,
		delayBeforeStart: 0,
		direction: 'left',
		duplicated: true,
		pauseOnHover: true,
		startVisible:true,
	});
	$('.marquee-right').marquee({
		gap: 28,
		speed: 40,
		delayBeforeStart: 0,
		direction: 'right',
		duplicated: true,
		pauseOnHover: true,
		startVisible:true,
	});

	$(window).on("scroll", function() {
		if ($(this).scrollTop() > 200) {
			$('.bk-scrollup').fadeIn();
		} else {
			$('.bk-scrollup').fadeOut();
		}
	});
	$('.bk-scrollup').on("click", function()  {
		$("html, body").animate({
			scrollTop: 0
		}, 800);
		return false;
	}); 
	gsap.registerPlugin(ScrollTrigger);
	
	// Animation
	if($('.wow').length){
		var wow = new WOW(
		{
			boxClass:     'wow',
			animateClass: 'animated',
			offset:       0,
			mobile:       true,
			live:         true
		}
		);
		wow.init();
	};
	jQuery('.video_box').magnificPopup({
		disableOn: 200,
		type: 'iframe',
		mainClass: 'mfp-fade',
		removalDelay: 160,
		preloader: false,
		fixedContentPos: false,
	});

	// windows-loaded-before-functions
	document.addEventListener("DOMContentLoaded", function () {
		window.addEventListener('load', function(){


			let preloader = document.querySelector("#preloader");
			if (preloader) {
				preloader.classList.add("preloaded");
				setTimeout(function () {
					preloader.remove();
				}, 1000 ) ;

			}
			setTimeout(function() {
				if($(".bk_hero_title").length) {
					var AGTTitleAni = $(".bk_hero_title");
					if(AGTTitleAni.length == 0) return; gsap.registerPlugin(SplitText); AGTTitleAni.each(function(index, el) {

						el.split = new SplitText(el, { 
							type: "lines,words,chars",
							linesClass: "split-line"
						});

						gsap.set(el, { perspective: 400 });

						if( $(el).hasClass('hero_title_1') ){
							gsap.set(el.split.chars, {
								x: 100,
								opacity: 0,
							});
						}
						if( $(el).hasClass('hero_title_2') ){
							gsap.set(el.split.chars, {
								y: 100,
								opacity: 0,
							});
						}
						if( $(el).hasClass('hero_title_3') ){
							gsap.set(el.split.chars, {
								y: 100,
								scaleY: 0,
								opacity: 0,
								rotationX: 15,
							});
						}
						el.anim = gsap.to(el.split.chars, {
							scrollTrigger: {
								trigger: el,
								start: "top 90%",
								toggleActions: "play reverse play reverse",
								markers: false,
							},
							x: 0,
							y: 0,
							scaleX: 1,
							scaleY: 1,
							opacity: 1,
							duration: 1,
							stagger: .03,
							rotationX: 15,
							delay: .1,
							ease: "power3.inOut",
						});
					});
				}
				const BKHero = gsap.timeline();
				BKHero
				.from(".bk-hero-text .hero-slug", { scaleX: 0,  x: 100, duration: 1, transformOrigin: "left",  ease: "power1.out" })
				.from(".bk-hero-text .btn-wrap", {   y: 100, opacity: 0, duration: 1, transformOrigin: "left",  ease: "power1.out" })


				const BKHero2 = gsap.timeline();
				BKHero2
				.from(".bk-hero2-text .hero-slug", { scaleX: 0,  x: 100, duration: 1, transformOrigin: "left",  ease: "power1.out" })
				.from(".bk-hero2-text .btn-wrap", {   y: 100, opacity: 0, duration: 1, transformOrigin: "left",  ease: "power1.out" })

				const BKHero3 = gsap.timeline();
				BKHero3
				.from(".bk-hero3-content .bg_shape", { height: 0, duration: 1.5, transformOrigin: "top",  ease: "power1.out" })
				.from(".bk-hero3-form .form-wrapper .item-field:nth-child(1)", {   y: 30, opacity: 0, duration: 1, transformOrigin: "top",  ease: "power1.out" },"< = .5")
				.from(".bk-hero3-form .form-wrapper .item-field:nth-child(2)", {   y: 30, opacity: 0, duration: 1, transformOrigin: "top",  ease: "power1.out" },"< = .2")				
				.from(".bk-hero3-form .form-wrapper .item-field:nth-child(3)", {   y: 30, opacity: 0, duration: 1, transformOrigin: "top",  ease: "power1.out" },"< = .2")				
				.from(".bk-hero3-form .form-wrapper .item-field:nth-child(4)", {   y: 30, opacity: 0, duration: 1, transformOrigin: "top",  ease: "power1.out" },"< = .2")								
			}, 700);
		})		
	});
	
	if($('.bk-itm-title').length) {
		var txtheading = $(".bk-itm-title");
		if(txtheading.length == 0) return; gsap.registerPlugin(SplitText); txtheading.each(function(index, el) {
			el.split = new SplitText(el, { 
				type: "lines,words,chars",
				linesClass: "split-line"
			});
			if( $(el).hasClass('bk-itm-anim') ){
				gsap.set(el.split.chars, {
					opacity: .3,
					color: "#C29D68",
					x: "-7",
				});
			}
			el.anim = gsap.to(el.split.chars, {
				scrollTrigger: {
					trigger: el,
					start: "top 92%",
					end: "top 60%",
					markers: false,
					scrub: 1,
				},

				x: "0",
				y: "0",
				color: "inherit",
				opacity: 1,
				duration: .7,
				stagger: 0.2,
			});

		});
	}
	if ($('.bk-service-slider').length > 0 ) {
		var slider = new Swiper('.bk-service-slider', {
			slidesPerView: 3,
			loop: true,
			spaceBetween: 24,
			speed: 1000,
			navigation: {
				nextEl: ".lg-ser-next",
				prevEl: ".lg-ser-prev",
			},
			pagination: {
				el: ".lg-ser-pagi",
				type: 'fraction',
				formatFractionCurrent: function (number) {
					return number < 10 ? '0'+ number: number;
				},
				formatFractionTotal: function (number) {
					return number < 10 ? '0'+ number : number;
				}
			},
			scrollbar: {
				el: '.lg-ser-scrollbar',
				draggable: false,
			},
			breakpoints: {
				'1600': {
					slidesPerView: 3,
				},
				'1200': {
					slidesPerView: 3,
				},
				'991': {
					slidesPerView: 2,
				},
				'768': {
					slidesPerView: 2,
				},
				'576': {
					slidesPerView: 1,
				},
				'0': {
					slidesPerView: 1,
				},
			},
		});
	};

	

	if (window.matchMedia("(min-width: 1200px)").matches) {
		let areas = Array.from(document.querySelectorAll(".bk-testi-area"));
		let itemsToAnimate = areas.slice(1).reverse();
		var BKTesti = gsap.timeline({
			scrollTrigger: {
				trigger: '.bk-testimonial-sec',
				start: "top 0%",
				end: "bottom 30%",
				scrub: 2,
				pin: true,
				pinSpacing: true,
				markers: false,
			}
		});
		itemsToAnimate.forEach(item => {
			BKTesti.to(item, {
				yPercent: -200,
				ease: "power1.out"
			});
		});
	}

	if ($('.bk-ft-spon-slide').length > 0 ) {
		var slider = new Swiper('.bk-ft-spon-slide', {
			spaceBetween: 60,
			slidesPerView: 4,
			loop: true,
			autoplay: {
				enabled: true,
				delay: 1000
			},
			speed: 1000,
			breakpoints: {
				'1600': {
					slidesPerView: 4,
				},
				'1200': {
					slidesPerView: 4,
				},
				'992': {
					slidesPerView: 3,
				},
				'768': {
					slidesPerView: 3,
				},
				'576': {
					slidesPerView: 2,
				},
				'480': {
					slidesPerView: 1,
				},
				'0': {
					slidesPerView: 1,
				},
			},
		});
	}; 


	const buttons = document.querySelectorAll(".bk-btn1 a , .bk-hero-text .btn-wrap a , .bk-hero2-text .btn-wrap a , .bk-gallery-text .btn-wrap a");
	buttons.forEach(btn => {
		const split = new SplitText(btn, { type: "chars" });
		gsap.set(split.chars, { y: 0, opacity: 1 });
		btn.addEventListener("mouseenter", () => {
			gsap.fromTo(
				split.chars,
				{ y: 20, opacity: 0 },
				{
					y: 0,
					opacity: 1,
					duration: 0.4,
					stagger: 0.07,
					ease: "power3.out"
				}
				);
		});
	});

	$('.odometer').appear(function () {
		var $this = $(this); 
		var countNumber = $this.attr("data-count");
		$this.html(countNumber);
	});


	gsap.utils.toArray(".bk-img-app").forEach((el, index) => {
		let tl1 = gsap.timeline({
			scrollTrigger: {
				trigger: el,
				scrub: 1,
				start: "top 80%",
				end: "top 50%",
				toggleActions: "play none none reverse",
				markers: false,
			},
		});

		tl1.from(
			el,
			{  height: 0 },
			{ opacity: 1, duration: 1, immediateRender: false }
			);
	});

	gsap.utils.toArray(".img-parallax").forEach(function(container) {
		let image = container.querySelector("img");

		let tl = gsap.timeline({
			scrollTrigger: {
				trigger: container,
				scrub: true,
				pin: false,
			},
		}); 
		tl.from(image, {
			yPercent: -30,
			ease: "none",
		}).to(image, {
			yPercent: 30,
			ease: "none",
		}); 
	});

	gsap.utils.toArray(' .bk-facilites-sec').forEach((el, index) => { 
		let tlcta = gsap.timeline({
			scrollTrigger: {
				trigger: el,
				scrub: 1.5,
				end: "top 0%",
				start: "top 60%",
				toggleActions: "play none none reverse",
				markers: false
			}
		})

		tlcta
		.from(el, {  marginLeft: "0px",  marginRight: "0px"})
	});

	if (window.matchMedia("(min-width: 1200px)").matches) { 
		var Testi_pin = document.querySelectorAll(".bk-facilites-text")
		Testi_pin.forEach((item) => {
			gsap.to(item, {
				scrollTrigger: {
					trigger: item,
					markers: false,
					pin: true,
					pinSpacing: false,
					start: "top 15%",
					end: "bottom 50%",
				},
			});
		});
	}
	$('.bk-offer-item-wrap .accordion-button').on('click', function () {
		const newImg = $(this).data('img');
		const imgTag = $('.bk-offer-img img');

		imgTag.addClass('fade');

		setTimeout(() => {
			imgTag.attr('src', newImg).removeClass('fade');
		}, 300);
	});

	if ($('.bk-room2-slider').length > 0 ) {
		var slider = new Swiper('.bk-room2-slider', {
			slidesPerView: 5,
			loop: true,
			spaceBetween: 28,
			speed: 1000,
			autoplay: {
				enabled: true,
				delay: 6000
			},
			breakpoints: {
				'1600': {
					slidesPerView: 5,
				},
				'1200': {
					slidesPerView: 4,
				},
				'992': {
					slidesPerView: 3,
				},
				'991': {
					slidesPerView: 2,
				},
				'768': {
					slidesPerView: 2,
				},
				'680': {
					slidesPerView: 2,
				},
				'0': {
					slidesPerView: 1,
				},
			},
		});
	};


	if(window.innerWidth> 992){
		var BKROOMS = gsap.timeline({

			scrollTrigger: {
				trigger: '.bk-room3-sec',
				start: "top 0%",
				end: "top -100%",
				scrub: 1,
				pinSpacing: true,
				pin: ".bk-room3-sec",
			}

		});

		BKROOMS
		.to(".bk-room3-item:is(.item_3)", {yPercent: -200,  duration: 2})
		.to(".bk-room3-img .item_3_img", {yPercent: -200,  duration: 2},"<")
		.to(".bk-room3-item:is(.item_2)", {yPercent: -200,  duration: 2},"< = .8")
		.to(".bk-room3-img .item_2_img", {yPercent: -200,  duration: 2},"<")
	}

	if (window.matchMedia("(min-width: 1200px)").matches) {
		gsap.to(".bk-faci2-title", {
			scrollTrigger: {
				trigger: ".bk-faci2-sec",
				start: "top 0%", 
				end: "bottom bottom", 
				pin: ".bk-faci2-title", 
				pinSpacing: false,
				markers: false
			}
		});
		const items = gsap.utils.toArray('.bk-faci2-item .inner-item');
		items.forEach(animateItem);
		function animateItem(el) {
			gsap.timeline({
				scrollTrigger: {
					trigger: el,
					scrub: 1,
					start: "top 90%",
					end: "bottom 50%",
					toggleActions: "play none none reverse",
					markers: false
				}
			})
			.set(el, {
				perspective: 1000,
				rotatey: "-90deg",
				transformStyle: "preserve-3d",
				transformOrigin: "50% 100%"
			})
			.from(el, {
				scaleY: 0,
				opacity: 0
			});
		}
	}

	if (window.matchMedia("(min-width: 1200px)").matches) {
		var TechHero = gsap.timeline({
			scrollTrigger: {
				trigger: '.bk-testi2-content',
				start: "top 10%",
				end: "top -100%",
				scrub: 1.5,
				pin: true,
				pinSpacing: true,
				markers: false
			}
		});

		TechHero
		.from( ".bk-testi2-item:nth-child(1)" , {yPercent: 50, xPercent: 105, duration: 1, ease: "power1.out" },"<")
		.from( ".bk-testi2-item:nth-child(2)" , {yPercent: 50,  duration: 1, ease: "power1.out" },"<")
		.from( ".bk-testi2-item:nth-child(3)" , {yPercent: 50, xPercent: -105, duration: 1, ease: "power1.out" },"<")
		.from( ".bk-testi2-item:nth-child(4)" , {yPercent: -56, xPercent: 105, duration: 1, ease: "power1.out" },"<")
		.from( ".bk-testi2-item:nth-child(5)" , {yPercent: -56, xPercent: 0, duration: 1, ease: "power1.out" },"<")
		.from( ".bk-testi2-item:nth-child(6)" , {yPercent: -56, xPercent: -105, duration: 1, ease: "power1.out" },"<")
	}



	var EdProg = gsap.timeline({
		scrollTrigger: {
			trigger: ".bk-cus-content",
			start: "top 70%",
			toggleActions: "play reverse play reverse",
			markers: false,
		},
	})
	EdProg
	.from(".bk-cus-item", {
		yPercent: 100,
		opacity: 0,
		ease: "back.out(1.5)",
		duration: 1, 
		stagger: -.2,
	})

	var BKTEAM = gsap.timeline({
		scrollTrigger: {
			trigger: ".bk-team-content",
			start: "top 70%",
			toggleActions: "play reverse play reverse",
			markers: false,
		},
	})
	BKTEAM
	.from(".bk-team-item", {
		xPercent: 100,
		opacity: 0,
		ease: "back.out(1.5)",
		duration: 1, 
		stagger: -.2,
	})

	if($(".bottom-text").length) {
		var aniTitle1 = $(".bottom-text");
		if(aniTitle1.length == 0) return; gsap.registerPlugin(SplitText); aniTitle1.each(function(index, el) {

			el.split = new SplitText(el, { 
				type: "lines,words,chars",
				linesClass: "split-line"
			});

			gsap.set(el, { perspective: 400 });

			if( $(el).hasClass('bottom-text') ){
				gsap.set(el.split.chars, {
					yPercent: 100,
					opacity: 0,

				});
			}
			el.anim = gsap.to(el.split.chars, {
				scrollTrigger: {
					trigger: el,
					start: "top 90%",
					toggleActions: "play reverse play reverse",
					markers: false,

				},

				yPercent: 0,
				xPercent: 0,
				opacity: 1,
				duration: 2,
				stagger: .1,
				ease: "bounce.out",
			});

		});
	}

	$('.bk-blog2-feed-wrap').each(function () {
		var $wrap = $(this);
		var $items = $wrap.find('.bk-blog2-item');

		$items.on('mouseover', function () {
			$items.removeClass('active');
			$(this).addClass('active');
		});
	});


	if (window.matchMedia("(min-width: 1600px)").matches) {
		var TechHero = gsap.timeline({
			scrollTrigger: {
				trigger: ".bk-facility3-sec",
				start: "top 30%",
				end: "top -30%",
				toggleActions: "play none none reverse",
				scrub: true,
				markers: false,
			}

		});

		TechHero
		.from( ".bk-facility3-item:nth-child(1)" , {x: 1200,y: -300,rotate: -4, duration: 3, ease: "power1.out" })
		.from( ".bk-facility3-item:nth-child(2)" , {x: 800,y: -300,rotate: 4, duration: 3, ease: "power1.out" },"<")
		.from( ".bk-facility3-item:nth-child(3)" , {x: 400,y: -300,rotate: -8, duration: 3, ease: "power1.out" },"<")
		.from( ".bk-facility3-item:nth-child(4)" , {x: 0,y: -300,rotate: 8, duration: 3, ease: "power1.out" },"<")

	}


	if ($('.bk-food-slider').length > 0 ) {
		var slider = new Swiper('.bk-food-slider', {
			slidesPerView: 6,
			loop: true,
			spaceBetween: 24,
			speed: 1000,
			autoplay: {
				enabled: true,
				delay: 6000
			},
			breakpoints: {
				'1600': {
					slidesPerView: 6,
				},
				'1200': {
					slidesPerView: 5,
				},
				'992': {
					slidesPerView: 4,
				},
				'991': {
					slidesPerView: 3,
				},
				'768': {
					slidesPerView: 2,
				},
				'680': {
					slidesPerView: 2,
				},
				'0': {
					slidesPerView: 1,
				},
			},
		});
	};

	if ($('.bk-project3-slider').length > 0 ) {
		var slider = new Swiper('.bk-project3-slider', {
			spaceBetween: 0,
			slidesPerView: 1,
			loop: true,
			speed: 1000,
			effect: "fade",
			autoplay: {
				enabled: true,
				delay: 6000
			},
			pagination: {
				el: ".bk-pr3-pagi",
				clickable: true,
				renderBullet: function (index, className) {
					return '<span class="' + className + '">' + (index + 1) + '</span>';
				}
			}

		});
	};



	if ($('.bk-gallery-slider').length > 0 ) {
		var slider = new Swiper('.bk-gallery-slider', {
			slidesPerView: 3,
			loop: true,
			spaceBetween: 24,
			speed: 1000,
			navigation: {
				nextEl: ".bk-gl-next",
				prevEl: ".bk-gl-prev",
			},
			autoplay: {
				enabled: true,
				delay: 6000
			},
			breakpoints: {
				'1600': {
					slidesPerView: 3,
				},
				'1200': {
					slidesPerView: 3,
				},
				'992': {
					slidesPerView: 3,
				},

				'576': {
					slidesPerView: 2,
				},
				'0': {
					slidesPerView: 1,
				},
			},
		});
	};


	const TopView = document.querySelectorAll('.bk-fact-item');

	TopView.forEach((box) => {
		ScrollTrigger.create({
			trigger: box,
			toggleActions: 'play reverse play reverse',
			onEnter: () => box.classList.add('in-view'),
			onLeaveBack: () => box.classList.remove('in-view'),
			markers: false,
		});
	});



	if (window.matchMedia("(min-width: 1200px)").matches) { 
		var Testi_pin = document.querySelectorAll(".bk-blog3-text")
		Testi_pin.forEach((item) => {
			gsap.to(item, {
				scrollTrigger: {
					trigger: item,
					markers: false,
					pin: true,
					pinSpacing: false,
					start: "top 15%",
					end: "bottom 50%",
				},
			});
		});
	}

	$('.zoom-gallery').magnificPopup({
		delegate: 'a',
		type: 'image',
		closeOnContentClick: false,
		closeBtnInside: false,
		mainClass: 'mfp-with-zoom mfp-img-mobile',
		gallery: {
			enabled: true
		},
		zoom: {
			enabled: true,
			duration: 300, 
			opener: function(element) {
				return element.find('img');
			}
		}
	});


	
})(jQuery);