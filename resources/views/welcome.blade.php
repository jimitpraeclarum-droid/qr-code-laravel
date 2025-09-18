@extends('layouts.sleeky')

@section('title', 'The Digital Footprint')

@section('content')
    <!-- Hero Section with Slider -->
    <section id="home" class="hero">
        <div class="slider-container">
            <div class="progress-ring">
                <div class="progress-bar" id="progressBar"></div>
            </div>
            
            <div class="slider-stage" id="sliderStage"></div>
            <!-- You can edit slider images in JS file -->
            <!-- Edit templatemo-sleeky-scripts.js for image path / image filenames -->
            <div class="text-overlay" id="textOverlay">
                <h2 class="slide-title" id="slideTitle">Mountain Peak</h2>
                <p class="slide-description" id="slideDescription">Majestic snow-capped mountains reaching towards the endless sky, showcasing nature's raw beauty and power.</p>
            </div>
            
            <button class="nav-arrow prev" id="prevArrow" aria-label="Previous"></button>
            <button class="nav-arrow next" id="nextArrow" aria-label="Next"></button>
            
            <div class="controls">
                <button class="control-btn play-pause-btn" id="playPauseBtn" title="Play/Pause">
                    <span class="play-icon"></span>
                    <span class="pause-icon"></span>
                </button>
            </div>
            
            <div class="dots" id="dots"></div>
            <div class="thumbnails-container" id="thumbnails"></div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="section services">
        <div class="section-container">
            <div class="section-header">
                <h2 class="section-title">Create Your Digital Footprint</h2>
                <p class="section-subtitle">Generate, customize, and manage QR codes with ease. Connect the physical world to your digital content in a single scan.</p>
            </div>
            
            <div class="services-container">
                <div class="services-tabs">
                    <div class="service-tab active" data-service="photography">
                        <span class="service-tab-icon"><svg xmlns="http://www.w3.org/2000/svg" width="32" viewBox="0 0 512 512"><!--!Font Awesome Free v7.0.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M512 256c0 .9 0 1.8 0 2.7-.4 36.5-33.6 61.3-70.1 61.3L344 320c-26.5 0-48 21.5-48 48 0 3.4 .4 6.7 1 9.9 2.1 10.2 6.5 20 10.8 29.9 6.1 13.8 12.1 27.5 12.1 42 0 31.8-21.6 60.7-53.4 62-3.5 .1-7 .2-10.6 .2-141.4 0-256-114.6-256-256S114.6 0 256 0 512 114.6 512 256zM128 288a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zm0-96a32 32 0 1 0 0-64 32 32 0 1 0 0 64zM288 96a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zm96 96a32 32 0 1 0 0-64 32 32 0 1 0 0 64z"/></svg></span>
                        <h3>Advanced Customization</h3>
                        <p>Change colors, add logos, and select from various patterns to make your QR codes unique.</p>
                    </div>
                    
                    <div class="service-tab" data-service="expeditions">
                        <span class="service-tab-icon"><svg xmlns="http://www.w3.org/2000/svg" width="32" viewBox="0 0 640 640"><!--!Font Awesome Free v7.0.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M296.5 69.2C311.4 62.3 328.6 62.3 343.5 69.2L562.1 170.2C570.6 174.1 576 182.6 576 192C576 201.4 570.6 209.9 562.1 213.8L343.5 314.8C328.6 321.7 311.4 321.7 296.5 314.8L77.9 213.8C69.4 209.8 64 201.3 64 192C64 182.7 69.4 174.1 77.9 170.2L296.5 69.2zM112.1 282.4L276.4 358.3C304.1 371.1 336 371.1 363.7 358.3L528 282.4L562.1 298.2C570.6 302.1 576 310.6 576 320C576 329.4 570.6 337.9 562.1 341.8L343.5 442.8C328.6 449.7 311.4 449.7 296.5 442.8L77.9 341.8C69.4 337.8 64 329.3 64 320C64 310.7 69.4 302.1 77.9 298.2L112 282.4zM77.9 426.2L112 410.4L276.3 486.3C304 499.1 335.9 499.1 363.6 486.3L527.9 410.4L562 426.2C570.5 430.1 575.9 438.6 575.9 448C575.9 457.4 570.5 465.9 562 469.8L343.4 570.8C328.5 577.7 311.3 577.7 296.4 570.8L77.9 469.8C69.4 465.8 64 457.3 64 448C64 438.7 69.4 430.1 77.9 426.2z"/></svg></span>
                        <h3>Multiple QR Code Types</h3>
                        <p>From simple URLs to vCards, social media links, and app store downloads.</p>
                    </div>
                    
                    <div class="service-tab" data-service="prints">
                        <span class="service-tab-icon"><svg xmlns="http://www.w3.org/2000/svg" width="32" viewBox="0 0 512 512"><!--!Font Awesome Free v5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M332.8 320h38.4c6.4 0 12.8-6.4 12.8-12.8V172.8c0-6.4-6.4-12.8-12.8-12.8h-38.4c-6.4 0-12.8 6.4-12.8 12.8v134.4c0 6.4 6.4 12.8 12.8 12.8zm96 0h38.4c6.4 0 12.8-6.4 12.8-12.8V76.8c0-6.4-6.4-12.8-12.8-12.8h-38.4c-6.4 0-12.8 6.4-12.8 12.8v230.4c0 6.4 6.4 12.8 12.8 12.8zm-288 0h38.4c6.4 0 12.8-6.4 12.8-12.8v-70.4c0-6.4-6.4-12.8-12.8-12.8h-38.4c-6.4 0-12.8 6.4-12.8 12.8v70.4c0 6.4 6.4 12.8 12.8 12.8zm96 0h38.4c6.4 0 12.8-6.4 12.8-12.8V108.8c0-6.4-6.4-12.8-12.8-12.8h-38.4c-6.4 0-12.8 6.4-12.8 12.8v198.4c0 6.4 6.4 12.8 12.8 12.8zM496 384H64V80c0-8.84-7.16-16-16-16H16C7.16 64 0 71.16 0 80v336c0 17.67 14.33 32 32 32h464c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16z"/></svg></span>
                        <h3>Scan Analytics</h3>
                        <p>Track how many times your QR codes are scanned, from where, and on what devices.</p>
                    </div>
                </div>
                
                <div class="services-content">
                    <div class="service-content active" id="photography">
                        <div class="service-content-icon"><svg xmlns="http://www.w3.org/2000/svg" width="50" viewBox="0 0 512 512"><!--!Font Awesome Free v7.0.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M512 256c0 .9 0 1.8 0 2.7-.4 36.5-33.6 61.3-70.1 61.3L344 320c-26.5 0-48 21.5-48 48 0 3.4 .4 6.7 1 9.9 2.1 10.2 6.5 20 10.8 29.9 6.1 13.8 12.1 27.5 12.1 42 0 31.8-21.6 60.7-53.4 62-3.5 .1-7 .2-10.6 .2-141.4 0-256-114.6-256-256S114.6 0 256 0 512 114.6 512 256zM128 288a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zm0-96a32 32 0 1 0 0-64 32 32 0 1 0 0 64zM288 96a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zm96 96a32 32 0 1 0 0-64 32 32 0 1 0 0 64z"/></svg></div>
                        <h2>Advanced Customization</h2>
                        <p>Select between static and dynamic QR codes depending on your needs. Whether it’s opening a URL, sharing a PDF, displaying a menu, or providing contact details — you’re in full control.</p>
                        <ul class="service-features">
                            <li>Encodes fixed information (e.g., URL, text, contact details).</li>
                            <li>Lets you update the content later (e.g., change the URL or file without reprinting the code).</li>
                            <li>Offers tracking and analytics.</li>
                            <li>Ideal for menus, PDFs, campaigns, and marketing materials.</li>
                        </ul>
                    </div>
                    
                    <div class="service-content" id="expeditions">
                        <div class="service-content-icon"><svg xmlns="http://www.w3.org/2000/svg" width="50" viewBox="0 0 640 640"><!--!Font Awesome Free v7.0.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M296.5 69.2C311.4 62.3 328.6 62.3 343.5 69.2L562.1 170.2C570.6 174.1 576 182.6 576 192C576 201.4 570.6 209.9 562.1 213.8L343.5 314.8C328.6 321.7 311.4 321.7 296.5 314.8L77.9 213.8C69.4 209.8 64 201.3 64 192C64 182.7 69.4 174.1 77.9 170.2L296.5 69.2zM112.1 282.4L276.4 358.3C304.1 371.1 336 371.1 363.7 358.3L528 282.4L562.1 298.2C570.6 302.1 576 310.6 576 320C576 329.4 570.6 337.9 562.1 341.8L343.5 442.8C328.6 449.7 311.4 449.7 296.5 442.8L77.9 341.8C69.4 337.8 64 329.3 64 320C64 310.7 69.4 302.1 77.9 298.2L112 282.4zM77.9 426.2L112 410.4L276.3 486.3C304 499.1 335.9 499.1 363.6 486.3L527.9 410.4L562 426.2C570.5 430.1 575.9 438.6 575.9 448C575.9 457.4 570.5 465.9 562 469.8L343.4 570.8C328.5 577.7 311.3 577.7 296.4 570.8L77.9 469.8C69.4 465.8 64 457.3 64 448C64 438.7 69.4 430.1 77.9 426.2z"/></svg></div>
                        <h2>Multiple QR Code Types</h2>
                        <p>Stand out with QR codes designed your way. Change colors, add your logo, and choose from stylish patterns to create a code that’s truly unique to your brand.</p>
                        <ul class="service-features">
                            <li>Change Colors – Go beyond black-and-white with custom color combinations.</li>
                            <li>Style Your QR Code – Select patterns and shapes that match your brand or personal style.</li>
                            <li>Add a Logo – Upload your brand logo or icon to personalize your QR code.</li>
                            <li>Test in Real Time – Scan the live preview with your device to ensure it works properly.</li>
                        </ul>
                    </div>
                    
                    <div class="service-content" id="prints">
                        <div class="service-content-icon"><svg xmlns="http://www.w3.org/2000/svg" width="50" viewBox="0 0 512 512"><!--!Font Awesome Free v5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M332.8 320h38.4c6.4 0 12.8-6.4 12.8-12.8V172.8c0-6.4-6.4-12.8-12.8-12.8h-38.4c-6.4 0-12.8 6.4-12.8 12.8v134.4c0 6.4 6.4 12.8 12.8 12.8zm96 0h38.4c6.4 0 12.8-6.4 12.8-12.8V76.8c0-6.4-6.4-12.8-12.8-12.8h-38.4c-6.4 0-12.8 6.4-12.8 12.8v230.4c0 6.4 6.4 12.8 12.8 12.8zm-288 0h38.4c6.4 0 12.8-6.4 12.8-12.8v-70.4c0-6.4-6.4-12.8-12.8-12.8h-38.4c-6.4 0-12.8 6.4-12.8 12.8v70.4c0 6.4 6.4 12.8 12.8 12.8zm96 0h38.4c6.4 0 12.8-6.4 12.8-12.8V108.8c0-6.4-6.4-12.8-12.8-12.8h-38.4c-6.4 0-12.8 6.4-12.8 12.8v198.4c0 6.4 6.4 12.8 12.8 12.8zM496 384H64V80c0-8.84-7.16-16-16-16H16C7.16 64 0 71.16 0 80v336c0 17.67 14.33 32 32 32h464c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16z"/></svg></div>
                        <h2>Scan Analytics</h2>
                        <p>Pick PNG , click download, and your QR code is ready to use anywhere — online or offline.</p>
                        <ul class="service-features">
                            <li>PNG – great for web and social media.</li>
                            <li>Click Download.</li>
                            <li>Your QR code is now ready to share, print, or embed wherever you need.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="section about">
        <div class="section-container">
            <div class="section-header">
                <h2 class="section-title">How Businesses in Every Industry Leverage QR Codes</h2>
                <p class="section-subtitle">QR Codes have become universal, but their impact differs across industries. We’ve highlighted the most effective use cases, each designed to create value and ensure seamless adoption.</p>
            </div>
            
            <div class="about-grid">
                <div class="about-timeline">
                    <div class="timeline-line"></div>
                    
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-year">Retail</div>
                        <div class="timeline-title">Merchandising</div>
                        <div class="timeline-description">QR Codes in retail simplify the customer journey. Shoppers can complete contactless checkout, access detailed product pages, or leave reviews instantly, creating a smooth transition from in-store shelves to their smartphones.</div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-year">Real Estate</div>
                        <div class="timeline-title">Property</div>
                        <div class="timeline-description">Share virtual tours, property details, and agent contact info straight from signs or brochures. With one scan, buyers get instant access to everything they need.</div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-year">Healthcare</div>
                        <div class="timeline-title">Wellness services</div>
                        <div class="timeline-description">In healthcare, QR Codes make access to information seamless. Patients can book appointments, view digital reports, or find the right guidance instantly. From waiting rooms to prescription bags, QR Codes improve communication and convenience.</div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-year">Events</div>
                        <div class="timeline-title">Functions/ Exhibitions / Trade shows</div>
                        <div class="timeline-description">QR Codes simplify event experiences by streamlining check-ins, distributing digital tickets, and providing instant access to schedules and venue maps. They are widely used in conferences, concerts, and festivals to improve convenience and engagement.</div>
                    </div>
                </div>
                
                <div class="about-tech">
                    <div class="tech-card">
                        <div class="tech-icon">🤖</div>
                        <div class="tech-title">Education</div>
                        <div class="tech-description">In education, QR Codes make learning resources more accessible. Teachers can link handouts, assignments, or digital platforms through a simple scan, helping students stay on track while reducing administrative effort.</div>
                    </div>
                    
                    <div class="tech-card">
                        <div class="tech-icon">🛰️</div>
                        <div class="tech-title">Hospitality</div>
                        <div class="tech-description">In hospitality, QR Codes improve both safety and convenience. Restaurants and hotels can use them to replace printed menus, gather customer feedback, or promote offers at tables and check-in counters, giving guests a seamless, contactless experience.</div>
                    </div>
                    
                    <div class="tech-card">
                        <div class="tech-icon">🎯</div>
                        <div class="tech-title">Marketing</div>
                        <div class="tech-description">QR Codes can be placed anywhere your audience interacts with your brand. From packaging and posters to business cards and storefronts, they turn everyday touchpoints into digital gateways. Whether on tables, counters, or even email signatures — QR Codes make access simple and instant.</div>
                    </div>
                </div>
            </div>

            <div class="stats-futuristic">
                <div class="stat-futuristic">
                    <h4>47</h4>
                    <p>Events</p>
                </div>
                <div class="stat-futuristic">
                    <h4>23</h4>
                    <p>Hospitality</p>
                </div>
                <div class="stat-futuristic">
                    <h4>40</h4>
                    <p>Real Estate</p>
                </div>
                <div class="stat-futuristic">
                    <h4>19</h4>
                    <p>Healthcare</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="section contact">
        <div class="section-container">
            <div class="section-header">
                <h2 class="section-title">Get In Touch</h2>
                <p class="section-subtitle">Have a question or need assistance? Fill out the form below or connect with us directly — our team is here to help.</p>
            </div>
            
            <div class="contact-content">
                <div class="contact-form">
                    <form id="contactForm">
                        <div class="form-group">
                            <label for="name">Your Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" placeholder="Tell us about your project or adventure plans..." required></textarea>
                        </div>
                        
                        <button type="submit" class="submit-btn">Send Message</button>
                    </form>
                </div>
                
                <div class="contact-info">
                    <div class="contact-item">
                        <div class="contact-icon">📍</div>
                        <div class="contact-details">
                            <h4>Head Office Location</h4>
                            <p>New Mountain National Park<br>Colorado, USA 85858</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">📞</div>
                        <div class="contact-details">
                            <h4>Phone & WhatsApp</h4>
                            <p>+1 (555) 123-4567</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">✉️</div>
                        <div class="contact-details">
                            <h4>Email Address</h4>
                            <p>The Digital Footprint@gmail.com</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">🕐</div>
                        <div class="contact-details">
                            <h4>Business Hours</h4>
                            <p>Monday - Friday: 9:00 AM - 6:00 PM</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
