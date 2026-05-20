const data = {
    education: [
        ["2004-2008", "Doctor of Medicine (MD)", "Harvard Medical School", "Boston, MA", "Graduated with honors, Dean's List all four years", "ri-graduation-cap-fill"],
        ["2000-2004", "Bachelor of Science in Biology", "Stanford University", "Stanford, CA", "Summa Cum Laude, Pre-Medical Track", "ri-book-open-fill"],
        ["2008-2012", "Residency - Internal Medicine", "Johns Hopkins Hospital", "Baltimore, MD", "Chief Resident in final year", "ri-hospital-fill"],
        ["2012-2015", "Fellowship - Cardiology", "Mayo Clinic", "Rochester, MN", "Specialized in interventional cardiology", "ri-heart-pulse-fill"],
    ],
    credentials: [
        ["2012", "Board Certified - Internal Medicine", "American Board of Internal Medicine", "Active", "ri-award-fill"],
        ["2015", "Board Certified - Cardiovascular Disease", "American Board of Internal Medicine", "Active", "ri-shield-check-fill"],
        ["2023", "Advanced Cardiac Life Support (ACLS)", "American Heart Association", "Current", "ri-first-aid-kit-fill"],
        ["2023", "Basic Life Support (BLS)", "American Heart Association", "Current", "ri-file-shield-fill"],
        ["2016", "Fellow of American College of Physicians", "American College of Physicians", "Active", "ri-stethoscope-fill"],
        ["2017", "Fellow of American College of Cardiology", "American College of Cardiology", "Active", "ri-medal-fill"],
    ],
    affiliations: [
        ["Hospital Affiliation", "New York Presbyterian Hospital", "Attending Physician", "ri-hospital-line"],
        ["Hospital Affiliation", "Mount Sinai Medical Center", "Consulting Physician", "ri-building-line"],
        ["Professional Organization", "American Medical Association", "Active Member", "ri-team-fill"],
        ["Professional Organization", "American College of Cardiology", "Fellow (FACC)", "ri-heart-line"],
        ["Professional Organization", "American College of Physicians", "Fellow (FACP)", "ri-user-star-line"],
        ["State Organization", "New York State Medical Society", "Member", "ri-global-line"],
    ],
    expertise: [
        "Preventive Medicine & Health Screenings",
        "Chronic Disease Management",
        "Cardiovascular Health",
        "Diabetes Care & Management",
        "Hypertension Treatment",
        "Geriatric Medicine",
        "Women's Health",
        "Nutrition & Lifestyle Counseling",
    ],
    specialties: [
        ["Cardiology", "Comprehensive heart health assessment, treatment of cardiovascular diseases, and preventive cardiac care.", "2,500+ Patients Treated", "ri-heart-pulse-fill"],
        ["Internal Medicine", "Expert diagnosis and treatment of complex adult diseases with a holistic approach to patient care.", "3,200+ Patients Treated", "ri-stethoscope-fill"],
        ["Preventive Care", "Proactive health screenings, lifestyle counseling, and disease prevention strategies for optimal wellness.", "4,100+ Patients Treated", "ri-heart-add-fill"],
        ["Chronic Disease Management", "Specialized care for diabetes, hypertension, and other chronic conditions with personalized treatment plans.", "1,800+ Patients Treated", "ri-capsule-fill"],
        ["Geriatric Medicine", "Compassionate care tailored to the unique health needs of older adults and elderly patients.", "1,500+ Patients Treated", "ri-user-heart-fill"],
        ["Women's Health", "Comprehensive healthcare services addressing the specific medical needs of women at all life stages.", "2,900+ Patients Treated", "ri-parent-fill"],
    ],
    timeline: [
        ["2008", "Medical Degree", "Harvard Medical School", "Graduated with honors, specializing in Internal Medicine and Cardiology", "ri-graduation-cap-fill"],
        ["2008-2012", "Residency Program", "Johns Hopkins Hospital", "Completed intensive residency training in Internal Medicine", "ri-hospital-fill"],
        ["2012-2015", "Fellowship in Cardiology", "Mayo Clinic", "Advanced training in cardiovascular medicine and interventional cardiology", "ri-heart-pulse-fill"],
        ["2015-2018", "Senior Physician", "Mount Sinai Medical Center", "Led cardiology department and mentored junior physicians", "ri-award-fill"],
        ["2018-Present", "Private Practice", "Dr. Sarah Mitchell Medical Practice", "Established independent practice focusing on personalized patient care", "ri-building-fill"],
    ],
    gallery: [
        ["Reception Area", "Facility", "https://readdy.ai/api/search-image?query=Modern%20medical%20clinic%20reception%20area%20with%20professional%20staff%2C%20contemporary%20healthcare%20facility%20entrance%2C%20welcoming%20front%20desk%2C%20bright%20and%20clean%20medical%20office%20lobby&width=600&height=400&seq=gallery-img-001&orientation=landscape"],
        ["Patient Consultation", "Care", "https://readdy.ai/api/search-image?query=Doctor%20examining%20patient%20with%20stethoscope%20in%20modern%20examination%20room%2C%20professional%20medical%20consultation%2C%20caring%20physician%20with%20patient%2C%20medical%20checkup%20in%20progress&width=600&height=400&seq=gallery-img-002&orientation=landscape"],
        ["Examination Room", "Facility", "https://readdy.ai/api/search-image?query=State-of-the-art%20medical%20examination%20room%20with%20modern%20equipment%2C%20advanced%20diagnostic%20technology%2C%20clean%20medical%20treatment%20room%2C%20professional%20healthcare%20setting&width=600&height=400&seq=gallery-img-003&orientation=landscape"],
        ["Diagnostic Lab", "Technology", "https://readdy.ai/api/search-image?query=Medical%20laboratory%20with%20diagnostic%20equipment%2C%20modern%20clinical%20lab%20testing%20facility%2C%20healthcare%20technology%20and%20instruments%2C%20professional%20medical%20testing%20environment&width=600&height=400&seq=gallery-img-004&orientation=landscape"],
        ["Waiting Lounge", "Facility", "https://readdy.ai/api/search-image?query=Comfortable%20medical%20clinic%20waiting%20room%20with%20modern%20furniture%2C%20patient%20lounge%20area%2C%20welcoming%20healthcare%20facility%20waiting%20space%2C%20contemporary%20medical%20office%20seating&width=600&height=400&seq=gallery-img-005&orientation=landscape"],
        ["Medical Records", "Care", "https://readdy.ai/api/search-image?query=Doctor%20reviewing%20medical%20charts%20and%20patient%20records%2C%20physician%20analyzing%20health%20data%2C%20medical%20professional%20working%20with%20patient%20files%2C%20healthcare%20documentation&width=600&height=400&seq=gallery-img-006&orientation=landscape"],
        ["Cardiac Monitoring", "Technology", "https://readdy.ai/api/search-image?query=Advanced%20cardiac%20monitoring%20equipment%20in%20medical%20facility%2C%20ECG%20and%20heart%20health%20diagnostic%20technology%2C%20cardiovascular%20testing%20room%2C%20modern%20cardiology%20equipment&width=600&height=400&seq=gallery-img-007&orientation=landscape"],
        ["Patient Care", "Care", "https://readdy.ai/api/search-image?query=Doctor%20and%20patient%20having%20friendly%20conversation%20in%20medical%20office%2C%20positive%20healthcare%20interaction%2C%20physician%20explaining%20treatment%20to%20patient%2C%20compassionate%20medical%20care&width=600&height=400&seq=gallery-img-008&orientation=landscape"],
        ["Consultation Office", "Facility", "https://readdy.ai/api/search-image?query=Modern%20medical%20office%20consultation%20room%20with%20desk%20and%20chairs%2C%20professional%20physician%20office%20interior%2C%20doctor%20workspace%20with%20medical%20certificates%20on%20wall&width=600&height=400&seq=gallery-img-009&orientation=landscape"],
    ],
    testimonials: [
        ["Jennifer Martinez", "Patient since 2019", "Dr. Mitchell is an exceptional physician who truly cares about her patients. She takes the time to listen and explain everything thoroughly.", "https://readdy.ai/api/search-image?query=Professional%20portrait%20of%20happy%20middle-aged%20woman%20smiling%20warmly%2C%20natural%20lighting%2C%20clean%20simple%20background%2C%20friendly%20and%20approachable%2C%20headshot%20photo&width=200&height=200&seq=patient-001&orientation=squarish"],
        ["Robert Thompson", "Patient since 2020", "After years of searching for the right doctor, I finally found Dr. Mitchell. Her expertise has significantly improved my quality of life.", "https://readdy.ai/api/search-image?query=Professional%20portrait%20of%20confident%20senior%20man%20smiling%2C%20natural%20lighting%2C%20clean%20simple%20background%2C%20friendly%20demeanor%2C%20headshot%20photo&width=200&height=200&seq=patient-002&orientation=squarish"],
        ["Sarah Johnson", "Patient since 2018", "The best doctor I've ever had. Dr. Mitchell is knowledgeable, compassionate, and always available when I need her.", "https://readdy.ai/api/search-image?query=Professional%20portrait%20of%20young%20woman%20smiling%20warmly%2C%20natural%20lighting%2C%20clean%20simple%20background%2C%20friendly%20and%20positive%2C%20headshot%20photo&width=200&height=200&seq=patient-003&orientation=squarish"],
    ],
    faqs: [
        ["Do you accept new patients?", "Yes, we are currently accepting new patients. We welcome individuals and families seeking comprehensive medical care."],
        ["What insurance plans do you accept?", "We accept most major insurance plans including Medicare, Medicaid, Blue Cross Blue Shield, Aetna, Cigna, UnitedHealthcare, and many others."],
        ["How do I prepare for my first appointment?", "Please bring a valid photo ID, your insurance card, a list of current medications, and any relevant medical records."],
        ["What should I do in case of a medical emergency?", "For life-threatening emergencies, call 911 or go to the nearest emergency room immediately."],
        ["How long are typical appointments?", "Initial consultations typically last 45-60 minutes. Follow-up appointments are usually 20-30 minutes."],
        ["Can I request prescription refills online?", "Yes, you can request prescription refills through our patient portal or by calling our office."],
        ["Do you offer telemedicine appointments?", "Yes, we offer virtual consultations for appropriate medical concerns through secure online platforms."],
        ["What is your cancellation policy?", "We request at least 24 hours notice for appointment cancellations or rescheduling."],
    ],
};

const theme = window.templateTheme || {};

function sectionHead(kicker, title, text) {
    return `<div class="section-head"><span class="pill">${kicker}</span><h2 class="font-display">${title}</h2><p>${text}</p></div>`;
}

function iconCard(item) {
    return `<article class="card split-card"><div class="icon-box"><i class="${item[5] || item[3] || item[4]}"></i></div><div><div class="meta">${item[0]}</div><h3>${item[1]}</h3><p><strong>${item[2]}</strong></p>${item[3] ? `<p>${item[3]}</p>` : ""}${item[4] && item.length > 5 ? `<p>${item[4]}</p>` : ""}</div></article>`;
}

function render() {
    document.documentElement.style.setProperty("--primary", theme.primary || "#0891b2");
    document.documentElement.style.setProperty("--secondary", theme.secondary || "#14b8a6");
    document.documentElement.style.setProperty("--soft", theme.soft || "#ecfeff");
    document.documentElement.style.setProperty("--radius", theme.radius || "16px");
    document.documentElement.style.setProperty("--hero-bg", `url('${theme.heroBg || "https://img.freepik.com/free-photo/blur-hospital_1203-7972.jpg?w=740"}')`);
    document.title = `${theme.name || "Profile Template"} - Dr. Sarah Mitchell`;

    const app = document.getElementById("app");
    app.className = `page ${theme.layout || ""}`;
    app.innerHTML = `
        <header class="site-header">
            <div class="container nav-wrap">
                <a class="brand" href="#home"><span class="brand-icon"><i class="ri-stethoscope-line"></i></span><span><h1 class="brand-title font-display">Dr. Sarah Mitchell</h1><p class="brand-subtitle">Medical Practice</p></span></a>
                <nav class="nav" id="nav"><a href="#home">Home</a><a href="#about">About</a><a href="#specialties">Services</a><a href="#gallery">Gallery</a><a class="button" href="tel:5551234567"><i class="ri-phone-line"></i>(555) 123-4567</a></nav>
                <button class="mobile-toggle" id="mobileToggle"><i class="ri-menu-line"></i></button>
            </div>
        </header>
        <main>
            <section id="home" class="hero">
                <div class="container hero-grid">
                    <div>
                        <span class="pill"><i class="ri-stethoscope-line"></i>${theme.name || "Professional Medical Care"}</span>
                        <h1 class="font-display">${theme.headline || "Your Health,"}<br><span>${theme.accentLine || "Our Priority"}</span></h1>
                        <p>Dr. Sarah Mitchell provides comprehensive healthcare services with over 15 years of experience. Dedicated to delivering personalized medical care in a comfortable and professional environment.</p>
                        <div class="hero-actions"><a class="button" href="#appointment"><i class="ri-calendar-check-line"></i>Book Appointment</a><a class="button light" href="#about">Learn More</a></div>
                        <div class="hero-stats"><div class="hero-stat"><strong>15+</strong><span>Years Experience</span></div><div class="hero-stat"><strong>5000+</strong><span>Happy Patients</span></div><div class="hero-stat"><strong>98%</strong><span>Satisfaction Rate</span></div></div>
                    </div>
                    <div class="doctor-photo"><img src="${theme.doctorImage || "https://img.freepik.com/free-photo/female-doctor-hospital_23-2148827760.jpg?w=740"}" alt="Dr. Sarah Mitchell"><div class="cert-card"><i class="ri-shield-check-fill"></i><div><strong>Board Certified</strong><br><span>Licensed Physician</span></div></div></div>
                </div>
            </section>

            <section id="about">
                <div class="container">
                    ${sectionHead("About Dr. Mitchell", "Dedicated to Your Wellbeing", "With over 15 years of medical practice, Dr. Sarah Mitchell combines expertise with compassionate care to help patients achieve optimal health.")}
                    <div class="two-col">
                        <img class="image-card" src="https://readdy.ai/api/search-image?query=Professional%20doctor%20consulting%20with%20patient%20in%20modern%20medical%20office%2C%20warm%20interaction%2C%20medical%20examination%20room%20with%20natural%20light&width=800&height=600&seq=about-img-001&orientation=landscape" alt="Dr. Mitchell with patient">
                        <div class="content-copy"><h3>Professional Background</h3><p>Dr. Sarah Mitchell is a board-certified physician specializing in internal medicine and cardiology. She graduated from Harvard Medical School and completed her residency at Johns Hopkins Hospital.</p><p>Her approach emphasizes preventive care, patient education, and long-term relationships. Dr. Mitchell believes in treating the whole person, not just symptoms.</p><p>She has been recognized for excellence in patient care and is committed to staying current with the latest medical advances.</p></div>
                    </div>
                </div>
            </section>

            <section class="section-muted"><div class="container">${sectionHead("Education & Training", "Comprehensive Medical Education", "Training from world-renowned institutions and advanced clinical programs.")}<div class="grid grid-2">${data.education.map(iconCard).join("")}</div></div></section>

            <section><div class="container">${sectionHead("Credentials", "Board Certifications", "Maintaining the highest standards of medical excellence.")}<div class="grid grid-3">${data.credentials.map((item) => `<article class="card"><div class="icon-box"><i class="${item[4]}"></i></div><p><span class="meta">${item[0]}</span> <span class="badge">${item[3]}</span></p><h3>${item[1]}</h3><p>${item[2]}</p></article>`).join("")}</div></div></section>

            <section class="section-muted"><div class="container">${sectionHead("Affiliations", "Professional Affiliations", "Proud member of leading medical institutions and organizations.")}<div class="grid grid-3">${data.affiliations.map((item) => `<article class="card split-card"><div class="icon-box"><i class="${item[3]}"></i></div><div><div class="meta">${item[0]}</div><h3>${item[1]}</h3><p>${item[2]}</p></div></article>`).join("")}</div></div></section>

            <section><div class="container"><div class="expertise"><h2 class="font-display">Areas of Expertise</h2><div class="check-grid">${data.expertise.map((item) => `<div class="check-item"><i class="ri-check-line"></i><span>${item}</span></div>`).join("")}</div></div></div></section>

            <section id="specialties" class="section-alt"><div class="container">${sectionHead("Medical Specialties", "Areas of Specialization", "Providing expert medical care across multiple specialties with a patient-centered approach.")}<div class="grid grid-3">${data.specialties.map((item) => `<article class="card"><div class="icon-box"><i class="${item[3]}"></i></div><h3>${item[0]}</h3><p>${item[1]}</p><p class="meta"><i class="ri-user-line"></i> ${item[2]}</p></article>`).join("")}</div></div></section>

            <section id="experience"><div class="container">${sectionHead("Professional Journey", "Experience & Achievements", "A proven track record of excellence in medical practice and patient care.")}<div class="grid grid-4"><div class="card metric"><strong>15+</strong><span>Years Experience</span></div><div class="card metric"><strong>12,000+</strong><span>Patients Treated</span></div><div class="card metric"><strong>25+</strong><span>Medical Publications</span></div><div class="card metric"><strong>98%</strong><span>Patient Satisfaction</span></div></div><div class="timeline">${data.timeline.map((item) => `<div class="timeline-item"><article class="card timeline-card"><div class="meta">${item[0]}</div><h3>${item[1]}</h3><p><strong>${item[2]}</strong></p><p>${item[3]}</p></article><div class="timeline-dot"><i class="${item[4]}"></i></div></div>`).join("")}</div></div></section>

            <section class="section-alt"><div class="container">${sectionHead("Telemedicine Services", "Online Chamber & Virtual Care", "Experience quality healthcare from the comfort of your home with secure virtual consultations.")}<div class="two-col"><img class="image-card" src="https://readdy.ai/api/search-image?query=Professional%20female%20doctor%20conducting%20telemedicine%20video%20consultation%20on%20laptop%20in%20modern%20medical%20office&width=800&height=600&seq=online-chamber-001&orientation=landscape" alt="Online consultation"><div class="content-copy"><h3>Virtual Healthcare Made Easy</h3><p>Our online chamber brings expert medical care directly to you. Follow-up consultations and health concerns can be handled through secure, encrypted platforms.</p><div class="platforms"><h4>Supported Platforms</h4><div class="platform-list"><div><i class="ri-vidicon-fill"></i>Zoom</div><div><i class="ri-google-fill"></i>Google Meet</div><div><i class="ri-microsoft-fill"></i>Microsoft Teams</div><div><i class="ri-shield-check-fill"></i>Secure Portal</div></div></div></div></div><div class="grid grid-4" style="margin-top:34px">${[["Video Consultations","ri-video-chat-fill"],["Instant Messaging","ri-chat-4-fill"],["Digital Prescriptions","ri-file-text-fill"],["Online Scheduling","ri-calendar-check-fill"]].map((item) => `<article class="card"><div class="icon-box"><i class="${item[1]}"></i></div><h3>${item[0]}</h3><p>Convenient, secure, and patient-friendly access to modern care.</p></article>`).join("")}</div></div></section>

            <section id="gallery" class="section-muted"><div class="container">${sectionHead("Photo Gallery", "Our Clinic & Facilities", "Take a virtual tour of our modern medical facility.")}<div class="gallery-grid">${data.gallery.map((item) => `<figure class="gallery-item"><img src="${item[2]}" alt="${item[0]}"><figcaption class="gallery-caption"><div class="meta">${item[1]}</div><h3>${item[0]}</h3></figcaption></figure>`).join("")}</div></div></section>

            <section><div class="container">${sectionHead("Patient Testimonials", "What Our Patients Say", "Real feedback from patients who trust Dr. Mitchell for their healthcare needs.")}<div class="grid grid-3">${data.testimonials.map((item) => `<article class="card"><div class="split-card"><img src="${item[3]}" alt="${item[0]}" style="width:64px;height:64px;border-radius:50%;object-fit:cover"><div><h3>${item[0]}</h3><p>${item[1]}</p></div></div><div class="stars">★★★★★</div><p>"${item[2]}"</p><p class="meta"><i class="ri-verified-badge-fill"></i> Verified Patient</p></article>`).join("")}</div><div class="card" style="margin-top:34px;text-align:center"><div class="grid grid-3"><div><h3>98%</h3><p>Patient Satisfaction</p></div><div><h3>4.9</h3><p>Average Rating</p></div><div><h3>500+</h3><p>5-Star Reviews</p></div></div></div></div></section>

            <section class="section-muted"><div class="container">${sectionHead("FAQ", "Frequently Asked Questions", "Find answers to common questions about our practice, appointments, and services.")}<div class="grid">${data.faqs.map((item) => `<details><summary><span>${item[0]}</span><i class="ri-add-line"></i></summary><p>${item[1]}</p></details>`).join("")}</div><div class="card cta-panel" id="appointment" style="margin-top:34px;text-align:center"><h2 class="font-display">Still Have Questions?</h2><p>Our friendly staff is here to help with appointments, insurance, and care questions.</p><a class="button light" href="tel:5551234567"><i class="ri-phone-line"></i>Call Us: (555) 123-4567</a></div></div></section>
        </main>
        <div class="chat-button" id="chatButton"><i class="ri-chat-3-fill"></i><span>Talk with Us</span></div>
        <div class="chatbox hidden" id="chatbox"><div class="chat-header"><span>Talk with Us</span><button id="closeChat">&times;</button></div><div class="chat-body">Use text to communicate.</div><div class="chat-footer"><input type="text" placeholder="Type your message..."><button>➤</button></div></div>
        <footer class="footer"><div class="container"><div class="footer-grid"><div><h3 class="font-display">Dr. Sarah Mitchell</h3><p>Providing exceptional medical care with compassion and expertise for over 15 years.</p><div class="socials"><a href="#"><i class="ri-facebook-fill"></i></a><a href="#"><i class="ri-twitter-x-fill"></i></a><a href="#"><i class="ri-linkedin-fill"></i></a><a href="#"><i class="ri-instagram-fill"></i></a><a href="#"><i class="ri-youtube-fill"></i></a></div></div><div><h4>Quick Links</h4><ul><li><a href="#about">About Dr. Mitchell</a></li><li><a href="#specialties">Specialties</a></li><li><a href="#gallery">Gallery</a></li><li><a href="#appointment">Appointments</a></li></ul></div><div><h4>Services</h4><ul><li>General Checkups</li><li>Chronic Disease Management</li><li>Cardiovascular Care</li><li>Preventive Medicine</li><li>Telemedicine Services</li></ul></div><div><h4>Contact Info</h4><ul><li><i class="ri-map-pin-fill"></i> 123 Medical Plaza, Suite 200<br>New York, NY 10001</li><li><i class="ri-phone-fill"></i> (555) 123-4567</li><li><i class="ri-mail-fill"></i> info@drmitchell.com</li><li><i class="ri-time-line"></i> Mon-Fri: 8AM-6PM</li></ul></div></div><div class="footer-bottom"><span>© 2024 Dr. Sarah Mitchell Medical Practice. All rights reserved.</span><span>Privacy Policy · Terms of Service</span></div></div></footer>
    `;

    document.getElementById("mobileToggle")?.addEventListener("click", () => document.getElementById("nav")?.classList.toggle("open"));
    document.getElementById("chatButton")?.addEventListener("click", () => document.getElementById("chatbox")?.classList.toggle("hidden"));
    document.getElementById("closeChat")?.addEventListener("click", () => document.getElementById("chatbox")?.classList.add("hidden"));
}

document.addEventListener("DOMContentLoaded", render);
