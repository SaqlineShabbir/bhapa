
<?php 
session_start();
include 'db.php'; 
// Fetch events from database
$sql = "SELECT * FROM events ORDER BY event_date ASC LIMIT 3";
$result = $conn->query($sql);
$events = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>EventHub - Discover & Book Events</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* Reset & base */
    :root {
      --primary: #4361ee;
      --secondary: #3f37c9;
      --accent: #ff6f61;
      --light: #f8f9fa;
      --dark: #212529;
      --gray: #6c757d;
      --success: #4cc9f0;
      --shadow: 0 5px 15px rgba(0,0,0,0.08);
      --transition: all 0.3s ease;
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    html, body {
      height: 100%;
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f4f6f8;
      color: var(--dark);
      line-height: 1.6;
    }

    /* Flex container to push footer down */
    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    /* Typography */
    h1, h2, h3, h4 {
      font-weight: 700;
      line-height: 1.2;
      margin-bottom: 1rem;
    }
    
    h1 {
      font-size: 3.5rem;
    }
    
    h2 {
      font-size: 2.5rem;
      position: relative;
      padding-bottom: 15px;
    }
    
    h2:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      background: var(--accent);
      border-radius: 2px;
    }
    
    h3 {
      font-size: 1.8rem;
    }
    
    p {
      margin-bottom: 1rem;
    }
    
    .text-center {
      text-align: center;
    }
    
    .section-title {
      margin-bottom: 3rem;
    }

    /* Layout */
    .container {
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }
    
    section {
      padding: 80px 0;
    }
    
    .section-light {
      background: white;
    }
    
    .section-gray {
      background: #f8f9fa;
    }
    
    .section-dark {
      background: var(--secondary);
      color: white;
    }

    /* Buttons */
    .btn {
      display: inline-block;
      padding: 12px 30px;
      border-radius: 30px;
      text-decoration: none;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      transition: var(--transition);
      border: none;
      text-align: center;
    }
    
    .btn-primary {
      background: var(--primary);
      color: white;
    }
    
    .btn-primary:hover {
      background: var(--secondary);
      transform: translateY(-3px);
      box-shadow: var(--shadow);
    }
    
    .btn-outline {
      background: transparent;
      border: 2px solid white;
      color: white;
    }
    
    .btn-outline:hover {
      background: white;
      color: var(--primary);
    }
    
    .btn-accent {
      background: var(--accent);
      color: white;
    }
    
    .btn-accent:hover {
      background: #ff503e;
      transform: translateY(-3px);
      box-shadow: var(--shadow);
    }
    
    .btn-lg {
      padding: 15px 40px;
      font-size: 1.1rem;
    }

    /* Navbar styles */
    header {
      background: white; 
      color: var(--dark); 
      display: flex; 
      justify-content: space-between; 
      align-items: center; 
      padding: 15px 5%;
      position: sticky;
      top: 0;
      z-index: 1000;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .logo {
      font-size: 1.8rem;
      font-weight: 800;
      color: var(--primary);
      text-decoration: none;
      display: flex;
      align-items: center;
    }
    
    .logo i {
      margin-right: 10px;
      color: var(--accent);
    }
    
    nav {
      display: flex;
      align-items: center;
    }
    
    nav a {
      color: var(--dark);
      text-decoration: none;
      font-weight: 600;
      font-size: 1rem;
      margin-left: 25px;
      transition: var(--transition);
      position: relative;
    }
    
    nav a:hover {
      color: var(--primary);
    }
    
    nav a:after {
      content: '';
      position: absolute;
      bottom: -5px;
      left: 0;
      width: 0;
      height: 2px;
      background: var(--primary);
      transition: var(--transition);
    }
    
    nav a:hover:after {
      width: 100%;
    }

    /* User circle */
    #userCircle {
      width: 42px;
      height: 42px;
      background: var(--primary);
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 18px;
      cursor: pointer;
      user-select: none;
      box-shadow: var(--shadow);
      position: relative;
      transition: var(--transition);
      margin-left: 20px;
    }
    
    #userCircle:hover {
      transform: scale(1.05);
    }

    /* Dropdown menu */
    #dropdownMenu {
      display: none;
      position: absolute;
      top: 60px;
      right: 0;
      background: #fff;
      border-radius: 8px;
      box-shadow: var(--shadow);
      padding: 10px 0;
      min-width: 180px;
      text-align: left;
      z-index: 1000;
    }
    
    #dropdownMenu.show {
      display: block;
    }
    
    #dropdownMenu a {
      display: block;
      padding: 10px 20px;
      color: var(--dark);
      text-decoration: none;
      transition: var(--transition);
      font-weight: 500;
    }
    
    #dropdownMenu a:hover {
      background: #f8f9fa;
      color: var(--primary);
    }
    
    #dropdownMenu button {
      background: transparent;
      border: none;
      color: #e63946;
      padding: 10px 20px;
      cursor: pointer;
      font-weight: 600;
      font-size: 14px;
      width: 100%;
      text-align: left;
      transition: var(--transition);
    }
    
    #dropdownMenu button:hover {
      background: #f8f9fa;
      color: #b32c37;
    }

    /* Hero Section */
    .hero {
      background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
      color: white;
      padding: 120px 0 80px;
      position: relative;
     
    }
    
    .hero-content {
      max-width: 650px;
      position: relative;
      z-index: 2;
    }
    
    .hero h1 {
      margin-bottom: 20px;
      font-weight: 800;
    }
    
    .hero p {
      font-size: 1.25rem;
      margin-bottom: 30px;
      opacity: 0.9;
    }
    
    .hero-btns {
      display: flex;
      gap: 15px;
      margin-top: 20px;
    }
    
    .hero-image {
      position: absolute;
      right: 5%;
      top: 50%;
      transform: translateY(-50%);
      width: 40%;
      max-width: 500px;
      border-radius: 10px;
      box-shadow: var(--shadow);
      z-index: 1;
    }
    
    /* Search Section */
    .search-section {
      padding: 40px 0;
      background: white;
      box-shadow: 0 5px 20px rgba(0,0,0,0.05);
      position: relative;
      z-index: 10;
      margin-top: -40px;
      border-radius: 15px;
      max-width: 90%;
      margin-left: auto;
      margin-right: auto;
    }
    
    .search-container {
      display: flex;
      max-width: 1000px;
      margin: 0 auto;
      background: white;
      border-radius: 50px;
      overflow: hidden;
      box-shadow: var(--shadow);
    }
    
    .search-container input {
      flex: 1;
      padding: 20px 30px;
      border: none;
      font-size: 1rem;
      outline: none;
    }
    
    .search-container select {
      padding: 0 20px;
      border: none;
      border-left: 1px solid #eee;
      border-right: 1px solid #eee;
      outline: none;
      appearance: none;
      background: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e") no-repeat;
      background-position: right 15px center;
      background-size: 16px;
      padding-right: 40px;
    }
    
    .search-container button {
      padding: 0 40px;
      background: var(--accent);
      color: white;
      border: none;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
    }
    
    .search-container button:hover {
      background: #ff503e;
    }

    /* Categories */
    .categories-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap: 25px;
      margin-top: 30px;
    }
    
    .category-card {
      background: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: var(--shadow);
      transition: var(--transition);
      text-align: center;
      padding: 30px 20px;
    }
    
    .category-card:hover {
      transform: translateY(-10px);
    }
    
    .category-icon {
      width: 70px;
      height: 70px;
      background: rgba(67, 97, 238, 0.1);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
      color: var(--primary);
      font-size: 1.8rem;
    }
    
    .category-card h3 {
      font-size: 1.3rem;
      margin-bottom: 10px;
    }
    
    .category-card p {
      color: var(--gray);
      font-size: 0.9rem;
    }

    /* Featured Events */
    .events-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 30px;
    }
    
    .event-card {
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: var(--shadow);
      transition: var(--transition);
      position: relative;
    }
    
    .event-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }
    
    .event-badge {
      position: absolute;
      top: 15px;
      right: 15px;
      background: var(--accent);
      color: white;
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
      z-index: 2;
    }
    
    .event-image {
      height: 200px;
      width: 100%;
      background-size: cover;
      background-position: center;
      position: relative;
    }
    
    .event-image:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 60%;
      background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
    }
    
    .event-content {
      padding: 20px;
    }
    
    .event-date {
      display: flex;
      align-items: center;
      color: var(--primary);
      font-weight: 600;
      margin-bottom: 10px;
    }
    
    .event-date i {
      margin-right: 8px;
    }
    
    .event-card h3 {
      font-size: 1.4rem;
      margin-bottom: 10px;
      line-height: 1.3;
    }
    
    .event-location {
      display: flex;
      align-items: center;
      color: var(--gray);
      margin-bottom: 15px;
      font-size: 0.95rem;
    }
    
    .event-location i {
      margin-right: 8px;
    }
    
    .event-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 15px;
      padding-top: 15px;
      border-top: 1px solid #eee;
    }
    
    .event-price {
      font-weight: 700;
      color: var(--accent);
      font-size: 1.2rem;
    }
    
    .event-stats {
      display: flex;
      align-items: center;
      color: var(--gray);
      font-size: 0.9rem;
    }
    
    .event-stats i {
      margin-right: 5px;
    }

    /* Testimonials */
    .testimonials-container {
      max-width: 800px;
      margin: 0 auto;
    }
    
    .testimonial-card {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: var(--shadow);
      margin: 20px 0;
      position: relative;
    }
    
    .testimonial-card:before {
      content: '"';
      position: absolute;
      top: 20px;
      left: 20px;
      font-size: 5rem;
      color: rgba(67, 97, 238, 0.1);
      font-family: serif;
      line-height: 1;
    }
    
    .testimonial-content {
      font-style: italic;
      margin-bottom: 20px;
      position: relative;
      z-index: 2;
    }
    
    .testimonial-author {
      display: flex;
      align-items: center;
    }
    
    .author-avatar {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: var(--primary);
      margin-right: 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: bold;
      font-size: 1.2rem;
    }
    
    .author-info h4 {
      margin-bottom: 5px;
    }
    
    .author-info p {
      color: var(--gray);
      font-size: 0.9rem;
      margin: 0;
    }

    /* Stats */
    .stats-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      text-align: center;
    }
    
    .stat-box {
      background: rgba(255, 255, 255, 0.1);
      padding: 30px 20px;
      border-radius: 10px;
      backdrop-filter: blur(10px);
    }
    
    .stat-number {
      font-size: 3rem;
      font-weight: 800;
      margin-bottom: 10px;
      color: white;
    }
    
    .stat-text {
      font-size: 1.1rem;
      opacity: 0.9;
    }

    /* CTA */
    .cta-container {
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      border-radius: 15px;
      padding: 60px;
      text-align: center;
      box-shadow: var(--shadow);
      position: relative;
      overflow: hidden;
    }
    
    .cta-container h2 {
      color: white;
      margin-bottom: 20px;
    }
    
    .cta-container p {
      max-width: 600px;
      margin: 0 auto 30px;
      color: rgba(255,255,255,0.9);
      font-size: 1.2rem;
    }
    
    .cta-container:after {
      content: '';
      position: absolute;
      top: -50px;
      right: -50px;
      width: 200px;
      height: 200px;
      background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" stroke="white" stroke-width="2" fill="none" stroke-opacity="0.1"/></svg>');
      background-size: contain;
      z-index: 1;
    }

    /* Footer */
    
    
    /* Responsive */
    @media (max-width: 992px) {
      .hero-image {
        display: none;
      }
      
      .hero-content {
        max-width: 100%;
        text-align: center;
      }
      
      .hero-btns {
        justify-content: center;
      }
      
      .search-container {
        flex-direction: column;
        border-radius: 15px;
      }
      
      .search-container input, 
      .search-container select, 
      .search-container button {
        width: 100%;
        padding: 15px;
        border: none;
        border-bottom: 1px solid #eee;
      }
      
      .search-container button {
        border: none;
      }
    }
    
    @media (max-width: 768px) {
      header {
        flex-wrap: wrap;
        padding: 15px 20px;
      }
      
      nav {
        order: 3;
        width: 100%;
        margin-top: 15px;
        justify-content: center;
      }
      
      h1 {
        font-size: 2.5rem;
      }
      
      h2 {
        font-size: 2rem;
      }
      
      section {
        padding: 60px 0;
      }
      
      .cta-container {
        padding: 40px 20px;
      }
    }
  </style>
</head>
<body>

 <?php include 'navbar.php'; ?>

<!-- Hero Section -->
<section class="hero">
  <div class="container">
    <div class="hero-content">
      <h1>Discover Amazing Events Near You</h1>
      <p>Find, book, and enjoy concerts, workshops, festivals, and more. Secure your spot with just a few clicks!</p>
      <div class="hero-btns">
        <a href="events.php" class="btn btn-accent btn-lg">Browse Events</a>
        <a href="#" class="btn btn-outline btn-lg">Create Event</a>
      </div>
    </div>
  </div>
  <div class="hero-image" style="background: linear-gradient(45deg, #ff6f61, #ff9a8b);"></div>
</section>

<!-- Search Section -->
<!-- <section class="search-section">
  <div class="container">
    <div class="search-container">
      <input type="text" placeholder="Search for events, artists, or venues...">
      <select>
        <option>All Categories</option>
        <option>Music & Concerts</option>
        <option>Workshops</option>
        <option>Sports</option>
        <option>Food & Drink</option>
        <option>Arts & Theater</option>
      </select>
      <select>
        <option>Any Date</option>
        <option>Today</option>
        <option>This Weekend</option>
        <option>Next Week</option>
        <option>This Month</option>
      </select>
      <select>
        <option>Any Location</option>
        <option>New York</option>
        <option>Los Angeles</option>
        <option>Chicago</option>
        <option>Miami</option>
        <option>London</option>
      </select>
      <button>Search</button>
    </div>
  </div>
</section> -->

<!-- Categories Section -->
<section class="section-light">
  <div class="container">
    <div class="section-title text-center">
      <h2>Browse Categories</h2>
      <p>Discover events by your interests</p>
    </div>
    
    <div class="categories-grid">
      <div class="category-card">
        <div class="category-icon">
          <i class="fas fa-music"></i>
        </div>
        <h3>Music & Concerts</h3>
        <p>Concerts, festivals, DJ parties</p>
      </div>
      
      <div class="category-card">
        <div class="category-icon">
          <i class="fas fa-graduation-cap"></i>
        </div>
        <h3>Workshops</h3>
        <p>Learning, training, seminars</p>
      </div>
      
      <div class="category-card">
        <div class="category-icon">
          <i class="fas fa-utensils"></i>
        </div>
        <h3>Food & Drink</h3>
        <p>Tastings, festivals, dinners</p>
      </div>
      
      <div class="category-card">
        <div class="category-icon">
          <i class="fas fa-running"></i>
        </div>
        <h3>Sports</h3>
        <p>Games, matches, tournaments</p>
      </div>
      
      <div class="category-card">
        <div class="category-icon">
          <i class="fas fa-theater-masks"></i>
        </div>
        <h3>Arts & Theater</h3>
        <p>Shows, exhibitions, plays</p>
      </div>
      
      <div class="category-card">
        <div class="category-icon">
          <i class="fas fa-heart"></i>
        </div>
        <h3>Health & Wellness</h3>
        <p>Yoga, meditation, fitness</p>
      </div>
    </div>
  </div>
</section>

<!-- Featured Events -->
<section class="section-gray">
  <div class="container">
    <div class="section-title text-center">
      <h2>Featured Events</h2>
      <p>Popular events you don't want to miss</p>
    </div>
    
    <div class="events-grid">
          <?php foreach ($events as $event): 
        // Format date and time
        $event_date = date("F j, Y", strtotime($event['event_date']));
        $event_time = date("g:i A", strtotime($event['event_time']));
        
        // Format price
        $price = ($event['price'] > 0) ? '$' . number_format($event['price'], 2) : 'Free';
    ?>
    <a href="event.php?id=<?= $event['id'] ?>" style="text-decoration: none; color: inherit;">
    <div class="event-card">
        <?php if ($event['badge']): ?>
            <div class="event-badge"><?= $event['badge'] ?></div>
        <?php endif; ?>
        
        <div class="event-image" style="background-image: url('<?= $event['image_color'] ?>');"></div>
        
        <div class="event-content">
            <div class="event-date">
                <i class="far fa-calendar-alt"></i> <?= $event_date ?> • <?= $event_time ?>
            </div>
            <h3><?= htmlspecialchars($event['title']) ?></h3>
            <div class="event-location">
                <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($event['location']) ?>
            </div>
            <p><?= htmlspecialchars(substr($event['description'], 0, 100)) ?>...</p>
            <div class="event-footer">
                <div class="event-price"><?= $price ?></div>
                <div class="event-stats">
                    <i class="fas fa-users"></i> <?= number_format($event['attendees']) ?> Going
                </div>
            </div>
        </div>
    </div>
    </a>
    <?php endforeach; ?>
    </div>
    
    <div class="text-center" style="margin-top: 50px;">
      <a href="events.php" class="btn btn-primary">View All Events</a>
    </div>
  </div>
</section>

<!-- Stats Section -->
<section class="section-dark">
  <div class="container">
    <div class="stats-container">
      <div class="stat-box">
        <div class="stat-number">15,000+</div>
        <div class="stat-text">Events Listed</div>
      </div>
      
      <div class="stat-box">
        <div class="stat-number">500k+</div>
        <div class="stat-text">Active Users</div>
      </div>
      
      <div class="stat-box">
        <div class="stat-number">120+</div>
        <div class="stat-text">Cities Covered</div>
      </div>
      
      <div class="stat-box">
        <div class="stat-number">98%</div>
        <div class="stat-text">Customer Satisfaction</div>
      </div>
    </div>
  </div>
</section>

<!-- Testimonials -->
<section class="section-light">
  <div class="container">
    <div class="section-title text-center">
      <h2>What Our Users Say</h2>
      <p>Join thousands of satisfied event-goers</p>
    </div>
    
    <div class="testimonials-container">
      <div class="testimonial-card">
        <div class="testimonial-content">
          EventHub made finding and booking events so simple! I discovered amazing concerts I wouldn't have known about otherwise. The booking process is seamless and I love getting reminders.
        </div>
        <div class="testimonial-author">
          <div class="author-avatar">S</div>
          <div class="author-info">
            <h4>Sarah Johnson</h4>
            <p>Event Organizer</p>
          </div>
        </div>
      </div>
      
      <div class="testimonial-card">
        <div class="testimonial-content">
          As an event organizer, EventHub has transformed how I manage ticket sales. The platform is intuitive for both me and my attendees. I've increased attendance by 40% since switching!
        </div>
        <div class="testimonial-author">
          <div class="author-avatar">M</div>
          <div class="author-info">
            <h4>Michael Torres</h4>
            <p>Music Festival Director</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section>
  <div class="container">
    <div class="cta-container">
      <h2>Ready to Host Your Event?</h2>
      <p>Join thousands of organizers who use EventHub to sell tickets and manage events. Our platform makes event management simple and effective.</p>
      <a href="#" class="btn btn-accent btn-lg">Create Your Event</a>
    </div>
  </div>
</section>


<?php include 'footer.php'; ?>
<script>
  function toggleDropdown() {
    const dropdown = document.getElementById('dropdownMenu');
    dropdown.classList.toggle('show');
  }

  window.onclick = function(event) {
    if (!event.target.closest('#userCircle')) {
      const dropdown = document.getElementById('dropdownMenu');
      if (dropdown) dropdown.classList.remove('show');
    }
  }
  
  // Simple animation on scroll
  document.addEventListener('DOMContentLoaded', function() {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate-in');
        }
      });
    }, {
      threshold: 0.1
    });
    
    document.querySelectorAll('section').forEach(section => {
      observer.observe(section);
    });
  });
</script>

</body>
</html>