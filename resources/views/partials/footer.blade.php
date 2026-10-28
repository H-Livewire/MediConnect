<footer id="footer" class="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="single-footer">
                        <h2>About MediConnect</h2>
                        <p>MediConnect connects patients with trusted doctors and hospitals across Tanzania. Find a specialist and book your appointment online in minutes.</p>
                        <ul class="social">
                            <li><a href="#" aria-label="Facebook"><i class="icofont-facebook"></i></a></li>
                            <li><a href="#" aria-label="Twitter"><i class="icofont-twitter"></i></a></li>
                            <li><a href="#" aria-label="Instagram"><i class="icofont-instagram"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="single-footer f-link">
                        <h2>Quick Links</h2>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12">
                                <ul>
                                    <li><a href="{{ route('home') }}"><i class="fa fa-caret-right" aria-hidden="true"></i>Home</a></li>
                                    <li><a href="{{ route('about') }}"><i class="fa fa-caret-right" aria-hidden="true"></i>About Us</a></li>
                                    <li><a href="{{ route('doctors.index') }}"><i class="fa fa-caret-right" aria-hidden="true"></i>Doctors</a></li>
                                    <li><a href="{{ route('hospitals.index') }}"><i class="fa fa-caret-right" aria-hidden="true"></i>Hospitals</a></li>
                                </ul>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <ul>
                                    <li><a href="{{ route('afya-smart') }}"><i class="fa fa-caret-right" aria-hidden="true"></i>Afya Smart</a></li>
                                    <li><a href="{{ route('appointments.create') }}"><i class="fa fa-caret-right" aria-hidden="true"></i>Book Now</a></li>
                                    <li><a href="{{ route('login') }}"><i class="fa fa-caret-right" aria-hidden="true"></i>Login</a></li>
                                    <li><a href="{{ route('hospital.register') }}"><i class="fa fa-caret-right" aria-hidden="true"></i>For Hospitals</a></li>
                                    <li><a href="{{ route('contact') }}"><i class="fa fa-caret-right" aria-hidden="true"></i>Contact Us</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="single-footer">
                        <h2>Open Hours</h2>
                        <p>Online booking is available around the clock. Clinic hours vary by hospital.</p>
                        <ul class="time-sidual">
                            <li class="day">Monday - Friday <span>8.00-17.00</span></li>
                            <li class="day">Saturday <span>9.00-13.00</span></li>
                            <li class="day">Sunday <span>Emergency only</span></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="single-footer">
                        <h2>Contact</h2>
                        <p>Dar es Salaam, Tanzania<br>+255 700 000 000<br>support@mediconnect.co.tz</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="copyright-content">
                        <p>© {{ date('Y') }} MediConnect | All Rights Reserved</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
