<footer class="landing-footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <h5 class="footer-title">
                    <i class="fas fa-cut me-2"></i>
                    Salon Pro
                </h5>
                <p>
                    The complete salon management solution. Streamline operations, 
                    delight customers, and grow your business.
                </p>
                <div class="social-links mt-3">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 mb-4 mb-lg-0">
                <h5 class="footer-title">Product</h5>
                <ul class="footer-links">
                    <li><a href="/#features">Features</a></li>
                    <li><a href="/#pricing">Pricing</a></li>
                    <li><a href="{{ route('blog.index') }}">Blog</a></li>
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </div>
            
            <div class="col-lg-2 col-md-4 mb-4 mb-lg-0">
                <h5 class="footer-title">Resources</h5>
                <ul class="footer-links">
                    <li><a href="#">Documentation</a></li>
                    <li><a href="#">API Reference</a></li>
                    <li><a href="#">Support</a></li>
                    <li><a href="#">Blog</a></li>
                </ul>
            </div>
            
            <div class="col-lg-2 col-md-4 mb-4 mb-lg-0">
                <h5 class="footer-title">Company</h5>
                <ul class="footer-links">
                    <li><a href="{{ route('about') }}">About</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
            </div>
            
            <div class="col-lg-2 col-md-4">
                <h5 class="footer-title">Get Started</h5>
                <ul class="footer-links">
                    <li><a href="{{ route('register') }}">Sign Up</a></li>
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('contact') }}">Request Demo</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom text-center">
            <p class="mb-0">
                &copy; {{ date('Y') }} Salon Management Pro. All rights reserved.
            </p>
        </div>
    </div>
</footer>
