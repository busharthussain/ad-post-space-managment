<!DOCTYPE html PUBLIC >
<html>
<head>
    <meta charset="utf-8"/>
    <!-- IE Compatibility Meta -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- First Mobile Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tech Wizno</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800%7CRaleway:100,200,300,400,500,600,700,800,900" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{!! asset('css/bootstrap.css') !!}"/>
    <link rel="stylesheet" type="text/css" href="{!! asset('css/font-awesome.min.css') !!}"/>
    <link rel="stylesheet" type="text/css" href="{!! asset('css/ionicons.css') !!}"/>
    <link rel="stylesheet" type="text/css" href="{!! asset('css/owl.carousel.min.css') !!}"/>
    <link rel="stylesheet" type="text/css" href="{!! asset('css/owl.theme.default.min.css') !!}"/>
    <link rel="stylesheet" type="text/css" href="{!! asset('css/lity.min.css') !!}"/>
    <link rel="stylesheet" type="text/css" href="{!! asset('css/lightbox.css') !!}"/>
    <link rel="stylesheet" type="text/css" href="{!! asset('css/style.css') !!}"/>
    <link rel="stylesheet" type="text/css" href="{!! asset('css/media.css') !!}"/>
    <link rel="stylesheet" type="text/css" href="{!! asset('css/animate.css') !!}"/>

    {!! HTML::script('js/jquery-1.12.4.min.js') !!}
    {!! HTML::script('js/html5shiv.min.js') !!}
    {!! HTML::script('js/respond.min.js') !!}
</head>

<body>

<!-- Start  Loading Section -->
<div class="loading-overlay">
    <div class="spinner">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>
<!-- end loading section -->

<!-- start header -->
<header id="pageHeader" class="header navbar">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#myNavbar" aria-expanded="false">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Akser</a>
        </div>
        <nav id="myNavbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="#home">HOME</a></li>
                <li><a href="#about">ABOUT</a></li>
                <li><a href="#services">SERVICES</a></li>
                <li><a href="#work">WORK</a></li>
                <li><a href="#blog">BLOG</a></li>
                <li><a href="#contact">CONTACT</a></li>
            </ul>
        </nav>
    </div>
</header>
<!-- end header -->

<!-- start section home -->
<section class="home" id="home">
    <div class="overlay">
        <div class="title">
            <h1>We Are <span class="default-color">Akser</span> <br> Creative One Page Parallax</h1>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text .</p>
            <a href="#about" class="a-btn default-background first">Read More</a>
            <a href="#services" class="a-btn second">Our Services</a>
        </div>
    </div>
</section>
<!-- end section home -->

<!-- start section paragraph -->
<section class="paragraph sec-padding">
    <div class="container">
        <p >Lorem Ipsum is simply dummy text of the <strong>printing</strong> and typesetting industry. Lorem Ipsum has been the <strong>industry's</strong> standard dummy text ever since the 1500s, when an unknown printer took a <strong>galley</strong>.</p>
    </div>
</section>
<!-- end section paragraph -->

<!-- start section about -->
<section class="about sec-padding" id="about">
    <div class="container">
        <h1 class="heading">
            <span class="first">Words About Us</span>
            <br>
            <span class="second">We</span> Are Akser
        </h1>
        <p class="para">Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printern.</p>
        <div class="row">
            <div class="col-sm-6">
                <div class="words">
                    <h2>We Are The Best Designers</h2>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley.</p>
                    <a href="#" class="a-btn default-background">Read More</a>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="image">
                    <img alt="about" src="images/about.jpg" class="img-responsive">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end section about -->

<!-- start section skills -->
<section class="skills sec-padding">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="item">
                    <h5>Web Design</h5>
                    <div class="skills-progress"><span class="default-background" data-value='95%'></span></div>
                </div>
                <div class="item">
                    <h5>Graphic Design</h5>
                    <div class="skills-progress"><span class="default-background" data-value='85%'></span></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="item">
                    <h5>Web Development</h5>
                    <div class="skills-progress">
                        <span class="default-background" data-value='90%'></span>
                    </div>
                </div>
                <div class="item">
                    <h5>Photography</h5>
                    <div class="skills-progress"><span class="default-background" data-value='80%'></span></div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end section skills -->

<!-- start section services-tabs -->
<section class="services-tabs sec-padding">
    <div class="container">
        <h1 class="heading">
            <span class="second">Additional</span> Skills
        </h1>
        <p class="para">Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printern.</p>
        <ul class="mytabs list-unstyled">
            <li id="tab1" class="active"> <i class="fa fa-laptop"></i> Web Design</li>
            <li id="tab2"><i class="fa fa-code"></i> Web Development</li>
            <li id="tab3"><i class="fa fa-camera"></i> Photography</li>
            <li id="tab4"><i class="fa fa-phone"></i> Mobile App</li>
        </ul>
        <div class="box">
            <div id="tab1-content">
                <div class="row">
                    <div class="col-sm-4">
                        <img class="img-responsive" alt="image" src="images/k-32-working.jpg">
                    </div>
                    <div class="col-sm-8">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. </p>
                        <a href="#" class="a-btn default-background">Read More</a>
                    </div>
                </div>
            </div>
            <div id="tab2-content">
                <div class="row">
                    <div class="col-sm-4">
                        <img class="img-responsive" alt="image" src="images/notebook-hero-workspace-minimal.jpg">
                    </div>
                    <div class="col-sm-8">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. </p>
                        <a href="#" class="a-btn default-background">Read More</a>
                    </div>
                </div>
            </div>
            <div id="tab3-content">
                <div class="row">
                    <div class="col-sm-4">
                        <img class="img-responsive" alt="image" src="images/pexels-photo-214011.jpg">
                    </div>
                    <div class="col-sm-8">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. </p>
                        <a href="#" class="a-btn default-background">Read More</a>
                    </div>
                </div>
            </div>
            <div id="tab4-content">
                <div class="row">
                    <div class="col-sm-4">
                        <img class="img-responsive" alt="image" src="images/pexels-photo-775091.jpg">
                    </div>
                    <div class="col-sm-8">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. </p>
                        <a href="#" class="a-btn default-background">Read More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end section services-tabs -->

<!-- start section three-boxs -->
<section class="three-boxs">
    <div class="container-fluid">
        <div class="col-md-4 col-xs-12 first">
        </div>
        <div class="col-md-offset-4 col-md-4 col-xs-12" style="padding: 70px 15px">
            <h2>Be branding</h2>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,</p>
            <a href="#" class="a-btn">Read More</a>
        </div>
        <div class="col-md-4 col-xs-12 second">
        </div>
    </div>
</section>
<!-- end section three-boxs -->

<!-- start section three-boxs -->
<section class="three-boxs">
    <div class="container-fluid">
        <div class="col-md-4 col-xs-12" style="padding: 70px 15px">
            <h2>Be Creative</h2>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,</p>
            <a href="#" class="a-btn">Read More</a>
        </div>
        <div class="col-md-4 col-xs-12 third">
        </div>
        <div class="col-md-offset-4 col-md-4 col-xs-12" style="padding: 70px 15px">
            <h2>Be Ambitious</h2>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,</p>
            <a href="#" class="a-btn">Read More</a>
        </div>
    </div>
</section>
<!-- start section three-boxs -->

<!-- start section services -->
<section class="services sec-padding" id="services">
    <div class="container">
        <h1 class="heading">
            <span class="first">What We Do? </span>
            <br>
            <span class="second">Our</span> Services
        </h1>
        <p class="para">Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printern.</p>
        <div class="row">
            <div class="col-md-4 col-sm-6">
                <div class="item wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.4s">
                    <i class="fa fa-laptop"></i>
                    <h2>Web Design</h2>
                    <p>
                        Our Tech wizno have years of experience designing websites that are as good looking as they are hard working. We'll design your site so it works flawlessly on all the latest devices and gives your business an edge over the competition.
                    </p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="item wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.6s">
                    <i class="fa fa-code"></i>
                    <h2>Web Development</h2>
                    <p>
                        Web development is where our Tech wizno really shines. We are experts in PHP5, HTML5 and JAVA… and we write clean code that's consistent with best practices, so your site will be sustainable for years.
                    </p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="item wow fadeInUp" data-wow-duration="1s" data-wow-delay="1.2s">
                    <i class="fa fa-camera"></i>
                    <h2>IPHONE & ANDRIOD APPS</h2>
                    <p>
                        With the average person spending about 30 hours per month on mobile apps, having a presence in mobile is increasingly important to your business success. Let us help your business capitalize on this fast-growing market.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end section services -->

<!-- start section why -->
<section class="why">
    <div class="back">
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-offset-6 col-md-6 col-sm-12 col-xs-12">
                <div class="box">
                    <span>Advanced Features </span>
                    <h1>Why Choose <span class="default-color">Akser</span></h1>
                    <p class="last">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                    <div class="col-sm-6">
                        <div class="item">
                            <i class="icon ion-android-download"></i>
                            <h2>Free Updates</h2>
                            <p>when an unknown printer took a galley of type and scrambled  .</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="item">
                            <i class="icon ion-settings"></i>
                            <h2>Helping Support</h2>
                            <p>when an unknown printer took a galley of type and scrambled  .</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="item">
                            <i class="icon ion-clipboard"></i>
                            <h2>Well Documented</h2>
                            <p>when an unknown printer took a galley of type and scrambled  .</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="item">
                            <i class="icon ion-code-working"></i>
                            <h2>Clean Code</h2>
                            <p>when an unknown printer took a galley of type and scrambled  .</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end section why -->

<!-- start section video -->
<section class="video sec-padding">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="words">
                    <span>Who is Akser?</span>
                    <h1>Know Our Story</h1>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s .</p>
                    <h5>
                        <a title="our video" class="link default-background" href="https://www.youtube.com/watch?v=uQBL7pSAXR8" data-lity>
                            <i class="fa fa-play"></i>
                        </a> Watch the video
                    </h5>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="image">
                    <img alt="image" src="images/k-55-reading.jpg" class="img-responsive">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end section video -->

<!-- start section founder -->
<section class="founder">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6 back">
            </div>
            <div class="col-sm-offset-6 col-sm-6 box">
                <div class="item">
                    <i class="icon ion-quote"></i>
                    <p>An expert is one who knows more and more about less and less until he knows absolutely everything about nothing.</p>
                    <h2> Kennedy Booker</h2>
                    <span class="default-color">Co-Founder</span>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end section founder -->

<!-- start section team -->
<section class="team sec-padding">
    <div class="container">
        <h1 class="heading">
            <span class="first">Awesme Team</span>
            <br>
            <span class="second">Our</span> Team
        </h1>
        <p class="para">Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printern.</p>
        <div class="owl-carousel owl-theme">
            <div class="item">
                <div class="image">
                    <img class="img-responsive" src="images/team-1.jpg" alt="team">
                    <div class="overlay">
                        <div class="social">
                            <a href="#" class="default-background-hover"><i class="fa fa-facebook"></i></a>
                            <a href="#" class="default-background-hover"><i class="fa fa-twitter"></i></a>
                            <a href="#" class="default-background-hover"><i class="fa fa-google-plus"></i></a>
                            <a href="#" class="default-background-hover"><i class="fa fa-rss"></i></a>
                        </div>
                    </div>
                </div>
                <h5>Jone Kean</h5>
                <span>Co Founder</span>
                <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>
            </div>
            <div class="item">
                <div class="image">
                    <img class="img-responsive" src="images/team-2.jpg" alt="team">
                    <div class="overlay">
                        <div class="social">
                            <a href="#" class="default-background-hover"><i class="fa fa-facebook"></i></a>
                            <a href="#" class="default-background-hover"><i class="fa fa-twitter"></i></a>
                            <a href="#" class="default-background-hover"><i class="fa fa-google-plus"></i></a>
                            <a href="#" class="default-background-hover"><i class="fa fa-rss"></i></a>
                        </div>
                    </div>
                </div>
                <h5>Mark Ramos</h5>
                <span>Web Developer</span>
                <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>
            </div>
            <div class="item">
                <div class="image">
                    <img class="img-responsive" src="images/team-4.jpg" alt="team">
                    <div class="overlay">
                        <div class="social">
                            <a href="#" class="default-background-hover"><i class="fa fa-facebook"></i></a>
                            <a href="#" class="default-background-hover"><i class="fa fa-twitter"></i></a>
                            <a href="#" class="default-background-hover"><i class="fa fa-google-plus"></i></a>
                            <a href="#" class="default-background-hover"><i class="fa fa-rss"></i></a>
                        </div>
                    </div>
                </div>
                <h5>Jone Smith</h5>
                <span>Web Designer</span>
                <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>
            </div>
            <div class="item">
                <div class="image">
                    <img class="img-responsive" src="images/team-5.jpg" alt="team">
                    <div class="overlay">
                        <div class="social">
                            <a href="#" class="default-background-hover"><i class="fa fa-facebook"></i></a>
                            <a href="#" class="default-background-hover"><i class="fa fa-twitter"></i></a>
                            <a href="#" class="default-background-hover"><i class="fa fa-google-plus"></i></a>
                            <a href="#" class="default-background-hover"><i class="fa fa-rss"></i></a>
                        </div>
                    </div>
                </div>
                <h5>Kevin Alex</h5>
                <span>Web Designer</span>
                <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>
            </div>
            <div class="item">
                <div class="image">
                    <img class="img-responsive" src="images/team-3.jpg" alt="team">
                    <div class="overlay">
                        <div class="social">
                            <a href="#" class="default-background-hover"><i class="fa fa-facebook"></i></a>
                            <a href="#" class="default-background-hover"><i class="fa fa-twitter"></i></a>
                            <a href="#" class="default-background-hover"><i class="fa fa-google-plus"></i></a>
                            <a href="#" class="default-background-hover"><i class="fa fa-rss"></i></a>
                        </div>
                    </div>
                </div>
                <h5>Robben Kean</h5>
                <span>Web Designer</span>
                <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>
            </div>
        </div>
    </div>
</section>
<!-- end section team -->

<!-- start section numbers -->
<section class="numbers">
    <div class="overlay sec-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="item">
                        <h1 ><i class="fa fa-code"></i> <span class="counter default-color">367</span></h1>
                        <p>Completed Project</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="item">
                        <h1 ><i class="fa fa-users"></i> <span class="counter default-color">658</span></h1>
                        <p>Happy Clients</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="item">
                        <h1 ><i class="fa fa-heart"></i> <span class="counter default-color">265</span></h1>
                        <p>Likes</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="item">
                        <h1 ><i class="fa fa-cubes"></i> <span class="counter default-color">722</span></h1>
                        <p>companies</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end section numbers -->

<!-- start section my-portfolio -->
<section class="my-portfolio sec-padding" id="work">
    <div class="container">
        <h1 class="heading">
            <span class="first">See Our Work</span>
            <br>
            <span class="second">Our Recent</span>  Work
        </h1>
        <p class="para">Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printern.</p>
        <ul class="work_control">
            <li class="selected filter" data-filter="all">All</li>
            <li  class="filter" data-filter=".Graphic">Graphic</li>
            <li  class="filter" data-filter=".design">Design</li>
            <li  class="filter" data-filter=".developement">Developement</li>
        </ul>
        <div class="row" id="change" >
            <div class="col-md-4 col-sm-12  mix Graphic">
                <div class="work-area">
                    <div class="image">
                        <img style="max-height: 240px;" alt="image" src="{!! asset('images/dispo.png') !!}">
                    </div>
                    <a href="{!! asset('images/dispo.png') !!}" class="overlay" data-lightbox="image">
                        <div class="inner">
                            <h2>Web Design</h2>
                            <div class="line"></div>
                            <p>Design,Blog</p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-4 col-sm-12 mix design">
                <div class="work-area">
                    <div class="image">
                        <img style="height: 240px;" alt="image" src="{!! asset('images/acdemic.png') !!}">
                    </div>
                    <a href="{!! asset('images/acdemic.png') !!}" class="overlay" data-lightbox="image">
                        <div class="inner">
                            <h2>Web Design</h2>
                            <div class="line"></div>
                            <p>ALMS</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end section my-portfolio -->

<!--start section price -->
<section class="price">
    <div class="container">
        <h1 class="heading">
            <span class="first">See Packages</span>
            <br>
            <span class="second">Our</span>  Prices
        </h1>
        <p class="para">Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printern.</p>
        <div class="row">
            <div class="col-sm-4">
                <div class="item wow fadeInLeft" data-wow-duration="1s" data-wow-delay="0.6s">
                    <i class="icon ion-person"></i>
                    <div class="price-box">
                        <p>Basic</p>
                        <span><strong>$45</strong> /mo</span>
                    </div>
                    <p class="words">when an unknown printer took a galley of type and scrambled it</p>
                    <p><i class="fa fa-check"></i> 3 Users</p>
                    <p><i class="fa fa-check"></i> 2GB Space</p>
                    <p><i class="fa fa-check"></i> Supporting</p>
                    <p><i class="fa fa-check"></i> Well Documented</p>
                    <p><i class="fa fa-close"></i> 3 Accounts</p>
                    <p><i class="fa fa-close"></i> Free Updates</p>
                    <a href="#" class="a-btn default-background">Order Now</a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="item Premium wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="0.3s">
                    <div class="shape default-background"><p>Popular</p></div>
                    <i class="icon ion-ios-briefcase"></i>
                    <div class="price-box">
                        <p>Premium</p>
                        <span><strong class="default-color">$77</strong> /mo</span>
                    </div>
                    <p class="words">when an unknown printer took a galley of type and scrambled it</p>
                    <p><i class="fa fa-check"></i> 3 Users</p>
                    <p><i class="fa fa-check"></i> 2GB Space</p>
                    <p><i class="fa fa-check"></i> Supporting</p>
                    <p><i class="fa fa-check"></i> Well Documented</p>
                    <p><i class="fa fa-check"></i> 3 Accounts</p>
                    <p><i class="fa fa-close"></i> Free Updates</p>
                    <a href="#" class="a-btn default-background">Order Now</a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="item wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.6s">
                    <i class="icon ion-ios-people"></i>
                    <div class="price-box">
                        <p>Business</p>
                        <span><strong>$99</strong> /mo</span>
                    </div>
                    <p class="words">when an unknown printer took a galley of type and scrambled it</p>
                    <p><i class="fa fa-check"></i> 3 Users</p>
                    <p><i class="fa fa-check"></i> 2GB Space</p>
                    <p><i class="fa fa-check"></i> Supporting</p>
                    <p><i class="fa fa-check"></i> Well Documented</p>
                    <p><i class="fa fa-check"></i> 3 Accounts</p>
                    <p><i class="fa fa-check"></i> Free Updates</p>
                    <a href="#" class="a-btn default-background">Order Now</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!--end section price -->

<!-- end section clients -->
<section class="clients">
    <div class="overlay sec-padding">
        <div class="container">
            <h1 class="heading">
                <span class="first">What our clients say?</span>
                <br>
                <span class="second">Our</span> Testimonials
            </h1>
            <div class="owl-carousel owl-theme">
                <div class="item">
                    <i class="fa fa-quote-left"></i>
                    <p>Lorem Ipsum is simply dummy text of the  and typesetting standard. Lorem Ipsum has been the 's standard dummy text ever since the 1500s, when an unknown it to make a type specimen book.</p>
                    <h4 class="default-color">- Jonathon Doe -</h4>
                    <h6>Web Designer</h6>
                </div>
                <div class="item">
                    <i class="fa fa-quote-left"></i>
                    <p>Lorem Ipsum is simply dummy text of the  and typesetting standard. Lorem Ipsum has been the 's standard dummy text ever since the 1500s, when an unknown it to make a type specimen book.</p>
                    <h4 class="default-color">- Jonathon Doe -</h4>
                    <h6>Web Designer</h6>
                </div>
                <div class="item">
                    <i class="fa fa-quote-left"></i>
                    <p>Lorem Ipsum is simply dummy text of the  and typesetting standard. Lorem Ipsum has been the 's standard dummy text ever since the 1500s, when an unknown it to make a type specimen book.</p>
                    <h4 class="default-color">- Jonathon Doe -</h4>
                    <h6>Web Designer</h6>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end section clients -->

<!-- start section blog -->
<section class="blog sec-padding" id="blog">
    <div class="container">
        <h1 class="heading">
            <span class="first">See Latest News</span>
            <br>
            <span class="second">Our</span> Blog
        </h1>
        <p class="para">Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printern.</p>
        <div class="owl-carousel owl-theme">
            <div class="blog-area item">
                <div class="image">
                    <img alt="blog" class="img-responsive" src="images/k-32-working.jpg">
                </div>
                <div class="box">
                    <span>By Jone Deo</span>
                    <span><i class="fa fa-tag"></i> Design</span>
                    <span><i class="fa fa-comment"></i> 7 Comments</span>
                    <a href="#"><h2 class="default-color-hover">Akser is the way to success</h2></a>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
                    <a href="#" class="a-btn default-background">Read More</a>
                </div>
            </div>
            <div class="blog-area item">
                <div class="image">
                    <img alt="blog" class="img-responsive" src="images/pexels-photo-572463.jpg">
                </div>
                <div class="box">
                    <span>By Jone Deo</span>
                    <span><i class="fa fa-tag"></i> Design</span>
                    <span><i class="fa fa-comment"></i> 7 Comments</span>
                    <a href="#"><h2 class="default-color-hover">don't stop and keep going</h2></a>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
                    <a href="#" class="a-btn default-background">Read More</a>
                </div>
            </div>
            <div class="blog-area item">
                <div class="image">
                    <img alt="blog" class="img-responsive" src="images/pexels-photo-42157.jpg">
                </div>
                <div class="box">
                    <span>By Jone Deo</span>
                    <span><i class="fa fa-tag"></i> Design</span>
                    <span><i class="fa fa-comment"></i> 7 Comments</span>
                    <a href="#"><h2 class="default-color-hover">Grow yor business with us</h2></a>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
                    <a href="#" class="a-btn default-background">Read More</a>
                </div>
            </div>
            <div class="blog-area item">
                <div class="image">
                    <img alt="blog" class="img-responsive" src="images/pexels-photo-775091.jpg">
                </div>
                <div class="box">
                    <span>By Jone Deo</span>
                    <span><i class="fa fa-tag"></i> Design</span>
                    <span><i class="fa fa-comment"></i> 7 Comments</span>
                    <a href="#"><h2 class="default-color-hover">Work For Success</h2></a>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
                    <a href="#" class="a-btn default-background">Read More</a>
                </div>
            </div>
            <div class="blog-area item">
                <div class="image">
                    <img alt="blog" class="img-responsive" src="images/k-32-jj-04674-l171015_2_0.jpg">
                </div>
                <div class="box">
                    <span>By Jone Deo</span>
                    <span><i class="fa fa-tag"></i> Design</span>
                    <span><i class="fa fa-comment"></i> 7 Comments</span>
                    <a href="#"><h2 class="default-color-hover">The way to success</h2></a>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
                    <a href="#" class="a-btn default-background">Read More</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end section blog -->

<!-- start section partners -->
<section class="partners sec-padding">
    <div class="container">
        <div class="owl-carousel owl-theme">
            <div class="item">
                <img alt="image" src="images/brand-3.png">
            </div>
            <div class="item">
                <img alt="image" src="images/brand-2.png">
            </div>
            <div class="item">
                <img alt="image" src="images/brand-1.png">
            </div>
            <div class="item">
                <img alt="image" src="images/brand-3.png">
            </div>
            <div class="item">
                <img alt="image" src="images/brand-2.png">
            </div>
            <div class="item">
                <img alt="image" src="images/brand-1.png">
            </div>
            <div class="item">
                <img alt="image" src="images/brand-3.png">
            </div>
            <div class="item">
                <img alt="image" src="images/brand-2.png">
            </div>
            <div class="item">
                <img alt="image" src="images/brand-1.png">
            </div>
        </div>
    </div>
</section>
<!-- end section partners -->

<!-- start section contact -->
<section id="contact" class="contact sec-padding">
    <div class="container">
        <h1 class="heading">
            <span class="first">Get In Touch</span>
            <br>
            Contact <span class="second">Us</span>
        </h1>
        <p class="para">Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printern.</p>
        <div class="row">
            <div class="col-sm-4">
                <div class="item">
                    <i class="fa fa-mobile default-background"></i>
                    <h5>Phone</h5>
                    <p>+01 23456789</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="item">
                    <i class="fa fa-envelope-o default-background"></i>
                    <h5>Mail</h5>
                    <p>info@gmail.com</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="item">
                    <i class="fa fa-map-marker default-background"></i>
                    <h5>Address</h5>
                    <p>15 El Massara st</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="contact-form">
                    <form class='form' id='contact-form' method='post'><input type='hidden' name='form-name' value='contact-form' />
                        <div class="messages"></div>
                        <div class="controls">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="fa fa-user-o"></label>
                                    <input id="form_name" class="form-control" type="text" name="name" placeholder="Name"  required="required" data-error="Firstname is required.">
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fa fa-envelope-o"></label>
                                    <input id="form_email" class="form-control" type="email" name="email" placeholder="Email" required="required" data-error="Valid email is required.">
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fa fa-edit"></label>
                                    <input id="form_subject" class="form-control" type="text" name="subject" placeholder="Subject">
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="fa fa-comments-o"></label>
                                    <textarea id="form_message" class="form-control" name="message" placeholder="Message" rows="6" required="required" data-error="Message."></textarea>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-sm-offset-3 col-sm-6">
                                <input type="submit" value="Send message">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div id="map"></div>
            </div>
        </div>
    </div>
</section>
<!-- end section contact -->

<!-- start div social-icon-div -->
<div class="social-icon-div">
    <a href="#"><i class="fa fa-facebook wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s"></i></a>
    <a href="#"><i class="fa fa-twitter wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.4s"></i></a>
    <a href="#"><i class="fa fa-google-plus wow fadeInUp" data-wow-duration="1s" data-wow-delay=".6s"></i></a>
    <a href="#"><i class="fa fa-instagram wow fadeInUp" data-wow-duration="1s" data-wow-delay=".8s"></i></a>
    <p class="wow fadeInUp" data-wow-duration="1s" data-wow-delay="1s">Copyright © 2018 MTthemes22, All Rights Reserved.</p>
</div>
<!-- end div social-icon-div -->

<!-- Start Scroll To Top -->
<div id="scroll-top">
    <i class="fa fa-angle-up"></i>
</div>
<!-- end Scroll To Top -->


<script src="js/jquery.min.js"></script>
<script src="js/jquery.counterup.min.js"></script>
<script src="js/waypoints.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/validator.js"></script>
<script src="js/lity.min.js"></script>
<script src="js/lightbox.js"></script>
<script src="js/jquery.fittext.js"></script>
<script src="js/jquery.lettering.js"></script>
<script src="js/jquery.textillate.js"></script>
<script src="js/typed.js"></script>
<script src="js/swiper.min.js"></script>
<script src="js/mixitup.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA_egfOLjahHB0IWpykRZrVFD8fN4JMgmw"></script>
<script src="js/map.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/custom.js"></script>
<script src="js/wow.min.js"></script>
<script>new WOW().init();</script>
</body>

<!-- Mirrored from akser-one-page-parallax.bitballoon.com/image.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 07 Mar 2018 05:27:50 GMT -->
</html>