<?php
//Important Section
//1- Newsletter
//2- Add To Cart Button So when Item Already on the cart show something on the button
//3- Remove or disable or change label of Add to Cart button when Out of Stock
//4- when user login merge its cart with guest cart
//5- reserve product when a user put it in the cart (for some time)

//TODO: there is an error with currency convertor when converting to BHD or KWD ... etc but not all currency

//TODO: what should do if user opens the checkout page and the cart were empty

// TODO: auto calculate regular price when adding items to bundle
// TODO: add Queue for image generation in production
// TODO: change database for cache ... , to redis in production
// TODO: when Scrolling the side menu lag
// TODO: fix slug in the product , model
// TODO: exporter for all tables
// TODO: clockwork on production

//14/11
//TODO: like Product page
//TODO: check Product status (if published ,order ,... etc)
//TODO: link shop page
//TODO: add spatie cache library and delete cache when faq , Product , reviews ..... etc
// TODO: bundle front end Page

//25/11
//TODO: add Post , Blog , Archive (for , product , bundle , post) front end pages
//TODO: make them responsive
//TODO: finish Bundle Page
//TODO: add soft delete for every resource

//animation
//TODO: check if there is any animation you can add to product , shop page
//TODO: add swiper animation in home page
//TODO: add Scale animation for the cards

// use this for product card for seo
//<div class="product" itemscope itemtype="https://schema.org/Product">
//  <h2 itemprop="name">Product Name</h2>
//  <img src="product.jpg" alt="Product Image" itemprop="image">
//  <p itemprop="description">A short description of the product.</p>
//  <span itemprop="priceCurrency" content="USD">$<span itemprop="price">99.99</span></span>
//  <button>Add to Cart</button>
//</div>
