<?php
// vet.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Vet Page</title>
  <style>
    /* base */
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f7f7f7;
      color: #111;
    }

    /* header */
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #fff;               /* white bg as requested */
      padding: 16px 28px;
      font-weight: 600;               /* semi-bold */
      color: #000;                    /* black text */
      border-bottom: 1px solid #e6e6e6;
      position: sticky;
      top: 0;
      z-index: 1000;
    }
    .logo { font-size: 20px; font-weight: 700; }

    nav a {
      margin-left: 18px;
      text-decoration: none;
      color: #000;
      font-weight: 600;
      vertical-align: middle;
    }
    nav a:hover { color: #ff6600; }

    /* homepage three big cards */
    .cards {
      display: flex;
      gap: 20px;
      padding: 36px;
      justify-content: center;
      flex-wrap: wrap;
    }

    .card {
      background: #fff;
      flex: 1 1 300px;     /* 1fr-ish behaviour, min width ~300 */
      max-width: 340px;
      padding: 22px;
      border-radius: 12px;
      box-shadow: 0 6px 16px rgba(0,0,0,0.08);
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      transition: transform .18s ease;
    }
    .card:hover { transform: translateY(-6px); }

    .card h2 { margin: 4px 0 10px; font-size: 20px; }
    .card p { margin: 0; color: #444; font-size: 14.5px; }

    /* orange CTA */
    .btn {
      background: #ff6600;
      color: #fff;
      border: none;
      padding: 12px 18px;
      border-radius: 8px;
      font-weight: 700;
      margin-top: 16px;
      text-decoration: none;
      display: inline-block;
    }
    .btn:hover { background: #e55b00; }

    /* detail sections (simple lists, no cards) */
    .section {
      padding: 28px 36px;
      border-top: 1px solid #eee;
      background: transparent;
    }
    .section h2 {
      margin: 0 0 12px;
      font-size: 20px;
    }

    .doctor-list,
    .med-list,
    .firstaid-list {
      margin: 0;
      padding-left: 20px;
      color: #333;
      font-size: 15px;
      line-height: 1.6;
    }

    /* responsive: stack homepage cards, make them full width-ish */
    @media (max-width: 768px) {
      .cards {
        padding: 20px;
        flex-direction: column;
        align-items: stretch;
      }
      .card {
        max-width: 100%;
      }
    }
  </style>
</head>
<body>

<header>
  <div class="logo">🐾 VetCare</div>
  <nav>
    <a href="#home">Home</a>
    <a href="#appointment-section">Appointments</a>
    <a href="#meds-section">Meds</a>
    <a href="#firstaid-section">First Aid</a>
  </nav>
</header>

<main>
  <!-- homepage: 3 prominent category cards (always visible) -->
  <div class="cards" id="home">
    <div class="card">
      <h2>Appointments</h2>
      <p>See available doctors and set an appointment.</p>
      <a class="btn" href="#appointment-section">Set Appointment</a>
    </div>

    <div class="card">
      <h2>Meds</h2>
      <p>View prescribed medicines and order.</p>
      <a class="btn" href="#meds-section">Order Now</a>
    </div>

    <div class="card">
      <h2>First Aid</h2>
      <p>Essential first-aid items for your pet.</p>
      <a class="btn" href="#firstaid-section">Order Now</a>
    </div>
  </div>

  <!-- appointment: only a simple list of doctors (no cards) -->
  <section id="appointment-section" class="section" aria-label="Appointments">
    <h2>Available Doctors</h2>
    <ul class="doctor-list">
      <li><strong>Dr. Sarah Khan</strong> — Surgery — 10 Sept, 3:00 PM</li>
      <li><strong>Dr. James Lee</strong> — Dentistry — 12 Sept, 11:00 AM</li>
      <li><strong>Dr. Anita Das</strong> — Nutrition — 15 Sept, 5:30 PM</li>
      <li><strong>Dr. Omar Rahman</strong> — General Care — 18 Sept, 9:00 AM</li>
    </ul>
  </section>

  <!-- meds: only a simple list of meds (no cards) -->
  <section id="meds-section" class="section" aria-label="Medicines">
    <h2>Prescribed Medicines</h2>
    <ul class="med-list">
      <li><strong>Amoxicillin</strong> — 250 mg — twice daily</li>
      <li><strong>Vitamin D</strong> — 1000 IU — once daily</li>
      <li><strong>Metacam</strong> — pain relief — follow vet instructions</li>
      <li><strong>Furosemide</strong> — 20 mg — morning only</li>
    </ul>
  </section>

  <!-- first aid: only a simple demo list (no cards) -->
  <section id="firstaid-section" class="section" aria-label="First Aid">
    <h2>First Aid List</h2>
    <ul class="firstaid-list">
      <li>Antiseptic Solution — for wound cleaning</li>
      <li>Bandage Roll — elastic wrap</li>
      <li>Cotton Pads — pack of 100</li>
      <li>Digital Thermometer — pet-safe</li>
      <li>Pain Relief Spray — vet-approved topical spray</li>
    </ul>
  </section>
</main>

</body>
</html>
